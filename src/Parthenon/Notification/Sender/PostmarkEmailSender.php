<?php

declare(strict_types=1);

/*
 * Copyright (C) 2020-2024 Iain Cambridge
 *
 *     This program is free software: you can redistribute it and/or modify
 *     it under the terms of the GNU General Public License as published by
 *     the Free Software Foundation, either version 3 of the License, or
 *     (at your option) any later version.
 *
 *     This program is distributed in the hope that it will be useful,
 *     but WITHOUT ANY WARRANTY; without even the implied warranty of
 *     MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *     GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Parthenon\Notification\Sender;

use Parthenon\Common\LoggerAwareTrait;
use Parthenon\Notification\Configuration;
use Parthenon\Notification\EmailInterface;
use Parthenon\Notification\EmailSenderInterface;
use Parthenon\Notification\Exception\UnableToSendMessageException;
use Postmark\Models\PostmarkAttachment;
use Postmark\PostmarkClient;

final class PostmarkEmailSender implements EmailSenderInterface
{
    use LoggerAwareTrait;

    public function __construct(private PostmarkClient $postmarkClient, private Configuration $configuration)
    {
    }

    public function send(EmailInterface $message)
    {
        $attachments = [];
        foreach ($message->getAttachments() as $attachment) {
            $attachments[] = PostmarkAttachment::fromRawData($attachment->getContent(), $attachment->getName());
        }
        $from = $message->getFromAddress() ?? $this->configuration->getFromAddress();

        $email = [
            'To' => $message->getToAddress(),
            'From' => $from,
            'Subject' => $message->getSubject(),
            'Attachments' => $attachments,
        ];
        try {
            if ($message->isTemplate()) {
                $email['TemplateAlias'] = $message->getTemplateName();
                $email['TemplateModel'] = $message->getTemplateVariables();
                $response = $this->postmarkClient->sendEmailBatchWithTemplate([$email]);
            } else {
                $email['HtmlBody'] = $message->getContent();
                $response = $this->postmarkClient->sendEmailBatch([$email]);
            }

            $this->getLogger()->info('Sent email using PostMark');
        } catch (\Exception $e) {
            $this->getLogger()->warning('Unable to send email using PostMark', ['exception_message' => $e->getMessage()]);
            throw new UnableToSendMessageException($e->getMessage(), $e->getCode(), $e);
        }
    }
}

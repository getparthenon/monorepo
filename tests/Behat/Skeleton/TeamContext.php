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

namespace App\Tests\Behat\Skeleton;

use App\Repository\Orm\TeamInviteCodeRepository;
use App\Repository\Orm\TeamRepository;
use Behat\Behat\Context\Context;
use Behat\Mink\Session;
use Parthenon\User\Entity\InviteCode;

class TeamContext implements Context
{
    use SendRequestTrait;
    use TeamTrait;

    public function __construct(
        private Session $session,
        private TeamRepository $teamRepository,
        private TeamInviteCodeRepository $inviteCodeRepository
    ) {
    }

    /**
     * @When I view the team view
     */
    public function iViewTheTeamView()
    {
        $this->sendJsonRequest('GET', '/api/user/team');
    }

    /**
     * @Then there should be an invite for :arg1 to :arg2
     */
    public function thereShouldBeAnInviteForTo($email, $teamName)
    {
        $team = $this->getTeamByName($teamName);
        $inviteCode = $this->inviteCodeRepository->findOneBy(['email' => $email, 'team' => $team]);

        if (!$inviteCode instanceof InviteCode) {
            throw new \Exception('No invite code');
        }
    }

    /**
     * @Then I should be told that the email has already been invited
     */
    public function iShouldBeToldThatTheEmailHasAlreadyBeenInvited()
    {
        $jsonData = json_decode($this->session->getPage()->getContent(), true);

        if (!$jsonData['already_invited']) {
            throw new \Exception('Not declared as already invited');
        }
    }

    /**
     * @When I cancel the invite for :arg1
     */
    public function iCancelTheInviteFor($email)
    {
        /** @var InviteCode $inviteCode */
        $inviteCode = $this->inviteCodeRepository->findOneBy(['email' => $email]);

        $this->sendJsonRequest('POST', '/api/user/team/invite/'.$inviteCode->getId().'/cancel');
    }

    /**
     * @Then I should see :arg1 as an invited user
     */
    public function iShouldSeeAsAnInvitedUser($email)
    {
        $jsonData = json_decode($this->session->getPage()->getContent(), true);
        foreach ($jsonData['sent_invites'] as $invite) {
            if ($invite['email'] == $email) {
                return;
            }
        }

        throw new \Exception('The user is not in the invited list');
    }

    /**
     * @Then I should see the member :arg1 in the member list
     */
    public function iShouldSeeTheMemberInTheMemberList($email)
    {
        $jsonData = json_decode($this->session->getPage()->getContent(), true);

        foreach ($jsonData['members'] as $invite) {
            if ($invite['email'] == $email) {
                return;
            }
        }

        throw new \Exception('Email not found');
    }

    /**
     * @Then the invite for :arg1 shouldn't be usable
     */
    public function theInviteForShouldntBeUsable($email)
    {
        /** @var InviteCode $inviteCode */
        $inviteCode = $this->inviteCodeRepository->findOneBy(['email' => $email]);
        $this->inviteCodeRepository->getEntityManager()->refresh($inviteCode);

        if (!$inviteCode->isUsed()) {
            throw new \Exception('Invite is usable');
        }
    }

    /**
     * @Then /^I should be told that the email is already a member$/
     */
    public function iShouldBeToldThatTheEmailIsAlreadyAMember()
    {
        $jsonData = json_decode($this->session->getPage()->getContent(), true);

        if (!$jsonData['already_a_member']) {
            throw new \Exception('Not declared as already a member');
        }
    }
}

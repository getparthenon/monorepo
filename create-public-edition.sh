#!/bin/sh

echo "Starting creation of public edition"

echo "[x] Removing old files"
rm -Rf ../public-edition/src/AbTesting/*
rm -Rf ../public-edition/src/Athena/*
rm -Rf ../public-edition/src/Common/*
rm -Rf ../public-edition/src/Funnel/*
rm -Rf ../public-edition/src/Health/*
rm -Rf ../public-edition/src/Invoice/*
rm -Rf ../public-edition/src/Notification/*
rm -Rf ../public-edition/src/Payments/*
rm -Rf ../public-edition/src/Subscriptions/*
rm -Rf ../public-edition/src/User/*
rm -Rf ../public-edition/src/MultiTenancy/*
rm -Rf ../public-edition/spec/Parthenon/*
rm -Rf ../public-edition/src/DependencyInjection/Modules/*
rm -Rf ../public-edition/src/Resources/config/doctrine-mapping/*
rm -Rf ../public-edition/src/Resources/views/*
rm -Rf ../public-edition/src/Resources/config/services/*
rm -Rf ../public-edition/tests/*
mkdir ../public-edition/src/Resources/config/services/orm/
mkdir ../public-edition/src/Resources/config/services/odm/
mkdir ../public-edition/src/Resources/config/services/common/
mkdir -p ../public-edition/tests/Parthenon

cp src/Parthenon/DependencyInjection ../public-edition/src/DependencyInjection/
cp src/Parthenon/ParthenonBundle.php ../public-edition/src/ParthenonBundle.php

echo "[x] Update Core"
cp -R src/Parthenon/Resources/translations ../public-edition/src/Resources/translations

echo "[x] Update AB Testing"
cp -R src/Parthenon/AbTesting ../public-edition/src/
cp src/Parthenon/DependencyInjection/Modules/AbTesting.php ../public-edition/src/DependencyInjection/Modules/AbTesting.php
cp -R src/Parthenon/Resources/config/doctrine-mapping/AbTesting ../public-edition/src/Resources/config/doctrine-mapping/
cp -R src/Parthenon/Resources/config/services/ab_testing.xml ../public-edition/src/Resources/config/services/ab_testing.xml
cp -R src/Parthenon/Resources/views/abtesting ../public-edition/src/Resources/views/
cp -R tests/Parthenon/AbTesting ../public-edition/tests/Parthenon/

echo "[x] Update Athena"
cp -R src/Parthenon/Athena ../public-edition/src/
cp src/Parthenon/DependencyInjection/Modules/Athena.php ../public-edition/src/DependencyInjection/Modules/Athena.php
cp -R src/Parthenon/Resources/config/doctrine-mapping/Athena ../public-edition/src/Resources/config/doctrine-mapping/
cp -R src/Parthenon/Resources/config/services/athena.xml ../public-edition/src/Resources/config/services/athena.xml
cp -R src/Parthenon/Resources/views/athena ../public-edition/src/Resources/views/
cp -R tests/Parthenon/Athena ../public-edition/tests/Parthenon/

echo "[x] Update Common"
cp -R src/Parthenon/Common ../public-edition/src/
cp src/Parthenon/DependencyInjection/Modules/Common.php ../public-edition/src/DependencyInjection/Modules/Common.php
cp -R src/Parthenon/Resources/config/doctrine-mapping/Common ../public-edition/src/Resources/config/doctrine-mapping/
cp -R src/Parthenon/Resources/config/services/common/pdf ../public-edition/src/Resources/config/services/common/pdf
cp -R src/Parthenon/Resources/config/services/common.xml ../public-edition/src/Resources/config/services/common.xml
cp -R tests/Parthenon/Common ../public-edition/tests/Parthenon/

echo "[x] Update Cloud"
cp -R src/Parthenon/Cloud ../public-edition/src/
cp src/Parthenon/DependencyInjection/Modules/Cloud.php ../public-edition/src/DependencyInjection/Modules/Cloud.php
cp -R src/Parthenon/Resources/config/services/cloud.xml ../public-edition/src/Resources/config/services/cloud.xml

echo "[x] Update Funnel"
cp -R src/Parthenon/Funnel ../public-edition/src/
cp src/Parthenon/DependencyInjection/Modules/Funnel.php ../public-edition/src/DependencyInjection/Modules/Funnel.php
cp -R src/Parthenon/Resources/config/services/funnel.xml ../public-edition/src/Resources/config/services/funnel.xml
cp -R tests/Parthenon/Funnel ../public-edition/tests/Parthenon/

echo "[x] Update Health"
cp -R src/Parthenon/Health ../public-edition/src/
cp src/Parthenon/DependencyInjection/Modules/Health.php ../public-edition/src/DependencyInjection/Modules/Health.php
cp -R src/Parthenon/Resources/config/services/orm/health.xml ../public-edition/src/Resources/config/services/orm/health.xml
cp -R src/Parthenon/Resources/config/services/health.xml ../public-edition/src/Resources/config/services/health.xml
cp -R tests/Parthenon/Health ../public-edition/tests/Parthenon/

echo "[x] Update Invoice"
cp -R src/Parthenon/Invoice ../public-edition/src/
cp src/Parthenon/DependencyInjection/Modules/Invoice.php ../public-edition/src/DependencyInjection/Modules/Invoice.php
cp -R src/Parthenon/Resources/config/services/invoice.xml ../public-edition/src/Resources/config/services/invoice.xml
cp -R tests/Parthenon/Invoice ../public-edition/tests/Parthenon/

echo "[x] Update MultiTenancy"
cp -R src/Parthenon/MultiTenancy ../public-edition/src/
cp src/Parthenon/DependencyInjection/Modules/MultiTenancy.php ../public-edition/src/DependencyInjection/Modules/MultiTenancy.php
cp -R src/Parthenon/Resources/config/doctrine-mapping/MultiTenancy ../public-edition/src/Resources/config/doctrine-mapping/MultiTenancy
cp -R src/Parthenon/Resources/config/services/orm/multi_tenancy.xml ../public-edition/src/Resources/config/services/orm/multi_tenancy.xml
cp -R src/Parthenon/Resources/config/services/odm/multi_tenancy.xml ../public-edition/src/Resources/config/services/odm/multi_tenancy.xml
cp -R src/Parthenon/Resources/config/services/multi_tenancy.xml ../public-edition/src/Resources/config/services/multi_tenancy.xml
cp -R tests/Parthenon/MultiTenancy ../public-edition/tests/Parthenon/

echo "[x] Update Notification"
cp -R src/Parthenon/Notification ../public-edition/src/
cp src/Parthenon/DependencyInjection/Modules/Notification.php ../public-edition/src/DependencyInjection/Modules/Notification.php
cp -R src/Parthenon/Resources/config/services/notification.xml ../public-edition/src/Resources/config/services/notification.xml
cp -R tests/Parthenon/Notification ../public-edition/tests/Parthenon/

echo "[x] Update Payments"
cp -R src/Parthenon/Payments ../public-edition/src/
cp src/Parthenon/DependencyInjection/Modules/Payments.php ../public-edition/src/DependencyInjection/Modules/Payments.php
cp -R src/Parthenon/Resources/config/services/payments.xml ../public-edition/src/Resources/config/services/payments.xml
cp -R src/Parthenon/Resources/config/doctrine-mapping/Payments ../public-edition/src/Resources/config/doctrine-mapping/
cp -R tests/Parthenon/Payments ../public-edition/tests/Parthenon/


echo "[x] Update User"
cp -R src/Parthenon/User ../public-edition/src/
cp src/Parthenon/DependencyInjection/Modules/User.php ../public-edition/src/DependencyInjection/Modules/User.php
cp -R src/Parthenon/Resources/config/doctrine-mapping/User ../public-edition/src/Resources/config/doctrine-mapping/
cp -R src/Parthenon/Resources/config/services/orm/user.xml ../public-edition/src/Resources/config/services/orm/user.xml
cp -R src/Parthenon/Resources/config/services/odm/user.xml ../public-edition/src/Resources/config/services/odm/user.xml
cp -R src/Parthenon/Resources/config/services/user.xml ../public-edition/src/Resources/config/services/user.xml
cp -R src/Parthenon/Resources/views/user ../public-edition/src/Resources/views/
cp -R tests/Parthenon/User ../public-edition/tests/Parthenon/

cd ../public-edition
git add -A
if [ -z "$(git status --porcelain)" ]; then
  echo "No changes found"
else
  echo "Pushing update to repository"
  git commit -am "$COMMIT_MESSAGE


Automatic commit for https://github.com/getparthenon/parthenon-dev/commit/$GITHUB_SHA"
  git push origin $BRANCH
fi

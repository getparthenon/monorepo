#!/bin/sh

echo "Starting creation of export bundle"

echo "[x] Removing old files"
rm -Rf ../export-bundle/src/Common/*
rm -Rf ../export-bundle/src/Export/*
rm -Rf ../export-bundle/src/Notification/*
rm -Rf ../export-bundle/src/DependencyInjection/*
rm -Rf ../export-bundle/src/Resources/config/services/*
mkdir ../export-bundle/src/Resources/config/services/orm/
mkdir ../export-bundle/src/Resources/config/services/odm/
mkdir ../export-bundle/src/Resources/config/services/
mkdir -p ../export-bundle/tests/Parthenon


echo "[x] Update Common"
cp -R src/Parthenon/Common ../export-bundle/src/
cp -R src/Parthenon/Resources/config/doctrine-mapping/Common ../export-bundle/src/Resources/config/doctrine-mapping/
cp -R src/Parthenon/Resources/config/services/common/pdf ../export-bundle/src/Resources/config/services/common/pdf
cp -R src/Parthenon/Resources/config/services/common.xml ../export-bundle/src/Resources/config/services/common.xml
cp -R src/Parthenon/DependencyInjection/Modules/Common.php ../export-bundle/src/DependencyInjection/Modules/Common.php
cp -R tests/Parthenon/Common ../export-bundle/tests/Parthenon/

echo "[x] Update Export"
cp -R src/Parthenon/Export ../export-bundle/src/
cp -R src/Parthenon/Resources/config/doctrine-mapping/Export ../export-bundle/src/Resources/config/doctrine-mapping/Export
cp -R src/Parthenon/Resources/config/services/orm/export.xml ../export-bundle/src/Resources/config/services/orm/export.xml
cp -R src/Parthenon/Resources/config/services/odm/export.xml ../export-bundle/src/Resources/config/services/odm/export.xml
cp -R src/Parthenon/Resources/config/services/export.xml ../export-bundle/src/Resources/config/services/export.xml
cp -R src/Parthenon/DependencyInjection/Modules/Export.php ../export-bundle/src/DependencyInjection/Modules/Export.php
cp -R tests/Parthenon/Export ../export-bundle/tests/Parthenon/

echo "[x] Update Notification"
cp -R src/Parthenon/Notification ../export-bundle/src/
cp -R src/Parthenon/DependencyInjection/Modules/Notification.php ../export-bundle/src/DependencyInjection/Modules/Notification.php
cp -R src/Parthenon/Resources/config/services/notification.xml ../export-bundle/src/Resources/config/services/notification.xml
cp -R tests/Parthenon/Notification ../export-bundle/tests/Parthenon/

cd ../export-bundle
git add -A
if [ -z "$(git status --porcelain)" ]; then
  echo "No changes found"
else
  echo "Pushing update to repository"
  git commit -am "$COMMIT_MESSAGE


Automatic commit for https://github.com/getparthenon/monorepo/commit/$GITHUB_SHA"
  git push origin $BRANCH
fi

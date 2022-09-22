#!/bin/sh

echo "Starting creation of public edition of the skeleton"

echo "[x] Removing old files"
rm -Rf ../skeleton/assets/components/*
rm -Rf ../skeleton/assets/helpers/*
rm -Rf ../skeleton/assets/services/*
rm -Rf ../skeleton/assets/store/*
rm -Rf ../skeleton/assets/styles/*
rm -Rf ../skeleton/assets/translations/*
rm -Rf ../skeleton/assets/views/*
rm -Rf ../skeleton/features/*


echo "[x] Update Core"
cp -R assets/components ../skeleton/assets
cp -R assets/helpers ../skeleton/assets
cp -R assets/services ../skeleton/assets
cp -R assets/store ../skeleton/assets
cp -R assets/styles ../skeleton/assets
cp -R assets/translations ../skeleton/assets
cp -R assets/views ../skeleton/assets
cp -R features/Skeleton/* ../skeleton/features

cd ../skeleton
git add -A
if [ -z "$(git status --porcelain)" ]; then
  echo "No changes found"
else
  echo "Pushing update to repository"
  git commit -am "$COMMIT_MESSAGE


Automatic commit for https://github.com/getparthenon/parthenon-dev/commit/$GITHUB_SHA"
  git push origin $BRANCH
fi

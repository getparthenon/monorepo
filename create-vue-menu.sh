#!/bin/sh

echo "Starting creation of public edition of vue-menu"

echo "[x] Removing old files"
rm -Rf ../vue-menu/src/*
rm -Rf ../vue-menu/package.json

echo "[x] Update Core"
cp -R js-packages/vue-menu/src ../vue-menu/
cp -R js-packages/vue-menu/package.json ../vue-menu/package.json

cd ../vue-menu
git add -A
if [ -z "$(git status --porcelain)" ]; then
  echo "No changes found"
else
  echo "Pushing update to repository"
  git commit -am "$COMMIT_MESSAGE


Automatic commit for https://github.com/getparthenon/monorepo/commit/$GITHUB_SHA"
  git push origin $BRANCH
fi

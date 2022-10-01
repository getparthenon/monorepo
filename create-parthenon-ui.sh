#!/bin/sh

echo "Starting creation of public edition of vue-menu"

echo "[x] Removing old files"
rm -Rf ../ui/src/*
rm -Rf ../ui/package.json

echo "[x] Update Core"
cp -R js-packages/ui/src ../ui/
cp -R js-packages/ui/package.json ../ui/package.json

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

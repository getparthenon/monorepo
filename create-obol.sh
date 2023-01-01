#!/bin/sh

echo "Starting creation of obol"

echo "[x] Removing old files"
mkdir -p ../obol/src
rm -Rf ../obol/src/*
mkdir -p ../obol/tests/Obol
rm -Rf ../obol/tests/Obol/*


echo "[x] Update Obol"
cp -R src/Obol/* ../obol/src/
cp -R tests/Obol/* ../obol/tests/Obol/



cd ../obol
vendor/bin/php-cs-fixer fix .

git add -A
if [ -z "$(git status --porcelain)" ]; then
  echo "No changes found"
else
  echo "Pushing update to repository"
  git commit -am "$COMMIT_MESSAGE


Automatic commit for https://github.com/getparthenon/monorepo/commit/$GITHUB_SHA"
  git push origin $BRANCH
fi

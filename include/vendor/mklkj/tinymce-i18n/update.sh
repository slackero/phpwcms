#!/usr/bin/env bash

set -ex

wget https://download.tiny.cloud/tinymce/community/languagepacks/8/langs.zip -O langs8.zip
wget https://download.tiny.cloud/tinymce/community/languagepacks/7/langs.zip -O langs7.zip
# wget https://download.tiny.cloud/tinymce/community/languagepacks/6/langs.zip -O langs6.zip
# wget https://download.tiny.cloud/tinymce/community/languagepacks/5/langs.zip -O langs5.zip
wget https://download.tiny.cloud/tinymce/community/languagepacks/4/langs.zip -O langs4.zip

npm install

rm -rf ./langs
# rm -rf ./langs5
# rm -rf ./langs6
rm -rf ./langs7
rm -rf ./langs8

unzip -q langs7.zip
mv langs langs7
./node_modules/.bin/js-beautify -q langs7/*

unzip -q langs8.zip
mv langs langs8
./node_modules/.bin/js-beautify -q langs8/*

# Use correct sed syntax for Linux vs macOS
if [[ "$OSTYPE" == "darwin"* ]]; then
  SED="sed -i ''"
else
  SED="sed -i"
fi

# Fix file content and rename files with underscores
for file in langs8/*_*; do
  [ -e "$file" ] || continue

  dashed="${file//_/-}"

  # change the id of the lang in the file
  $SED 's/_/-/g' "$file"

  # update the filename
  if [[ "$file" != "$dashed" ]]; then
    mv "$file" "$dashed"
    echo "Renamed: $file → $dashed"
  fi
done

# Fix macOS mistake: remove '' from filenames
for file in langs8/*\'\'; do
  [ -e "$file" ] || continue

  fixed="${file//\'\'}"
  mv "$file" "$fixed"
  echo "Fixed extra quotes: $file → $fixed"
done

# Remove _ versions if - version exists
for file in langs8/*_*; do
  [ -e "$file" ] || continue

  dashed="${file//_/-}"
  [ -e "$dashed" ] && rm "$file" && echo "Removed duplicate: $file"
done

# langs6 - disabled due to mismatch with langs7
# unzip -q langs6.zip
# mv langs langs6
# ./node_modules/.bin/js-beautify -q langs6/*

# langs5
# unzip -q langs5.zip
# mv langs langs5

# langs4
unzip -q langs4.zip

git apply patches/*

# stop script if there is no lang updates
git diff --quiet && git diff --cached --quiet && exit 0

echo "Changes in langs detected, updating version in package.json"

jq ".version = \"`date +%y.%-m.%-d`\"" package.json > tmp.$$.json && mv tmp.$$.json package.json
npm install

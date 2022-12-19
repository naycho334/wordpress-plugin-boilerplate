#!/bin/bash

index=1
DOMAIN=""

# get --languages argument
for i in "$@"; do
  # increment index
  index=$(($index + 1))

  # check if $i equals "languages"
  if [ "$i" == "--languages" ]; then

    LANGUAGES=${!index}

    # split $LANGUAGES by comma
    IFS=',' read -ra LOCALES <<<"$LANGUAGES"

    # loop through languages
    for i in "${LOCALES[@]}"; do
      # generate -new.po file
      php wp-cli.phar i18n make-pot ./ lang/$i-new.po --domain=$DOMAIN

      # check if -new.po file exists
      if [ -f lang/$i-new.po ]; then
        # check if old .po file exists
        if [ -f lang/$i.po ]; then
          # merge -new.po file with old .po file
          php wp-cli.phar i18n make-pot ./ lang/$i-new.po --domain=$DOMAIN --merge=lang/$i.po
        fi

        # rename -new.po file to -old.po
        mv lang/$i-new.po lang/$i.po
      fi
    done
  fi

  # check if $i equals "domain"
  if [ "$i" == "--domain" ]; then
    $DOMAIN=${!index}
  fi

done
# while [ $# -gt 0 ]; do

# done

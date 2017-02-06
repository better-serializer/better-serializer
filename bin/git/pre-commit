#!/bin/bash

PHPCS_BIN=./bin/phpcs
PHPCS_CODING_STANDARD=PSR2
PHPCS_IGNORE="tests/*,bin/*"
PHPCS_FILE_PATTERN="\.(php|phtml)$"
PHPCS_ENCODING=utf-8

PHPMD_BIN=./bin/phpmd
PHPMD_RULESETS=bin/static_analysis/phpmd.ruleset.xml

PHPLINT_OUTPUT=""
PHPLINT_RETVAL=0
PHPCS_OUTPUT=""
PHPCS_RETVAL=0
PHPCS_IGNORE_WARNINGS=0
PHPMD_OUTPUT=""
PHPMD_RETVAL=0

TMP_STAGING=".tmp_staging"

# Check if executables are present
if [ ! -x $PHPMD_BIN ]; then
    echo "PHP Mess Detector is missing -> $PHPMD_BIN";
    exit 1;
fi
if [ ! -x $PHPCS_BIN ]; then
    echo "PHP CodeSniffer is missing -> $PHPCS_BIN";
    exit 1;
fi

# stolen from template file
if git rev-parse --verify HEAD
then
    against=HEAD
else
    # Initial commit: diff against an empty tree object
    against=4b825dc642cb6eb9a060e54bf8d69288fbee4904
fi

# this is the magic:
# retrieve all files in staging area that are added, modified or renamed
# but no deletions etc
FILES=$(git diff-index --name-only --cached --diff-filter=ACMR $against -- )

if [ "$FILES" == "" ]; then
    exit 0;
fi

# create temporary copy of staging area
if [ -e $TMP_STAGING ]; then
    rm -rf $TMP_STAGING
fi
mkdir $TMP_STAGING

# match files against whitelist
PHP_FILES_TO_CHECK=""

for FILE in $FILES
do
    echo "$FILE" | egrep -q "$PHPCS_FILE_PATTERN"
    RETVAL=$?
    if [ "$RETVAL" -eq "0" ]
    then
        PHP_FILES_TO_CHECK="$PHP_FILES_TO_CHECK $FILE"
    fi
done

# Copy contents of staged version of files to temporary staging area
# because we only want the staged version that will be commited and not
# the version in the working directory
PHP_STAGED_FILES=""
for FILE in $PHP_FILES_TO_CHECK
do
  ID=$(git diff-index --cached HEAD $FILE | cut -d " " -f4)

  # create staged version of file in temporary staging area with the same
  # path as the original file so that the phpcs ignore filters can be applied
  mkdir -p "$TMP_STAGING/$(dirname $FILE)"
  git cat-file blob $ID > "$TMP_STAGING/$FILE"
  PHP_STAGED_FILES="$PHP_STAGED_FILES $TMP_STAGING/$FILE"
done

if [ "$PHP_STAGED_FILES" != "" ]; then
    # execute the code sniffer
    if [ "$PHPCS_IGNORE" != "" ]; then
        IGNORE="--ignore=$PHPCS_IGNORE"
    else
        IGNORE=""
    fi

    if [ "$PHPCS_ENCODING" != "" ]; then
        ENCODING="--encoding=$PHPCS_ENCODING"
    else
        ENCODING=""
    fi

    if [ "$PHPCS_IGNORE_WARNINGS" == "1" ]; then
        IGNORE_WARNINGS="-n"
    else
        IGNORE_WARNINGS=""
    fi

    for FILE in $PHP_STAGED_FILES
    do
        OUTPUT=$(php -l -d display_errors=0 $FILE)
        if [ $? -ne 0 ]; then
            PHPLINT_RETVAL=1
            PHPLINT_OUTPUT="$PHPLINT_OUTPUT $OUTPUT"
        fi
    done

    PHPCS_OUTPUT=$($PHPCS_BIN -s $IGNORE_WARNINGS  --standard=$PHPCS_CODING_STANDARD $ENCODING $IGNORE $PHP_STAGED_FILES)
    PHPCS_RETVAL=$?

#    PHPMD_OUTPUT=$($PHPMD_BIN $TMP_STAGING text $PHPMD_RULESETS --exclude $PHPCS_IGNORE)
    PHPMD_RETVAL=0
fi

# delete temporary copy of staging area
rm -rf $TMP_STAGING

EXIT_CODE=0;

# Display errors
if [ $PHPLINT_RETVAL -ne 0 ]; then
    echo "$PHPLINT_OUTPUT";
    echo "PHP Lint found some errors. You need to fix them to be able to commit.";
    EXIT_CODE=$PHPLINT_RETVAL;
fi
if [ $PHPCS_RETVAL -ne 0 ]; then
    echo "$PHPCS_OUTPUT";
    echo "PHP CodeSniffer found some errors. You need to fix them to be able to commit.";
    EXIT_CODE=$PHPCS_RETVAL;
fi
if [ $PHPMD_RETVAL -ne 0 ]; then
    echo "$PHPMD_OUTPUT";
    echo "PHP MassDetector found some errors. You need to fix them to be able to commit.";
    EXIT_CODE=$PHPMD_RETVAL;
fi

exit $EXIT_CODE;
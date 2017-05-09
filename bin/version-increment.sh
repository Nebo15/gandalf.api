#!/bin/bash
# This script increments patch version number in bootstrap/app.php according to a SEMVER spec.

# Find bootstrap/app.php inside project tree.
# This allows to call bash scripts within any folder inside project.
PROJECT_DIR=$(git rev-parse --show-toplevel)
if [ ! -f "${PROJECT_DIR}/bootstrap/app.php" ]; then
    echo "[E] Can't find '${PROJECT_DIR}/bootstrap/app.php'."
    echo "    Check that you run this script inside git repo or init a new one in project root."
fi

# Extract project name and version from bootstrap/app.php
PROJECT_NAME=$(sed -n 's/.*APPLICATION_NAME = "\([^"]*\)".*/\1/pg' "${PROJECT_DIR}/bootstrap/app.php")
PROJECT_VERSION=$(sed -n 's/.*APPLICATION_VERSION = "\([^"]*\)".*/\1/pg' "${PROJECT_DIR}/bootstrap/app.php")

# Increment patch version
# Source: https://github.com/fmahnke/shell-semver/blob/master/increment_version.sh
a=( ${PROJECT_VERSION//./ } )
((a[2]++))
NEW_PROJECT_VERSION="${a[0]}.${a[1]}.${a[2]}"

echo "[I] Incrementing project version from '${PROJECT_VERSION}' to '${NEW_PROJECT_VERSION}' in 'bootstrap/app.php'."
sed -i'' -e "s/APPLICATION_VERSION = \"${PROJECT_VERSION}\"/APPLICATION_VERSION = \"${NEW_PROJECT_VERSION}\"/g" "${PROJECT_DIR}/bootstrap/app.php"
# Here you can modify other files (for eg. README.md) that contains version.
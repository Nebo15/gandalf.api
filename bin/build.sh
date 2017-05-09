#!/bin/bash
# This script builds an image based on a Dockerfile that is located in root of git working tree.

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
echo "[I] Building a Docker container '${PROJECT_NAME}' (version '${PROJECT_VERSION}') from path '${PROJECT_DIR}'.."

docker build --tag "${PROJECT_NAME}:${PROJECT_VERSION}" \
             --file "${PROJECT_DIR}/Dockerfile" \
             $PROJECT_DIR
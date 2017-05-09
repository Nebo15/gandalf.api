#!/bin/bash
# This script starts a local Docker container with created image.

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
HOST_IP=`ifconfig | sed -En 's/127.0.0.1//;s/.*inet (addr:)?(([0-9]*\.){3}[0-9]*).*/\2/p' | head -n 1`
HOST_NAME="travis"

echo "[I] Starting a Docker container '${PROJECT_NAME}' (version '${PROJECT_VERSION}') from path '${PROJECT_DIR}'.."
echo "[I] Assigning parent host '${HOST_NAME}' with IP '${HOST_IP}'."

docker run -p 9001:9000 \
    --env-file .env \
    -d \
    --add-host=$HOST_NAME:$HOST_IP \
    --name ${PROJECT_NAME} \
    "${PROJECT_NAME}:${PROJECT_VERSION}"
#!/bin/bash

# This script simplifies releasing a new Docker image of your release.
# It will run following steps:
#   1. Create git tag with version number specified in app.php
#   2. Tag Docker container that is created by build.sh script to a Docker Hub repo.
#   3. Upload changes to Docker Hub.
#
# Usage:
# ./bin/release.sh -a DOCKER_HUB_ACCOUNT_NAME [-v RELEASE_VERSION -l -f]
#   '-l' - create additional tag :latest.
#   '-f' - force tag creating when git working tree is not empty.

# Find bootstrap/app.php inside project tree.
# This allows to call bash scripts within any folder inside project.
PROJECT_DIR=$(git rev-parse --show-toplevel)
if [ ! -f "${PROJECT_DIR}/bootstrap/app.php" ]; then
    echo "[E] Can't find '${PROJECT_DIR}/bootstrap/app.php'."
    echo "    Check that you run this script inside git repo or init a new one in project root."
fi

# Extract project name and version
PROJECT_NAME=$(sed -n 's/.*APPLICATION_NAME = "\([^"]*\)".*/\1/pg' "${PROJECT_DIR}/bootstrap/app.php")
PROJECT_VERSION=$(sed -n 's/.*APPLICATION_VERSION = "\([^"]*\)".*/\1/pg' "${PROJECT_DIR}/bootstrap/app.php")
REPO_TAG=$PROJECT_VERSION

# A POSIX variable
OPTIND=1 # Reset in case getopts has been used previously in the shell.

# Default settings
IS_LATEST=0
IS_STABLE=0

if git diff-index --quiet HEAD --; then
  PASS_GIT=1
    # no changes
else
  PASS_GIT=0
fi

# Parse ARGS
while getopts "v:lsa:ft:" opt; do
  case "$opt" in
    a)  HUB_ACCOUNT=$OPTARG
        ;;
    v)  PROJECT_VERSION=$OPTARG
        ;;
    t)  REPO_TAG=$OPTARG
        ;;
    l)  IS_LATEST=1
        ;;
    s)  IS_STABLE=1
        ;;
    f)  PASS_GIT=1
        ;;
  esac
done

if [ ! $HUB_ACCOUNT ]; then
  echo "[E] You need to specify Docker Hub account with '-a' option."
  exit 1
fi

# Get release notes
PREVIOUS_TAG=$(git describe HEAD^1 --abbrev=0 --tags)
GIT_HISTORY=$(git log --no-merges --format="- %s (%an)" $PREVIOUS_TAG..HEAD | grep --invert-match "ci skip" | grep --invert-match "changelog skip")

if [[ $PREVIOUS_TAG == "" ]]; then
  GIT_HISTORY=$(git log --no-merges --format="- %s (%an)" | grep --invert-match "ci skip" | grep --invert-match "changelog skip")
fi;

# Create git tag that matches release version
if [ `git tag --list $PROJECT_VERSION` ]; then
  echo "[W] Git tag '${PROJECT_VERSION}' already exists. I won't be created during release."
else
  if [ ! $PASS_GIT ]; then
    echo "[E] Working tree contains uncommited changes. This may cause wrong relation between image tag and git tag."
    echo "    You can skip this check with '-f' option."
    exit 1
  else
    echo "[I] Creating git tag '${PROJECT_VERSION}'.."
    echo "    Release Notes: "
    echo $GIT_HISTORY

    git tag -a $PROJECT_VERSION -m "${GIT_HISTORY}"
  fi
fi

if [ "${REPO_TAG}" != "${PROJECT_VERSION}" ]; then
  echo "[I] Tagging image '${PROJECT_NAME}:${PROJECT_VERSION}' into a Docker Hub repository '${HUB_ACCOUNT}/${PROJECT_NAME}:${REPO_TAG}'.."
  docker tag "${PROJECT_NAME}:${PROJECT_VERSION}" "${HUB_ACCOUNT}/${PROJECT_NAME}:${REPO_TAG}"
fi

echo "[I] Tagging image '${PROJECT_NAME}:${PROJECT_VERSION}' into a Docker Hub repository '${HUB_ACCOUNT}/${PROJECT_NAME}:${PROJECT_VERSION}'.."
docker tag "${PROJECT_NAME}:${PROJECT_VERSION}" "${HUB_ACCOUNT}/${PROJECT_NAME}:${PROJECT_VERSION}"

if [ $IS_LATEST == 1 ]; then
  echo "[I] Assigning additional tag '${HUB_ACCOUNT}/${PROJECT_NAME}:latest'.."
  docker tag "${PROJECT_NAME}:${PROJECT_VERSION}" "${HUB_ACCOUNT}/${PROJECT_NAME}:latest"
fi

if [ $IS_STABLE == 1 ]; then
  echo "[I] Assigning additional tag '${HUB_ACCOUNT}/${PROJECT_NAME}:stable'.."
  docker tag "${PROJECT_NAME}:${PROJECT_VERSION}" "${HUB_ACCOUNT}/${PROJECT_NAME}:stable"
fi

echo "[I] Pushing changes to Docker Hub.."
docker push "${HUB_ACCOUNT}/${PROJECT_NAME}"

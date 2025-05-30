#!/bin/bash
set -eo pipefail

if [[ -z "$SVN_USERNAME" ]]; then
	echo "Set the SVN_USERNAME secret"
	exit 1
fi

if [[ -z "$SVN_PASSWORD" ]]; then
	echo "Set the SVN_PASSWORD secret"
	exit 1
fi

if [[ -z "$PLUGIN_VERSION" ]]; then
	echo "Set the PLUGIN_VERSION env var"
	exit 1
fi

if [[ -z "$PLUGIN_SLUG" ]]; then
	echo "Set the PLUGIN_SLUG env var"
	exit 1
fi

# Ensure SVN is installed
svn --version

echo "SVN installed"

echo "Publish version: ${PLUGIN_VERSION}"

PLUGIN_PATH="$GITHUB_WORKSPACE"
SVN_PATH="$GITHUB_WORKSPACE/svn"

cd $PLUGIN_PATH
pwd
mkdir -p $SVN_PATH
cd $SVN_PATH

echo "Checkout from SVN"
svn co https://plugins.svn.wordpress.org/${PLUGIN_SLUG}/trunk

echo "Clean trunk folder"
cd $SVN_PATH/trunk
find . -maxdepth 1 -not -name ".svn" -not -name "." -not -name ".." -exec rm -rf {} +

echo "Copy files"
rsync -ah --progress $PLUGIN_PATH/* $SVN_PATH/trunk

echo "Preparing files"
cd $SVN_PATH/trunk

echo "svn delete"
svn status | grep -v '^.[ \t]*\\..*' | { grep '^!' || true; } | awk '{print $2}' | xargs -r svn delete || true

echo "svn add"
svn status | grep -v '^.[ \t]*\\..*' | { grep '^?' || true; } | awk '{print $2}' | xargs -r svn add || true

svn status

echo "Commit files to trunk"
svn ci -m "Upload v${PLUGIN_VERSION}" --no-auth-cache --non-interactive  --username "$SVN_USERNAME" --password "$SVN_PASSWORD"

echo "Copy files from trunk to tag ${PLUGIN_VERSION}"
svn cp https://plugins.svn.wordpress.org/${PLUGIN_SLUG}/trunk https://plugins.svn.wordpress.org/${PLUGIN_SLUG}/tags/${PLUGIN_VERSION} --message "Tagged ${PLUGIN_VERSION}" --no-auth-cache --non-interactive  --username "$SVN_USERNAME" --password "$SVN_PASSWORD"
svn update

echo "Remove the SVN folder from the workspace (for multiple releases in the same Action)"
rm -rf $SVN_PATH

echo "Back to the workspace root"
cd $GITHUB_WORKSPACE

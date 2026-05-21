#!/usr/bin/env bash
set -euo pipefail

if [ $# -ne 5 ]; then
  echo "Usage: $0 <source-repo> <target-repo> <source-branch-name> <target-branch-name> <commit-msg>"
  echo "Example: $0 InterNACHI/starwatch-public you/starwatch-2026-jane-doe feature-branch jane-doe-feature 'New feature'"
  exit 1
fi

SOURCE_REPO="$1"
TARGET_REPO="$2"
SOURCE_BRANCH_NAME="$3"
TARGET_BRANCH_NAME="$4"
COMMIT_MESSAGE="$5"

TMPDIR=$(mktemp -d)
PATCH=$(mktemp)

# Clone source (just main and the source branch)
gh repo clone "$SOURCE_REPO" "$TMPDIR/source" -- --single-branch --branch "$SOURCE_BRANCH_NAME"
git -C "$TMPDIR/source" fetch origin main:main

# Clone target
gh repo clone "$TARGET_REPO" "$TMPDIR/target"

# Export diff from source
git -C "$TMPDIR/source" diff main.."$SOURCE_BRANCH_NAME" > "$PATCH"

# Apply to target
git -C "$TMPDIR/target" checkout -b "$TARGET_BRANCH_NAME" main
git -C "$TMPDIR/target" apply "$PATCH"
git -C "$TMPDIR/target" add -A
git -C "$TMPDIR/target" commit -m "$COMMIT_MESSAGE"
git -C "$TMPDIR/target" push origin "$TARGET_BRANCH_NAME"

# Cleanup
rm -f "$PATCH"
rm -rf "$TMPDIR"

echo "✅ Branch '${TARGET_BRANCH_NAME}' pushed to ${TARGET_REPO}"

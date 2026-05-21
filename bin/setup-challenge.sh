#!/usr/bin/env bash
set -euo pipefail

# ---- Configuration ----
SOURCE_REPO="https://github.com/InterNACHI/starwatch-public.git"
ORG="InterNACHI"
YEAR=$(date +%Y)

# Pass candidate names as arguments, e.g.:
# ./setup-challenge.sh jane-doe john-smith
if [ $# -eq 0 ]; then
  echo "Usage: $0 <candidate-name> [candidate-name ...]"
  echo "Example: $0 jane-doe john-smith"
  exit 1
fi

# ---- Clone source once ----
TMPDIR=$(mktemp -d)
git clone --bare "$SOURCE_REPO" "$TMPDIR/starwatch.git"

for CANDIDATE in "$@"; do
  REPO_NAME="starwatch-${YEAR}-${CANDIDATE}"
  FULL_REPO="${ORG}/${REPO_NAME}"

  echo "==> Setting up ${FULL_REPO}..."

  # Create private repo
  gh repo create "$FULL_REPO" --private

  # Push mirror
  git -C "$TMPDIR/starwatch.git" push --mirror "https://github.com/${FULL_REPO}.git"

  # Add candidate as collaborator (assumes GH username matches candidate name)
  # Update this if their GH username differs
  gh api "repos/${FULL_REPO}/collaborators/${CANDIDATE}" -X PUT -f permission=push

  echo "    ✅ https://github.com/${FULL_REPO}"
done

# ---- Cleanup ----
rm -rf "$TMPDIR"

echo ""
echo "Done! All candidate repos created."

#!/usr/bin/env bash
set -euo pipefail

# ---- Configuration ----
SOURCE_REPO="https://github.com/InterNACHI/starwatch-public.git"
ORG="inxilpro"
REVIEWERS=("bogdankharchenko" "skylerkatz")
YEAR=$(date +%Y)

echo ""

# Pass candidate names as arguments, e.g.:
# ./setup-challenge.sh jane-doe john-smith
if [ $# -eq 0 ]; then
  echo "Usage: $0 <candidate-name> [candidate-name ...]"
  echo "Example: $0 jane-doe john-smith"
  echo ""
  exit 1
fi

# ---- Clone source once (main only, single commit) ----
TMPDIR=$(mktemp -d)
git clone --single-branch --branch main "$SOURCE_REPO" "$TMPDIR/starwatch"

# Squash to a single commit
cd "$TMPDIR/starwatch"
git reset --soft "$(git rev-list --max-parents=0 HEAD)"
git commit --amend -m "Initial commit"
cd -

for CANDIDATE in "$@"; do
  REPO_NAME="starwatch-${YEAR}-${CANDIDATE}"
  FULL_REPO="${ORG}/${REPO_NAME}"

  echo "==> Setting up ${FULL_REPO}..."

  # Create private repo
  gh repo create "$FULL_REPO" --private -d "Private StarWatch ${YEAR} code challenge for @${CANDIDATE}"

  # Push main branch only
  git -C "$TMPDIR/starwatch" push "git@github.com:${FULL_REPO}.git" main

  # Add candidate as collaborator (assumes GH username matches candidate name)
  # Update this if their GH username differs
  gh api "repos/${FULL_REPO}/collaborators/${CANDIDATE}" -X PUT -f permission=push --silent
  
  # Add reviewers as collaborators
  for REVIEWER in "${REVIEWERS[@]}"; do
    gh api "repos/${FULL_REPO}/collaborators/${REVIEWER}" -X PUT -f permission=push --silent
  done

  echo "    ✅ https://github.com/${FULL_REPO}"
done

# ---- Cleanup ----
rm -rf "$TMPDIR"

echo ""
echo "Done! All candidate repos created."
echo ""

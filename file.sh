#!/usr/bin/env bash
set -euo pipefail

ROOT=".claude"

dirs=(
  "$ROOT/skills/task-planning"
  "$ROOT/skills/php-feature-delivery"
  "$ROOT/skills/contract-sync"
  "$ROOT/skills/database-change-safety"
  "$ROOT/skills/test-execution"
  "$ROOT/skills/security-hardening"
  "$ROOT/skills/ci-debugging"
  "$ROOT/agents"
)

files=(
  "$ROOT/skills/task-planning/SKILL.md"
  "$ROOT/skills/task-planning/README.md"
  "$ROOT/skills/php-feature-delivery/SKILL.md"
  "$ROOT/skills/php-feature-delivery/README.md"
  "$ROOT/skills/contract-sync/SKILL.md"
  "$ROOT/skills/contract-sync/README.md"
  "$ROOT/skills/database-change-safety/SKILL.md"
  "$ROOT/skills/database-change-safety/README.md"
  "$ROOT/skills/test-execution/SKILL.md"
  "$ROOT/skills/test-execution/README.md"
  "$ROOT/skills/security-hardening/SKILL.md"
  "$ROOT/skills/security-hardening/README.md"
  "$ROOT/skills/ci-debugging/SKILL.md"
  "$ROOT/skills/ci-debugging/README.md"
  "$ROOT/skills/README.md"
  "$ROOT/settings.local.json"
  "$ROOT/agents/architect.md"
  "$ROOT/agents/php-engineer.md"
  "$ROOT/agents/frontend-integration-engineer.md"
  "$ROOT/agents/security-reviewer.md"
  "$ROOT/agents/code-reviewer.md"
  "$ROOT/agents/test-automator.md"
  "$ROOT/agents/devops-engineer.md"
)

mkdir -p "${dirs[@]}"

for file in "${files[@]}"; do
  mkdir -p "$(dirname "$file")"
  touch "$file"
done
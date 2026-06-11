#!/usr/bin/env bash
# Wrapper gọi `podman compose` hoặc `docker compose` (ưu tiên Podman nếu có).
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
COMPOSE_FILE="${COMPOSE_FILE:-$ROOT/compose.yaml}"

if [[ ! -f "$COMPOSE_FILE" ]]; then
  COMPOSE_FILE="$ROOT/docker-compose.yml"
fi

run_compose() {
  local runtime="$1"
  shift

  if [[ "$runtime" == "podman" ]]; then
    exec podman compose -f "$COMPOSE_FILE" "$@"
  fi

  exec docker compose -f "$COMPOSE_FILE" "$@"
}

if command -v podman >/dev/null 2>&1; then
  if podman compose version >/dev/null 2>&1; then
    run_compose podman "$@"
  elif command -v podman-compose >/dev/null 2>&1; then
    exec podman-compose -f "$COMPOSE_FILE" "$@"
  fi
fi

if command -v docker >/dev/null 2>&1 && docker compose version >/dev/null 2>&1; then
  run_compose docker "$@"
fi

echo "Không tìm thấy Podman Compose hoặc Docker Compose." >&2
echo "Cài Podman (khuyến nghị) hoặc Docker Desktop, rồi thử lại." >&2
exit 1

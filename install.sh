#!/usr/bin/env bash

COMPOSE_DIR="link-shortener"
COMPOSER_CONTAINER="laravel.test"

run_compose() {
  if command -v docker compose >/dev/null 2>&1; then
	echo "Using docker compose"
	docker compose -f $COMPOSE_DIR/docker-compose.yml up -d
  elif command -v docker-compose >/dev/null 2>&1; then
	echo "Using docker-compose (legacy)"
	docker-compose -f $COMPOSE_DIR/docker-compose.yml up -d
  else
	echo "Error: Neither docker compose nor docker-compose found. Please install one of them."
	exit 1
  fi
}

run_composer_install() {
  if command -v docker compose >/dev/null 2>&1; then
	echo "Using docker compose to run composer install"
	docker compose -f $COMPOSE_DIR/docker-compose.yml exec $COMPOSER_CONTAINER composer install
  elif command -v docker-compose >/dev/null 2>&1; then
	echo "Using docker-compose (legacy) to run composer install"
	docker-compose -f $COMPOSE_DIR/docker-compose.yml exec $COMPOSER_CONTAINER composer install
  else
	echo "Error: Neither docker compose nor docker-compose found."
	exit 1
  fi
}

# Give user feedback and run compose
echo "Bringing up containers..."
run_compose

# Run composer install inside the container
echo "Running composer install inside '$COMPOSER_CONTAINER' container..."
run_composer_install
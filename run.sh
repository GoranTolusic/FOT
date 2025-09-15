#!/bin/bash
set -e

# Clear old containers
docker stop web || true
docker stop app || true
docker container prune -f

# Build containers
docker-compose build

# Run compose in detached mode wth all services
docker-compose up -d
sleep 3
docker logs -f app


#!/bin/bash

# Create a builder instance if not exists
docker buildx create --name mybuilder --use || true

# Build and push the Docker image for amd64 architecture directly to Docker Hub
docker buildx build --platform linux/amd64 \
  --tag sschonss/social-care-backend:latest \
  --push \
  .

# Check if the build was successful
if [ $? -eq 0 ]; then
  echo "Docker image built and pushed successfully!"
  echo "To run the container: docker run -p 80:80 -d --name social-care-backend sschonss/social-care-backend:latest"
else
  echo "ERROR: Docker build failed. Please check the error messages above."
  exit 1
fi

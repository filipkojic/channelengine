#!/bin/bash

MODULE_NAME="channelengine"
OUTPUT_DIR="deploy"
VERSION="1.0.0"

# Kreiraj deploy direktorijum ako ne postoji
mkdir -p $OUTPUT_DIR

# Kreiraj ZIP arhivu sa nazivom koji sadrži ime modula i verziju
ZIP_NAME="${OUTPUT_DIR}/${MODULE_NAME}-${VERSION}.zip"

# Kreiraj ZIP arhivu, isključujući nepotrebne fajlove i direktorijume
zip -r $ZIP_NAME . -x "deploy/*" "*.git/*" "*.idea/*" "*.DS_Store" "tests/*" "*.gitignore" "*.vscode/*" "composer.json" "composer.lock"

echo "Deployment package created: $ZIP_NAME"


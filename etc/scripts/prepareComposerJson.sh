#!/usr/bin/env bash

sed -i "s#[\"]shopware/shopware[\"]: [\"]^5.6[\"]#\"shopware/shopware\": \"${SHOPWARE_VERSION}\"#g" composer.json

#!/usr/bin/env bash

clear; cd "${0%/*}"
open -F http://localhost:8080
php -S localhost:8080 -t ./$1
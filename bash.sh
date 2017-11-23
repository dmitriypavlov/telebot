#!/usr/bin/env bash

php="/usr/bin/env php"
username="username"

send() {
	text=$(printf "$1")
    $php $(dirname "$0")/send.php $username "$text"
}

send "text message"

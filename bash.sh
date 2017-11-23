#!/usr/bin/env bash

php="/usr/bin/env php"
username="username"

send() {
	$php $(dirname "$0")/send.php $username "$1"
}

send "text message"

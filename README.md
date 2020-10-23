## telebot.php

TeleBot is a simple PHP class to use with Telergram Bot API.

### Features
* sends text directly to @username
* automatically gets and saves @username chat ID

### Requirements
* Telegram bot
* API token

### Usage
1. create a Telegram bot and save API **token** to `telebot.json`
2. @username required to send bot a `/start` command to subscribe
3. send message to @username `telebot('username', 'text message')`

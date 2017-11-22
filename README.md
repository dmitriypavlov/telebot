## TeleBot.class.php

TeleBot is a simple PHP class to use with Telergram Bot API.

### Features
* can be used in webserver or CLI mode
* sends text directly to @username
* automatically gets and saves @username chat ID

### Usage
1. create a Telegram bot and save API **token** to `TeleBot.config.json`
2. @username required to send bot a `/start` command to subscribe
3. send message to @username in **webserver mode** `$obj->send('username', 'text message')` **or**
`php telebot.php username 'text message'` **CLI mode**

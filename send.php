#!/usr/bin/env php
<?php

require 'TeleBot.class.php';

$bot = new TeleBot();
$bot->send($argv[1], $argv[2]);

//$bot->send('username', 'text message');

?>
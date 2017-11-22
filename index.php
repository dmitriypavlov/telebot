<?php

require 'TeleBot.class.php';

$bot = new TeleBot();
$bot->send('username', 'hello world');

?>
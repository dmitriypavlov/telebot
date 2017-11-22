<?php

require 'TeleBot.class.php';

$bot = new TeleBot();

echo "<pre>";

echo $bot->send('dmitriypavlovcom', 'hello world');

?>
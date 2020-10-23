<?php

class TeleBot {
	
	private $botAPI = "https://api.telegram.org/bot";
	private $configJSON = "/storage/telebot.json";
	private $configData = [];
	private $updatesData = [];
	
	public function __construct() {

		$this->configJSON = dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->configJSON;
		$this->loadConfig();
		$this->getUpdates();
	}
	
	public function __toString() {

		return json_encode($this->updatesData, JSON_PRETTY_PRINT);
	}
	
	final protected function loadConfig() {

		$json = file_get_contents($this->configJSON);
		$this->configData = json_decode($json, JSON_OBJECT_AS_ARRAY);
	}
	
	final protected function saveConfig() {

		$json = json_encode($this->configData, JSON_FORCE_OBJECT | JSON_NUMERIC_CHECK | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
		file_put_contents($this->configJSON, $json, LOCK_EX);
	}
	
	final protected function getUpdates() {

		$query = $this->botAPI . $this->configData['token'] . "/getUpdates?offset=0";
		$result = file_get_contents($query);
		$this->updatesData = json_decode($result, JSON_OBJECT_AS_ARRAY);
	}
	
	final protected function getChatID($name) {

		foreach($this->updatesData['result'] as $update) {

			if ($update['message']['chat']['username'] == $name || $update['message']['chat']['title'] == $name) {

				return $update['message']['chat']['id'];
			}
		}
		
		return "error";
	}
	
	final protected function sendMessage($chatID, $text) {

		$text = urlencode($text);
		$query = $this->botAPI . $this->configData['token'] . "/sendMessage?chat_id=" . $chatID . "&text=" . $text;
		
		if ($result = @file_get_contents($query)) {

			$result = json_decode($result, JSON_OBJECT_AS_ARRAY);

		} else {
			
			$result = "error";
		}
		
		return $result;
	}
	
	final public function send($name, $text) {

		if (is_numeric($this->configData['users'][$name])) {

			$chatID = $this->configData['users'][$name];

		} else {

			$chatID = $this->getChatID($name);
			$this->configData['users'][$name] = $chatID;
			$this->saveConfig();
		}
		
		$result = $this->sendMessage($chatID, $text);
		
		return $result;
	}  
}

//function telebot($chat, $message) {
//
//	$telebot = new TeleBot();
//	$telebot->send($chat, $message);
//}

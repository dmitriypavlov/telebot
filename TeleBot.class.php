<?php

class TeleBot {
    
    private $botAPI = 'https://api.telegram.org/bot';
    private $configJSON = 'TeleBot.config.json';
    private $configData = array();
    private $updatesData = array();
    
    public function __construct() {
        $this->configJSON = dirname(__FILE__) . DIRECTORY_SEPARATOR . $this->configJSON;
        $this->loadConfig();
    }
    
    public function __toString() {
        $this->getUpdates();
        $info = print_r($this->updatesData, true);
        
        return $info;
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
    
    final protected function getChatID($username) {        
        $this->getUpdates();
        
        foreach($this->updatesData['result'] as $update) {
            if ($update['message']['chat']['username'] == $username) {
                $chatID = $update['message']['chat']['id'];
            } else {
                $chatID = 'error';
            }
        }
        
        return $chatID;
    }
    
    final protected function sendMessage($chatID, $text) {
        $text = urlencode($text);
        $query = $this->botAPI . $this->configData['token'] . "/sendMessage?chat_id=" . $chatID . "&text=" . $text;
        
        if ($result = @file_get_contents($query)) {
            $result = json_decode($result, JSON_OBJECT_AS_ARRAY);
            $result = print_r($result, true);
        } else {
            $result = 'error';
        }
        
        return $result;
    }
    
    final public function send($username, $text) {
        // check $username is 9 digits $chatID
        if (preg_match('/^[0-9]{9}$/', $this->configData['users'][$username])) {
            $chatID = $this->configData['users'][$username];
        } else {
            $chatID = $this->getChatID($username);
            $this->configData['users'][$username] = $chatID;
            $this->saveConfig();
        }
        
        $result = $this->sendMessage($chatID, $text);
        
        return $result;
    }
    
}

?>
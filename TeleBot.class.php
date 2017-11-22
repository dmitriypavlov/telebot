<?php

class TeleBot {
    
    private $apiRoot = 'https://api.telegram.org/bot';
    private $confJSON = 'TeleBot.conf.json';
    private $confData = array();
    private $botUpdates = array();
    
    public function __construct() {
        $this->loadConf();
        $this->getUpdates();
    }
    
    final protected function loadConf() {
        $json = file_get_contents($this->confJSON);
        $this->confData = json_decode($json, JSON_OBJECT_AS_ARRAY);
    }
    
    final protected function getUpdates() {
        $query = $this->apiRoot . $this->confData['api_token'] . "/getUpdates?offset=0";
        $result = file_get_contents($query);
        $this->botUpdates = json_decode($result, JSON_OBJECT_AS_ARRAY);
    }
    
    final protected function getChatID($username) {
        foreach($this->botUpdates['result'] as $update) {
            if ($update['message']['chat']['username'] == $username) {
                $chatID = $update['message']['chat']['id'];
            } else {
                $chatID = 'error';
            }
        }
        
        return $chatID;
    }
    
    final protected function sendMessage($chatID, $text) {
        $query = $this->apiRoot . $this->confData['api_token'] . "/sendMessage?chat_id=" . $chatID . "&text=" . $text;
        if ($result = @file_get_contents($query)) {
            return $result;
        } else {
            return 'error';
        }
    }
    
    final public function send($username, $text) {
        $chatID = getChatID($username);
        $result = sendMessage($chatID, $text);
        
        return $result;
    }
    
}

?>
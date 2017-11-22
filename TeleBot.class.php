<?php

// TODO: save chat_id to $confJSON
// TODO: load chat_id from $confJSON

class TeleBot {
    
    private $apiRoot = 'https://api.telegram.org/bot';
    private $confJSON = 'TeleBot.conf.json';
    private $confData = array();
    private $botUpdates = array();
    
    public function __construct() {
        $this->loadConf();
        $this->getUpdates();
    }
    
    public function __toString() {
        $info = print_r($this->confData, true);
        
        return $info;
    }
    
    final protected function loadConf() {
        $json = file_get_contents($this->confJSON);
        $this->confData = json_decode($json, JSON_OBJECT_AS_ARRAY);
    }
    
    final protected function saveConf() {
        $json = json_encode($this->confData, JSON_FORCE_OBJECT | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        file_put_contents($this->$confJSON, $json, LOCK_EX);
    }
    
    final protected function getUpdates() {
        $query = $this->apiRoot . $this->confData['token'] . "/getUpdates?offset=0";
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
        $query = $this->apiRoot . $this->confData['token'] . "/sendMessage?chat_id=" . $chatID . "&text=" . $text;
        
        if ($result = @file_get_contents($query)) {
            $result = json_decode($result, JSON_OBJECT_AS_ARRAY);
            $result = print_r($result, true);
        } else {
            $result = 'error';
        }
        
        return $result;
    }
    
    final public function send($username, $text) {
        $chatID = $this->getChatID($username);
        $result = $this->sendMessage($chatID, $text);
        
        return $result;
    }
    
}

?>
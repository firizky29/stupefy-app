<?php

require_once __DIR__ . '/../database/database.php';
require_once __DIR__ . '/../config/constants.php';

class Subscription{
    private $db;
    private $table = DB_SUBSCRIPTION_TABLE;

    public function __construct(){
        $this->db = new Database();
    }

    public function getAllAccepted(){
        $this->db->prepare("SELECT creator_id FROM $this->table");
        return $this->db->getAll();
    }
    
    public function getAcceptedBySubscriber($id){
        $this->db->prepare("SELECT creator_id FROM $this->table WHERE subscriber = :id");
        $this->db->bind(':id', $id);
        return $this->db->getAll();
    }
}

?>
<?php

require_once __DIR__ . '/../database/database.php';
require_once __DIR__ . '/../config/constants.php';

class Subscription{
    private $db;
    private $table = DB_USER_TABLE;

    public function __construct(){
        $this->db = new Database();
    }

    public function getAll(){
        $this->db->prepare("SELECT * FROM $this->table");
        return $this->db->getAll();
    }

    public function addPendingSubs($creator_id, $subscriber) {
        $this->db->prepare("INSERT INTO subscription (creator_id, subscriber) VALUES (:creator_id, :subscriber)");
        $this->db->bind(':creator_id', $creator_id);
        $this->db->bind(':subscriber', $subscriber);
        return $this->db->execute();
    }
    
    public function update($creator_id, $subscriber, $status) {
        $this->db->prepare("UPDATE subscription SET status= '$status' WHERE creator_id=:creator_id AND subscriber=:subscriber");
        // $this->db->bind(':status', $status);
        $this->db->bind(':creator_id', $creator_id);
        $this->db->bind(':subscriber', $subscriber);
        return $this->db->execute();
    }

    public function isExist($creator_id, $subscriber, $status) {
        $this->db->prepare("SELECT * FROM subscription WHERE creator_id=:creator_id AND subscriber=:subscriber AND status='$status'");
        $this->db->bind(':creator_id', $creator_id);
        $this->db->bind(':subscriber', $subscriber);
        return $this->db->getOne();
    }
}

?>
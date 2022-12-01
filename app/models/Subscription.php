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
        $this->db->prepare("SELECT creator_id FROM $this->table WHERE status = 'ACCEPTED'");
        return $this->db->getAll();
    }

    public function getAllPending() {
        $this->db->prepare("SELECT creator_id FROM $this->table WHERE status = 'PENDING'");
        return $this->db->getAll();
    }

    public function addPendingSubs($creator_id, $subscriber) {
        $this->db->prepare("INSERT INTO subscription (creator_id, subscriber) VALUES (:creator_id, :subscriber)");
        $this->db->bind(':creator_id', $creator_id);
        $this->db->bind(':subscriber', $subscriber);
        return $this->db->execute();
    }
    
    public function getAcceptedBySubscriber($id){
        $this->db->prepare("SELECT creator_id FROM $this->table WHERE subscriber = :id AND status = 'ACCEPTED'");
        $this->db->bind(':id', $id);
        return $this->db->getAll();
    }

    public function getPendingBySubscriber($id) {
        $this->db->prepare("SELECT creator_id FROM $this->table WHERE subscriber = :id AND status = 'PENDING'");
        $this->db->bind(':id', $id);
        return $this->db->getAll();
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
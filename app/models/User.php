<?php

require_once __DIR__ . '/../database/database.php';
require_once __DIR__ . '/../config/constants.php';

class User{
    private $db;
    private $table = DB_USER_TABLE;

    public function __construct(){
        $this->db = new Database();
    }

    public function getAll(){
        $this->db->prepare("SELECT * FROM $this->table");
        return $this->db->getAll();
    }

    public function getByID($id){
        $this->db->prepare("SELECT * FROM $this->table WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->getOne();
    }

    public function get($username, $is_admin = false, $offset, $limit){
        $this->db->prepare("SELECT * FROM $this->table WHERE username = :username AND is_admin = :is_admin LIMIT :offset, :limit");
        $this->db->bind(':username', $username);
        $this->db->bind(':is_admin', $is_admin);
        $user = $this->db->getOne();
        if($user){
            if($is_admin){
                if($user['is_admin'] == 1){
                    return $user;
                }
            }else{
                return $user;
            }
        }
        return false;
    }

    public function signup($data){
        if(!isset($data['name']) || !isset($data['username']) || !isset($data['email']) || !isset($data['password'])){
            return false;
        }
        $this->db->prepare("INSERT INTO $this->table (name, username, email, password, isAdmin) VALUES (:name, :username, :email, :password, false)");
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $id = $this->db->lastInsertId();
        if(!empty($id)){
            return $id;
        } else {
            return false;
        }
    }
}

?>
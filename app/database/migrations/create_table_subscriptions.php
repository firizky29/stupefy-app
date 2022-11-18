<?php

require_once __DIR__ . '/../database.php';
require_once __DIR__ . '/../../config/constants.php';

function create_table_subscriptions(){
    $db = new Database();
    $db->prepare('CREATE TABLE IF NOT EXISTS subscription(
        creator_id INT NOT NULL,
        subscriber INT NOT NULL,
        status ENUM("PENDING", "ACCEPTED", "REJECTED") NOT NULL DEFAULT "PENDING",
        last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY(creator_id, subscriber),
        FOREIGN KEY(subscriber) REFERENCES user(user_id)
        ON DELETE CASCADE
    )');

    $db->execute();
}

?>
<?php
    require_once __DIR__ . '/../models/Subscription.php';

    if(isset($_GET['id'])){
        $subscription = new Subscription();
        $subscription = $subscription->getAcceptedBySubscriber(intval($_GET['id']));
        echo json_encode([$subscription]);
    }

?>
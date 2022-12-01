<?php 

require_once __DIR__ . '/../models/Subscription.php';
require_once __DIR__ . '/AuthController.php';

function putSubs($creator_id, $subscriber) {
    $body = file_get_contents('php://input');
    $data = json_decode($body, true);
    // var_dump($data["status"]);
    $subs = new Subscription();
    if($subs->isExist($creator_id, $subscriber, "PENDING")) {
        $ret = $subs->update($creator_id, $subscriber, $data["status"]);
        if($ret) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode(array(
                "status" => "success",
                "data" => $ret,
                "message" => "Subscription updated successfully"
            ));
        } else {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(array(
                "status" => "error",
                "data" => null,
                "message" => "Subscription update failed"
            ));
        }
    } else {
        http_response_code(422);
        header('Content-Type: application/json');
        echo json_encode(array(
            "status" => "Ignoring",
            "data" => null,
            "message" => "Try update non-pending/non-existing subscription"
        ));
    }
    // var_dump($ret);
}

if(isset($_POST['subscribe'])){
    $user_id = isValidAuthCookie($_COOKIE);
    if($id_user){
        $creator_id = $_POST['creator_id'];
        $iplocal = "192.168.0.189";
        // minta ke soap
        $wsdl = "http://".$iplocal.":3101/SubscriptionService?wsdl";
        $client = new SoapClient($wsdl, array('trace' => 1));
        $token = createCallbackToken("/public/api/subscription", $creator_id, $id_user);
        $callbackUrl = "http://".$iplocal.":8080/public/api/subscription/".$token;
        $params = array(
            'creator_id' => $creator_id,
            'user_id' => $user_id,
            'callbackUrl' => $callbackUrl,
            'apiKey' => "sdfaf"
        );
        try {
            $response = $client->requestSubscribe($params);
            $subscription = new Subscription();
            $subscription->addPendingSubs($creator_id, $id_user);
            echo $response;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

?>
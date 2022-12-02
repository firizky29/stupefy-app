<?php
require_once __DIR__ . '/../models/Subscription.php';
require_once __DIR__ . '/AuthController.php';
require_once __DIR__ . '/../config/constants.php';

if(isset($_GET['status'])){
    $subscription = new Subscription();
    $id_user = isValidAuthCookie($_COOKIE);
    if(!($id_user)) {
        $id_user = -1;
    }
    if($_GET['status']=== 'ACCEPTED'){
        $subs = $subscription->getAcceptedBySubscriber($id_user);
        echo json_encode([$subs]);
    }else if($_GET['status']=== 'PENDING'){
        $subs = $subscription->getPendingBySubscriber($id_user);
        echo json_encode([$subs]);
    }
}

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
    $id_user = isValidAuthCookie($_COOKIE);
    if($id_user){
        // echo "yea";
        $creator_id = $_POST['creator_id'];
        $iplocal = APP_IP_ADDRESS;
        $ip_soap = SOAP_IP_ADDRESS;
        $portlocal = APP_PORT;
        $port_soap = SOAP_PORT;
        // $ip_app = "stupefy-app-server-1";
        // minta ke soap
        $wsdl = "http://".$ip_soap.":".$port_soap."/SubscriptionService?wsdl";
        $client = new SoapClient($wsdl, array('trace' => 1));
        $token = createCallbackToken("/public/api/subscription", $creator_id, $id_user);
        // $callbackUrl = "http://".$ip_app.":80/public/api/subscription/".$token;
        $callbackUrl = "http://".$iplocal.":".$portlocal."/public/api/subscription/".$token;
        $params = array(
            'creator_id' => $creator_id,
            'subscriber' => $id_user,
            'callbackUrl' => $callbackUrl,
            'apiKey' => SOAP_API_KEY
        );
        try {
            // echo "yea";
            $response = $client->requestSubscribe($params);
            if($response->SubscribeResponse->data > 0) {
                $subscription = new Subscription();
                $subscription->addPendingSubs($creator_id, $id_user);
            }
            echo json_encode([$response]);
        } catch (Exception $e) {
            echo json_encode([$e->getMessage()]);
        }
    }
}

?>
<?php
    require_once __DIR__ . '/../../app/controllers/AuthController.php';
    require_once __DIR__ . '/../../app/controllers/SubscriptionController.php';

    if(strcasecmp($_SERVER['REQUEST_METHOD'], 'PUT') === 0){
        // var_dump($_SERVER['REQUEST_URI']);
        $regex = '/\/public\/api\/subscription\/([\w\.]+)/';
        $isMatch = preg_match($regex, $_SERVER['REQUEST_URI'], $matches, PREG_OFFSET_CAPTURE);
        if($isMatch) {
            array_shift($matches);
            $params = array_map(function($param) {
                return $param[0];
            }, $matches);
            // var_dump($params);
            $token = $params[0];

            $token_parts = isValidCallbackToken($token);
            // $token_parts = array('/public/api/subscription', '1', '1');
            // $token_parts = false;
            if($token_parts) {
                $path = $token_parts[0];
                if($path==="/public/api/subscription") {
                    $creator_id = (int)$token_parts[1];
                    $subscriber = (int)$token_parts[2];
                    putSubs($creator_id, $subscriber);
                }
            } else {
                http_response_code(403);
                header('Content-Type: application/json');
                echo json_encode(array(
                    "status" => "Forbidden",
                    "data" => null,
                    "message" => "Invalid token"
                ));
            }
        }
    }
?>
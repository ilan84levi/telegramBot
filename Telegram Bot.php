
<?php

$botToken = "1013039524:AAH4uGVYtL59jL_pzvUG1zd27EpMBdB8bM4";
$website = "https://api.telegram.org/bot" . $botToken;

$update = file_get_contents($website . '/getupdates');
$updateArray = json_decode($update, $assoc = TRUE);

foreach ($updateArray['result'] as $update) {

    if (isset($update['message']['from']['first_name'])) {
        $first_name = $update['message']['from']['first_name'];
    }

    if (isset($update['message']['chat']['id'])) {
        $chat_id = $update['message']['chat']['id'];
    }

    if (isset($update['message']['from']['id'])) {
        $user_Id = $update['message']['from']['id'];
    }

    $text = $update['message']['text'];
    $message_id = $update['message']['message_id'];
    $from = $update['message']['from']['id'];
}
if (isset($update['message']['forward_from'])) {
    $forwardArray = $update['message']['forward_from'];
    if (isset($update['message']['forward_from']['first_name'])) {
        $forwardName = $update['message']['forward_from']['first_name'];
    }
    if (isset($update['message']['forward_from']['id'])) {
        $forwardUserId = $update['message']['forward_from']['id'];
    }
}

if (isset($update['message']['forward_sender_name'])) {
    $forward_sender_name = $update['message']['forward_sender_name'];
}


switch ($update) {
    case isset($update['message']['forward_from']):
        if (isset($forwardUserId)) {
            $myParams = ['chat_id' => $chat_id, 'text' => $forwardUserId];
            sendMessage('sendMessage', $myParams);
        } else {
            $myParams = ['chat_id' => $chat_id, 'text' => $forwardUserId];
            sendMessage('sendMessage', $forwardName);
        }
        break;

    case isset($update['message']['forward_sender_name']) :
        $myParams = ['chat_id' => $chat_id, 'text' => $forward_sender_name];
        sendMessage('sendMessage', $myParams);
        break;

    default:
        $chat = file_get_contents($website . "/getChatMember?chat_id=" . $chat_id . '&user_id=' . $user_Id);
        $chatDecode = json_decode($chat, $assoc = TRUE);
        $chatUserName = $chatDecode['result']['user']['username'];

        if ($chatUserName) {
            $myParams = ['chat_id' => $chat_id, 'text' => $chatUserName, 'reply_to_message_id' => $message_id];
            sendMessage('sendMessage', $myParams);
        } else {
            $myParams = ['chat_id' => $chat_id, 'text' => $first_name, 'reply_to_message_id' => $message_id];
            sendMessage('sendMessage', $myParams);
        }

        break;
}

function sendMessage($method, $params = []) {
    $url = $GLOBALS['website'] . '/' . $method . '?' . http_build_query($params);
    file_get_contents($url);
}

?>

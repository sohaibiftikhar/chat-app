<?php
/**
 * Created by IntelliJ IDEA.
 * User: sohaib
 * Date: 10/10/15
 * Time: 10:28 AM
 */

function class_autoloader($class_name) {
    include 'classes/' .$class_name . '.php';
}

spl_autoload_register('class_autoloader');

$conn = new OurChatConnection();
$logger = new Logger();

function get_chats(){
    global $conn;
    $query = "SELECT * FROM chat_history ORDER BY time";
    $results = $conn->query($query);
    return $results;
}

function store_conv($username, $message, $is_resource){
    global $conn;
    $u = $conn->escape($username);
    $m = $conn->escape($message);
    $resource = $is_resource?'true':'false';
    $query = "INSERT INTO chat_history (username, time, text, is_resource) VALUES
              ('$u', NOW(), '$m', $resource)
              ";
    $return = "";
    if($conn->query($query)){
        $result = $conn->query("SELECT LAST_INSERT_ID() id");
        if($result!=FALSE && count($result)==1){
            $id = $result[0]['id'];
            $return =  populate_div($id, $u, $u, $m, $is_resource);
        }
    }
    return $return;
}

function populate_div($convo_id, $logged_user, $convo_username, $message, $is_resource){
    global $logger;
    $logger->log($is_resource);
    if($is_resource){
        $message = "<img class='img-resource' src='data:image/png;base64, " . base64_encode(file_get_contents($message)) . "' />";
    }
    $div =  array(
                "<div id='{$convo_id}' class='chat-wrapper'>",
                "<div class='" , ($logged_user==$convo_username)?"chat-box-right":"chat-box-left" , "'>\n" ,
                $message , "\n" ,
                "</div>\n" ,
                "<div class='" , ($logged_user==$convo_username)?"chat-box-name-right":"chat-box-name-left" , "'>\n" ,
                "<img src='assets/img/{$convo_username}.jpeg' alt='bootstrap Chat box user image' class='img-circle' />\n" ,
                "- {$convo_username}\n" ,
                "</div>\n" ,
                "<hr class='hr-clas' />\n",
                "</div>",
        );
    return implode("", $div);
}

function update_chats($user_id, $last_updated_id){
    global $conn;
    $last_updated_id = $conn->escape($last_updated_id);
    $query = "SELECT * FROM chat_history WHERE conv_id > $last_updated_id ORDER BY conv_id";
    $results = $conn->query($query);
    $return = array();
    if($results!=FALSE){
        foreach($results as $result){
            $return[] = populate_div($result['conv_id'], $user_id, $result['username'], $result['text'], $result['is_resource']);
        }
    }
    return implode("", $return);
}


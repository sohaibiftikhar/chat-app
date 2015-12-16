<?php
include_once __DIR__ . "/utils.php";
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if(!isset($_SESSION['login']) || !$_SESSION['login']){
        if(isset($_POST['username']) && isset($_POST['password'])){
            $user = $conn->escape($_POST['username']);
            $pass = $conn->escape(($_POST['password']));
            $results = $conn->query("SELECT * FROM ourchat.user WHERE username='$user' AND password='$pass'");
            if($results == FALSE || count($results) == 0){
                $_SESSION['login'] = false;
                header("Location: /login.php?invalid_attempt=1");
                //Unable to login
                return;
            }else if( count($results) > 0){
                $_SESSION['login'] = true;
                $_SESSION['user'] = $user;
            }else{
                header("Location: /login.php");
                return;
                //Invalid username password
            }
        }
    }else if($_SESSION['login'] == true){
        $return = "";

        if(isset($_POST['action']) && $_POST['action'] === 'upload_pic') {
            foreach($_FILES as $file){
                $file_name = "../chat-store/{$_SESSION['user']}_" . time() . $file['name'];
                if(move_uploaded_file($file['tmp_name'], $file_name)){
                    $return .= store_conv($_SESSION['user'], $file_name, true);
                } else {
                    $logger->log("Unable to upload files");
                }
            }
        }
        if(isset($_POST['data'])){
            $message = trim($_POST['data']);
            if(strlen($message)>0){
                //syslog(LOG_ERR, $message);
                $return = store_conv($_SESSION['user'], $message, false);
            }
        }
        echo $return;
        return;
    }
}
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    if(isset($_SESSION['login']) && $_SESSION['login']==true && isset($_GET['refresh'])){
        $result = "";
        $id = $_GET['refresh'];
        if(is_numeric($id)){
            $result = update_chats($_SESSION['user'], $id);
        }
        echo $result;
        return;
    }
}
if(!isset($_SESSION['login']) || !$_SESSION['login']){
    header("Location: /login.php");
}
?>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <title>OurChat | <?php echo $_SESSION['user']?></title>
    <!-- BOOTSTRAP CORE STYLE CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FONT AWESOME  CSS -->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- CUSTOM STYLE CSS -->
    <link href="assets/css/style.css" rel="stylesheet" />
</head>
<body>


    <div class="container">
        <div class="row pad-top pad-bottom">


            <div class=" col-lg-6 col-md-6 col-sm-6 center">
                <div class="chat-box-div">
                    <div class="chat-box-head">
                        Our Chat
                            <div class="btn-group pull-right">
                                <button type="button" class="btn btn-info dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                                    <span class="fa fa-cogs"></span>
                                    <span class="sr-only">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" role="menu">
                                    <li><a href="#"><span class="fa fa-map-marker"></span>&nbsp;Invisible</a></li>
                                    <li><a href="#"><span class="fa fa-comments-o"></span>&nbsp;Online</a></li>
                                    <li><a href="#"><span class="fa fa-lock"></span>&nbsp;Busy</a></li>
                                    <li class="divider"></li>
                                    <li><a href="logout.php"><span class="fa fa-circle-o-notch"></span>&nbsp;Logout</a></li>
                                </ul>
                            </div>
                    </div>
                    <div id="chat-box-main" class="panel-body chat-box-main">
                        <?php
                        $chats = get_chats();
                        if($chats != FALSE){
                            $user = $_SESSION['user'];
                            foreach($chats as $chat){
                                echo populate_div($chat['conv_id'], $user, $chat['username'], $chat['text'], $chat['is_resource']);
                            }
                        }
                        ?>
                    </div>
                    <div class="chat-box-footer">
                        <div class="input-group">
                            <input id="message-body" type="text" class="form-control" placeholder="Enter Text Here...">
                            <span class="input-group-btn">
                                <button id="img-send" class="btn btn-info" type="button"><span class="glyphicon glyphicon-camera"</span></button>
                                <input id="img-send-hidden" class="hidden" type="file" name="photo" accept="image/*;capture=camera"/>
                            </span>
                            <span class="input-group-btn">
                                <button id="send-message" class="btn btn-info" type="button">SEND</button>
                            </span>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <!-- USING SCRIPTS BELOW TO REDUCE THE LOAD TIME -->
    <!-- CORE JQUERY SCRIPTS FILE -->
    <script src="assets/js/jquery-1.11.1.js"></script>
    <!-- CORE BOOTSTRAP SCRIPTS  FILE -->
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/myactions.js"></script>
</body>

</html>

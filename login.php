<?php
include_once __DIR__ . "/utils.php";
$invalid_attempt = false;
if(isset($_GET['invalid_attempt'])){
    $invalid_attempt = true;
}
session_start();
if(isset($_SESSION['login']) && $_SESSION['login']){
    header("Location: /index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
		<meta charset="utf-8">
		<title>Ourchat|Login</title>
		<meta name="generator" content="Bootply" />
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link href="assets/css/bootstrap.css" rel="stylesheet">
		<!--[if lt IE 9]>
			<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link href="assets/css/styles_login.css" rel="stylesheet">
	</head>
	<body>
<!--login modal-->
<div id="loginModal" class="modal show" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h1 class="text-center">Login</h1>
      </div>
      <div class="modal-body <?php echo ($invalid_attempt==true)? "has-error": "" ?>">
          <form action="index.php" method="POST" class="form col-md-12 center-block">
            <div class="form-group">
              <input type="text" name="username" id="username" class="form-control input-lg" placeholder="UserID"/>
            </div>
            <div class="form-group">
              <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password"/>
            </div>
            <div class="form-group">
              <button type="submit" class="btn btn-primary btn-lg btn-block">Sign In</button>
              <!--<span class="pull-right"><a href="#">Register</a></span><span><a href="#">Need help?</a></span>-->
            </div>
          </form>
      </div>
      <div class="modal-footer">
          <div class="col-md-12">
          <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
		  </div>	
      </div>
  </div>
  </div>
</div>
	<!-- script references -->
		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
		<script src="assets/js/bootstrap.js"></script>
	</body>
</html>

<?php
/**
 * Created by IntelliJ IDEA.
 * User: sohaib
 * Date: 10/10/15
 * Time: 10:36 AM
 */


session_start();
session_destroy();
header("Location: /login.php");

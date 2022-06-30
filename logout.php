<?php
require_once 'inc/head.php';

if (isset($_SESSION['userId'])) {

    include_once 'class/dbh.php';
    include_once 'class/user.php';


    $user = new User($userFirstname, $userLastname, $userEmail, $userPassword);
    $user->logoutUser();

    header("location: login.php");

}
else {
    header("location: index.php");
    exit();
}

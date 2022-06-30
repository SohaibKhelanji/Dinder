<?php

session_start();

if (isset($_SESSION['userId'])) {
    $userId = $_SESSION['userId'];
    $userFirstname = $_SESSION['userFirstname'];
    $userLastname = $_SESSION['userLastname'];
    $userProfilePicture = $_SESSION["userProfilePicture"];
    $userBiography = $_SESSION["userBiography"];
    $userEmail = $_SESSION['userEmail'];
    $userPassword = $_SESSION['userPassword'];
}

?>
<!doctype html>
<htmL lang="nl">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Dinder</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <link rel="icon" type="image/x-icon" href="img/icon.png">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/slicknav.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>

<body>

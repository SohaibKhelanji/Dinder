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

    include '../class/dbh.php';
    include '../class/user.php';

    $database = new dbh();
    $user = new User($userFirstname, $userLastname, $userEmail, $userPassword);

    $sql = "SELECT * FROM requests WHERE sender = ? and status != 'Geaccepteerd' or 'Afgewezen' ";
    $result = $database->connection()->prepare($sql);

    if (!$result->execute(array($userId))) {
        $result = null;
        header("location:profile.php?error=stmtGetRandomUserDogFailed");
        exit();
    }

    $inboxMessages = $result->fetchAll();

    foreach ($inboxMessages as $row) {

        $status = $row['status'];
        $receiverFlagNameId = $row['receiver'];

        $stmt = "SELECT firstname, id FROM users WHERE id= ? ";
        $resultStmt = $database->connection()->prepare($stmt);

        if (!$resultStmt->execute(array($receiverFlagNameId))) {
            $result = null;
            header("location:profile.php?error=stmtGetRandomUserDogFailed");
            exit();
        }

        $receiverDb = $resultStmt->fetchAll();

        if ($status == 'In afwachting') {

            foreach ($receiverDb as $receiver) {

                echo "<div style='cursor: pointer' id='messageReturnedRequest' onclick='window.location.href= \"zoekPartner.php?id=$receiver[id]\"'>
                        <p>Je hebt <span>$receiver[firstname]</span> geflagged</p>
                       </div>
                       ";
            }
        }
    }

//    accepted/Denied requests

    $sqlReturnedRequest = "SELECT * FROM requests WHERE receiver = ?";
    $resultReturnedRequest = $database->connection()->prepare($sqlReturnedRequest);

    if (!$resultReturnedRequest->execute(array($userId))) {
        $resultReturnedRequest = null;
        header("location:profile.php?error=stmtGetRandomUserDogFailed");
        exit();
    }

    $inboxReturnedMessages = $resultReturnedRequest->fetchAll();

    foreach ($inboxReturnedMessages as $row) {

        $status = $row['status'];
        $senderFlagIdName = $row['sender'];

        $stmtReturnedRequest = "SELECT firstname, id FROM users WHERE id= ? ";
        $resultStmtReturnedRequest = $database->connection()->prepare($stmtReturnedRequest);

        if (!$resultStmtReturnedRequest->execute(array($senderFlagIdName))) {
            $result = null;
            header("location:profile.php?error=stmtGetRandomUserDogFailed");
            exit();
        }

        $senderDb = $resultStmtReturnedRequest->fetchAll();

        if ($status == 'Geaccepteerd') {

            foreach ($senderDb as $sender) {

                echo "<div style='cursor: pointer' id='messageReturnedRequest' onclick='window.location.href= \"zoekPartner.php?id=$sender[id]\" '>
                        <p>Je hebt <span>$sender[firstname]</span>'s flag geaccepteerd!</p>
                       </div>
                       ";
            }
        }

        if ($status == 'Afgewezen') {

            foreach ($senderDb as $sender) {

                echo "<div style='cursor: pointer' id='messageReturnedRequest' onclick='window.location.href= \"zoekPartner.php?id=$sender[id]\" '>
                        <p>Je hebt <span>$sender[firstname]</span>'s flag afgewezen</p>
                       </div>
                       ";
            }
        }
    }
}




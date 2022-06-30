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

    $sql = "SELECT * FROM requests WHERE receiver = ? and status != 'Geaccepteerd' or 'Afgewezen' ";
    $result = $database->connection()->prepare($sql);

    if (!$result->execute(array($userId))) {
        $result = null;
        header("location:profile.php?error=stmtGetRandomUserDogFailed");
        exit();
    }

    $inboxMessages = $result->fetchAll();

    foreach ($inboxMessages as $row) {

        $status = $row['status'];
        $senderId = $row['sender'];

        $stmt = "SELECT firstname, id FROM users WHERE id= ? ";
        $resultStmt = $database->connection()->prepare($stmt);

        if (!$resultStmt->execute(array($senderId))) {
            $result = null;
            header("location:profile.php?error=stmtGetRandomUserDogFailed");
            exit();
        }

        $senderDb = $resultStmt->fetchAll();

        if ($status == 'In afwachting') {

            foreach ($senderDb as $sender) {

                echo "<div id='message'>
                        <p><span style='cursor: pointer' onclick='window.location.href= \"zoekPartner.php?id=$sender[id]\"'>$sender[firstname]</span> heeft jou geflagged</p>
                        <br>
                        <button onclick='window.location.href= \"inbox.php?denyRequestId=$sender[id]\"' id='denyRequestButton' style='font-weight: bold'>X</button>
                        <button  onclick='window.location.href= \"inbox.php?acceptRequestId=$sender[id]\"' id='acceptRequestButton'><i class=\"fa fa-check\"></i></button>
                       </div>
                       ";
            }
        }
    }

//    accepted/Denied requests

    $sqlReturnedRequest = "SELECT * FROM requests WHERE sender = ?";
    $resultReturnedRequest = $database->connection()->prepare($sqlReturnedRequest);

    if (!$resultReturnedRequest->execute(array($userId))) {
        $resultReturnedRequest = null;
        header("location:profile.php?error=stmtGetRandomUserDogFailed");
        exit();
    }

    $inboxReturnedMessages = $resultReturnedRequest->fetchAll();

    foreach ($inboxReturnedMessages as $row) {

        $status = $row['status'];
        $receiverId = $row['receiver'];

        $stmtReturnedRequest = "SELECT firstname, id FROM users WHERE id= ? ";
        $resultStmtReturnedRequest = $database->connection()->prepare($stmtReturnedRequest);

        if (!$resultStmtReturnedRequest->execute(array($receiverId))) {
            $result = null;
            header("location:profile.php?error=stmtGetRandomUserDogFailed");
            exit();
        }

        $receiverDb = $resultStmtReturnedRequest->fetchAll();

        if ($status == 'Geaccepteerd') {

            foreach ($receiverDb as $receiver) {

                echo "<div style='cursor: pointer' id='messageReturnedRequest' onclick='window.location.href= \"zoekPartner.php?id=$receiver[id]\" '>
                        <p><span>$receiver[firstname]</span> heeft jou flag geaccepteerd!</p>
                       </div>
                       ";
            }
        }

        if ($status == 'Afgewezen') {

            foreach ($receiverDb as $receiver) {

                echo "<div style='cursor: pointer' id='messageReturnedRequest' onclick='window.location.href= \"zoekPartner.php?id=$receiver[id]\" '>
                        <p><span>$receiver[firstname]</span> heeft jou flag afgewezen</p>
                       </div>
                       ";
            }
        }
    }
}

?>


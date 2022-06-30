<?php
include 'class/dbh.php';
include 'class/user.php';
include 'inc/head.php';
include 'inc/navBar.php';

if (!isset($_SESSION['userId'])) {
    header('location: login.php');
}

$database = new dbh();
$user = new User($userFirstname, $userLastname, $userEmail, $userPassword);

if (isset($_GET['acceptRequestId'])) {
    $acceptRequestId = $_GET['acceptRequestId'];

    $sqlAcceptRequest = "UPDATE requests SET status = 'Geaccepteerd' WHERE receiver = ? AND sender = ?";
    $resultAcceptRequest = $database->connection()->prepare($sqlAcceptRequest);

    if (!$resultAcceptRequest->execute(array($userId, $acceptRequestId))) {
        $result = null;
        header("location:profile.php?error=stmtGetRandomUserDogFailed");
        exit();
    }

    header("location:inbox.php");
}

if (isset($_GET['denyRequestId'])) {
    $denyRequestId = $_GET['denyRequestId'];

    $sqlDenyRequest = "UPDATE requests SET status = 'Afgewezen' WHERE receiver = ? AND sender = ?";
    $resultDenyRequest = $database->connection()->prepare($sqlDenyRequest);

    if (!$resultDenyRequest->execute(array($userId, $denyRequestId))) {
        $result = null;
        header("location:profile.php?error=stmtGetRandomUserDogFailed");
        exit();
    }

    header("location:inbox.php");
}


?>

<div id="inboxNavBar">
<h1 class="inboxTitle" id="inbox"> <span>In</span>box</h1>
<h1 class="inboxTitle" id="outbox"><span>Out</span>box</h1>
</div>

<div id="messageBoxWrapper">
<div id="messagesBox">
    <?php
    $sql = "SELECT * FROM requests WHERE receiver = ? and status != 'Geaccepteerd' or 'Afgewezen' ";
    $result = $database->connection()->prepare($sql);

    if (!$result->execute(array($userId))) {
        $result = null;
        header("location:profile.php?error=stmtGetRandomUserDogFailed");
        exit();
    }

    $inboxMessages = $result->fetchAll();

    foreach ($inboxMessages as $row ) {

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

        if ( $status == 'In afwachting') {

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
    ?>
<!--    accepted/Denied requests-->
    <?php
    $sqlReturnedRequest = "SELECT * FROM requests WHERE sender = ?";
    $resultReturnedRequest = $database->connection()->prepare($sqlReturnedRequest);

    if (!$resultReturnedRequest->execute(array($userId))) {
        $resultReturnedRequest = null;
        header("location:profile.php?error=stmtGetRandomUserDogFailed");
        exit();
    }

    $inboxReturnedMessages = $resultReturnedRequest->fetchAll();

    foreach ($inboxReturnedMessages as $row ) {

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

        if ( $status == 'Geaccepteerd') {

            foreach ($receiverDb as $receiver) {

                echo "<div style='cursor: pointer' id='messageReturnedRequest' onclick='window.location.href= \"zoekPartner.php?id=$receiver[id]\" '>
                        <p><span>$receiver[firstname]</span> heeft jou flag geaccepteerd!</p>
                       </div>
                       ";
            }
        }

        if ( $status == 'Afgewezen') {

            foreach ($receiverDb as $receiver) {

                echo "<div style='cursor: pointer' id='messageReturnedRequest' onclick='window.location.href= \"zoekPartner.php?id=$receiver[id]\" '>
                        <p><span>$receiver[firstname]</span> heeft jou flag afgewezen</p>
                       </div>
                       ";
            }
        }
    }

    ?>
</div>
</div>

<script>
    $(document).ready(function () {
        $("#inbox").click(function () {

            $("#outbox").css({"text-decoration": "none", "opacity": "0.7"});
            $("#inbox").css({"text-decoration": "underline", "opacity": "1"});
            $("#messagesBox").load("inc/inboxScript.php", {

            })
        });
    });

    $(document).ready(function () {
        $("#outbox").click(function () {

            $("#inbox").css({"text-decoration": "none", "opacity": "0.7"});
            $("#outbox").css({"text-decoration": "underline", "opacity": "1"});
            $("#messagesBox").load("inc/outboxScript.php", {

            })
        });
    });

</script>

<?php

if (isset($_POST['receiver'])) {
    include 'zoekPartnerHead.php';
    include '../class/dbh.php';
    $database = new dbh();

    $receiver = $_POST['receiver'];
    $previousUser = $_POST['previousUser'];

    $stmtFlag  ="INSERT into requests (sender, receiver) VALUES (?,?)";
    $resultFlag = $database->connection()->prepare($stmtFlag);

    if (!$resultFlag->execute(array($userId, $receiver))) {
        $resultFlag = null;
        header("location:profile.php?error=stmtGetRandomUserFailed");
        exit();
    }

    $stmt  ="SELECT id, firstname, lastname, profilepicture, biography FROM users where id != $userId and id != $previousUser order by rand() limit 1";
    $result = $database->connection()->prepare($stmt);

    if (!$result->execute()) {
        $result = null;
        header("location:profile.php?error=stmtGetRandomUserFailed");
        exit();
    }

    $randomUser = $result->fetchAll();

    foreach ($randomUser as $row) {

        $randomUserId = $row["id"];

        echo "<figure>
            <figcaption>$row[firstname] $row[lastname]</figcaption>
            <br>
            <img src='img/profilepictures/$row[profilepicture]' alt='Gebruiker zijn profielfoto' id='userProfilePictureImageCard'>
            <br>
            <br>
            <p>$row[biography]</p>
           </figure>         
         ";

        echo "<div id=randomUserCardSlides>";


        $sql = "SELECT * FROM dogs WHERE owner = ?";
        $resultSql = $database->connection()->prepare($sql);

        if (!$resultSql->execute(array($randomUserId))) {
            $resultSql = null;
            header("location:profile.php?error=stmtGetRandomUserDogFailed");
            exit();
        }

        $randomUserDog = $resultSql->fetchAll();

        foreach ($randomUserDog as $dog) {
            echo "
        <div id='randomUserCardDogsWrapper'>
            <img src='img/dogs/$dog[image]' alt='Foto van een hond'>
            <div id='randomUserCardDogsOverlay'>
                <div class='randomUserCardDogsOverlayText'>
                <p>$dog[name]<br>
                   <br> 
                   $dog[age] Jaar<br>
                   $dog[breed]
                   </p>
                </div>
            </div>
        </div>
       
        ";
        }
        echo "</div>
           <br>
           <br>";
        echo "<div id='randomUserCardButtons'>
            <button id='randomUserCardNewButton' class=\"btn\"><i class=\"fa fa-repeat\"></i></button>
            <button id='randomUserCardFlagButton' class=\"btn\"><i class=\"fa fa-flag\"></i></button>
          </div>";

        echo "<script>
         $(document).ready(function () {
               $(\"#randomUserCardNewButton\").click(function () {
           
                    $(\"#randomUserCard\").load(\"inc/zoekPartnerScript.php\", {
                        previousUser: $randomUserId
                     })

               });
         });
         
         $(document).ready(function () {
               $(\"#randomUserCardFlagButton\").click(function () {
           
                    $(\"#randomUserCard\").load(\"inc/flagUserScript.php\", {
                           previousUser: $randomUserId,
                           receiver: $row[id]
                     }) 

               });
         });
    </script>";
    }


}
else {
    header('location: ../index.php');
    exit();
}

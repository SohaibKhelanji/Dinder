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


$hasProfilePicture = "placeholderprofilepicture.png";

if (strcmp(trim($hasProfilePicture), trim($userProfilePicture)) == 0) {
    echo "
          <div id='noPartnerText'>  
              <h1>Je hebt nog geen foto van je zelf of een hond toegevoegd aan je profiel!</h1>
              <br>
              <h2>Voeg dit toe aan je profiel om een partner te kunnen zoeken.</h2>
          </div>
         ";

    exit();
}

if (isset($_GET['id'])) {
    $userProfileId = $_GET['id'];

    $stmt  ="SELECT id, firstname, lastname, profilepicture, biography FROM users where id = $userProfileId ";
    $result = $database->connection()->prepare($stmt);

    if (!$result->execute()) {
        $result = null;
        header("location:profile.php?error=stmtGetRandomUserFailed");
        exit();
    }

    $selectedUser = $result->fetchAll();

    foreach ($selectedUser as $row) {

        $selectedUserId = $row["id"];


        echo "
           <div id='randomUserCardWrapper'>
           <div id='randomUserCard'>   
          ";
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

        if (!$resultSql->execute(array($selectedUserId))) {
            $resultSql = null;
            header("location:profile.php?error=stmtGetRandomUserDogFailed");
            exit();
        }

        $selectedUserDog = $resultSql->fetchAll();

        foreach ($selectedUserDog as $dog) {
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


        echo "</div>
          </div>";
    }
    exit();
}


$stmt  ="SELECT id, firstname, lastname, profilepicture, biography FROM users where id != $userId AND profilepicture != 'placeholderprofilepicture.png' order by rand() limit 1";
$result = $database->connection()->prepare($stmt);

if (!$result->execute()) {
    $result = null;
    header("location:profile.php?error=stmtGetRandomUserFailed");
    exit();
}

$randomUser = $result->fetchAll();

        foreach ($randomUser as $row) {

            $randomUserId = $row["id"];


            echo "
           <div id='randomUserCardWrapper'>
           <div id='randomUserCard'>   
          ";
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

            echo "</div>
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
    </script>
    ";
}




?>

</div>
</div>



<?php
include 'inc/foot.php';
?>
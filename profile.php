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

if (isset($_POST['dogSubmit'])) {

    unlink('img/dogs/' . $_POST['dogImage']);
    $dogName = $_POST['dogName'];
    $dogAge = $_POST['dogAge'];
    $dogBreed= $_POST['dogBreed'];

    $dogImageFile = $_FILES['dogImage'];
    $dogImageName = $dogImageFile['name'];
    $dogImageTmpName = $dogImageFile['tmp_name'];
    $dogImageError = $dogImageFile['error'];

    $dogImageExt = explode('.', $dogImageName);
    $dogImageActualExt = strtolower(end($dogImageExt));
    $dogImageAllowed = array('jpg', 'jpeg', 'png');

    if (in_array($dogImageActualExt, $dogImageAllowed)) {
            if ($dogImageError === 0) {
                $dogImageDbName = $dogName . $userId . "." . $dogImageActualExt;
                $dogImageDestination = 'img/dogs/' .  $dogImageDbName;
                move_uploaded_file($dogImageTmpName, $dogImageDestination);
            }
            else {
                header("location: profile.php?error=dogImageError");
                exit();
            }
        }
        else {
            header("location: profile.php?error=WrongDogImageFormat");
            exit();
        }

    function addDog($database, $dogName, $dogAge, $dogBreed, $dogImageDbName, $userId) {
            $stmt  ="INSERT INTO dogs (name, age, breed, image, owner) VALUES (?, ?, ?, ?, ?);";
            $result = $database->connection()->prepare($stmt);

        if (!$result->execute(array($dogName, $dogAge, $dogBreed, $dogImageDbName, $userId))) {
            $result = null;
            header("location:profile.php?error=stmtAddDogFailed");
            exit();
        }

    }

    addDog($database, $dogName, $dogAge, $dogBreed, $dogImageDbName, $userId);

    header("location: profile.php?error=addDogNone");
    exit();
}

if (isset($_POST['dogUpdate'])) {
    $dogId = $_POST['dogId'];
    $dogName = $_POST['dogName'];
    $dogAge = $_POST['dogAge'];
    $dogBreed = $_POST['dogBreed'];

    if (is_uploaded_file($_FILES['dogImage'] ['tmp_name'])) {
        $dogImageFile = $_FILES['dogImage'];
        $dogImageName = $dogImageFile['name'];
        $dogImageTmpName = $dogImageFile['tmp_name'];
        $dogImageError = $dogImageFile['error'];

        $dogImageExt = explode('.', $dogImageName);
        $dogImageActualExt = strtolower(end($dogImageExt));
        $dogImageAllowed = array('jpg', 'jpeg', 'png');

        if (in_array($dogImageActualExt, $dogImageAllowed)) {
            if ($dogImageError === 0) {
                $dogImageDbName = $dogName . $userId . "." . $dogImageActualExt;
                $dogImageDestination = 'img/dogs/' . $dogImageDbName;
                move_uploaded_file($dogImageTmpName, $dogImageDestination);
            } else {
                header("location: profile.php?error=dogUpdateImageError");
                exit();
            }
        } else {
            header("location: profile.php?error=WrongDogUpdateImageFormat");
            exit();
        }
    }
    else {

        $dogImageDbName = $_POST["dogImage"];
    }

    function updateDog($database, $dogName, $dogAge, $dogBreed, $dogImageDbName, $userId, $dogId) {
        $stmt  ="UPDATE dogs SET name = ?, age = ?, breed = ?, image = ?, owner = ? WHERE id = ?;";
        $result = $database->connection()->prepare($stmt);

        if (!$result->execute(array($dogName, $dogAge, $dogBreed, $dogImageDbName, $userId, $dogId))) {
            $result = null;
            header("location:profile.php?error=stmtUpdateDogFailed");
            exit();
        }

    }

    updateDog($database, $dogName, $dogAge, $dogBreed, $dogImageDbName, $userId, $dogId);

    header("location: profile.php?error=UpdateDogNone");
    exit();
}

if (isset($_POST["dogDelete"])) {

    $dogId = $_POST["dogId"];
    $stmt  ="SELECT owner FROM dogs WHERE id = ?";
    $result = $database->connection()->prepare($stmt);

    if (!$result->execute(array($dogId))) {
        $result = null;
        header("location:profile.php?error=stmtGetDogOwnerDeleteDogFailed");
        exit();
    }

    $dogOwner = $result->fetchAll();

    if ($dogOwner = $userId) {
        unlink('img/dogs/' . $_POST['dogImage']);
        $stmt  ="DELETE FROM dogs WHERE id = ?";
        $result = $database->connection()->prepare($stmt);

        if (!$result->execute(array($dogId))) {
            $result = null;
            header("location:profile.php?error=stmtDeleteDogFailed");
            exit();
        }
    }
    else {
        header("location:profile.php?error=notOwnerDeleteDogFailed");
        exit();
    }




    function updateDog($database, $dogName, $dogAge, $dogBreed, $dogImageDbName, $userId, $dogId) {
        $stmt  ="UPDATE dogs SET name = ?, age = ?, breed = ?, image = ?, owner = ? WHERE id = ?;";
        $result = $database->connection()->prepare($stmt);

        if (!$result->execute(array($dogName, $dogAge, $dogBreed, $dogImageDbName, $userId, $dogId))) {
            $result = null;
            header("location:profile.php?error=stmtUpdateDogFailed");
            exit();
        }

    }
}


if (isset($_POST['profileSubmit'])) {

    if (!empty($_POST['biography'])) {
        $biography = $_POST['biography'];
    }
    else {
        $biography = NULL;
    }


    if (is_uploaded_file($_FILES['ProfilePicture'] ['tmp_name'])) {
        $ProfilePictureImageFile = $_FILES['ProfilePicture'];
        $ProfilePictureImageName = $ProfilePictureImageFile['name'];
        $ProfilePictureImageTmpName = $ProfilePictureImageFile['tmp_name'];
        $ProfilePictureImageError = $ProfilePictureImageFile['error'];

        $ProfilePictureImageExt = explode('.', $ProfilePictureImageName);
        $ProfilePictureImageActualExt = strtolower(end($ProfilePictureImageExt));
        $ProfilePictureImageAllowed = array('jpg', 'jpeg', 'png');

    echo "test";


       if (in_array($ProfilePictureImageActualExt, $ProfilePictureImageAllowed)) {
           echo "hier";
           if ($ProfilePictureImageError === 0) {
               echo 'hallo';
               $ProfilePictureImageDbName = $userFirstname . $userId . "profilePicture" . "." . $ProfilePictureImageActualExt;
               $ProfilePictureImageDestination = 'img/profilepictures/' . $ProfilePictureImageDbName;
               move_uploaded_file($ProfilePictureImageTmpName, $ProfilePictureImageDestination);
           } else {
               header("location: profile.php?error=ProfilePictureImageError");
               exit();
           }
       } else {
           header("location: profile.php?error=WrongProfilePictureImageFormat");
           exit();
       }

        $user->updateProfilePicture($ProfilePictureImageDbName);
   }
    $user->updateBiography($biography);

    header("location: profile.php?error=saveProfileNone");
    exit();

}

?>

<div id="welcomeBanner-profile">
    <?php
    $time = date("H");
    $timezone = date("e");
    if ($time < "12") {
        echo "<h2><span>Goedemorgen</span> $userFirstname </h2>";
    } else
        if ($time >= "12" && $time < "17") {
            echo "<h2><span>Goedemiddag</span> $userFirstname</h2>";
        } else
            if ($time >= "17" && $time < "22") {
                echo "<h2><span>Goedenavond</span> $userFirstname</h2>";
            } else
                if ($time >= "22") {
                    echo "<h2><span>Goedenacht</span> $userFirstname</h2>";
                }

    ?>
</div>

<div id="profilePicture-profile">
    <form class="form" method="post" enctype="multipart/form-data">
        <?php
        echo "<img src=\"img/profilepictures/$userProfilePicture\" alt=\"Profiel Foto\" id=\"profilePictureDisplay\" onclick=\"triggerClick()\">
        <input type=\"file\" id=\"ProfilePictureUpload\" name=\"ProfilePicture\" onchange=\"displayImage(this)\" style=\"display:none ;\">
        <textarea name=\"biography\" rows=\"4\" cols=\"40\" placeholder=\"Vertel een beetje over jezelf...\">$userBiography</textarea>";
        ?>
        <br>
        <input type="submit" name="profileSubmit" id="ProfilePictureSubmit-button" value="Opslaan">
    </form>
</div>

<div id="myDogs-profile">
    <h3><span>Mijn</span> hond(en)</h3>

    <div id="myDogsDogs-profile">
        <?php
        $stmt = "SELECT * FROM dogs WHERE owner = $userId";
        $result = $database->connection()->query($stmt);
        $resultCheck = $result->fetchAll();
        $resultCount = count($resultCheck);

        if ($resultCount > 0) {

            foreach ($resultCheck as $row) {
                echo " <figure>
                <img src=\"img/dogs/$row[image]\" alt=\"Foto van je hond\" onclick='openDogModal($row[id])'>
                <figcaption>$row[name]</figcaption>
              </figure>
              
            <dialog class=\"modal\" id=\"modalDog$row[id]\">
                <button class=\"closeModalButton close-button-dog\" onclick='closeDogModal($row[id])'>X</button>
                <h2>$row[name]</h2>
                <p>Verander de gegevens van of verwijder uw hond</p>
                <form class=\"form\" method=\"post\" enctype=\"multipart/form-data\">
                    <img id='dogPictureUpdateDisplay$row[id]' src='img/dogs/$row[image]' alt='Foto van je hond' onclick='triggerClickDog($row[id])'>
                    <input style=\"display: none\" type=\"file\" name=\"dogImage\" id='dogPictureUpdateUpload$row[id]' value='$row[image]' onchange='displayImageDog(this, $row[id])'  >
                    <br>
                    <label>Naam <input id=\"dogName\" name=\"dogName\" type=\"text\" value='$row[name]' ></label>
                    <label>Leeftijd <input id=\"dogAge\" name=\"dogAge\" type=\"tel\" value='$row[age]'  maxlength=\"2\"></label>
                    <label>Ras <input id=\"dogBreed\" name=\"dogBreed\" type=\"text\" value='$row[breed]' ></label>
                    <br>
                    <input type='hidden' name='dogImage' value='$row[image]' readonly>
                    <input type='hidden' name='dogId' value='$row[id]' readonly>
                    <input id=\"modal-submit\" type=\"submit\" name=\"dogUpdate\" value=\"Opslaan\">
                    <input id=\"modal-delete\" type=\"submit\" name=\"dogDelete\" value=\"Verwijderen\">
                    
                </form>
            </dialog>
                    ";


            }
        }
        else {
            echo "
            <p id='noDogErrorMsg'>Je hebt nog geen hond toegevoegd!</p>
            ";
        }

        ?>
        <button class="openModalButton open-button">+<br> Hond Toevoegen</button>
    </div>



</div>

<dialog class="modal" id="modal">
    <button class="closeModalButton close-button">X</button>
    <h2>Hond toevoegen</h2>
    <p>Vul hier de gegevens in van de hond die u wilt toevoegen.</p>
    <br>
    <form class="form" method="post" enctype="multipart/form-data">
        <label>Naam <input id="dogName" name="dogName" type="text" required></label>
        <label>Leeftijd <input id="dogAge" name="dogAge" type="tel" required maxlength="2"></label>
        <label>Ras <input id="dogBreed" name="dogBreed" type="text" required></label>
        <label>Foto<input style="cursor: pointer" type="file" name="dogImage" required ></label>
        <br>
        <input id="modal-submit" type="submit" name="dogSubmit" value="Toevoegen">
    </form>
</dialog>

<script>
    const modal = document.querySelector("#modal");
    const modalDog = document.querySelector("#modalDog");
    const openModal = document.querySelector(".open-button");
    const closeModal = document.querySelector(".close-button");


    openModal.addEventListener("click", openModalFunction);
    closeModal.addEventListener("click", closeModalFunction);

   function openModalFunction() {
       modal.style.display = "block";
   }

   function closeModalFunction() {
       modal.style.display = "none";
   }


    function closeModalDogFunction() {
        modalDog.style.display = "none";
    }

    function openDogModal(newDogId) {
        let selector = "#modalDog" + newDogId;
        const modalDog = document.querySelector(selector);



        openModalDogFunction();


        function openModalDogFunction() {
            modalDog.style.display = "block";
        }


    }

    function closeDogModal(dogId) {
        let selector = "#modalDog" + dogId;
        const modalDog = document.querySelector(selector);


        closeModalDogFunction();

        function closeModalDogFunction() {

            modalDog.style.display = "none";
        }
    }


   function triggerClick() {
       document.querySelector('#ProfilePictureUpload').click();
   }

   function displayImage(e) {
       if (e.files[0]){
            var reader = new FileReader();

           reader.onload = function(e) {
               document.querySelector('#profilePictureDisplay').setAttribute('src', e.target.result);
           }
           reader.readAsDataURL(e.files[0]);
       }
   }

    function triggerClickDog(dogId) {
        let selector = "#dogPictureUpdateUpload" + dogId;
        document.querySelector(selector).click();
    }

    function displayImageDog(a, dogId) {
       let selector = "#dogPictureUpdateDisplay" + dogId;
        if (a.files[0]){
            var reader = new FileReader();

            reader.onload = function(a) {
                document.querySelector(selector).setAttribute('src', a.target.result);
            }
            reader.readAsDataURL(a.files[0]);
        }
    }




</script>

<?php
include "inc/foot.php";
?>




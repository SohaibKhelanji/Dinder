<?php

include_once 'class/dbh.php';
include_once 'class/user.php';

if (isset($_POST['submit'])) {

    $firstname = null;
    $lastname = null;
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User($firstname, $lastname, $email, $password);
    $user->loginUser();
    header('location: index.php');
}
?>
<?php
include 'inc/head.php';
include 'inc/navBar.php';
?>

<div class="login">
    <h1><b>Login</b></h1>
    <form method="post">
        <input type="text" name="email" placeholder="Email" id="email" required>
        <input type="password" name="password" placeholder="Wachtwoord" id="password" required>
        <input type="submit" name="submit" value="Login">
        <p>Nog geen account?<br><a href="register.php">Meld je aan!</a> </p>
        <p>Of</p>
        <p>Wachtwoord vergeten?<br><a href="forgottenPassword.php">Wachtwoord herstellen</a> </p>
    </form>
</div>


<?php
include 'inc/foot.php';
?>


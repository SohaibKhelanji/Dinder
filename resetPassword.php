<?php
include_once 'class/dbh.php';
include_once 'class/user.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if (isset($_POST['submit'])) {

    $firstname = null;
    $lastname = null;
    $email = $_GET['email'];
    $password = $_POST['password'];
    $resetCode = $_POST['code'];

    $user = new User($firstname, $lastname, $email, $password);
    $user->updatePasswordViaResetCode($resetCode);
    $emailFirstname = $user->getUserFirstname();
    date_default_timezone_set("Europe/Amsterdam");
    $date = date("d-m-Y");
    $time = date("H:i");

    $mailBody = "
    <body id='mailBody' style='width: 100%;height: 650px;color: black;background: white;font-size: 20px'>
        <div id='mailBanner' style='display: flex'>
        <img src= https://i.ibb.co/k1n5CDG/logo.png alt='Dinder logo' style='float: left;margin-top: 20px;width: 220px;height:55px'>
        </div>
        <br>
        <h1>Je wachtwoord is gewijzigd </h1>
        <p>Hallo $emailFirstname,
        <br>
        <br>
        Het wachtwoord voor je Dinder account is op $date om $time gewijzigd.<br>
        </p>
        </body>
    ";

//Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
//        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.aol.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'noreply.testcompany@aol.com';                     //SMTP username
        $mail->Password = '@TestCompany12345';                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom('no-reply@dinder.nl', 'Dinder');
        $mail->addAddress($email);     //Add a recipient
//        $mail->addReplyTo('info@example.com', 'Information');
//        $mail->addCC('cc@example.com');
//        $mail->addBCC('bcc@example.com');

        //Attachments
//        $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
//        $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Je wachtwoord is gewijzigd!';
        $mail->Body = $mailBody;
        $mail->AltBody = strip_tags($mailBody);

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    header("location: forgottenPassword.php?error=passwordResetSuccess");
}

?>




<?php include 'inc/head.php';
include 'inc/navBar.php';
?>

<div class="login">
    <h1>Wachtwoord resetten</h1>
    <form method="post">
        <input type="text" name="code" placeholder="Code" id="code" required>
        <input type="password" name="password" placeholder="Nieuw Wachtwoord" id="password" required>
        <input type="submit" name="submit" value="Resetten">
        <br>
        <p>Er is een code verstuurd naar je e-mail,<br> gebruik deze hierboven samen met je nieuwe gewenste wachtwoord om je oude te vervangen*</p>
    </form>
</div>

<?php
include 'inc/foot.php';
?>

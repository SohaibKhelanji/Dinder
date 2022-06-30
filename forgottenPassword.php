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
    $email = $_POST['email'];
    $password = null;
    $resetCode = rand();

    $user = new User($firstname, $lastname, $email, $password);


    $emailFirstname = $user->getUserFirstname();


    $mailBody = "
    <body id='mailBody' style='width: 100%;height: 650px;color: black;background: white;font-size: 20px'>
        <div id='mailBanner' style='display: flex'>
        <img src= https://i.ibb.co/k1n5CDG/logo.png alt='Dinder logo' style='float: left;margin-top: 20px;width: 220px;height:55px'>
        </div>
        <br>
        <h1>Wachtwoord resetten </h1>
        <p>Hallo $emailFirstname,
        <br>
        <br>
        Je hebt een verzoek gedaan voor het opnieuw instellen van je wachtwoord.<br>
        <br>
        Om je verzoek te voltooien, gebruik de volgende code: $resetCode
        </p>
        </body>
    ";

//Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
//        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'no.replydinder@gmail.com';                     //SMTP username
        $mail->Password = '@Dinder12345';                               //SMTP password
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
        $mail->Subject = 'Je hebt een nieuw wachtwoord aangevraagd!';
        $mail->Body = $mailBody;
        $mail->AltBody = strip_tags($mailBody);

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }

    $user->insertResetCode($resetCode);

    header("location: resetPassword.php?email=$email");
}
?>
<?php include 'inc/head.php';
include 'inc/navBar.php';
?>

<div class="login">
    <h1>Wachtwoord vergeten</h1>
    <form method="post">
        <input type="text" name="email" placeholder="E-mail" id="email" required>
        <input type="submit" name="submit" value="Aanvragen">
        <br>
    </form>
</div>

<?php
include 'inc/foot.php';
?>




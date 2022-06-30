<?php
include_once 'class/dbh.php';
include_once 'class/user.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

if (isset($_POST['submit'])) {

    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $user = new User($firstname, $lastname, $email, $password);
    $user->createUser();

    $mailBody = "
    <body id='mailBody' style='width: 100%;height: 650px;color: black;background: white;font-size: 20px'>
        <div id='mailBanner' style='display: flex'>
        <img src= https://i.ibb.co/k1n5CDG/logo.png alt='Dinder logo' style='float: left;margin-top: 20px;width: 220px;height:55px'>
        </div>
        <br>
        <h1>Je registratie is sucsessvol voltooid!</h1>
        <p>Hallo $firstname,
        <br>
        <br>
        Je account is sucsessvol geregistreerd! veel plezier met Dinderen!
        </p>
        </body>
    ";

//Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
//        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->SMTPDebug = 2;
        $mail->Host = 'smtp.mail.yahoo.com';                     //Set the SMTP server to send through
        $mail->SMTPAuth = true;                                   //Enable SMTP authentication
        $mail->Username = 'noreply.testcompany@yahoo.com';                     //SMTP username
        $mail->Password = 'oayphernjziqjjqk';                               //SMTP password
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
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
        $mail->Subject = 'Welkom bij Dinder!';
        $mail->Body = $mailBody;
        $mail->AltBody = strip_tags($mailBody);

        $mail->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    header("location: register.php?error=none");
}
?>
<?php include 'inc/head.php';
include 'inc/navBar.php';
?>

<div class="login">
    <h1>Aanmelden</h1>
    <form method="post">
        <input type="text" name="firstname" placeholder="Voornaam" id="firstname" required>
        <input type="text" name="lastname" placeholder="Achternaam" id="lastname" required>
        <input type="text" name="email" placeholder="E-mail" id="email" required>
        <input type="password" name="password" placeholder="Wachtwoord" id="password" required>
        <input type="submit" name="submit" value="Aanmelden">
        <p>al een account?<br><a href="login.php">klik hier om in te loggen!</a></p>
    </form>
</div>

<?php
include 'inc/foot.php';
?>



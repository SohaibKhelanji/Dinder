<?php

class User extends dbh
{

 Private $firstname;
 Private $lastname;
 Private $email;
 Private $password;

    public function __construct ($firstname, $lastname, $email, $password) {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->password = $password;
    }

    protected function userExists() {
        $stmt = $this->connection()->prepare('SELECT email FROM users WHERE email= ?;');

        if(!$stmt->execute(array($this->email))) {
        $stmt = null;
        header("location: register.php?error=stmtUserExistsFailed");
        exit();
        }
        else {
            $userExists = false;

            if(!$stmt->rowCount() > 0) {
                $userExists = true;
            }

            return $userExists;
        }

    }


    public function createUser()
    {
            $userExists = $this->userExists();
        if ($userExists == 1) {
            $stmt = $this->connection()->prepare('INSERT INTO users (firstname, lastname, email, password) VALUES (?, ?, ?, ?);');
            $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

            if (!$stmt->execute(array($this->firstname, $this->lastname, $this->email, $hashedPassword))) {
                $stmt = null;
                header("location: register.php?error=stmtCreateUserFailed");
                exit();
            }

            $stmt = null;
        }
    }

    public function loginUser() {
        $userExists=  $this->userExists();

        if ($userExists == 0) {
            $stmt = $this->connection()->prepare('SELECT password FROM users WHERE email= ?;');


            if (!$stmt->execute(array($this->email))) {
                $stmt = null;
                header("location: login.php?error=loginUserStmt1Failed");
                exit();
            }

            $hashedPassword = $stmt->fetchAll();
            $checkPassword = password_verify($this->password, $hashedPassword[0]["password"]);

            if (!$checkPassword == false) {

                $stmt = $this->connection()->prepare('SELECT * FROM users WHERE email= ?;');

                if (!$stmt->execute(array($this->email))) {
                    $stmt = null;
                    header("location: index.php?error=loginUserStmt2Failed");
                    exit();
                }

                if ($stmt->rowCount() == 0) {

                    $stmt = null;
                    header("location: login.php?error=userNotFound");
                    exit();
                }
                $user = $stmt->fetchAll();

                session_start();
                $_SESSION["userId"] = $user[0]["id"];
                $_SESSION["userFirstname"] = $user[0]["firstname"];
                $_SESSION["userLastname"] = $user[0]["lastname"];
                $_SESSION["userProfilePicture"] = $user[0]["profilepicture"];
                $_SESSION["userBiography"] = $user[0]["biography"];
                $_SESSION["userEmail"] = $user[0]["email"];
                $_SESSION["userPassword"] = $user[0]["password"];

            $stmt = null;
            }
            else {
                $stmt = null;
                header("location: login.php?error=wrongLoginCredentials");
                exit();
            }
        }
        else {
            header("location: login.php?error=userNotFound");
            exit();
        }
    }

    public function logoutUser() {
        session_start();
        unset($_SESSION['userId']);
    }

    public function insertResetCode($resetCode) {
        $userExists=  $this->userExists();

        if ($userExists == 0) {
            $stmt = $this->connection()->prepare('UPDATE users SET resetcode = ? WHERE email = ?;');

            $hashedResetCode = password_hash($resetCode, PASSWORD_DEFAULT);
            if (!$stmt->execute(array($hashedResetCode, $this->email))) {
                $stmt = null;
                header("location:forgottenPassword.php?error=stmtGenerateResetCodeFailed");
                exit();
            }

            $stmt = null;
        }
    }

    public function getUserFirstname() {
        $userExists=  $this->userExists();

        if ($userExists == 0) {
            $stmt = $this->connection()->prepare('SELECT * FROM users WHERE email = ?;');

            if (!$stmt->execute(array($this->email))) {
                $stmt = null;
                header("location:forgottenPassword.php?error=stmtGenerateResetCodeFailed");
                exit();
            }

            $getFirstname = $stmt->fetchAll();

            $stmt = null;
            return $getFirstname[0]["firstname"];
        }
    }

    public function updatePasswordViaResetCode($resetCode) {
        $userExists=  $this->userExists();

        if ($userExists == 0) {
            $stmt = $this->connection()->prepare('SELECT * FROM users WHERE email = ?;');

            if (!$stmt->execute(array($this->email))) {
                $stmt = null;
                header("location:forgottenPassword.php?error=stmtVerifyResetCodeFailed");
                exit();
            }

            $hashedCode = $stmt->fetchAll();
            $checkCode = password_verify($resetCode, $hashedCode['0']['resetcode']);

            if(!$checkCode == false) {

                $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);
                $stmt = $this->connection()->prepare('UPDATE users SET password = ? WHERE email = ?;');

                if(!$stmt->execute(array($hashedPassword, $this->email))) {
                    $stmt = null;
                    header("location: resetPassword?error=stmtResetPasswordFailed");
                    exit();
                }
                else {

                $stmt2 = $this->connection()->prepare('UPDATE users SET resetcode = NULL WHERE email = ?;');

                if (!$stmt2->execute(array($this->email))) {
                    $stmt = null;
                    header("location:forgottenPassword.php?error=stmt2ResetPasswordFailed");
                    exit();
                }
            }

                $stmt = null;

            }
            else {
                header("location:resetPassword.php?email=$this->email&error=InvalidCode");
                exit();
            }
        }

    }

    public function updateProfilePicture($imageName) {

        $stmt = $this->connection()->prepare('UPDATE users SET profilepicture = ? WHERE email = ?;');

        if (!$stmt->execute(array($imageName, $this->email))) {
            $stmt = null;
            header("location:profile.php?error=stmtUpdateProfilePictureFailed");
            exit();
        }

        $_SESSION['userProfilePicture'] = $imageName;
    }

    public function updateBiography($biography) {
        $stmt  ="UPDATE users SET biography = ? WHERE email = ?";
        $result = $this->connection()->prepare($stmt);

        if (!$result->execute(array($biography, $this->email))) {
            $result = null;
            header("location:profile.php?error=stmtUpdateBiographyFailed");
            exit();
        }
        $_SESSION['userBiography'] = $biography;
    }

}
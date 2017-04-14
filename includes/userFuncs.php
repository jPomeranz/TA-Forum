<?php
    function checkLogin($email, $password)
    {
        require_once "includes/dbutil.php";
        $db = DbUtil::loginConnection(false);

        $stmt = $db->stmt_init();

        if($stmt->prepare("SELECT password_hash FROM user WHERE email LIKE ?") or die(mysqli_error($db))) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($stored_pass_hash);
            $stmt->store_result(); //Needed to use num_rows

            if($stmt->num_rows != 1) {
                $stmt->close();
                $db->close();
                return false;
            }
            else {
                $stmt->fetch();
                if(!password_verify($password, $stored_pass_hash))
                {
                    $stmt->close();
                    $db->close();
                    return false;
                }
            }
            $stmt->close();
        }
        $db->close();
        return true;
    }

    function registerUser($email, $name, $password)
    {
        require_once "includes/dbutil.php";
        $db = DbUtil::loginConnection(false);

        $stmt = $db->stmt_init();

        if($stmt->prepare("INSERT INTO user VALUES(?,?,?)") or die(mysqli_error($db))) {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt->bind_param("sss", $email, $name, $password_hash);
            $stmt->execute();
            $stmt->close();
        }
        else {
            $stmt->close();
            $db->close();
            return false;
        }
        $db->close();
        return true;
    }

    if(isset($_POST["loginSubmitted"]))
    {
        $email = $_POST["email"];
        $password = $_POST["password"];
        if(checkLogin($email, $password)) {
            $_SESSION["email"] = $email;
            header("Refresh:0");
            echo "User " . $email . " has successfully logged in.";
        }
        else
            echo "Login error. Incorrect email or password.";
    }
    if(isset($_POST["registerSubmitted"]))
    {
        $name = $_POST["name"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        if(registerUser($email, $name, $password)) {
            $_SESSION["email"] = $email;
            header("Refresh:0");
            echo "User " . $email . " has successfully registered and logged in.";
        }
        else
            echo "Registration failed. Email in use. Please try again."; //Insert will fail if email not unique
    }
?>
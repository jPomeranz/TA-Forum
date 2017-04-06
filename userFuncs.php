<?php
    function checkLogin($email, $password)
    {
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
?>
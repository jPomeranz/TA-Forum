<?php
    function checkLogin($email, $password)
    {
        require_once "includes/dbutil.php";
        $db = DbUtil::loginConnection(false);

        $stmt = $db->stmt_init();

        if($stmt->prepare("SELECT name, password_hash FROM user WHERE email LIKE ?") or die(mysqli_error($db))) {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($name, $stored_pass_hash);
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
        $_SESSION['user_name'] = $name;
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
<!-- Large modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title" id="myModalLabel">Please Login or Register</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12" style="padding-right: 30px;">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" style="padding-bottom: 20px;">
                            <li class="active"><a href="#Login" data-toggle="tab">Login</a></li>
                            <li><a href="#Registration" data-toggle="tab">Registration</a></li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="Login">
                                <form role="form" class="form-horizontal" id="login" action="<?php echo $_SERVER['CONTEXT_PREFIX'] ?>/index.php" method="post" accept-charset="UTF-8">
                                <div class="form-group">
                                    <label for="email" class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required="true"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-sm-2 control-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" required="true"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                    </div>
                                    <div class="col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-sm" name="loginSubmitted"></input>
                                        <a href="javascript:;">Forgot your password?</a>
                                    </div>
                                </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="Registration">
                                <form role="form" class="form-horizontal" id="register" action="index.php" method="post" accept-charset="UTF-8">
                                <div class="form-group">
                                    <label for="name" class="col-sm-2 control-label">Name</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Name"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" placeholder="Email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$" required="true"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-sm-2 control-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="password" placeholder="Password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters" required="true"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-10">
                                        <input type="submit" class="btn btn-primary btn-sm" name="registerSubmitted"></input>
                                        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal" aria-hidden="true">Cancel</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

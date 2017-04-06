<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Provide Feedback</title>

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> -->
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="bootstrap.min.css">
    <link rel="stylesheet" href="custom.css">

    <link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">
</head>

<?php
    //DELETE ME FROM PROD!
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if (!isset($_SESSION)) {
        session_start();
    }
?>


<body>
    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">The TA Forum</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="index.php">Home</a></li>
                <li><a href="#about">About</a></li>
                <li><a href="#contact">Contact</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">Nav header</li>
                        <li><a href="feedback.php">Leave Feedback</a></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right" id="loginbox">
                <?php
                    if(!isset($_SESSION["email"])) //Show login button if user not logged in
                        echo "<li><a href=\"#\" data-toggle=\"modal\" data-target=\"#myModal\">Login</a></li>";
                    else echo "<li><a href=\"#\">" . $_SESSION["email"] . "</a></li>";
                ?>
            </ul>
        </div><!--/.nav-collapse -->
    </nav>

    <div class="container">
        <?php
            if(isset($_POST["feedbackSubmitted"]))
            {
                $description = $_POST["description"];
                if(addFeedback($description)) {
                    echo "Feedback has been successfully submitted.\nAn admin will review your comments soon.";
                }
                else
                    echo "There was an error submitting your feedback.";
            }
        ?>
    </div>

    <div class="container">
        <form role="form" class="form-horizontal" id="feedback" action="feedback.php" method="post" accept-charset="UTF-8">
            <div class="form-group">
                <h2>Please enter and submit your feedback here:</h2>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="description" name="description" placeholder="Feedback goes here" style="height:100px" required="True"/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10">
                    <input type="submit" class="btn btn-primary btn-sm" name="feedbackSubmitted"></input>
                </div>
            </div>
        </form>
    </div>

    <div class="container">
        <?php
            require_once "dbutil.php";

            $db = DbUtil::loginConnection();

            $stmt = $db->stmt_init();

            echo "<h2>Feedback:</h2>";
            $result = mysqli_query($db, "SELECT description FROM feedback");
            while($row = mysqli_fetch_array($result)) {
                echo "<p>- " . $row['description'] . "</p>";
            }

            $db->close();
        ?>
    </div>
</body>

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
                                <form role="form" class="form-horizontal" id="login" action="index.php" method="post" accept-charset="UTF-8">
                                <div class="form-group">
                                    <label for="email" class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" name="email" placeholder="Email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-sm-2 control-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Password" />
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
                                        <input type="text" class="form-control" id="name" name="name" placeholder="Name" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-sm-2 control-label">Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="email" placeholder="Email" name="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,3}$"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-sm-2 control-label">Password</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="password" placeholder="Password" name="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Must contain at least one number and one uppercase and lowercase letter, and at least 8 or more characters"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                    </div>
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

<?php
    function addFeedback($description)
    {
        require_once "dbutil.php";

        $db = DbUtil::loginConnection();

        $stmt = $db->stmt_init();

        if($stmt->prepare("INSERT INTO feedback (description) VALUES(?)") or die(mysqli_error($db))) {
            $stmt->bind_param("s", $description);
            $stmt->execute();
            $stmt->close();
        } else {
            $stmt->close();
            $db->close();
            return false;
        }

        $db->close();
        return true;
    }
?>
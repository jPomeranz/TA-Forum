<head>
    <?php
        include "includes/head.php";
    ?>
</head>

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
                <li><a href="index.php">Browse</a></li>
                <li class="active"><a href="feedback.php">Feedback</a></li>
                <li><a href="search.php">Search</a></li>
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
            <ul class="nav navbar-nav navbar-right">
                <?php 
                    if(!isset($_SESSION["email"])) //Show login button if user not logged in
                        echo "<li><a href=\"#\" data-toggle=\"modal\" data-target=\"#myModal\">Login</a></li>";
                    else echo "<li><a href=\"#\">" . $_SESSION["email"] . "</a></li><li><a href=\"logout.php\">Logout</a></li>"; //Else print email of user and logout button
                ?>
            </ul>
        </div><!--/.nav-collapse -->
    </nav>

    <div class="container">
        <?php
            require "includes/dbutil.php";
            require "includes/userFuncs.php";
            require "login.php";
            
            if(isset($_POST["feedbackSubmitted"])) {
                $description = $_POST["description"];
                if(addFeedback($description)) {
                    echo "Feedback has been successfully submitted.\nAn admin will review your comments soon.";
                }
                else
                    echo "There was an error submitting your feedback.";
            }
        ?>
        <form role="form" class="form-horizontal" id="feedbackForm" action="feedback.php" method="post" accept-charset="UTF-8">
            <div class="form-group">
                <h2>Please enter and submit your feedback here:</h2>
                <div class="col-sm-10">
                    <textarea class="form-control" id="description" name="description" placeholder="Feedback goes here" required="True" rows="5"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10">
                    <input type="submit" class="btn btn-primary btn-sm" name="feedbackSubmitted"></input>
                </div>
            </div>
        </form>
        <?php
            require_once "includes/dbutil.php";

            $db = DbUtil::loginConnection(true);

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

<?php
    function addFeedback($description) {
        require_once "includes/dbutil.php";

        $db = DbUtil::loginConnection(true);

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
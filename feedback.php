<head>
    <?php
        include "includes/head.php";
    ?>
</head>

<body>
    <?php
        $currentPage = "feedback";
        include "includes/navbar.php";
    ?>

    <div class="container">
        <?php
            require "includes/dbutil.php";
            require "includes/userFuncs.php";

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
                <div class="col-sm-12">
                    <h2>Please enter and submit your feedback here:</h2>
                    <textarea class="form-control" id="description" name="description" placeholder="Feedback goes here" required="True" rows="5"></textarea>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
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
            if ($result->num_rows > 0) {
                echo "<div class=\"table-responsive\"><table class=\"table table-striped\">";
                while($row = mysqli_fetch_array($result)) {
                    echo "<tr><td>" . $row['description'] . "</td></tr>";
                }
                echo "</table></div>";
            } else {
                echo "<div class=\"well well-sm\">No site feedback. Please add some!</div>";
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
            $description = htmlentities($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
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

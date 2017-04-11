<?php
    if(isset($_POST["feedbackSubmitted"])) {
        $description = $_POST["description"];
        if(addFeedback($description)) {
            echo "Feedback has been successfully submitted.\nAn admin will review your comments soon.";
        }
        else
            echo "There was an error submitting your feedback.";
    }
?>
<form role="form" class="form-horizontal" id="feedbackForm" action="index.php" method="post" accept-charset="UTF-8">
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
    require_once "dbutil.php";

    $db = DbUtil::loginConnection(true);

    $stmt = $db->stmt_init();

    echo "<h2>Feedback:</h2>";
    $result = mysqli_query($db, "SELECT description FROM feedback");
    while($row = mysqli_fetch_array($result)) {
        echo "<p>- " . $row['description'] . "</p>";
    }

    $db->close();
?>
<?php
    function addFeedback($description) {
        require_once "dbutil.php";

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
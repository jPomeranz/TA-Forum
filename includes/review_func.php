<?php
    function addReview($description) {
        require_once "includes/dbutil.php";

        $db = DbUtil::loginConnection(true);

        $stmt = $db->stmt_init();

        $email = $_SESSION["email"];

        if($stmt->prepare("INSERT INTO review (description, email) VALUES(?,?)") or die(mysqli_error($db))) {
            $stmt->bind_param("ss", $description, $email);
            $stmt->execute();
            $stmt->close();
            $new_id = $db->insert_id;
        } else {
            $stmt->close();
            $new_id = 0;
        }

        $db->close();
        return $new_id;
    }

    function addReviewAbout($review_id,$ta_id,$section_id) {
        require_once "includes/dbutil.php";

        $db = DbUtil::loginConnection(true);

        $stmt = $db->stmt_init();

        if($stmt->prepare("INSERT INTO review_about (review_id, ta_id, section_id) VALUES(?,?,?)") or die(mysqli_error($db))) {
            $stmt->bind_param("sss", $review_id, $ta_id, $section_id);
            $stmt->execute();
            $stmt->close();
            $db->close();
            return true;
        } else {
            $stmt->close();
            $db->close();
            return false;
        }
    }
?>
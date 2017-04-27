<?php
    require_once "dbutil.php";
    $db = DbUtil::loginConnection(true);
    session_start();

    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        if ($_GET['func'] == 'getCourseSections') {
            getCourseSections($_GET['course_dept'], $_GET['course_mnemonic_number']);
        }
    } elseif ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if ($_POST['func'] == 'addTA') {
            addTA($_POST['ta_id'], $_POST['name'], $_POST['graduation_year'], $_POST['section_id']);
        } else if ($_POST['func'] == 'addReview') {
            addReview($_POST['ta_id'], $_POST['section_id'], $_POST['description']);
        } else if ($_POST['func'] == 'deleteReview') {
            deleteReview($_POST['review_id']);
        } else if ($_POST['func'] == 'updateReview') {
            updateReview($_POST['review_id'], $_POST['section_id'], $_POST['ta_id'], $_POST['description']);
        } else if ($_POST['func'] == 'updateReviewLight') {
            updateReviewLight($_POST['review_id'], $_POST['description']);
        }
    }

    function getCourseSections($course_dept, $course_mnemonic_number) {
        global $db;

        $stmt = $db->prepare("SELECT section.year, section.semester, section.section_number, section.section_id FROM course INNER JOIN section_of ON course.course_id = section_of.course_id INNER JOIN section ON section_of.section_id = section.section_id WHERE course.course_dept = ? AND course.course_mnemonic_number = ?");
        $stmt->bind_param("si", $course_dept, $course_mnemonic_number);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($year, $semester, $section_number, $section_id);

        $sections = array();
        while ($stmt->fetch())
            $sections[$year][$semester][$section_number] = $section_id;

        header('Content-Type: application/json');
        echo json_encode($sections);
    }

    function addTA($ta_id, $name, $graduation_year, $section_id) {
        global $db;

        $name = htmlspecialchars($name, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $stmt = $db->prepare("INSERT INTO ta (ta_id, name, graduation_year) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $ta_id, $name, $graduation_year);
        $stmt->execute();
        $stmt->close();

        addTAToSection($ta_id, $section_id);
    }

    function addReview($ta_id, $section_id, $description) {
        global $db;

        addTAToSection($ta_id, $section_id);

        $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $stmt = $db->prepare("INSERT INTO review (description, email) VALUES (?, ?)");
        $stmt->bind_param("ss", $description, $_SESSION["email"]);
        $stmt->execute();
        $stmt->close();

        $review_id = $db->insert_id;

        $stmt = $db->prepare("INSERT INTO review_about (review_id, ta_id, section_id) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $review_id, $ta_id, $section_id);
        $stmt->execute();
        $stmt->close();
    }

    function updateReview($review_id, $section_id, $ta_id, $description) {
        global $db;

        addTAToSection($ta_id, $section_id);

        $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        updateReviewLight($review_id, $description);

        $stmt = $db->prepare("UPDATE review_about SET section_id = ? WHERE review_id = ?");
        $stmt->bind_param("ii", $section_id, $review_id);
        $stmt->execute();
        $stmt->close();
    }

    function updateReviewLight($review_id, $description) {
        global $db;

        $description = htmlspecialchars($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $stmt = $db->prepare("UPDATE review SET description = ? WHERE review_id = ?");
        $stmt->bind_param("si", $description, $review_id);
        $stmt->execute();
        $stmt->close();
    }

    function deleteReview($review_id) {
        global $db;

        $stmt = $db->prepare("DELETE FROM review WHERE review_id = ?");
        $stmt->bind_param("i", $review_id);
        $stmt->execute();
        $stmt->close();
    }

    function addTAToSection($ta_id, $section_id) {
        global $db;

        $stmt = $db->prepare("INSERT INTO teaches (ta_id, section_id) VALUES (?, ?)");
        $stmt->bind_param("si", $ta_id, $section_id);
        $stmt->execute();
        $stmt->close();
    }
?>

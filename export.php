<?php
    require "includes/dbutil.php";

    header('Content-type:application/json;charset=utf-8');
    header("Content-Disposition: attachment; filename=reviews.json");

    $db = DbUtil::loginConnection(true);

    $stmt = $db->stmt_init();

    $results_array = array();
    $result = mysqli_query($db, "SELECT course_dept, course_mnemonic_number, section_number, description, name FROM dept NATURAL JOIN course NATURAL JOIN section_of NATURAL JOIN section NATURAL JOIN review_about NATURAL JOIN review NATURAL JOIN ta");
    while($row = mysqli_fetch_array($result)) {
        $results_array[] = array('Course Dept'=>$row['course_dept'], 'Course Mnemonic Number'=>$row['course_mnemonic_number'], 'Section Number'=>$row['section_number'], 'Review Description'=>$row['description'], 'TA Name'=>$row['name']);
    }

    $db->close();

    echo json_encode($results_array);
?>
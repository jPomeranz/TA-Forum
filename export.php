<?php
    require "includes/dbutil.php";

    header('Content-type:application/json;charset=utf-8');
    header("Content-Disposition: attachment; filename=reviews.json");

    $db = DbUtil::loginConnection(true);

    $stmt = $db->stmt_init();

    $results_array = array();
    $result = mysqli_query($db, "SELECT * FROM `get_export_info`");
    while($row = mysqli_fetch_array($result)) {
        $results_array[] = array('Course Dept'=>$row['course_dept'], 'Course Mnemonic Number'=>$row['course_mnemonic_number'], 'Section Number'=>$row['section_number'], 'Semester'=>$row['semester'], 'Year'=>$row['year'], 'Review Description'=>$row['description'], 'Review Timestamp'=>$row['timestamp'], 'TA Name'=>$row['name'], 'TA Graduation Year'=>$row['graduation_year']);
    }

    $db->close();

    echo json_encode($results_array);
?>
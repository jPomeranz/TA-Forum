<?php
    function checkCourse($course_dept, $course_mnemonic_number) {
        global $db;

        $stmt = $db->prepare("SELECT COUNT(1) FROM course WHERE course_dept = ? AND course_mnemonic_number = ?");
        $stmt->bind_param("si", $course_dept, $course_mnemonic_number);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count == 1;
    }

    function checkTAId($ta_id) {
        global $db;

        $stmt = $db->prepare("SELECT COUNT(1) FROM ta WHERE ta_id = ?");
        $stmt->bind_param("s", $ta_id);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        return $count > 0;
    }
?>

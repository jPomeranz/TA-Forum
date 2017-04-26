<head>
    <?php
        require_once "includes/head.php";
    ?>
    <script src="<?=$_SERVER['CONTEXT_PREFIX']?>/assets/ajax.js"></script>
</head>

<body>
    <?php
        $currentPage = "index";

        require_once "includes/navbar.php";
        require_once "includes/dbutil.php";
        require_once "includes/userFuncs.php";
        require_once "includes/validators.php";

        $db = DbUtil::loginConnection(true);
    ?>
    <div class="container">
        <?php
            if (isset($_GET['course_dept'])) {
                $course_dept = strtoupper($_GET['course_dept']);
                if (isset($_GET['course_mnemonic_number'])) {
                    $course_mnemonic_number = $_GET['course_mnemonic_number'];
                    if (isset($_GET['ta_id'])) {
                        $ta_id = strtolower($_GET['ta_id']); ?>
                        <ol class="breadcrumb">
                            <li><a href="<?=$_SERVER['CONTEXT_PREFIX']?>">Home</a></li>
                            <li><a href="<?=$_SERVER['CONTEXT_PREFIX']?>/<?=$course_dept?>"><?=$course_dept?></a></li>
                            <li><a href="<?=$_SERVER['CONTEXT_PREFIX']?>/<?=$course_dept?>/<?=$course_mnemonic_number?>"><?=$course_mnemonic_number?></a></li>
                            <li class="active"><?=$ta_id?></li>
                        </ol> <?php

                        showTAReviews($course_dept, $course_mnemonic_number, $ta_id);
                    } else { ?>
                        <ol class="breadcrumb">
                            <li><a href="<?=$_SERVER['CONTEXT_PREFIX']?>">Home</a></li>
                            <li><a href="<?=$_SERVER['CONTEXT_PREFIX']?>/<?=$course_dept?>"><?=$course_dept?></a></li>
                            <li class="active"><?=$course_mnemonic_number?></li>
                        </ol> <?php

                        showCourseTAs($course_dept, $course_mnemonic_number);
                    }
                } else { ?>
                    <ol class="breadcrumb">
                        <li><a href="<?=$_SERVER['CONTEXT_PREFIX']?>">Home</a></li>
                        <li class="active"><?=$course_dept?></li>
                    </ol> <?php

                    showDepartmentCourses($course_dept);
                }
            } else { ?>
                <ol class="breadcrumb">
                    <li class="active">Home</li>
                </ol> <?php

                foreach($db->query("SELECT DISTINCT school FROM dept") as $row)
                    showSchoolDepartments($row['school']);
            }

            function showSchoolDepartments($school) {
                global $db;

                $stmt = $db->prepare("SELECT course_dept FROM dept WHERE school = ?");
                $stmt->bind_param("s", $school);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($course_dept);

                $num_rows = $stmt->num_rows;

                echo "<h2>$school Departments:</h2><hr />";
                echo "<div class=\"container-fluid\">";
                echo "<div class=\"row text-center\">";
                for ($i = 0; $i < $num_rows; $i += ceil($num_rows / 6)) {
                    echo "<div class=\"col-xs-2 list-unstyled\" style=\"padding: 0px 1px;\">";
                    for ($j = $i; $j < $i + ceil($num_rows / 6) && $stmt->fetch(); $j++) {
                        echo "<a class=\"list-group-item\" style=\"padding: 4px;\" href=\"$course_dept\">$course_dept</a>";
                    }
                    echo "</div>";
                }
                echo "</div>";
                echo "</div>";

                $stmt->close();
            }

            function showDepartmentCourses($course_dept) {
                global $db;

                $stmt = $db->prepare("SELECT course_mnemonic_number, title FROM course WHERE course_dept = ?");
                $stmt->bind_param("s", $course_dept);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($course_mnemonic_number, $title);

                $num_rows = $stmt->num_rows;

                echo "<h2>$course_dept Courses:</h2><hr />";
                if ($num_rows > 0) {
                    echo "<div class=\"container-fluid\">";
                    echo "<div class=\"row text-left\">";
                    for ($i = 0; $i < $num_rows; $i += ceil($num_rows / 2)) {
                        echo "<div class=\"col-xs-12 col-sm-6 list-unstyled\" style=\"padding: 0px 1px\">";
                        for ($j = $i; $j < $i + ceil($num_rows / 2) && $stmt->fetch(); $j++) {
                            echo "<a class=\"list-group-item\" style=\"padding: 4px;\" href=\"$course_dept/$course_mnemonic_number\">$course_dept $course_mnemonic_number: $title</a>";
                        }
                        echo "</div>";
                    }
                    echo "</div>";
                    echo "</div>";
                } else {
                    echo "<div class=\"alert alert-danger\"><strong>Error!</strong> Could not find department \"$course_dept.\"</div>";
                }
            }

            function showCourseTAs($course_dept, $course_mnemonic_number) {
                global $db;

                $stmt = $db->prepare("SELECT DISTINCT ta.name, ta.ta_id FROM ta INNER JOIN teaches ON ta.ta_id = teaches.ta_id INNER JOIN section_of ON teaches.section_id = section_of.section_id INNER JOIN course ON section_of.course_id = course.course_id WHERE course.course_dept = ? AND course.course_mnemonic_number = ? ORDER BY ta.name ASC");
                $stmt->bind_param("si", $course_dept, $course_mnemonic_number);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($ta_name, $ta_id);

                $num_rows = $stmt->num_rows;

                echo "<h2>$course_dept $course_mnemonic_number TAs: ";
                if (isset($_SESSION['email'])) {
                    echo '<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addModal">Add TA</button>';
                }
                echo '</h2><hr />';

                if ($num_rows > 0) {
                    echo "<div class=\"container-fluid\">";
                    echo "<div class=\"row text-left\">";
                    for ($i = 0; $i < $num_rows; $i += ceil($num_rows / 3)) {
                        echo "<div class=\"col-xs-12 col-sm-4 list-unstyled\" style=\"padding: 0px 1px\">";
                        for ($j = $i; $j < $i + ceil($num_rows / 3) && $stmt->fetch(); $j++) {
                            echo "<a class=\"list-group-item\" style=\"padding: 4px;\" href=\"$course_mnemonic_number/$ta_id\">$ta_name</a>";
                        }
                        echo "</div>";
                    }
                    echo "</div>";
                    echo "</div>";
                } else if (checkCourse($course_dept, $course_mnemonic_number)) {
                    echo "<div class=\"well well-sm\">No teaching assistants found for $course_dept $course_mnemonic_number.</div>";
                } else {
                    echo "<div class=\"alert alert-danger\"><strong>Error!</strong> Could not find course \"$course_dept $course_mnemonic_number.\"</div>";
                }
            }

            function showTAReviews($course_dept, $course_mnemonic_number, $ta_id) {
                global $db;

                $stmt = $db->prepare("SELECT ta.name, review.review_id, review.description, review.timestamp, review.email, section.semester, section.year FROM review INNER JOIN review_about ON review.review_id = review_about.review_id INNER JOIN ta ON review_about.ta_id = ta.ta_id INNER JOIN section ON review_about.section_id = section.section_id INNER JOIN section_of ON section.section_id = section_of.section_id INNER JOIN course ON section_of.course_id = course.course_id WHERE course.course_dept = ? AND course.course_mnemonic_number = ? AND ta.ta_id = ? ORDER BY section.year ASC, section.semester ASC");
                $stmt->bind_param("sis", $course_dept, $course_mnemonic_number, $ta_id);
                $stmt->execute();
                $stmt->store_result();
                $stmt->bind_result($name, $review_id, $description, $timestamp, $review_email, $semester, $year);

                $num_rows = $stmt->num_rows;

                $reviews = array();
                while ($stmt->fetch()) {
                    $reviews[$year][$semester][] = array("id" => $review_id,
                                                         "description" => $description,
                                                         "timestamp" => $timestamp,
                                                         "review_email" => $review_email);
                }
                if (empty($name))
                    echo "<h2>{$ta_id}'s Reviews: ";
                else
                    echo "<h2>{$name}'s Reviews: ";
                if (isset($_SESSION['email'])) {
                    echo '<button class="btn btn-primary" type="button" data-toggle="modal" data-target="#addModal">Add Review</button>';
                }
                echo '</h2><hr />';
                if ($num_rows > 0) {
                    echo '<div class="panel-group">';
                    foreach ($reviews as $year => $semesters) {
                        foreach (array_keys($semesters) as $semester) {
                            ?>
                            <div class="panel panel-default">
                                <div class="panel-heading" data-toggle="collapse" data-target="#<?=$year?><?=$semester?>" style="cursor: pointer;">
                                    <h3 class="panel-title"><?=$year?> <?=$semester?></h3>
                                </div>
                                <div class="panel-collapse collapse" id="<?=$year?><?=$semester?>">
                                    <ul class="list-group">
                                    <?php foreach ($semesters[$semester] as $review): ?>
                                        <li class="list-group-item">
                                            <span class="badge">
                                                <?=$review['timestamp']?>
                                                <?php if (isset($_SESSION['email']) && $_SESSION['email'] == $review['review_email']): ?>
                                                    <button class="btn btn-xs btn-primary" type="button" id="delete-<?=$review['id']?>">Delete</button>
                                                <?php endif; ?>
                                            </span>
                                            <?=$review['description']?>
                                        </li>
                                    <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    echo '</div>';
                } else if (checkCourse($course_dept, $course_mnemonic_number)) {
                    if (checkTAId($ta_id)) {
                        echo "<div class=\"well well-sm\">No reviews found for $course_dept $course_mnemonic_number.</div>";
                    } else {
                        echo "<div class=\"alert alert-danger\"><strong>Error!</strong> Could not find TA \"$ta_id.\"</div>";
                    }
                } else {
                    echo "<div class=\"alert alert-danger\"><strong>Error!</strong> Could not find course \"$course_dept $course_mnemonic_number.\"</div>";
                }
            }
        ?>
    </div>

    <div id="addModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <?php if (isset($course_mnemonic_number) && !isset($ta_id)): ?>
                        <h4 class="modal-title">Add Teaching Assistant</h4>
                    <?php elseif (isset($ta_id)): ?>
                        <h4 class="modal-title">Add Review</h4>
                    <?php endif; ?>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Year:</label>
                            <div class="col-sm-9">
                                <select id="section_year" class="form-control input-sm"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Semester:</label>
                            <div class="col-sm-9">
                                <select id="section_semester" class="form-control input-sm"></select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Section #:</label>
                            <div class="col-sm-9">
                                <select id="section_num" class="form-control input-sm"></select>
                            </div>
                        </div>
                        <hr />
                        <?php if (isset($course_mnemonic_number) && !isset($ta_id)): ?>
                            <div class="form-group has-error">
                                <label class="col-sm-3 control-label">Name:</label>
                                <div class="col-sm-9">
                                    <input id="ta_name" type="text" class="form-control input-sm" />
                                </div>
                            </div>
                            <div class="form-group has-error">
                                <label class="col-sm-3 control-label">Computing ID:</label>
                                <div class="col-sm-9">
                                    <input id="ta_id" type="text" class="form-control input-sm" />
                                </div>
                            </div>
                            <div class="form-group has-error">
                                <label class="col-sm-3 control-label">Graduation Year:</label>
                                <div class="col-sm-9">
                                    <input id="ta_graduation_year" type="number" class="form-control input-sm" />
                                </div>
                            </div>
                        <?php elseif (isset($ta_id)): ?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Review:</label>
                                <div class="col-sm-9">
                                    <textarea id="review_description" class="form-control" rows="5"></textarea>
                                </div>
                            </div>
                        <?php endif; ?>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <?php if (isset($course_mnemonic_number) && !isset($ta_id)): ?>
                        <button id="add_ta_submit" type="button" class="btn btn-primary">Add TA</button>
                    <?php elseif (isset($ta_id)): ?>
                        <button id="add_review_submit" type="button" class="btn btn-primary">Add Review</button>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</body>

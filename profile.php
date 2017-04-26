<head>
    <?php
        require_once "includes/head.php";
    ?>
    <script src="<?=$_SERVER['CONTEXT_PREFIX']?>/assets/ajax.js"></script>
</head>
<body>
    <?php
        $currentPage = "profile";

        require_once "includes/navbar.php";
        require_once "includes/dbutil.php";
        require_once "includes/userFuncs.php";
        require_once "includes/validators.php";

        $db = DbUtil::loginConnection(true);
    ?>
    <div class="container">
        <?php
        if (isset($_SESSION['email']))
            $email = $_SESSION['email'];
        else {
            echo "<div class=\"alert alert-danger\"><strong>Error!</strong> Please log in to access your profile.</div>";
            die();
        }

        $stmt = $db->prepare("SELECT ta.name, review.review_id, review.description, review.timestamp, review.email, section.section_id, section.semester, section.year, course.course_dept, course.course_mnemonic_number FROM review INNER JOIN review_about ON review.review_id = review_about.review_id INNER JOIN ta ON review_about.ta_id = ta.ta_id INNER JOIN section ON review_about.section_id = section.section_id INNER JOIN section_of ON section.section_id = section_of.section_id INNER JOIN course ON section_of.course_id = course.course_id WHERE review.email = ? ORDER BY section.year ASC, section.semester ASC");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($name, $review_id, $description, $timestamp, $review_email, $section_id, $semester, $year, $course_dept, $course_mnemonic_number);

        $num_rows = $stmt->num_rows;

        $reviews = array();
        while ($stmt->fetch()) {
            $reviews[$year][$semester][] = array("name" => $name,
                                                 "id" => $review_id,
                                                 "description" => $description,
                                                 "timestamp" => $timestamp,
                                                 "review_email" => $review_email,
                                                 "section_id" => $section_id,
                                                 "course_dept" => $course_dept,
                                                 "course_mnemonic_number" => $course_mnemonic_number);
        }

        $stmt->close();

        echo "<h2>{$_SESSION['user_name']}'s Profile:</h2><hr />";

        if ($num_rows > 0) {
            echo '<div class="panel-group">';
            foreach ($reviews as $year => $semesters) {
                foreach (array_keys($semesters) as $semester) {
                    ?>
                    <div class="panel panel-default">
                        <div class="panel-heading" data-toggle="collapse" data-target="#<?=$year?>-<?=$semester?>" style="cursor: pointer;">
                            <h3 class="panel-title"><?=$year?> <?=$semester?></h3>
                        </div>
                        <div class="panel-collapse collapse" id="<?=$year?>-<?=$semester?>">
                            <ul class="list-group">
                            <?php foreach ($semesters[$semester] as $review): ?>
                                <li class="list-group-item">
                                    <span class="badge">
                                        <?=$review['timestamp']?>
                                        <div class="btn-group">
                                            <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
                                                Edit <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu" style="min-width: 80px; background-color: #b15315;">
                                                <li><a href="javascript:void(0)" id="edit-<?=$review['id']?>">Edit</a></li>
                                                <li><a href="javascript:void(0)" id="delete-<?=$review['id']?>">Delete</a></li>
                                            </ul>
                                        </div>
                                    </span>
                                    <h4 class="list-group-item-heading">
                                        <?php echo "{$review['course_dept']} {$review['course_mnemonic_number']} - {$review['name']}"; ?>
                                    </h4>
                                    <div id="review-<?=$review['id']?>-<?=$year?>-<?=$semester?>-<?=$review['section_id']?>">
                                        <?=$review['description']?>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <?php
                }
            }
            echo '</div>';
        } else {
            echo "<div class=\"well well-sm\">You have no reviews. Go write some!</div>";
        }
        ?>
    </div>

    <div id="addModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Update Review</h4>
                </div>
                <div class="modal-body">
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Review:</label>
                            <div class="col-sm-10">
                                <textarea id="review_description" class="form-control" rows="5"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button id="modal_close" type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button id="add_review_submit" type="button" class="btn btn-primary">Save Review</button>
                </div>
            </div>
        </div>
    </div>
</body>

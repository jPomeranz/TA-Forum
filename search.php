<head>
    <?php
        include "includes/head.php";
    ?>
</head>

<body>
    <?php
        $currentPage = "search";
        include "includes/navbar.php";
    ?>

    <div class="container">
        <form role="form" class="form-horizontal" id="searchForm" action="search.php" method="post" accept-charset="UTF-8">                
            <div class="form-group">
                <h2>Please enter all information about the TA you're searching for:</h2>
                <label for="name" class="col-sm-2 control-label">TA Name</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="name" name="name" placeholder="TA Name"/>
                </div>
            </div>
            <div class="form-group">
                <label for="title" class="col-sm-2 control-label">Course Title</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="title" name="title" placeholder="Course Title"/>
                </div>
            </div>
            <div class="form-group">
                <label for="dept" class="col-sm-2 control-label">Course Dept</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="dept" name="dept" placeholder="Course Department Abbreviation"/>
                </div>
            </div>
            <div class="form-group">
                <label for="mnemonic" class="col-sm-2 control-label">Course Number</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="mnemonic" name="mnemonic" placeholder="Course Number"/>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2">
                </div>
                <div class="col-sm-10">
                    <input type="submit" class="btn btn-primary btn-sm" name="searchSubmitted" value="Search"></input>
                </div>
            </div>
        </form>
        <?php
            require "includes/dbutil.php";
            require "includes/userFuncs.php";
            
            if(isset($_POST["searchSubmitted"])) {
                $name = $_POST["name"];
                $title = $_POST["title"];
                $dept = $_POST["dept"];
                $mnemonic = $_POST["mnemonic"];
                if(!search($name, $title, $dept, $mnemonic))
                    echo "There was an error performing your search. Please try again later.";
            }
        ?>
    </div>
</body>

<?php
    function search($name, $title, $dept, $mnemonic) {
        require_once "includes/dbutil.php";

        $db = DbUtil::loginConnection(true);

        $stmt = $db->stmt_init();

        $date = date("Y");
        $name = "%" . $name . "%";
        $title = "%" . $title . "%";
        $dept = "%" . $dept . "%";
        $mnemonic = "%" . $mnemonic . "%";

        if($stmt->prepare("SELECT ta_id, name, title, course_dept, course_mnemonic_number, section_number, semester FROM ta NATURAL JOIN teaches NATURAL JOIN section NATURAL JOIN section_of NATURAL JOIN course where year=$date AND name LIKE ? AND title LIKE ? AND course_dept LIKE ? AND course_mnemonic_number LIKE ?") or die(mysqli_error($db))) {
            $stmt->bind_param("ssss", $name, $title, $dept, $mnemonic);
            $stmt->execute();
            $stmt->bind_result($ta_id, $name, $title, $course_dept, $course_mnemonic_number, $section_number, $semester);

            echo "<h3>Search Results:</h3>";
            echo "<div class=\"table-responsive\"><table class=\"table\"><th>Name</th><th>Course Title</th><th>Course Department</th><th>Course Number</th><th>Section Number</th><th>Semester</th><br>";
            while($stmt->fetch()) {
                echo "<tr><td>$name</td><td><a href=\"" . $_SERVER['CONTEXT_PREFIX'] . "/$course_dept/$course_mnemonic_number/$ta_id\">$title</a></td><td>$course_dept</td><td>$course_mnemonic_number</td><td>$section_number</td><td>$semester</td></tr>";
            }
            echo "</table></div>";
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
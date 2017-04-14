<head>
    <?php
        include "includes/head.php";
    ?>
</head>

<body>
    <nav class="navbar navbar-inverse">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">The TA Forum</a>
        </div>
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="index.php">Browse</a></li>
                <li><a href="feedback.php">Feedback</a></li>
                <li class="active"><a href="search.php">Search</a></li>
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Dropdown <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="#">Action</a></li>
                        <li><a href="#">Another action</a></li>
                        <li><a href="#">Something else here</a></li>
                        <li role="separator" class="divider"></li>
                        <li class="dropdown-header">Nav header</li>
                        <li><a href="feedback.php">Leave Feedback</a></li>
                        <li><a href="#">One more separated link</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <?php 
                    if(!isset($_SESSION["email"])) //Show login button if user not logged in
                        echo "<li><a href=\"#\" data-toggle=\"modal\" data-target=\"#myModal\">Login</a></li>";
                    else echo "<li><a href=\"#\">" . $_SESSION["email"] . "</a></li><li><a href=\"logout.php\">Logout</a></li>"; //Else print email of user and logout button
                ?>
            </ul>
        </div><!--/.nav-collapse -->
    </nav>

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
            require "login.php";
            
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

        if($stmt->prepare("SELECT name, title, section_number, semester FROM ta NATURAL JOIN teaches NATURAL JOIN section NATURAL JOIN section_of NATURAL JOIN course where year=$date AND name LIKE ? AND title LIKE ? AND course_dept LIKE ? AND course_mnemonic_number LIKE ?") or die(mysqli_error($db))) {
            $stmt->bind_param("ssss", $name, $title, $dept, $mnemonic);
            $stmt->execute();
            $stmt->bind_result($name, $title, $section_number, $semester);

            echo "<h3>Search Results:</h3>";
            echo "<div class=\"table-responsive\"><table class=\"table\"><th>Name</th><th>Course Title</th><th>Section Number</th><th>Semester</th><br>";
            while($stmt->fetch()) {
                echo "<tr><td>$name</td><td>$title</td><td>$section_number</td><td>$semester</td></tr>";
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
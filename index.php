<head>
    <?php
        include "includes/head.php";
    ?>
</head>

<body>
    <?php
        $currentPage = "index";
        include "includes/navbar.php";
    ?>

    <div class="container">
        <?php
            require "includes/dbutil.php";
            require "includes/userFuncs.php";

            $db = DbUtil::loginConnection(true);

            $stmt = $db->stmt_init();

            echo "<h2>Browse Courses By School:</h2>";
            $result = mysqli_query($db, "SELECT DISTINCT school FROM dept");
            while($row = mysqli_fetch_array($result)) {
                echo "<p><b>" . $row['school'] . ":</b></p><div class='check-margin2'>";
                $subresult = mysqli_query($db, "SELECT course_dept FROM dept WHERE school=\"" . $row['school'] . "\" ORDER BY school");
                while($subrow = mysqli_fetch_array($subresult)) {
                    echo $subrow['course_dept'] . "<br>";
                }
                echo "</div>";
            }

            $db->close();
        ?>
    </div>
</body>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>The TA Forum</title>

	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous"> -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<link rel="stylesheet" href="assets/bootstrap.min.css">
	<link rel="stylesheet" href="assets/custom.css">
	<link href="//fonts.googleapis.com/css?family=Raleway:400,300,600" rel="stylesheet" type="text/css">
    <script>
        $(document).ready(function(){    
            $(".navbar-nav a").on("click", function(){
                $(".navbar-nav").find(".active").removeClass("active");
                $(this).parent().addClass("active");
            });
        });
        function toggleInvis(divId) {
            $("#" + divId).removeClass('hidden').siblings().addClass('hidden');
        }
    </script>
</head>

<?php
    //DELETE ME FROM PROD!
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

    if (!isset($_SESSION)) {
        session_start();
    }
?>


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
				<li class="active"><a href="#" onclick="toggleInvis('browse');">Browse</a></li>
				<li><a href="#" onclick="toggleInvis('feedback');">Feedback</a></li>
				<li><a href="#" onclick="toggleInvis('search');">Search</a></li>
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

    <?php
        require 'login.php';
    ?>

	<div class="container">
        <div id="browse">
    		<?php
    			require "dbutil.php";
                require "userFuncs.php";

                if(isset($_POST["loginSubmitted"]))
                {
                    $email = $_POST["email"];
                    $password = $_POST["password"];
                    if(checkLogin($email, $password)) {
                        $_SESSION["email"] = $email;
                        echo "User " . $email . " has successfully logged in.";
                        header("Refresh:0");
                    }
                    else
                        echo "Login error. Incorrect email or password.";
                }
                if(isset($_POST["registerSubmitted"]))
                {
                    $name = $_POST["name"];
                    $email = $_POST["email"];
                    $password = $_POST["password"];
                    if(registerUser($email, $name, $password)) {
                        $_SESSION["email"] = $email;
                        echo "User " . $email . " has successfully registered and logged in.";
                        header("Refresh:0");
                    }
                    else
                        echo "Registration failed. Email in use. Please try again."; //Insert will fail if email not unique
                }


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

        <div id="feedback" class="hidden">
            <?php
                require 'feedback.php';
            ?>
        </div>
        <div id="search" class="hidden">
            <?php
                require 'search.php';
            ?>
        </div>
    </div>
</body>
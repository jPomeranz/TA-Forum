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
			<li class="<?php if($currentPage =='index'){echo 'active';}?>"><a href="index.php">Browse</a></li>
			<li class="<?php if($currentPage =='feedback'){echo 'active';}?>"><a href="feedback.php">Feedback</a></li>
			<li class="<?php if($currentPage =='search'){echo 'active';}?>"><a href="search.php">Search</a></li>
			<li class="<?php if($currentPage =='export'){echo 'active';}?>"><a href="export.php">Export</a></li>
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
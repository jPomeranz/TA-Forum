<nav class="navbar navbar-inverse">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="<?php echo $_SERVER['CONTEXT_PREFIX'] ?>">The TA Forum</a>
	</div>
	<div class="navbar-collapse collapse">
		<ul class="nav navbar-nav">
			<li class="<?php if($currentPage =='index'){echo 'active';}?>"><a href="<?php echo $_SERVER['CONTEXT_PREFIX'] ?>">Browse</a></li>
			<li class="<?php if($currentPage =='feedback'){echo 'active';}?>"><a href="<?php echo $_SERVER['CONTEXT_PREFIX'] ?>/feedback.php">Feedback</a></li>
			<li class="<?php if($currentPage =='search'){echo 'active';}?>"><a href="<?php echo $_SERVER['CONTEXT_PREFIX'] ?>/search.php">Search</a></li>
			<li class="<?php if($currentPage =='export'){echo 'active';}?>"><a href="<?php echo $_SERVER['CONTEXT_PREFIX'] ?>/export.php">Export</a></li>
		</ul>
		<ul class="nav navbar-nav navbar-right">
            <?php
                if(!isset($_SESSION["email"])) //Show login button if user not logged in
				    echo "<li><a href=\"#\" data-toggle=\"modal\" data-target=\"#myModal\">Login</a></li>";
                else echo "<li><a href=\"#\">" . $_SESSION["email"] . "</a></li><li><a href=\"{$_SERVER['CONTEXT_PREFIX']}/logout.php\">Logout</a></li>"; //Else print email of user and logout button
            ?>
		</ul>
	</div><!--/.nav-collapse -->
</nav>

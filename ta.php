<head>
    <?php
        include "includes/head.php";
    ?>
</head>

<body>
    <?php
        $currentPage = "ta";
        include "includes/navbar.php";

        if(isset($_GET["section"]) && !empty($_GET["section"])){
    ?>
            <div class="container">
                <form role="form" class="form-horizontal" id="taForm" action="browse.php" method="post" accept-charset="UTF-8">
                    <input type="hidden" name="section_id" value="<?php echo $_GET['section']; ?>" />
                    <div class="form-group">
                        <h2>Enter information for new TA:</h2>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="ta_name" name="ta_name" placeholder="Name of TA goes here" required="True"/>
                            <input type="number" class="form-control" id="grad_year" name="grad_year" min="2017" max="2050" placeholder="Year of TA graduation" required="True"/>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-10">
                            <input type="submit" class="btn btn-primary btn-sm" name="taSubmitted"></input>
                        </div>
                    </div>
                </form>
            </div>
    <?php
        }
        else {
            echo "Missing section entries.";
        }
    ?>
</body>
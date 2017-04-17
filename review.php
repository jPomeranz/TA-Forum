<head>
    <?php
        include "includes/head.php";
    ?>
</head>

<body>
    <?php
        $currentPage = "review";
        include "includes/navbar.php";

        if(isset($_GET["ta"]) && !empty($_GET["ta"]) && isset($_GET["section"]) && !empty($_GET["section"])){
    ?>
            <div class="container">
                <form role="form" class="form-horizontal" id="reviewForm" action="browse.php" method="post" accept-charset="UTF-8">
                    <input type="hidden" name="ta_id" value="<?php echo $_GET['ta']; ?>" />
                    <input type="hidden" name="section_id" value="<?php echo $_GET['section']; ?>" />
                    <div class="form-group">
                        <h2>Enter your review here:</h2>
                        <div class="col-sm-10">
                            <textarea class="form-control" id="review_desc" name="review_desc" placeholder="Review goes here" required="True" rows="5"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-10">
                            <input type="submit" class="btn btn-primary btn-sm" name="reviewSubmitted"></input>
                        </div>
                    </div>
                </form>
            </div>
    <?php
        }
        else {
            echo "Missing TA and section entries.";
        }
    ?>
</body>
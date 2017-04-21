<head>
    <?php
        include "includes/head.php";
    ?>
</head>

<body>
    <?php
        $currentPage = "browse";
        include "includes/navbar.php";
    ?>

    <div class="container">
        <?php
            require_once "includes/userFuncs.php";
            require_once "includes/db_funcs.php";

            if(isset($_POST["reviewSubmitted"])) {
                $description = $_POST["review_desc"];
                if(isset($_POST["ta_id"]) && !empty($_POST["ta_id"]) && isset($_POST["section_id"]) && !empty($_POST["section_id"])){
                    $ta_id = $_POST["ta_id"];
                    $section_id = $_POST["section_id"];
                    $rev_id = addReview($description);
                    if($rev_id != 0) {
                        if(addReviewAbout($rev_id,$ta_id,$section_id)) {
                            echo "Review has been successfully submitted.\n";
                        }
                        else {
                            echo "Error creating relationship.";
                        }
                    }
                    else {
                        echo "There was an error submitting your review.";
                    }
                }
                else {
                    echo "Missing TA and section entries.";
                }
            }

            if(isset($_POST["taSubmitted"])) {
                $name = $_POST["ta_name"];
                $grad_year = $_POST["grad_year"];
                if(isset($_POST["section_id"]) && !empty($_POST["section_id"])){
                    $section_id = $_POST["section_id"];
                    $ta_id = addTA($name, $grad_year);
                    if($ta_id != 0) {
                        if(addTeaches($ta_id,$section_id)) {
                            echo "TA has been added successfully.\n";
                        }
                        else {
                            echo "Error creating relationship.";
                        }
                    }
                    else {
                        echo "There was an error creating the TA.";
                    }
                }
                else {
                    echo "Missing entries.";
                }
            }
        ?>
    </div>
</body>
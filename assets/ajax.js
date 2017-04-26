$(function() {
    var url = window.location.pathname;
    var url_parts = url.substring(1).split("/");
    var script_url = "/" + url_parts[0] + "/includes/db_funcs.php";

    var sections;

    if (url_parts.length >= 3) {
        $.getJSON(script_url, {"func": "getCourseSections", "course_dept": url_parts[1], "course_mnemonic_number": url_parts[2]}, function(data) {
            sections = data;

            //
            // Dynamically update year/semester/section
            //

            $("#section_semester").on("change", function() {
                var el = $("#section_num");
                el.empty();
                $.each(sections[$("#section_year").val()][this.value], function(key, value) {
                    el.append($("<option></option>").attr("value", value).text(key));
                });
            });

            $("#section_year").on("change", function() {
                var el = $("#section_semester");
                el.empty();
                $.each(sections[this.value], function(key, value) {
                    el.append($("<option></option>").attr("value", key).text(key));
                });
                el.trigger("change");
            });

            var el = $("#section_year");
            $.each(Object.keys(sections).sort().reverse(), function(key, value) {
                el.append($("<option></option>").attr("value", value).text(value));
            });
            el.trigger("change");
        });
    }

    //
    // POST requests
    //

    $("#add_ta_submit").on("click", function() {
        if ($("#addModal .has-error").length == 0) {
            $.post(script_url, {"func": "addTA", "ta_id": $("#ta_id").val(), "name": $("#ta_name").val(), "graduation_year": $("#ta_graduation_year").val(), "section_id": $("#section_num").val()}, function() {
                location.reload();
            });
        }
    });

    $("#add_review_submit").on("click", postAddReview);

    function postAddReview() {
        $.post(script_url, {"func": "addReview", "description": $("#review_description").val(), "ta_id": url_parts[3], "section_id": $("#section_num").val()}, function() {
            location.reload();
        });
    }

    $("a[id^='delete-']").on("click", function() {
        var review_id = $(this).attr("id").split("-")[1];
        $.post(script_url, {"func": "deleteReview", "review_id": review_id}, function() {
            location.reload();
        });
    });

    $("a[id^='edit-']").on("click", function() {
        var review_id = $(this).attr("id").split("-")[1];
        var review_div = $("div[id^='review-" + review_id + "']");
        var review_description = review_div.text().trim();
        var review_parts = review_div.attr("id").split("-");

        $(".modal-title").text('Edit Review');
        $("#add_review_submit").text('Save Review');
        $("#review_description").val(review_description);

        $("#section_year").val(review_parts[2]);
        $("#section_year").trigger("change");

        $("#section_semester").val(review_parts[3]);
        $("#section_semester").trigger("change");

        $("#section_num").val(review_parts[4]);

        $("#add_review_submit").off().on("click", function() {
            var post_data;

            if (url_parts[1].startsWith("profile")) {
                post_data = {"func": "updateReviewLight", "review_id": review_id, "description": $("#review_description").val()};
            } else {
                post_data = {"func": "updateReview", "review_id": review_id, "section_id": $("#section_num").val(), "ta_id": url_parts[3], "description": $("#review_description").val()};
            }

            $.post(script_url, post_data, function() {
                location.reload();
            });
        });

        $("#addModal").modal("show");

        $("#addModal").one("hide.bs.modal", function() {
            $(".modal-title").text('Add Review');
            $("#add_review_submit").text('Add Review');
            $("#add_review_submit").off().on("click", postAddReview);
            $("#review_description").val('');
        });
    });

    //
    // Input Validators for TA adding
    //

    $("#ta_name").on("input", function() {
        if (this.value.length > 0) {
            $(this).closest(".form-group").removeClass("has-error");
        } else {
            $(this).closest(".form-group").addClass("has-error");
        }
    });

    $("#ta_id").on("input", function() {
        var patt = /^[a-z]{2,3}(?:\d[a-z]{1,2})?$/;
        if (patt.test(this.value)) {
            $(this).closest(".form-group").removeClass("has-error");
        } else {
            $(this).closest(".form-group").addClass("has-error");
        }
    });

    $("#ta_graduation_year").on("input", function() {
        var patt = /^\d{4}$/;
        if (patt.test(this.value)) {
            $(this).closest(".form-group").removeClass("has-error");
        } else {
            $(this).closest(".form-group").addClass("has-error");
        }
    });
});

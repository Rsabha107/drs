$(document).ready(function () {
    // console.log("all tasksJS file");

    // ************************************************** task venues

    $("body").on("click", "#offcanvas-add-participant", function () {
        console.log("inside #offcanvas-add-participant");
        $("#cover-spin").show();
        $("#offcanvas-add-participant-modal").offcanvas("show");
        $("#cover-spin").hide();
    });

    $("body").on("click", "#change_participant_status", function () {
        console.log("inside #change_participant_status");
        $("#cover-spin").show();
        var id = $(this).data("id");
        var status_id = $(this).data("status_id");
        var table = $(this).data("table");
        console.log("id", id);
        console.log("table", table);
        $("#participant_id").val(id);
        $("#editStatusSelection").val(status_id);
        $("#change-participant-status-modal").modal("show");
        $("#cover-spin").hide();
    });

    $("body").on("click", "#edit_participant_offcanvas", function () {
        console.log("inside #edit_participant_offcanvas");
        $("#cover-spin").show();
        var id = $(this).data("id");
        var table = $(this).data("table");
        console.log("id", id);
        console.log("table", table);

        const input = document.querySelector("#edit_date_of_birth");
        const dobPicker = input._flatpickr;
        // const dobPicker = flatpickr("#edit_date_of_birth", {
        //     dateFormat: "Y-m-d",
        //     allowInput: true,
        // });

        $.ajax({
            url: "/ypi/customer/guardian/get/" + id,
            method: "GET",
            async: true,
            success: function (response) {
                const hasFoodAllergy = response.op.food_allergy == 1;
                const hasHealthIssues = response.op.health_issues == 1;
                const dob = response.op.date_of_birth;

                console.log("dob", dob);

                if (dob) {
                    dobPicker.setDate(dob, true, "Y-m-d");
                } else {
                    dobPicker.clear();
                }

                $("#edit_participant_type").val(
                    response.op.participant_type_id
                );
                $("#edit_gender").val(response.op.gender_id);
                $("#edit_participant_id").val(response.op.id);
                $("#edit_participant_name").val(response.op.full_name);
                $("#edit_date_of_birth").val(response.op.date_of_birth_dmy);
                $("#edit_nationality").val(response.op.nationality_id);
                $("#edit_school_name").val(response.op.school_name);
                $("#edit_jacket_size").val(response.op.jacket_size_id);
                $("#edit_shoe_size").val(response.op.shoe_size_id);
                $("#edit_pants_size").val(response.op.pants_size_id);
                $("#edit_jersey_size").val(response.op.jersey_size_id);
                $("#edit_qid").val(response.op.qid);

                $("#edit_has_food_allergy").val(response.op.food_allergy);
                $("#edit_has_food_allergy").prop("checked", hasFoodAllergy);
                $("#edit_food_allergy_details_wrap").toggleClass(
                    "d-none",
                    !hasFoodAllergy
                );
                $("#edit_food_allergy_details").val(
                    response.op.food_allergy_details
                );

                $("#edit_has_health_issues").val(response.op.health_issues);
                $("#edit_has_health_issues").prop("checked", hasHealthIssues);
                $("#edit_health_issues_details").val(
                    response.op.health_issues_details
                );
                $("#edit_health_issues_details_wrap").toggleClass(
                    "d-none",
                    !hasHealthIssues
                );

                $("#edit_participant_table").val(table);

                // ✅ preload docs via EventPond
                console.log("Window EventPondEdit:", window.EventPondEdit);
                if (window.EventPondEdit) {
                    console.log(
                        "Preloading docs into EventPondEdit:",
                        response.event_docs
                    );
                    window.EventPondEdit.preload(response.event_docs);
                }

                $("#change-participant-status-modal").modal("show");
                $("#cover-spin").hide();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(xhr.status);
                console.log(thrownError);
                $("#cover-spin").hide();
            },
        });
    });

    document
        .getElementById("has_food_allergy")
        .addEventListener("change", function () {
            document
                .getElementById("food_allergy_details_wrap")
                .classList.toggle("d-none", !this.checked);
        });

    document
        .getElementById("edit_has_food_allergy")
        .addEventListener("change", function () {
            document
                .getElementById("edit_food_allergy_details_wrap")
                .classList.toggle("d-none", !this.checked);
        });

    document
        .getElementById("has_health_issues")
        .addEventListener("change", function () {
            document
                .getElementById("health_issues_details_wrap")
                .classList.toggle("d-none", !this.checked);
        });

    document
        .getElementById("edit_has_health_issues")
        .addEventListener("change", function () {
            document
                .getElementById("edit_health_issues_details_wrap")
                .classList.toggle("d-none", !this.checked);
        });

    // delete participant
    $("body").on("click", "#deleteParticipant", function (e) {
        var id = $(this).data("id");
        var tableID = $(this).data("table");
        e.preventDefault();
        // console.log('in deleteBooking '+id);
        // console.log('in deleteBooking '+tableID);
        var link = $(this).attr("href");
        Swal.fire({
            title: "Are you sure?",
            text: "Delete This Data?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
        }).then((result) => {
            if (result.isConfirmed) {
                // console.log('inside confirmed')
                $.ajax({
                    url: "/ypi/admin/participant/delete/" + id,
                    type: "DELETE",
                    headers: {
                        // "X-CSRF-TOKEN": $('input[name="_token"]').attr("value"),
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                            "content"
                        ),
                    },
                    dataType: "json",
                    success: function (result) {
                        // alert(result)
                        if (!result["error"]) {
                            toastr.success(result["message"]);
                            $("#" + tableID).bootstrapTable("refresh");
                            // Swal.fire(
                            //     'Deleted!',
                            //     'Your file has been deleted.',
                            //     'success'
                            //   )
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        console.log(xhr.status);
                        console.log(thrownError);
                    },
                });
            }
        });
    });
});

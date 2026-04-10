$(document).ready(function () {
    // console.log("all tasksJS file");

    // ************************************************** task venues

    $("body").on("click", "#offcanvas-add-participant", function () {
        console.log("inside #offcanvas-add-participant");
        // $("#add_edit_form").get(0).reset()
        // console.log(window.choices.removeActiveItems())
        $("#cover-spin").show();
        $("#offcanvas-add-participant-modal").offcanvas("show");
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
            url: "/ypi/admin/participant/status/get/" + id,
            method: "GET",
            async: true,
            success: function (response) {
                console.log("response", response);
                $("#status_id").val(response.op.status_id);

                $("#edit_participant_table").val(table);

                console.log("Window EventPondEdit:", window.EventPondEdit);
                if (window.EventPondEdit) {
                    console.log(
                        "Preloading docs into EventPondEdit:",
                        response.event_docs
                    );
                    window.EventPondEdit.preload(response.event_docs);
                }

                $("#offcanvas-edit-participant-modal").offcanvas("show");
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

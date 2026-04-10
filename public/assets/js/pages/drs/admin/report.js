$(document).ready(function () {
    console.log('report.js loaded');

    // delete report
    $("body").on("click", "#deleteReport", function (e) {
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
                    url: "/drs/shared/delete/" + id,
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

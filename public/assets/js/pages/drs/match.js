$(document).ready(function () {
    // console.log("all tasksJS file");

    // ************************************************** task matches

    $("body").on("click", "#editMatch", function () {
        console.log('inside editMatch')
        var id = $(this).data("id");
        var table = $(this).data("table");
        const fp = $("#edit_matches_date")[0]._flatpickr;
        // console.log('edit matches in match.js');
        // console.log('id: '+id);
        // console.log('table: '+table);
        // var target = document.getElementById("edit_matches_modal");
        // var spinner = new Spinner().spin(target);
        // $("#edit_matches_modal").modal("show");
        $.ajax({
            url: "/drs/setting/match/get/" + id,
            type: "get",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').attr("value"), // Replace with your method of getting the CSRF token
            },
            dataType: "json",
            success: function (response) {
                console.log(response)
                console.log('response.match_number: '+response.match_number);

                $("#edit_matches_id").val(response.match.id);
                $("#edit_matches_venue_id").val(response.match.venue.id);
                $("#edit_matches_event_id").val(response.match.event.id);
                $("#edit_matches_event_name").val(response.match.event.name);
                $("#edit_matches_number").val(response.match.match_number);
                $("#edit_matches_pma1").val(response.match.pma1);
                $("#edit_matches_pma2").val(response.match.pma2);
                $("#edit_matches_stage").val(response.match.stage);
                $("#edit_matches_date").val(response.match_date);
                $("#edit_matches_table").val(table);
                if (fp) {
                    fp.setDate(response.match_date, true);
                }
                // $("#edit_matches_modal").modal("show");
            },
        }).done(function () {
            $("#edit_matches_modal").modal("show");
        });
    });
});

$("body").on("click", "#deleteMatch", function (e) {
    var id = $(this).data("id");
    var tableID = $(this).data("table");
    e.preventDefault();
    // alert('in deleteStatus '+tableID);
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
            $.ajax({
                url: "/drs/setting/match/delete/" + id,
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('input[name="_token"]').attr("value"), // Replace with your method of getting the CSRF token
                },
                dataType: "json",
                success: function (result) {
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

("use strict");
function queryParams(p) {
    return {
        page: p.offset / p.limit + 1,
        limit: p.limit,
        sort: p.sort,
        order: p.order,
        offset: p.offset,
        search: p.search,
    };
}

window.icons = {
    refresh: "bx-refresh",
    toggleOn: "bx-toggle-right",
    toggleOff: "bx-toggle-left",
    fullscreen: "bx-fullscreen",
    columns: "bx-list-ul",
    export_data: "bx-list-ul",
};

function loadingTemplate(message) {
    return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical" ></i>';
}



function actionsFormatter(value, row, index) {
    return [
        '<a href="javascript:void(0);" class="edit-matches" id="editMatch" data-id=' +
            row.id +
            " title=" +
            label_update +
            ' data-table="matches_table" class="card-link"><i class="bx bx-edit mx-1"></i></a>' +
            "<button title=" +
            label_delete +
            ' type="button" data-table="matches_table" class="btn delete" id="deleteMatch" data-id=' +
            row.id +
            ' data-type="status">' +
            '<i class="bx bx-trash text-danger mx-1"></i>' +
            "</button>",
    ];
}

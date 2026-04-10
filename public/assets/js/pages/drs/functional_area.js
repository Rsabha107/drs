$(document).ready(function () {

    $("body").on("click", "#editFunctionalArea", function () {
        var id = $(this).data("id");
        var table = $(this).data("table");
        $.ajax({
            url: "/drs/setting/functional_area/get/" + id,
            type: "get",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').attr("value"),
            },
            dataType: "json",
            success: function (response) {
                $("#edit_functional_areas_id").val(response.functional_area.id);
                $("#edit_functional_areas_fa_code").val(response.functional_area.fa_code);
                $("#edit_functional_areas_title").val(response.functional_area.title);
                $("#edit_functional_areas_focal_point_name").val(response.functional_area.focal_point_name);
                $("#edit_functional_areas_focal_point_email").val(response.functional_area.focal_point_email);
                $("#edit_functional_areas_table").val(table);
            },
        }).done(function () {
            $("#edit_functional_areas_modal").modal("show");
        });
    });
});

$("body").on("click", "#deleteFunctionalArea", function (e) {
    var id = $(this).data("id");
    var tableID = $(this).data("table");
    e.preventDefault();
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
                url: "/drs/setting/functional_area/delete/" + id,
                type: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": $('input[name="_token"]').attr("value"),
                },
                dataType: "json",
                success: function (result) {
                    if (!result["error"]) {
                        toastr.success(result["message"]);
                        $("#" + tableID).bootstrapTable("refresh");
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
        '<a href="javascript:void(0);" id="editFunctionalArea" data-id=' +
            row.id +
            " title=" +
            label_update +
            ' data-table="functional_areas_table" class="card-link"><i class="bx bx-edit mx-1"></i></a>' +
            "<button title=" +
            label_delete +
            ' type="button" data-table="functional_areas_table" class="btn delete" id="deleteFunctionalArea" data-id=' +
            row.id +
            ' data-type="status">' +
            '<i class="bx bx-trash text-danger mx-1"></i>' +
            "</button>",
    ];
}

"use strict";

$(document).ready(function () {
    
    // ================= Edit nationality =================
    $("body").on("click", "#editNationality", function () {
        var id = $(this).data("id");
        var table = $(this).data("table");

        $.ajax({
            url: "/drs/setting/nationality/get/" + id,
            type: "GET",
            headers: { "X-CSRF-TOKEN": $('input[name="_token"]').val() },
            dataType: "json",
            success: function (response) {
                $("#edit_nationalities_id").val(response.op.id);
                $("#edit_nationalities_title").val(response.op.title);
                $("#edit_nationalities_num_code").val(response.op.num_code);
                $("#edit_nationalities_alpha_2_code").val(response.op.alpha_2_code);
                $("#edit_nationalities_alpha3_code").val(response.op.alpha_3_code);
                $("#edit_nationalities_en_short_name").val(response.op.en_short_name);
                $("#edit_nationalities_table").val(table);

                $("#edit_nationalities_modal").modal("show");
            },
        });
    });

    // ================= Submit Edit Form =================
    $("#edit_form_submit_event").on("submit", function (e) {
        e.preventDefault();
        var form = $(this);

        $.ajax({
            url: form.attr("action"),
            type: "POST",
            data: form.serialize(),
            dataType: "json",
            headers: { "X-CSRF-TOKEN": $('input[name="_token"]').val() },
            success: function (response) {
                if (!response.error) {
                    toastr.success(response.message);
                    $("#edit_nationalities_modal").modal("hide");
                    $("#nationalities_table").bootstrapTable("refresh");
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr) {
                console.log(xhr.responseText);
                toastr.error("Something went wrong while updating.");
            }
        });
    });

    // ================= Delete nationality =================
    $("body").on("click", "#deleteNationality", function (e) {
        e.preventDefault();
        var id = $(this).data("id");
        var tableID = $(this).data("table");

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
                    url: "/drs/setting/nationality/delete/" + id,
                    type: "DELETE",
                    headers: { "X-CSRF-TOKEN": $('input[name="_token"]').val() },
                    dataType: "json",
                    success: function (result) {
                        if (!result.error) {
                            toastr.success(result.message);
                            $("#" + tableID).bootstrapTable("refresh");
                        } else {
                            toastr.error(result.message);
                        }
                    },
                    error: function (xhr) {
                        console.log(xhr.responseText);
                        toastr.error("Something went wrong while deleting.");
                    },
                });
            }
        });
    });

});

// ================= Bootstrap Table Query Params =================
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

// ================= Icons =================
window.icons = {
    refresh: "bx-refresh",
    toggleOn: "bx-toggle-right",
    toggleOff: "bx-toggle-left",
    fullscreen: "bx-fullscreen",
    columns: "bx-list-ul",
    export_data: "bx-list-ul",
};

// ================= Loading Template =================
function loadingTemplate(message) {
    return '<i class="bx bx-loader-alt bx-spin bx-flip-vertical"></i>';
}

// ================= Actions Formatter =================
function actionsFormatter(value, row, index) {
    return [
        '<a href="javascript:void(0);" id="editNationality" data-id="' + row.id + '" data-table="nationalities_table" title="' + label_update + '"><i class="bx bx-edit mx-1"></i></a>',
        '<button type="button" id="deleteNationality" data-id="' + row.id + '" data-table="nationalities_table" title="' + label_delete + '" class="btn"><i class="bx bx-trash text-danger mx-1"></i></button>'
    ].join("");
}

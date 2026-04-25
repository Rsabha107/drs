$(document).ready(function () {
    console.log("sheet_type.js file");

    // Initialize flatpickr for datetimepicker inputs
    var datetimepickers = document.querySelectorAll('.datetimepicker');
    datetimepickers.forEach(function (element) {
        var options = element.getAttribute('data-options');
        if (options) {
            try {
                options = JSON.parse(options);
            } catch (e) {
                options = {};
            }
        } else {
            options = {};
        }
        flatpickr(element, options);
    });

    $(".js-select-event-assign-multiple-venue_id").select2({
        closeOnSelect: false,
        placeholder: "Select ...",
    });

    $(".js-select-event-assign-multiple-edit_venue_id").select2({
        closeOnSelect: false,
        placeholder: "Select ...",
    });

    // Reinitialize flatpickr when modals are opened
    $('body').on('shown.bs.modal', '#create_sheet_type_modal', function() {
        var dateInput = document.getElementById('eventStartDate');
        if (dateInput && dateInput._flatpickr) {
            dateInput._flatpickr.destroy();
        }
        var options = dateInput.getAttribute('data-options');
        if (options) {
            options = JSON.parse(options);
        }
        flatpickr(dateInput, options || {});
    });

    $('body').on('shown.bs.modal', '#edit_sheet_type_modal', function() {
        var dateInput = document.getElementById('edit_sheet_type_start_date');
        if (dateInput && dateInput._flatpickr) {
            dateInput._flatpickr.destroy();
        }
        var options = dateInput.getAttribute('data-options');
        if (options) {
            options = JSON.parse(options);
        }
        flatpickr(dateInput, options || {});
    });

    $("body").on(
        "click",
        "[data-bs-target='#create_sheet_type_modal']",
        function () {
            window.EventPondCreate?.clearUI();
            window.EventPondCreate?.resetDeletes();
        }
    );

    $("body").on("click", "#editSheetTypes", function () {
        console.log("edit sheet type clicked");
        const id = $(this).data("id");
        const table = $(this).data("table");

        $.ajax({
            url: "/drs/setting/sheet-type/get/" + id,
            type: "GET",
            headers: { "X-CSRF-TOKEN": $('input[name="_token"]').val() },
            dataType: "json",
            success: function (response) {
                console.log("AJAX response:", response);

                $("#edit_sheet_type_id").val(response.op.id);
                $("#edit_sheet_type_name").val(response.op.name);
                $("#edit_sheet_type_start_date").val(response.op.event_start_date);
                $("#edit_sheet_type_description").val(response.op.description);
                $("#edit_venue_id").val(response.op.venue_id).trigger("change");
                $("#edit_event_id").val(response.op.event_id).trigger("change");
                $("#editActiveFlag").val(response.op.active_flag);
                $("#edit_sheet_type_table").val(table);

            },
        }).done(function () {
            $("#edit_sheet_type_modal").modal("show");
        });
    });
});

$("body").on("click", "#deleteSheetType", function (e) {
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
                url: "/drs/setting/sheet-type/delete/" + id,
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

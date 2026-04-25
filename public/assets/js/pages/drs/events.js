$(document).ready(function () {
    console.log("events.js file");

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
    $('body').on('shown.bs.modal', '#create_event_modal', function() {
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

    $('body').on('shown.bs.modal', '#edit_event_modal', function() {
        var dateInput = document.getElementById('edit_event_start_date');
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
        "[data-bs-target='#create_event_modal']",
        function () {
            window.EventPondCreate?.clearUI();
            window.EventPondCreate?.resetDeletes();
        }
    );

    $("body").on("click", "#editEvents", function () {
        console.log("edit event clicked");
        const id = $(this).data("id");
        const table = $(this).data("table");

        // clear pond before loading (safe)
        if (window.EventPondEdit) window.EventPondEdit.clearUI();

        $.ajax({
            url: "/drs/setting/event/get/" + id,
            type: "GET",
            headers: { "X-CSRF-TOKEN": $('input[name="_token"]').val() },
            dataType: "json",
            success: function (response) {
                console.log("AJAX response:", response);
                const eventVenues = (response.venues || []).map((v) => v.id);

                if (window.EventPondEdit?.resetDeletes)
                    window.EventPondEdit.resetDeletes();

                $("#edit_event_id").val(response.op.id);
                $("#edit_event_name").val(response.op.name);
                $("#edit_event_start_date").val(response.op.event_start_date);
                
                // Format and set event_start_date for flatpickr
                if (response.op.event_start_date) {
                    var dateInput = document.getElementById('edit_event_start_date');
                    if (dateInput._flatpickr) {
                        dateInput._flatpickr.setDate(response.op.event_start_date);
                    } else {
                        $("#edit_event_start_date").val(response.op.event_start_date);
                    }
                }
                
                $("#edit_venue_id").val(eventVenues).trigger("change");
                $("#editActiveFlag").val(response.op.active_flag);
                $("#edit_event_table").val(table);

                // ✅ preload docs via EventPond
                console.log("Window EventPondEdit:", window.EventPondEdit);
                if (window.EventPondEdit) {
                    console.log("Preloading docs into EventPondEdit:", response.event_docs);
                    window.EventPondEdit.preload(response.event_docs);
                }
            },
        }).done(function () {
            $("#edit_event_modal").modal("show");
        });
    });
});

$("body").on("click", "#deleteEvent", function (e) {
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
                url: "/drs/setting/event/delete/" + id,
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

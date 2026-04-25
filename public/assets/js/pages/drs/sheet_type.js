$(document).ready(function () {
    console.log("sheet_type.js file");

    // Function to load matches based on event_id and venue_id
    function loadMatches(eventId, venueId, selectElementId) {
        const selectElement = $('#' + selectElementId);
        
        if (!eventId || !venueId) {
            selectElement.html('<option value="">Select event and venue first</option>').prop('disabled', true);
            return;
        }

        $.ajax({
            url: '/drs/setting/sheet-type/get-matches',
            type: 'GET',
            data: {
                event_id: eventId,
                venue_id: venueId
            },
            dataType: 'json',
            headers: { "X-CSRF-TOKEN": $('input[name="_token"]').val() },
            success: function (response) {
                let html = '<option value="">Select match</option>';
                if (response.matches && response.matches.length > 0) {
                    response.matches.forEach(function(match) {
                        html += '<option value="' + match.id + '">Match ' + match.match_number + '</option>';
                    });
                    selectElement.prop('disabled', false);
                } else {
                    html += '<option value="" disabled>No matches found</option>';
                    selectElement.prop('disabled', false);
                }
                selectElement.html(html);
            },
            error: function() {
                selectElement.html('<option value="">Error loading matches</option>').prop('disabled', true);
            }
        });
    }

    // Create modal - Event change handler
    $('#create_event_id').on('change', function() {
        const eventId = $(this).val();
        const venueId = $('#create_venue_id').val();
        if (eventId) {
            loadMatches(eventId, venueId, 'create_match_id');
        }
    });

    // Create modal - Venue change handler
    $('#create_venue_id').on('change', function() {
        const eventId = $('#create_event_id').val();
        const venueId = $(this).val();
        if (eventId && venueId) {
            loadMatches(eventId, venueId, 'create_match_id');
        }
    });

    // Edit modal - Event change handler
    $('#edit_event_id').on('change', function() {
        const eventId = $(this).val();
        const venueId = $('#edit_venue_id').val();
        if (eventId) {
            loadMatches(eventId, venueId, 'edit_match_id');
        }
    });

    // Edit modal - Venue change handler
    $('#edit_venue_id').on('change', function() {
        const eventId = $('#edit_event_id').val();
        const venueId = $(this).val();
        if (eventId && venueId) {
            loadMatches(eventId, venueId, 'edit_match_id');
        }
    });

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
                $("#edit_sheet_type_code").val(response.op.code);
                $("#edit_sheet_type_name").val(response.op.title);
                $("#edit_sheet_type_description").val(response.op.description);
                $("#edit_event_id").val(response.op.event_id).trigger("change");
                
                // Delay venue selection to allow event to load first
                setTimeout(function() {
                    $("#edit_venue_id").val(response.op.venue_id).trigger("change");
                    
                    // Delay match selection after matches are loaded
                    setTimeout(function() {
                        if (response.op.match_id) {
                            $("#edit_match_id").val(response.op.match_id);
                        }
                    }, 500);
                }, 300);
                
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

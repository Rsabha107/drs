$(document).ready(function () {
    console.log("events.js file");

    $(".js-select-event-assign-multiple-venue_id").select2({
        closeOnSelect: false,
        placeholder: "Select ...",
    });

    $(".js-select-event-assign-multiple-edit_venue_id").select2({
        closeOnSelect: false,
        placeholder: "Select ...",
    });

    $("body").on("click", "#editEvents", function () {
        console.log("inside edit_events");
        console.log("edit_image count:", $("#edit_image").length);
        console.log($("#edit_image").data("dropify"));
        var id = $(this).data("id");
        var table = $(this).data("table");
        // console.log('edit venues in venues.js');
        // console.log('id: '+id);
        // console.log('table: '+table);
        // var target = document.getElementById("edit_venues_modal");
        // var spinner = new Spinner().spin(target);
        // $("#edit_event_table").modal("show");

        $.ajax({
            url: "/ypi/setting/event/get/" + id,
            type: "get",
            headers: {
                "X-CSRF-TOKEN": $('input[name="_token"]').attr("value"), // Replace with your method of getting the CSRF token
            },
            dataType: "json",
            success: function (response) {
                console.log(response);
                var eventVenues = response.venues.map((venue) => venue.id);
                console.log(eventVenues);

                $("#edit_event_id").val(response.op.id);
                $("#edit_event_name").val(response.op.name);
                $("#edit_venue_id").val(eventVenues);
                $("#edit_venue_id").trigger("change");
                $("#editActiveFlag").val(response.op.active_flag);
                $("#edit_event_table").val(table);
                console.log("event_docs:", response.event_docs);
    // 🔥 let events_upload.js handle FilePond safely
    if (window.loadParticipantIntoModal) {
        window.loadParticipantIntoModal(id);
    }
                // (response.event_docs || []).forEach(function (doc) {
                //     pond.addFile(doc.download_url, {
                //         type: "local",
                //         file: { name: doc.original_name, size: doc.size },
                //         metadata: {
                //             docId: doc.id,
                //             deleteUrl: doc.delete_url,
                //         },
                //     });
                // });
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
                url: "/ypi/setting/event/delete/" + id,
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

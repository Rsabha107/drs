$(document).ready(function () {

    console.log('Pick JS loaded');
    $('#event_id').on('change', function () {
        console.log('Event changed');

        const eventId = $(this).val();
        const $venue = $('#venue_id');

        console.log('Event changed to:', eventId);

        $venue.prop('disabled', true)
              .html('<option value="">Loading venues…</option>');

        if (!eventId) {
            console.log('No event selected, clearing venues');
            $venue.html('<option value="">Select Venue…</option>')
                  .prop('disabled', true);
            return;
        }

        console.log('Fetching venues for event ID:', eventId);

        $url = `/drs/events/${eventId}/venues`;
        console.log('AJAX URL:', $url);

        $.ajax({
            url: `/drs/events/${eventId}/venues`,
            type: 'GET',
            async: true,
            success: function (venues) {
                console.log('Venues loaded:', venues);
                let options = '<option value="">Select Venue…</option>';

                venues.forEach(v => {
                    options += `<option value="${v.id}">${v.title}</option>`;
                });

                $venue.html(options).prop('disabled', false);
            },
            error: function () {
                $venue.html('<option value="">Error loading venues</option>')
                      .prop('disabled', true);
            }
        });
    });

});

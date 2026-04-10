<script>
console.log('script of event')
$(document).ready(function() {
    console.log('event-js.blade.php');



    $('#dataList').DataTable({
        // processing: true,
        serverSide: true,
        // ordering: true,
        // searching: true,
        ajax: {
            method: "get",
            {{-- url: "{{ route('main.event.get.list') }}",  activate this if you want to use it.  also activate the route in web --}}
        },
        columns: [
            // {
            //     data: 'event_id',
            //     name: 'event_id',
            // },
            {
                data: 'event_name',
                name: 'event_name',
            },
            {
                data: 'status',
                name: 'status',
            },
            {
                data: 'modify',
                name: 'modify',
            }
        ],
        // dom: 'Bfrtip',
        // buttons: [{
        //     extend: 'collection',
        //     text: 'Export',
        //     buttons: [
        //         {
        //         extend: 'copyHtml5',
        //         exportOptions: {
        //             columns: [ 0, ':visible' ]
        //         }
        //     },
        //     {
        //         extend: 'excelHtml5',
        //         exportOptions: {
        //             columns: ':visible'
        //         }
        //     },
        //     {
        //         extend: 'pdfHtml5',
        //         exportOptions: {
        //             columns: [ 0, 1, 2, 5 ]
        //         }
        //     },
        //     'colvis'
        //     ],

        // }]
    });
});
</script>

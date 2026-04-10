<script>
console.log('script of report')
$(document).ready(function() {
    console.log('datalist report.blade.php');
    $('#dataList').DataTable({
        processing: true,
        serverSide: true,
        ordering: true,
        searching: true,
        ajax: {
            method: "get",
            url: "{{ route('main.get.order') }}",
        },
        columns: [
            // {
            //     data: 'id',
            //     name: 'id',
            // },
            {
                data: 'order_id',
                name: 'order_id',
            },
            {
                data: 'status',
                name: 'status',
            },
            {
                data: 'project_name',
                name: 'project_name',
            },
            {
                data: 'item',
                name: 'item',
            },
            {
                data: 'modify',
                name: 'modify',
            },
        ],
        dom: 'Bfrtip',
        buttons: [{
            extend: 'collection',
            text: 'Export',
            buttons: [
                {
                extend: 'copyHtml5',
                exportOptions: {
                    columns: [ 0, ':visible' ]
                }
            },
            {
                extend: 'excelHtml5',
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                exportOptions: {
                    columns: [ 0, 1, 2, 5 ]
                }
            },
            'colvis'
            ],

        }]
    });
});
</script>

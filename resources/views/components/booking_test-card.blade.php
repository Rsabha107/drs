<!-- meetings -->

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            {{$slot}}
            @if (is_countable($bookings) && count($bookings) > 0)
            <input type="hidden" id="data_type" value="booking">
            <div class="mx-2 mb-2">
                <table class="table data-table table-hover" style="width:100%">
                    <thead class="table-light">
                        <tr>
                            <th>No</th>
                            <th>Reference</th>
                            <th>receiver</th>
                            <th>booking_date</th>
                            <th>venue</th>
                            <th width="105px">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            @else
            <?php
            $type = 'Bookings'; ?>
            <x-empty-state-card :type="$type" />
            @endif
        </div>
    </div>
</div>

<script type="text/javascript">
        $(function() {
            gb_DataTable = $(".data-table").DataTable({
                autoWidth: true,
                order: [0, "ASC"],
                pagingType: 'simple_numbers',
                processing: true,
                serverSide: true,
                // searchDelay: 2000,
                paging: true,
                scrollX: true,
                ajax: "{{ route('mds.booking.test') }}",
                // iDisplayLength: "25",
                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                    {
                        text: 'Reload',
                        action: function(e, dt, node, config) {
                            dt.ajax.reload();
                        }
                    },
                    'excel', 'pdf', 'colvis',
                ],
                // buttons: [
                //     'pageLength',
                //     {
                //         extend: 'copy',
                //         className: 'btn btn-primary btn-sm'
                //     },
                //     {
                //         extend: 'excel',
                //         className: 'btn btn-primary btn-sm'
                //     },
                //     {
                //         text: 'Reload',
                //         className: 'btn btn-primary btn-sm',
                //         action: function(e, dt, node, config) {
                //             dt.ajax.reload();
                //         }
                //     },
                //     {
                //         extend: 'colvis',
                //         className: 'btn btn-primary btn-sm'
                //     },
                // ],
                layout: {
                    topStart: {
                        buttons: [
                            'pageLength',
                            {
                                extend: 'csv',
                                split: ['pdf', 'excel', 'colvis', ]
                            },


                            {
                                text: 'Reload',
                                className: 'btn btn-primary btn-sm',
                                action: function(e, dt, node, config) {
                                    dt.ajax.reload();
                                }
                            },
                        ]
                    },
                    // topEnd: {
                    //     search: {
                    //         placeholder: 'Type search here'
                    //     }
                    // },
                    // bottomEnd: 'paging',
                },
                columns: [{
                        visible: false,
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'booking_ref_number',
                        name: 'booking_ref_number'
                    },
                    {
                        data: 'receiver_name',
                        name: 'receiver_name'
                    },
                    {
                        data: 'booking_date',
                        name: 'booking_date'
                    },
                    {
                        data: 'venue_id',
                        name: 'venue_id'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                lengthMenu: [5, 10, 25, 50, {
                    label: 'All',
                    value: -1
                }],
            });
        });
    </script>

<!-- meetings -->

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            {{$slot}}
            <input type="hidden" id="data_type" value="booking">
            <div class="mx-2 mb-2">
                <table id="bookings_table"
                    data-toggle="table"
                    data-classes="table table-hover  fs-9 mb-0 border-top border-translucent"
                    data-loading-template="loadingTemplate"
                    data-url="{{ route('mds.customer.booking.list')}}"
                    data-icons-prefix="bx"
                    data-icons="icons"
                    data-show-export="true"
                    data-export-types="['csv', 'txt', 'doc', 'excel', 'xlsx', 'pdf']"
                    data-show-columns-toggle-all="true"
                    data-show-refresh="true"
                    data-show-toggle="true"
                    data-total-field="total"
                    data-trim-on-search="false"
                    data-data-field="rows"
                    data-page-list="[5, 10, 20, 50, 100, 200]"
                    data-search="true"
                    data-searchable="true"
                    data-strict-search="true"
                    data-side-pagination="server"
                    data-show-columns="true"
                    data-pagination="true"
                    data-sort-name="id"
                    data-sort-order="desc"
                    data-mobile-responsive="true"
                    data-buttons-class="secondary"
                    data-query-params="queryParams">

                    <thead>
                        <tr>
                            <!-- <th data-checkbox="true"></th> -->
                            <!-- <th data-sortable="true" data-field="id" ><?= get_label('id', 'ID') ?></th> -->
                            <th data-sortable="true" data-field="delivery_status_id" ><?= get_label('status', 'Status') ?></th>
                            <th data-sortable="true" data-field="booking_ref_number" ><?= get_label('booking_ref_number', 'Refrence') ?></th>
                            <th data-sortable="false" data-field="venue_id" >Venue</th>
                            <th data-sortable="false" data-field="rsp_name" ><?= get_label('rsp_name', 'RSP Number') ?></th>
                            <th data-sortable="false" data-field="client_group" ><?= get_label('client_group', 'Client Group') ?></th>
                            <th data-sortable="false" data-field="booking_date" ><?= get_label('booking_date', 'Schedule Date') ?></th>
                            <th data-sortable="false" data-field="booking_time" ><?= get_label('booking_time', 'Schedule Time') ?></th>
                            <th data-sortable="false" data-field="booking_party_company_name" ><?= get_label('booking_party_company_name', 'Booking Party') ?></th>
                            <th data-sortable="false" data-field="booking_party_contact_name" ><?= get_label('booking_party_contact_name', 'Contact Name') ?></th>
                            <th data-sortable="false" data-field="booking_party_contact_email" data-visible="false" ><?= get_label('booking_party_contact_email', 'Contact Email') ?></th>
                            <th data-sortable="false" data-field="booking_party_contact_number" data-visible="false" ><?= get_label('booking_party_contact_number', 'Contact Number') ?></th>
                            <th data-sortable="false" data-field="arrival_date_time" ><?= get_label('arrival_date_time', 'Arrival') ?></th>
                            <th data-sortable="false" data-field="delivering_party_company_name" data-visible="false" ><?= get_label('delivering_party_company_name', 'Delivering Company Name') ?></th>
                            <th data-sortable="false" data-field="delivering_party_contact_number" data-visible="false" ><?= get_label('delivering_party_contact_number', 'Delivering Company Contact Number') ?></th>
                            <th data-sortable="false" data-field="delivering_party_contact_email" data-visible="false" ><?= get_label('delivering_party_contact_email', 'Delivering Company Email') ?></th>
                            <th data-sortable="false" data-field="driver_first_name" ><?= get_label('driver_first_name', 'Driver First Name') ?></th>
                            <th data-sortable="false" data-field="driver_last_name" ><?= get_label('driver_last_name', 'Driver Last Name') ?></th>
                            <th data-sortable="false" data-field="driver_national_id" ><?= get_label('driver_national_id', 'Driver QID/Passport') ?></th>
                            <th data-sortable="false" data-field="driver_phone_number" ><?= get_label('driver_phone_number', 'Driver Phone') ?></th>
                            <th data-sortable="false" data-field="vehicle_make" ><?= get_label('vehicle_make', 'Vehicle Make') ?></th>
                            <th data-sortable="false" data-field="license_plate" ><?= get_label('license_plate', 'License Plate') ?></th>
                            <th data-sortable="false" data-field="vehicle_type" ><?= get_label('vehicle_type', 'Vehicle Type') ?></th>
                            <th data-sortable="false" data-field="vehicle_make" ><?= get_label('vehicle_make', 'Vehicle Make') ?></th>
                            <th data-sortable="false" data-searchable="true" data-field="receiver_name" ><?= get_label('receiver_name', 'Receiver Name') ?></th>
                            <th data-sortable="false" data-field="receiver_contact_number" ><?= get_label('receiver_contact_number', 'Receiver Contact') ?></th>
                            <th data-sortable="false" data-field="loading_zone" ><?= get_label('loading_zone', 'Loading Zone') ?></th>
                            <th data-sortable="false" data-field="cargo" ><?= get_label('cargo', 'Cargo') ?></th>
                            <th data-sortable="false" data-field="delivery_type" ><?= get_label('delivery_type', 'Del/Col') ?></th>
                            <th data-sortable="false" data-field="booking" ><?= get_label('boooking', 'Booking') ?></th>
                            <th data-sortable="true" data-field="created_at" data-visible="false" ><?= get_label('created_at', 'Created at') ?></th>
                            <th data-sortable="true" data-field="updated_at" data-visible="false" ><?= get_label('updated_at', 'Updated at') ?></th>
                            <th data-field="action" class="text-end"><?= get_label('actions', 'Actions') ?></th>
                            <!-- <th data-formatter="actionsFormatter" class="text-end"><?= get_label('actions', 'Actions') ?></th> -->
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>
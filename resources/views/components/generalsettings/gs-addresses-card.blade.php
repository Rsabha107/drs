<div class="card mb-4">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            {{$slot}}

            <input type="hidden" id="data_type" value="tags">
            <table id="business_address_table" data-classes="table table-hover  fs-9 mb-0 border-top border-translucent"
                data-toggle="table" data-loading-template="loadingTemplate"
                data-url="{{route('general.settings.address.list')}}"
                data-icons-prefix="bx"
                data-icons="icons"
                data-show-export="true"
                data-export-types="['csv', 'txt', 'doc', 'excel', 'xlsx', 'pdf']"
                data-show-refresh="true" data-total-field="total"
                data-trim-on-search="false" data-data-field="rows"
                data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true"
                data-side-pagination="server" data-show-columns="true"
                data-pagination="true" data-sort-name="id" data-sort-order="desc"
                data-mobile-responsive="true" data-query-params="queryParams">
                <thead>
                    <tr>
                        <!-- <th data-sortable="true" data-field="id1"><?= get_label('id', 'ID') ?></th> -->
                        <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                        <th class="align-middle white-space-nowrap fs-9 text-body" data-sortable="true" data-field="address1"><?= get_label('address1', 'Address1') ?></th>
                        <th class="align-middle white-space-nowrap fs-9 text-body" data-sortable="true" data-field="address2"><?= get_label('address2', 'Address2') ?></th>
                        <th class="align-middle white-space-nowrap fs-9 text-body" data-sortable="true" data-field="city"><?= get_label('city', 'City') ?></th>
                        <th class="align-middle white-space-nowrap fs-9 text-body" data-sortable="true" data-field="state"><?= get_label('state', 'State') ?></th>
                        <th class="align-middle white-space-nowrap fs-9 text-body" data-sortable="true" data-field="zipcode"><?= get_label('zipcode', 'Zipcode') ?></th>
                        <th class="align-middle white-space-nowrap fs-9 text-body" data-sortable="true" data-field="country"><?= get_label('country', 'Country') ?></th>
                        <th class="align-middle white-space-nowrap fs-9 text-body" data-sortable="true" data-field="default_address"><?= get_label('default', 'Default') ?></th>
                        <th class="align-middle white-space-nowrap fs-9 text-body" data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                        <th class="align-middle white-space-nowrap fs-9 text-body" data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                        <th data-sortable="true" data-field="actions"><?= get_label('actions', 'Actions') ?></th>
                        <!-- <th data-formatter="actions2Formatter"><?= get_label('actions', 'Actions') ?></th> -->
                    </tr>
                </thead>
            </table>

        </div>
    </div>
</div>

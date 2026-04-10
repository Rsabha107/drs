<!-- meetings -->

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            {{$slot}}
            <input type="hidden" id="data_type" value="status">
            <div class="mx-2 mb-2">
                <table  id="audit_table"
                        data-toggle="table"
                        data-classes="table table-hover  fs-9 mb-0 border-top border-translucent"
                        data-loading-template="loadingTemplate"
                        data-url="{{ route('sec.audit.list')}}"
                        data-icons-prefix="bx"
                        data-icons="icons"
                        data-show-export="true"
                        data-show-columns-toggle-all="true"
                        data-show-toggle="true"
                        data-show-fullscreen="true"
                        data-show-refresh="true"
                        data-total-field="total"
                        data-trim-on-search="false"
                        data-data-field="rows"
                        data-page-size="10"
                        data-page-list="[5, 10, 20, 50, 100, 200]"
                        data-search="true"
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
                            <!-- <th data-sortable="true" data-field="id1"><?= get_label('id', 'ID') ?></th> -->
                            <th data-sortable="true" data-field="user_id"><?= get_label('user', 'User') ?></th>
                            <th data-sortable="true" data-field="event"><?= get_label('event', 'Event') ?></th>
                            <th data-sortable="true" data-field="auditable_type"><?= get_label('Type', 'Type') ?></th>
                            <th data-sortable="true" data-field="old_values"><?= get_label('old_value', 'Old Values') ?></th>
                            <th data-sortable="true" data-field="new_values"><?= get_label('new_value', 'New Values') ?></th>
                            <th data-sortable="true" data-field="ip_address"><?= get_label('ip_address', 'IP Address') ?></th>
                            <th data-sortable="true" data-field="created_at" data-visible="true"><?= get_label('created_at', 'Created at') ?></th>
                            <th data-sortable="true" data-field="updated_at" data-visible="true"><?= get_label('updated_at', 'Updated at') ?></th>
                            <!-- <th data-formatter="actionsFormatter"><?= get_label('actions', 'Actions') ?></th> -->
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

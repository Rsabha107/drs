<!-- meetings -->

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            {{$slot}}
            <input type="hidden" id="data_type" value="status">
            <div class="mx-2 mb-2">
                <table  id="sheet_type_table"
                        data-toggle="table"
                        data-classes="table table-hover  fs-9 mb-0 border-top border-translucent"
                        data-loading-template="loadingTemplate"
                        data-url="{{ route('drs.setting.sheet.type.list') }}"
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
                        data-sort-order="asc"
                        data-mobile-responsive="true"
                        data-buttons-class="secondary"
                        data-query-params="queryParams">
                    <thead>
                        <tr>
                            <!-- <th data-checkbox="true"></th> -->
                            <th data-sortable="true" data-field="id" data-visible="false"><?= get_label('id', 'ID') ?></th>
                            <th data-sortable="true" data-field="code"><?= get_label('code', 'Code') ?></th>
                            <th data-sortable="true" data-field="title"><?= get_label('title', 'Title') ?></th>
                            <th data-sortable="true" data-field="description"><?= get_label('description', 'Description') ?></th>
                            <th data-sortable="true" data-field="event"><?= get_label('event', 'Event') ?></th>
                            <th data-sortable="true" data-field="venue"><?= get_label('venue', 'Venue') ?></th>
                            <th data-sortable="true" data-field="match_number"><?= get_label('match_number', 'Match #') ?></th>
                            <th data-sortable="true" data-field="match_date"><?= get_label('match_date', 'Match Date') ?></th>
                            <th data-sortable="true" data-field="md_date"><?= get_label('md_date', 'MD-x Date') ?></th>
                            <th data-sortable="true" data-field="cuff_date_time"><?= get_label('cuff_date_time', 'Cuff Date Time') ?></th>
                            <th data-sortable="true" data-field="actions" class="text-end">Actions</th>
                            <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                            <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

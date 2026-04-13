<!-- meetings -->

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            {{$slot}}
            <input type="hidden" id="data_type" value="matches">
            <div class="mx-2 mb-2">
                <table id="matches_table"
                    data-toggle="table"
                    data-classes="table table-hover  fs-9 mb-0 border-top border-translucent"
                    data-loading-template="loadingTemplate"
                    data-url="{{ route('drs.setting.match.list')}}"
                    data-icons-prefix="bx"
                    data-icons="icons"
                    data-show-export="true"
                    data-show-columns-toggle-all="true"
                    data-show-refresh="true"
                    data-total-field="total"
                    data-show-toggle="true"
                    data-trim-on-search="false"
                    data-data-field="rows"
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
                            <!-- <th data-sortable="true" data-field="id" class="align-middle white-space-wrap fw-bold fs-9"><?= get_label('id', 'ID') ?></th> -->
                            <th data-sortable="true" data-field="event_name" ><?= get_label('event_name', 'Event') ?></th>
                            <th data-sortable="true" data-field="venue_name" ><?= get_label('venue_name', 'Venue') ?></th>
                            <th data-sortable="true" data-field="match_number" ><?= get_label('match_number', 'Match Number') ?></th>
                            <th data-sortable="true" data-field="pma1" ><?= get_label('pma1', 'PMA1') ?></th>
                            <th data-sortable="true" data-field="pma2" ><?= get_label('pma2', 'PMA2') ?></th>
                            <th data-sortable="true" data-field="stage" ><?= get_label('stage', 'Stage') ?></th>
                            <th data-sortable="true" data-field="match_date" ><?= get_label('match_date', 'Match Date') ?></th>
                            <th data-sortable="true" data-field="gates_opening" ><?= get_label('gates_opening', 'Gates Opening') ?></th>
                            <th data-sortable="true" data-field="kick_off" ><?= get_label('kick_off', 'Kick Off') ?></th>
                            <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                            <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                            <th data-formatter="actionsFormatter" class="text-end"><?= get_label('actions', 'Actions') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<!-- meetings -->

<div class="card mt-4">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            {{$slot}}
            @if (is_countable($workspaces) && count($workspaces) > 0)
            <input type="hidden" id="data_type" value="workspace">
            <div class="mx-2 mb-2">
                <table  id="workspace_table"
                        data-toggle="table"
                        data-classes="table table-hover  fs-9 mb-0 border-top border-translucent"
                        data-loading-template="loadingTemplate"
                        data-url="workspace/list"
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
                        <th data-checkbox="true"></th>
                        <th data-sortable="true" data-field="id"><?= get_label('id', 'ID') ?></th>
                        <th data-sortable="true" data-field="title"><?= get_label('title', 'Title') ?></th>
                        <th data-field="assigned_to" data-formatter="userFormatter"><?= get_label('employees', 'Employees') ?></th>
                        <th data-field="client_id" data-formatter="clientFormatter"><?= get_label('clients', 'Clients') ?></th>
                        <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                        <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                        <th data-formatter="actionsFormatter"><?= get_label('actions', 'Actions') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>
            @else
            <?php
            $type = 'Workspace'; ?>
            <x-empty-state-card :type="$type" />

            @endif
        </div>
    </div>
</div>

<div class="card mt-0">
    <div class="card-body">
        <div class="table-responsive text-nowrap">
            {{$slot}}

            <div class="mb-4 mt-2">
                <div class="row g-1 mx-2">
                    <div class="col-auto px-2 pt-4">
                        @if (Auth::user()->can('task.funds.show') === 'xxx')
                        <div>
                            <h5 class="text-dark"><span class="fas fa-file-invoice-dollar me-2"></span>Remaining budget: </h5>
                        </div>
                        @endif
                    </div>
                    <!-- {{logger('route request: '.Request::is('main/task/*/list'))}} -->
                    <!-- {{logger('route request: '.Request::routeIs('tracki.task.list'))}} -->
                    <div class=" row col-auto scrollbar overflow-hidden-y flex-grow-1">
                        @if (Request::routeIs('tracki.task.list'))
                        <!-- we dont want to show the project search list if we are on task list -->
                        @else
                        <div class="col-md-3">
                            <select class="form-select" id="tasks_project_filter" aria-label="Default select example">
                                <option value=""><?= get_label('select_project', 'Select project') ?></option>
                                @foreach ($projects as $proj)
                                <option value="{{$proj->id}}" @if(request()->has('project') && request()->project == $proj->id) selected @endif>{{$proj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <div class="col-md-3">
                            <select class="form-select" id="tasks_department_filter" aria-label="Default select example">
                                <option value=""><?= get_label('select_department', 'Select department') ?></option>
                                @foreach ($departments as $dept)
                                <option value="{{$dept->id}}">{{$dept->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="tasks_person_filter" aria-label="Default select example">
                                <option value=""><?= get_label('select_person', 'Select person') ?></option>
                                @foreach ($users as $user)
                                <option value="{{$user->id}}">{{$user->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" id="task_status_filter" aria-label="Default select example">
                                <option value=""><?= get_label('select_status', 'Select status') ?></option>
                                @foreach ($statuses as $status)
                                @php
                                $selected = (request()->has('status') && request()->status == $status->id) ? 'selected' : '';
                                @endphp
                                <option value="{{ $status->id }}" {{ $selected }}>{{ $status->title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @php
                    $proj_id = (Request::routeIs('tracki.task.list'))? $projects->id: null;
                    @endphp
                    @if (Request::routeIs('tracki.task.list'))
                    @if (Auth::user()->can('task.create'))
                    <div class="col-auto">
                        <!-- <button class="btn btn-link text-900 me-4 px-0"><span
                                        class="fa-solid fa-file-export fs--1 me-2"></span>Export</button> -->
                        <!-- <a href="{{ route('tracki.task.add', ['id'=>$proj_id, 'modal_yn'=>'Y']) }}" class="btn btn-phoenix-primary px-3 px-sm-5 me-2"> -->
                        <!-- <span class="fas fa-plus me-2"></span>Add tasks</a> -->
                        <a href="#" id="add_edit_task" data-action="store" data-source="list" data-type="add" data-table="task_table" data-id="0" data-projectId="{{$proj_id}}">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_task', 'Create task') ?>">
                                <i class="bx bx-plus"></i>
                            </button>
                        </a>

                    </div>
                    @endif
                    @endif
                </div>
            </div>

            <input type="hidden" id="data_type" value="alltasks">
            <div class="mx-2 mb-2">
                <table table-stripped id="task_table" data-toggle="table" data-classes="table table-hover fs-9 mb-0 border-top border-translucent" data-loading-template="loadingTemplate" data-url="{{ route('tracki.client.get')}}" data-icons-prefix="bx" data-icons="icons" data-show-export="true" data-show-refresh="true" data-show-columns-toggle-all="true" data-show-toggle="true" data-show-fullscreen="true" data-total-field="total" data-trim-on-search="false" data-data-field="rows" data-page-size="10" data-page-list="[5, 10, 20, 50, 100, 200]" data-search="true" data-side-pagination="server" data-show-columns="true" data-pagination="true" data-sort-name="id" data-sort-order="desc" data-mobile-responsive="true" data-buttons-class="secondary" data-query-params="queryParams">
                    <thead>
                        <tr>
                            <!-- <th data-checkbox="true"></th> -->
                            <th data-sortable="true" data-field="id1"><?= get_label('id', 'ID') ?></th>
                            <th data-field="attributes"></th>
                            <th class="text-wrap" data-sortable="true" data-field="name"><?= get_label('task', 'Task') ?></th>
                            <th data-sortable="true" data-field="client"><?= get_label('client', 'Client') ?></th>
                            <th data-sortable="true" data-field="company"><?= get_label('company', 'Company') ?></th>
                            <th data-sortable="false" data-field="phone"><?= get_label('phone_number', 'Phone number') ?></th>
                            <th data-sortable="false" data-field="assigned" data-formatter="TaskUserFormatter"><?= get_label('assigned', 'Assigned') ?></th>
                            <th data-sortable="true" data-field="created_at" data-visible="false"><?= get_label('created_at', 'Created at') ?></th>
                            <th data-sortable="true" data-field="updated_at" data-visible="false"><?= get_label('updated_at', 'Updated at') ?></th>
                            <th data-formatter="actions2Formatter"><?= get_label('actions', 'Actions') ?></th>
                        </tr>
                    </thead>
                </table>
            </div>

        </div>
    </div>
</div>

<script>
    var label_update = '<?= get_label('update', 'Update') ?>';
    var label_delete = '<?= get_label('delete', 'Delete') ?>';
    var label_not_assigned = '<?= get_label('not_assigned', 'Not assigned') ?>';
    var can_edit = <?= (Auth::user()->can('task.edit')) == '' ? '0' : '1' ?>;
    var can_delete = <?= (Auth::user()->can('task.delete')) == '' ? '0' : '1' ?>;
    var x_source = '{{$source}}';
</script>
<script src="{{asset('assets/js/pages/tasks.js')}}"></script>

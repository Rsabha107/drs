plus button called from jquery with data attributes.  change as desired

                        <a href="#" id="add_edit_task" data-action="store" data-source="list" data-type="add" data-table="task_table" data-id="0" data-projectId="{{$proj_id}}">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-original-title=" <?= get_label('create_task', 'Create task') ?>">
                                <i class="bx bx-plus"></i>
                            </button>
                        </a>

code.js is where the swal for sweetalert2 is

update and delete icons
                        @if (Auth::user()->can('project.edit'))
                        <a href="javascript:void(0);" class="edit-tag" id="add_edite_project" data-action="get" data-source="list" data-type="edit" data-table="none" data-id="{{$item->id}}" title="Edit" class="card-link"><i class="bx bx-edit mx-1"></i></a>
                        @endif
                        @if (Auth::user()->can('project.delete'))
                        <a href="{{ route('main.project.delete',['id' => $item->id])}}" class="delete" id="delete" data-id="" title="Delete" class="card-link"><i class="bx bx-trash text-danger mx-1"></i></a>
                        @endif
                        </div>

update delete dropdown menu
                        <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h"></span></button>
                         <div class="dropdown-menu dropdown-menu-end py-2">
                            <a class="dropdown-item" href="javascript:void(0);" id="add_edit_project" data-action="get" data-source="list" data-type="edit" data-table="none" data-id="{{$eventData->id}}" data-redirect="list">Edit</a>
                            <!-- <a class="dropdown-item" href="{{ route('main.project.edit', ['id'=>$eventData->id, 'source'=>'plist']) }}">Edit</a> -->
                            <a class="dropdown-item text-danger" href="#!">Delete</a>
                            <a class="dropdown-item" href="#!">Download</a>
                            <a class="dropdown-item" href="{{ route('gantt') }}" target="_blank">Gantt</a>
                        </div>
or
                        <button class="btn btn-sm dropdown-toggle dropdown-caret-none transition-none btn-reveal" type="button" data-bs-toggle="dropdown" data-boundary="window" aria-haspopup="true" aria-expanded="false" data-bs-reference="parent"><span class="fas fa-ellipsis-h"></span></button>
                        <div class="dropdown-menu dropdown-menu-end py-2">
                            <a class="dropdown-item" href="javascript:void(0);" id="add_edit_project" data-action="get" data-source="list" data-type="edit" data-table="none" data-id="{{$item->id}}" data-redirect="card">Edit</a>
                            <a class="dropdown-item text-danger" href="#!" id="delete" data-id="" title="Delete" class="card-link">Delete</a>
                        </div>
or
                        @if (Auth::user()->can('project.edit'))
                        <a href="javascript:void(0);" class="edit-tag" id="add_edit_project" data-action="get" data-source="list" data-type="edit" data-table="none" data-id="{{$item->id}}" data-redirect="card" title="Edit" class="card-link"><i class="bx bx-edit mx-1"></i></a>
                        @endif
                        @if (Auth::user()->can('project.delete'))
                        <a href="{{ route('main.project.delete',['id' => $item->id])}}" class="delete" id="delete" data-id="" title="Delete" class="card-link"><i class="bx bx-trash text-danger mx-1"></i></a>
                        @endif

redirct type

json
        return response()->json([
            'error' => false,
            'message' => 'Task ' . $task->name . ' Task updated successfully ',
        ]);
laravel
            return Redirect::route('main.task.list', $request->id)->with($notification);

$workspace = session()->get('workspace_id');

typical form
            <form class="needs-validation" novalidate="" form-submit-eventx" id="add_new_todo" action="{{ route('main.todo.store') }}" method="POST">


            $project = Event::findOrFail($id);
        $project = $project->paginate(request('limit'))->through(function ($) {
            // $taskData = $taskData
            //     ->paginate(request("limit"))
            //     ->through(
            //         fn ($task) =>
            return  [
                'id' => $task->id,
                'project_title' => $task->project->name,
                // 'project_name' => $task->project->name,
                'name' => $task->name,
                'department_name' => $task->department->name,
                'assigned_by' => $task->assigned_by?->name,
                'assigned_to' => $task->users,
                'status_name' => $task->status->title,
                'status_color' => $task->status->color,
                'start_date' => format_date($task->start_date,  'H:i:s'),
                'due_date' => format_date($task->due_date,  'H:i:s'),
                'budget_allocation' => $task->budget_allocation,
                'actual_budget_allocated' => $task->actual_budget_allocated,
                'event_id' => $task->event_id,
                'notes' => $task->notes,
                'files' => $task->files,
                'subtasks' => $task->subtask,
                'attributes' => (($task->notes->count()) ? '<button class="btn p-0 text-body-tertiary fs-10 me-2"><span class="fas fa-sticky-note me-1"></span>' . $task->notes->count() . '</button>' : "") .
                    (($task->files->count()) ? '<button class="btn p-0 text-body-tertiary fs-10 me-2"><span class="fas fa-paperclip me-1"></span>' . $task->files->count() . '</button>' : ""),
                // 'attributes' => '<div class="ms-3 text-secondary">'.(($task->files->count()) ? '<span class="fas fa-file-alt me-1"></span>':"").' '.(($task->notes->count()) ? '<span class="fas fa-clipboard me-1"></span>':"").'</div>',
                'status' => '<span class="badge badge-phoenix fs--2 badge-phoenix-' . $task->status->color . ' "><span class="badge-label" data-bs-toggle="modal" data-bs-target="#taskStatusModal" id="editTaskStatus" data-id="' . $task->id . '" data-table="task_table">' . $task->status->title . '</span><span class="ms-1" data-feather="x" style="height:12.8px;width:12.8px;"></span></span>',
                'description' => $task->description,
                // 'description' => '<button class="btn btn-secondary m-1" type="button" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top Popover">Top Popover</button>',
                'created_at' => format_date($task->created_at,  'H:i:s'),
                'updated_at' => format_date($task->updated_at, 'H:i:s'),
            ];
        });



@extends('drs.layout.admin_template')

@section('main')


    <!-- ===============================================-->
    <!--    Main Content-->
    <!-- ===============================================-->

    <div class="mt-4">
        <div class="row g-4">
            <!-- this controls the size of the table  -->
            <div class="col-12 col-sm-12 order-1 order-xl-0">
                <div class="mb-9">

                    <div class="card shadow-none border border-300 mb-3" data-component-card="data-component-card">
                        <div class="card-header p-4 border-bottom border-300 bg-soft">
                            <div class="row g-3 justify-content-between align-items-center">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="col-12 col-md">
                                    <h4 class="text-900 mb-0" data-anchor="data-anchor">All Admin User List</h4>
                                </div>
                                <div class="col col-md-auto">
                                    <nav class="nav nav-underline justify-content-end doc-tab-nav align-items-center"
                                        role="tablist">
                                        <!-- <button class="btn btn-primary me-4" type="button" data-bs-toggle="modal"
                                                data-bs-target="#staticBackdrop" aria-haspopup="true" aria-expanded="false"
                                                data-bs-reference="parent"><span class="fas fa-plus me-2"></span>Add
                                                Deal</button> -->
                                        <a class="btn btn-sm btn-phoenix-primary preview-btn ms-2"
                                            href="{{ route('sec.adminuser.add') }}"><span
                                                class="fa-solid fa-add"></span>Add</a>
                                    </nav>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="collapse code-collapse" id="ajax-table-code">
                                <pre class="scrollbar" style="max-height:420px"><code class="language-html"></code></pre>
                            </div>
                            <div class="p-4 code-to-copy">
                                <div class="table-list">
                                    <div class="table-responsive scrollbar mb-3">
                                        <table class="table table-sm table-responsive fs--1 mb-0 overflow-hidden"
                                            id="dataList">
                                            <thead class="text-900 thead">
                                                <tr>
                                                    <th class="sort ps-3 pe-1 align-middle white-space-nowrap"
                                                        data-sort="orderId" style="min-width: 4.5rem;">SL</th>
                                                    <!-- <th class="sort ps-3 pe-1 align-middle white-space-nowrap" data-sort="orderId" style="min-width: 4.5rem;">Image</th> -->
                                                    <th class="sort ps-3 pe-1 align-middle white-space-nowrap"
                                                        data-sort="orderId" style="min-width: 4.5rem;">Name</th>
                                                    <th class="sort ps-3 pe-1 align-middle white-space-nowrap"
                                                        data-sort="orderId" style="min-width: 4.5rem;">Email</th>
                                                    <!-- <th class="sort ps-3 pe-1 align-middle white-space-nowrap" data-sort="orderId" style="min-width: 4.5rem;">User Type</th> -->
                                                    <th class="sort ps-3 pe-1 align-middle white-space-nowrap"
                                                        data-sort="orderId" style="min-width: 4.5rem;">Event</th>
                                                    <th class="sort ps-3 pe-1 align-middle white-space-nowrap"
                                                        data-sort="orderId" style="min-width: 4.5rem;">Venue</th>
                                                    <th class="sort ps-3 pe-1 align-middle white-space-nowrap"
                                                        data-sort="orderId" style="min-width: 4.5rem;">FA</th>
                                                    <th class="sort ps-3 pe-1 align-middle white-space-nowrap"
                                                        data-sort="orderId" style="min-width: 4.5rem;">Role</th>
                                                    <th class="no-sort" style="text-align:right"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="list">

                                                @foreach ($userdata as $key => $item)
                                                    <tr>
                                                        <td class="ps-3">{{ $item->id }}</td>
                                                        <td class="ps-3">{{ $item->name }}</td>
                                                        <td class="ps-3">{{ $item->email }}</td>
                                                        <!-- <td class="ps-3">{{ $item->usertype }}</td> -->
                                                        <td class="ps-3">
                                                            @foreach ($item->events as $event)
                                                                <span
                                                                    class='badge badge-pill bg-body-tertiary'>{{ $event->name }}</span>
                                                            @endforeach
                                                        </td>
                                                        <!-- <td class="ps-3">{{ $item->dept_name }}</td> -->
                                                        <td class="ps-3">
                                                            @foreach ($item->venues as $venue_item)
                                                                <span
                                                                    class='badge badge-pill bg-body-tertiary'>{{ $venue_item->title }}</span>
                                                            @endforeach
                                                        </td>
                                                        <td class="ps-3">
                                                            @foreach ($item->fa as $fa_item)
                                                                <span
                                                                    class='badge badge-pill bg-body-tertiary'>{{ $fa_item->title }}</span>
                                                            @endforeach
                                                        </td>
                                                        <td class="ps-3">
                                                            @foreach ($item->roles as $role)
                                                                <span
                                                                    class='badge badge-pill bg-danger'>{{ $role->name }}</span>
                                                            @endforeach
                                                        </td>


                                                        <td class="text-end">
                                                            <div class="actions">
                                                                <a href="{{ route('sec.adminuser.edit', $item->id) }}"
                                                                    class="btn btn-sm" data-toggle="modal"
                                                                    data-target='#editModal' data-route="perm"
                                                                    data-id="{{ $item->id }}">
                                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                                </a>
                                                                <a href="{{ route('sec.adminuser.delete', $item->id) }} "
                                                                    class="btn btn-sm delete" id="delete">
                                                                    <i class="fa-solid fa-trash"
                                                                        style="color: #f33061;"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ===============================================-->
    <!--    End of Main Content-->
    <!-- ===============================================-->
    <!-- add event modal 1-->
    <!-- <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#addEventModal">Launch demo modal 2</button> -->



@endsection

@push('script')
    <script type="text/javascript">
        $(document).ready(function() {

            $('#dataList').DataTable({
                "order": [
                    [0, "asc"]
                ],
                dom: 'Bfrtip',
                // buttons: [
                //     'copyHtml5',
                //     'excelHtml5',
                //     'csvHtml5',
                //     'pdf',
                //     // 'colvis'
                // ]
                buttons: [{
                    extend: 'collection',
                    text: 'Export',
                    buttons: [{
                            extend: 'copyHtml5',
                            exportOptions: {
                                columns: [0, ':visible']
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'csvHtml5',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'pdfHtml5',
                            exportOptions: {
                                columns: [0, 1, 2, 5]
                            }
                        },
                        'colvis'
                    ],
                }]
            });
        });
    </script>

    {{-- @include('vapp.partials.event-js') --}}
@endpush

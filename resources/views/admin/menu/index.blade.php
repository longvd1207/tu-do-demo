@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    @include('components.breadcrumb')
    <div class="row">
        <div class="col">

            <div class="h-100">
                <div class="row mb-1">
                    <div class="col-12">
                        <div class="card" id="partner-list">
                            @can('create_menu')
                                <div class="card-header border-0">
                                    <div class="d-flex align-items-center">
                                        <div class="flex-shrink-0">
                                            <a class="btn btn-primary" href="#" onclick="formCreate()">
                                                <i class="ri-add-line align-bottom me-1"></i>Thêm mới
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endcan

                            <div class="card-body border border-dashed border-end-0 border-start-0">
                                <form action="{{ route('menu.index') }}" method="post">
                                    @csrf
                                    <div class="row g-3 mb-0 align-items-center">
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <div class="input-group-text bg-primary text-white">Từ khoá</div>
                                                <input class="form-control" name="key_search"
                                                    placeholder="Tìm tên danh mục..." type="text"
                                                    value="{{ @$search['key_search'] }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-auto">
                                            <input name="comfirm_search" type="hidden" value="1" />
                                            <button class="btn btn-warning" type="submit"><i
                                                    class="ri-equalizer-fill me-1 align-bottom"></i> Tìm kiếm</button>
                                        </div>
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                            <div class="card-body col-4">
                                <div class="table-responsive  table-card mb-4">
                                    <table class="table table-sm table-bordered align-middle table-nowrap mb-0 "
                                        id="tasksTable">
                                        <thead class="table-light text-muted">
                                            <tr class="text-center">
                                                <th class="sort" style="width: 4%">STT</th>
                                                <th class="sort">Tên menu</th>
                                                <th style="width: 15%">Tháo tác</th>
                                            </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                            @isset($data)
                                                @foreach ($data as $key => $item)
                                                    <tr id="tr_{{ $item['id'] }}">
                                                        <td class="text-center">{{ $key + 1 }}</td>
                                                        <td id="name_{{ $item['id'] }}">{{ $item['name'] }}</td>
                                                        <td class="text-center">
                                                            <div class="flex-shrink-0 ms-4">
                                                                <ul class="list-inline tasks-list-menu mb-0">
                                                                    @can('update_menu')
                                                                        <li class="list-inline-item">
                                                                            <a class="btn btn-outline-primary btn-sm waves-effect waves-light"
                                                                                href="#"
                                                                                onclick="formEdit('{{ $item['id'] }}')"
                                                                                title="Sửa">
                                                                                <i class="ri-pencil-line "></i>
                                                                            </a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('delete_menu')
                                                                        <li class="list-inline-item">
                                                                            <a class="btn-sm btn btn-outline-danger waves-effect waves-light"
                                                                                href="#"
                                                                                onclick="deleteItems('{{ $item['id'] }}','tr_','{{ route('menu.delete', $item['id']) }}')"
                                                                                title="Xóa">
                                                                                <i class="ri-delete-bin-line"></i>
                                                                            </a>
                                                                        </li>
                                                                    @endcan
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endisset
                                        </tbody>
                                    </table>
                                    <!--end table-->
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <div class="pagination-wrap hstack gap-2">
                                        {{ !empty($data) ? $data->links() : '' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--End row -->
            </div> <!-- end .h-100-->

            <div class="overlay hidden lds-dual-ring" id="loader">
            </div>
            <div class="modal fade" id="exampleModalgrid" tabindex="-1" aria-labelledby="exampleModalgridLabel">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalgridLabel">Grid Modals</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="form_modal" action="javascript:void(0);" method="post">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-xxl-12">
                                        <div>
                                            <label for="firstName" class="form-label">Tên menu</label>
                                            <input type="text" name="menu_name" class="form-control" id="menu_name"
                                                placeholder="Tên menu" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="hstack gap-2 justify-content-end">
                                            <button type="button" class="btn btn-danger"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="button" onclick="submitForm()"
                                                class="btn btn-primary">Lưu</button>
                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('script')
        <script>
            @if (!empty(session('alert-success')))
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('alert-success') }}",
                    showConfirmButton: false,
                    timer: 1500,
                    showCloseButton: false
                });
            @endif

            @if (!empty(session('alert-error')))
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: "{{ session('alert-error') }}",
                    showConfirmButton: false,
                    timer: 1500,
                    showCloseButton: false
                });
            @endif

            let data = {
                html_alert: `<div class="bg-danger text-white text-center py-1">
                                                <span>Tên menu không được để trống</span>
                                            </div>`,
                html_input: `<input type="hidden" name="id" value="[id]">`


            }

            function submitForm() {
                if ($('#menu_name').val() != '') {
                    $('#form_modal').submit();
                } else {
                    $('.col-xxl-12 div .bg-danger').remove();
                    $('.col-xxl-12 div').append(data.html_alert);
                }
            }

            function formEdit(key) {
                var action = "{{ route('menu.update') }}";
                $('#exampleModalgridLabel').text('Chỉnh sửa menu');
                $('#form_modal').attr('action', action);
                $('#form_modal').append(data.html_input.replaceAll('[id]', key));
                var name = $('#name_' + key).text();
                $('#menu_name').val(name);
                // $('#menu_name').val('dfsdfasdfasdf');
                $('#exampleModalgrid').modal('show');

            }

            function formCreate() {
                var action = "{{ route('menu.create') }}";
                $('#exampleModalgridLabel').text('Thêm menu');
                $('#form_modal').attr('action', action);
                $('#exampleModalgrid').modal('show');
            }
        </script>
    @endsection

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
                            {{--                            @can('create_company')--}}
                            <div class="card-header border-0">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <a class="btn btn-primary" href="{{ route('cabinet.create') }}">
                                            <i class="ri-add-line align-bottom me-1"></i>Thêm mới
                                        </a>
                                    </div>
                                </div>
                            </div>
                            {{--@endcan--}}

                            <div class="card-body border border-dashed border-end-0 border-start-0">
                                <form action="{{ route('cabinet.index') }}" method="get">
                                    <div class="row g-3 mb-0 align-items-center">
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <div class="input-group-text bg-primary text-white">Từ khoá</div>
                                                <input class="form-control" name="key_search"
                                                       placeholder="Tìm theo tên tủ..." type="text"
                                                       value="{{ session('search.key_search') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-auto">
                                            <input name="confirm_search" type="hidden" value="1" />
                                            <button class="btn btn-warning" type="submit">
                                                <i class="ri-equalizer-fill me-1 align-bottom"></i> Tìm kiếm
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive table-card mb-4">
                                    <table class="table table-sm table-bordered align-middle table-nowrap mb-0 w-50"
                                           id="tasksTable">
                                        <thead class="table-light text-muted">
                                        <tr class="text-center">
                                            <th class="sort" style="width: 4%">STT</th>
                                            <th class="sort">Khu vực</th>
                                            <th class="sort">Tên tủ đồ</th>
                                            <th class="sort">Số lượng ngăn</th>
                                            <th class="sort">Sử dụng</th>
                                            <th style="width: 15%">Trạng thái</th>
                                            <th style="width: 15%">Thao tác</th>
                                        </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                        <tr>
                                            <td class="text-center">1</td>
                                            <td>Khu 1</td>
                                            <td>Tủ 1</td>
                                            <td>
                                                <li>Ngăn nhỏ: 10</li>
                                                <li>Ngăn vừa: 10</li>
                                                <li>Ngăn lớn: 10</li>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-soft-success text-uppercase">
                                                    <i class="ri-run-line"></i>3/30</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-soft-success"> Hoạt động </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="flex-shrink-0 ms-4">
                                                    <ul class="list-inline tasks-list-menu mb-0">
                                                        <li class="list-inline-item">
                                                            <a class="btn btn-outline-primary btn-sm waves-effect waves-light"
                                                               href="#" title="Sửa">
                                                                <i class="ri-pencil-line "></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item">
                                                            <button title="Delete"
                                                                    class="btn-sm btn btn-outline-danger waves-effect waves-light submitDeleteForm">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-center">2</td>
                                            <td>Khu 2</td>
                                            <td>Tủ 2</td>
                                            <td>
                                                <li>Ngăn nhỏ: 10</li>
                                                <li>Ngăn vừa: 10</li>
                                                <li>Ngăn lớn: 10</li>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-soft-success text-uppercase">
                                                    <i class="ri-run-line"></i>0/30</span>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge badge-soft-danger"> Bảo trì </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="flex-shrink-0 ms-4">
                                                    <ul class="list-inline tasks-list-menu mb-0">
                                                        <li class="list-inline-item">
                                                            <a class="btn btn-outline-primary btn-sm waves-effect waves-light"
                                                               href="#" title="Sửa">
                                                                <i class="ri-pencil-line "></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item">
                                                            <button title="Delete"
                                                                    class="btn-sm btn btn-outline-danger waves-effect waves-light submitDeleteForm">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>

                                        </tr>
                                        <tr>
                                            <td class="text-center">3</td>
                                            <td>Khu 3</td>
                                            <td>Tủ 3</td>
                                            <td>
                                                <li>Ngăn nhỏ: 10</li>
                                                <li>Ngăn vừa: 10</li>
                                                <li>Ngăn lớn: 10</li>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-soft-success text-uppercase">
                                                    <i class="ri-run-line"></i>13/30</span>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge badge-soft-success"> Hoạt động </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="flex-shrink-0 ms-4">
                                                    <ul class="list-inline tasks-list-menu mb-0">
                                                        <li class="list-inline-item">
                                                            <a class="btn btn-outline-primary btn-sm waves-effect waves-light"
                                                               href="#" title="Sửa">
                                                                <i class="ri-pencil-line "></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item">
                                                            <button title="Delete"
                                                                    class="btn-sm btn btn-outline-danger waves-effect waves-light submitDeleteForm">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-center">4</td>
                                            <td>Khu 4</td>
                                            <td>Tủ 4</td>
                                            <td>
                                                <li>Ngăn nhỏ: 10</li>
                                                <li>Ngăn vừa: 10</li>
                                                <li>Ngăn lớn: 10</li>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-soft-success text-uppercase">
                                                    <i class="ri-run-line"></i>20/30</span>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge badge-soft-success"> Hoạt động </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="flex-shrink-0 ms-4">
                                                    <ul class="list-inline tasks-list-menu mb-0">
                                                        <li class="list-inline-item">
                                                            <a class="btn btn-outline-primary btn-sm waves-effect waves-light"
                                                               href="#" title="Sửa">
                                                                <i class="ri-pencil-line "></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item">
                                                            <button title="Delete"
                                                                    class="btn-sm btn btn-outline-danger waves-effect waves-light submitDeleteForm">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="text-center">5</td>
                                            <td>Khu 5</td>
                                            <td>Tủ 5</td>
                                            <td>
                                                <li>Ngăn nhỏ: 10</li>
                                                <li>Ngăn vừa: 10</li>
                                                <li>Ngăn lớn: 10</li>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge badge-soft-success text-uppercase">
                                                    <i class="ri-run-line"></i>5/30</span>
                                            </td>

                                            <td class="text-center">
                                                <span class="badge badge-soft-success"> Hoạt động </span>
                                            </td>
                                            <td class="text-center">
                                                <div class="flex-shrink-0 ms-4">
                                                    <ul class="list-inline tasks-list-menu mb-0">
                                                        <li class="list-inline-item">
                                                            <a class="btn btn-outline-primary btn-sm waves-effect waves-light"
                                                               href="#" title="Sửa">
                                                                <i class="ri-pencil-line "></i>
                                                            </a>
                                                        </li>
                                                        <li class="list-inline-item">
                                                            <button title="Delete"
                                                                    class="btn-sm btn btn-outline-danger waves-effect waves-light submitDeleteForm">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>
                                    <!--end table-->
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <div class="pagination-wrap hstack gap-2">
                                        {{--                                        {{ $cabinets->links() }}--}}
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
        </div>
        @endsection

        @section('script')
            <script>
                @if (session('alert-error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: "{{ session('alert-error') }}",
                    timerProgressBar: true,
                    timer: 3000,
                    showCloseButton: false,
                    showConfirmButton: false,
                })
                @endif

                @if (session('alert-success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('alert-success') }}",
                    timerProgressBar: true,
                    timer: 3000,
                    showCloseButton: false,
                    showConfirmButton: false,
                })
                @endif

                $(document).ready(function() {
                    $('.submitDeleteForm').on('click', function(e) {
                        e.preventDefault()
                        var form = $(this).parents().children('form')
                        Swal.fire({
                            title: '',
                            text: 'Bạn có chắc chắn muốn xoá không ? Khi xoá khu vực vẫn giữ điểm vui chơi và các dịch vụ',
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Có',
                            cancelButtonText: 'Không',
                        }).then((result) => {
                            if (result.value) {
                                form.submit()
                            }
                        })
                    })
                })
            </script>
@endsection

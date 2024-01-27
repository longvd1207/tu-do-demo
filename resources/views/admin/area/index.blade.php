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
                                        <a class="btn btn-primary" href="{{ route('area.create') }}">
                                            <i class="ri-add-line align-bottom me-1"></i>Thêm mới
                                        </a>
                                    </div>
                                </div>
                            </div>
                            {{--@endcan--}}

                            <div class="card-body border border-dashed border-end-0 border-start-0">
                                <form action="{{ route('area.index') }}" method="get">
                                    <div class="row g-3 mb-0 align-items-center">
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <div class="input-group-text bg-primary text-white">Từ khoá</div>
                                                <input class="form-control" name="key_search"
                                                       placeholder="Tìm theo tên khu vực..." type="text"
                                                       value="{{ session('search.key_search') }}">
                                            </div>
                                        </div>
                                        <div class="col-sm-auto">
                                            <input name="confirm_search" type="hidden" value="1"/>
                                            <button class="btn btn-warning" type="submit">
                                                <i class="ri-equalizer-fill me-1 align-bottom"></i> Tìm kiếm
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive  table-card mb-4">
                                    <table class="table table-sm table-bordered align-middle table-nowrap mb-0 "
                                           id="tasksTable">
                                        <thead class="table-light text-muted">
                                        <tr class="text-center">
                                            <th class="sort" style="width: 4%">STT</th>
                                            <th class="sort">Tên khu vực</th>
                                            <th class="sort">Mô tả</th>
                                            <th style="width: 15%">Trạng thái</th>
                                            <th style="width: 15%">Thao tác</th>
                                        </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                        @isset($areas)
                                            @foreach ($areas as $key => $item)
                                                <tr id="tr_{{ $item['id'] }}">
                                                    {{--                                                        <td class="text-center"> <?php echo (session('search.page') - 1) * $limit + ($key + 1); ?></td>--}}
                                                    <td class="text-center"> {{ $key + 1 }}</td>
                                                    <td>{{ $item['name'] }}</td>
                                                    <td>{{ $item['description'] }}</td>
                                                    <td class="text-center ">
                                                        @if($item->status == 1)
                                                            <span class="badge badge-soft-success"> Hoạt động </span>
                                                        @else
                                                            <span class="badge badge-soft-danger"> Khóa </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="flex-shrink-0 ms-4">
                                                            <ul class="list-inline tasks-list-menu mb-0">

                                                                {{--                                                                    @can('update_area')--}}
                                                                <li class="list-inline-item">
                                                                    <a class="btn btn-outline-primary btn-sm waves-effect waves-light"
                                                                       href="
                                                                            {{ route('area.edit', $item['id']) }}"
                                                                       title="Sửa">
                                                                        <i class="ri-pencil-line "></i>
                                                                    </a>
                                                                </li>
                                                                {{--                                                                    @endcan--}}
                                                                {{--                                                                    @can('area.delete')--}}
                                                                <li class="list-inline-item">
                                                                    <form
                                                                        action="{{ route('area.destroy', $item['id']) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button title="Delete"
                                                                                class="btn-sm btn btn-outline-danger waves-effect waves-light submitDeleteForm">
                                                                            <i class="ri-delete-bin-line"></i>
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                                {{--                                                                    @endcan--}}
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
                                        {{ $areas->links() }}
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
                });
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
                });
                @endif

                $(document).ready(function () {
                    $('.submitDeleteForm').on('click', function (e) {
                        e.preventDefault();
                        var form = $(this).parents().children('form');
                        Swal.fire({
                            title: '',
                            text: "Bạn có chắc chắn muốn xoá không ? Khi xoá khu vực vẫn giữ điểm vui chơi và các dịch vụ",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Có',
                            cancelButtonText: 'Không'
                        }).then((result) => {
                            if (result.value) {
                                form.submit();
                            }
                        });
                    });
                });
            </script>
@endsection

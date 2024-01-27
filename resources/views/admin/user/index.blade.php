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
                        <div class="card" id="user-list">
                            <div class="card-header border-0">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        @can('create_user')
                                            <a class="btn btn-primary waves-effect waves-light"
                                               href="{{ url('admin/user/create') }}">
                                                <i class="ri-add-line align-bottom me-1"></i>Thêm mới
                                            </a>
                                        @endcan
                                    </div>
                                </div>
                            </div>

                            <div class="card-body border border-dashed border-end-0 border-start-0">
                                <form action="{{ url('admin/user/search') }}" method="post">
                                    @csrf
                                    <div class="row g-3 mb-0 align-items-center">
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <div class="input-group-text bg-primary text-white">Từ khoá</div>
                                                <input class="form-control" name="key_search"
                                                       placeholder="Tìm username, name, điện thoại"
                                                       type="text" value="{{ session('search.key_search') }}">
                                            </div>
                                        </div>

{{--                                        <div class="col-sm-3">--}}
{{--                                            <div class="input-group">--}}
{{--                                                <div class="input-group-text bg-primary text-white"> Loại</div>--}}
{{--                                                <select class="form-select" id="role_id" name="role_id">--}}
{{--                                                    <option disabled value=''>Lựa chọn</option>--}}
{{--                                                    @foreach ($roles as $item)--}}
{{--                                                        <option {{ old('role_id') == $item->id ? 'selected' : '' }}--}}
{{--                                                                @if (!empty($user))--}}
{{--                                                                    {{ @$user->getRoleNames()->first() == $item->name ? 'selected' : '' }}--}}
{{--                                                                @endif--}}
{{--                                                                value='{{ $item->id }}'>--}}
{{--                                                            {{ $item->name }}</option>--}}
{{--                                                    @endforeach--}}
{{--                                                </select>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}




                                        <div class="col-sm-auto">
                                            <input name="confirm_search" type="hidden" value="1"/>
                                            <button class="btn btn-warning" type="submit"><i
                                                    class="ri-equalizer-fill me-1 align-bottom"></i> Tìm kiếm
                                            </button>
                                        </div>
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive  table-card mb-4">
                                    <table class="table table-sm table-bordered align-middle table-nowrap mb-0 "
                                           id="tasksTable">
                                        <thead class="table-light text-muted">
                                        <tr class="text-center">
                                            <th class="sort">STT</th>
                                            <th class="sort">Username</th>
                                            <th class="sort">Tên</th>
{{--                                            <th class="sort">Email</th>--}}
                                            <th class="sort">Điện thoại</th>
{{--                                            <th class="sort">Địa chỉ</th>--}}
                                            <th class="sort">Loại tài khoản</th>
                                            <th style="width: 15%">Thao tác</th>
                                        </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                        @isset($users)
                                            @foreach ($users as $key => $item)
                                                <tr id="tr_item{{ $item['id'] }}">
                                                    <td class="text-center">{{ $key + 1 }}</td>
                                                    <td style="text-align: left">{{ $item['user_name'] }}</td>
                                                    <td style="text-align: left">{{ $item['name'] }}</td>
{{--                                                    <td style="text-align: left">{{ $item['email'] }}</td>--}}
                                                    <td style="text-align: center">{{ $item['phone'] }}</td>
{{--                                                    <td style="text-align: left">{{ $item['address'] }}</td>--}}
                                                    <td style="text-align: center">
                                                            <?php
                                                        if ((int)$item['type'] == 1) {

                                                            echo '<span class="badge badge-soft-success text-uppercase"><i
                                                                class="ri-admin-line"></i> Tài khoản <b>'.$item['role_name'].'</b></span>';


{{--                                                            if ((int)$item['type_of_web'] == 1) {--}}
{{--                                                            ?>--}}
{{--                                                                 <span class="badge badge-soft-success text-uppercase"><i--}}
{{--                                                                class="ri-admin-line"></i> Tài khoản trang quản--}}
{{--                                                                        trị</span>--}}
{{--                                                            <?php--}}
{{--                                                           } else if ((int)$item['type_of_web'] == 2) {--}}
{{--                                                            ?>--}}
{{--                                                              <span class="badge badge-soft-success text-uppercase"><i--}}
{{--                                                                    class="ri-admin-line"></i> Tài khoản đầu bếp</span>--}}
{{--                                                                <?php--}}
{{--                                                            }--}}

                                                        } else if ((int)$item['type'] == 2) {
                                                            ?>
                                                        <span class="badge badge-soft-primary text-uppercase"><i
                                                                class="ri-user-settings-line"></i> Tài khoản thiết bị</span>
                                                        {{--                                                        <p class="text-danger">Vị--}}
                                                        {{--                                                            trí: {{ @$item['localtionEat']['name'] }}--}}
                                                        {{--                                                        </p>--}}
                                                        <?php

                                                        }

                                                        ?>




                                                        {{--                                                        @switch($item['type'])--}}
                                                        {{--                                                            @case(1)--}}
                                                        {{--                                                                <span class="badge badge-soft-success text-uppercase"><i--}}
                                                        {{--                                                                        class="ri-admin-line"></i> Tài khoản trang quản--}}
                                                        {{--                                                                        trị</span>--}}
                                                        {{--                                                                @break--}}

                                                        {{--                                                            @case(2)--}}
                                                        {{--                                                                <span class="badge badge-soft-primary text-uppercase"><i--}}
                                                        {{--                                                                        class="ri-user-settings-line"></i> Tài khoản thiết bị</span>--}}
                                                        {{--                                                                <p class="text-danger">Vị--}}
                                                        {{--                                                                    trí: {{ @$item['localtionEat']['name'] }}--}}
                                                        {{--                                                                </p>--}}
                                                        {{--                                                                @break--}}

                                                        {{--                                                            @default--}}
                                                        {{--                                                                ---}}
                                                        {{--                                                        @endswitch--}}
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="flex-shrink-0 ms-4">
                                                            <ul class="list-inline tasks-list-menu mb-0">
{{--                                                                @if ($item['type'] == 1)--}}
{{--                                                                    @can('create_user')--}}
{{--                                                                        <li class="list-inline-item">--}}
{{--                                                                            <a class="btn btn-outline-success btn-sm waves-effect waves-light"--}}
{{--                                                                               href="{{ route('user_getFormAddcompanyToUser', $item['id']) }}"--}}
{{--                                                                               title="Gán phòng ban cho tài khoản">--}}
{{--                                                                                <i class="ri-git-merge-line"></i>--}}
{{--                                                                            </a>--}}
{{--                                                                        </li>--}}
{{--                                                                    @endcan--}}
{{--                                                                @endif--}}
                                                                @can('update_user')
                                                                    <li class="list-inline-item">
                                                                        <a class="btn btn-outline-primary btn-sm waves-effect waves-light"
                                                                           href="{{ url('admin/user/' . $item['id'] . '/edit') }}"
                                                                           title="Sửa">
                                                                            <i class="ri-pencil-line "></i>
                                                                        </a>
                                                                    </li>
                                                                @endcan
                                                                @can('delete_user')
                                                                    @if($item->user_name != "admin" and $item->user_name != "api" )
                                                                    <li class="list-inline-item">
                                                                        <a class="btn-sm btn btn-outline-danger waves-effect waves-light"
                                                                           href="#"
                                                                           onclick="deleteItems('{{ $item['id'] }}','tr_item','{{ route('user_delete', $item['id']) }}')"
                                                                           title="Xóa">
                                                                            <i class="ri-delete-bin-line"></i>
                                                                        </a>
                                                                    </li>
                                                                        @endif
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
                                        {{ $users->links() }}
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
                @if (!empty(session('alert-success')))
                // sweetSuccess('{{ session('alert-success') }}');
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

                $(document).ready(function () {
                    $('.submitForm').on('click', function (e) {
                        e.preventDefault();
                        var form = $(this).parents('form');
                        Swal.fire({
                            title: '',
                            text: "Bạn có chắc chắn muốn xoá không ?",
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
                        return false;
                    });
                });
            </script>
@endsection

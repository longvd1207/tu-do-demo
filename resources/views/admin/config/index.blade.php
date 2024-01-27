@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    @include('components.breadcrumb')
    <form id="form-data" action="{{ route('config.update') }}" method="post">
        @csrf
        <div class="card">
            {{-- <div class="card-body border border-dashed border-start-0 border-end-0 row">

                <div class="col-3">
                    <div class="form_input">
                        <label class="form-label">Thời gian quét thẻ giữa hai lần liên tiếp (giờ)<span
                                style="color:red;font-size:15px;font-weight:bold">*</span></label>
                        <input class="form-control" name="time_from_eat_before" type="number"
                            value="{{ old('time_from_eat_before', isset($data['time_from_eat_before']) ? $data['time_from_eat_before'] : '') }}"
                            placeholder="Thời gian quét thẻ giữa hai lần liên tiếp ...">
                        @if ($errors->has('time_from_eat_before'))
                            <div class="bg-danger text-white text-center py-1">
                                <span>{{ $errors->first('time_from_eat_before') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div> --}}

            <div class="card-body border border-dashed border-start-0 border-end-0 row">
                <div class="col-12">
                    <label class="form-label">Cấu hình bữa ăn<span
                            style="color:red;font-size:15px;font-weight:bold">*</span></label>
                    <a class="btn-sm btn-primary" href="#" style="float: right" onclick="createTr()">
                        <i class="ri-add-line align-bottom me-1"></i>Thêm mới
                    </a>
                    <a class="btn-sm btn-danger" href="#" style="float: right;margin-right: 5px;"
                        onclick="$('#note_modal').modal('show')">
                        <i class="ri-chat-1-line align-bottom me-1"></i>Lưu ý
                    </a>
                    <table class="table table-sm table-bordered align-middle table-nowrap mb-0 " id="tasksTable">
                        <thead class="table-light text-muted">
                            <tr>
                                <th class="sort">Bữa ăn</th>
                                <th class="sort text-center">Thời gian bắt đầu</th>
                                <th class="sort text-center">Thời gian kết thúc</th>
                                <th class="sort text-center">Đăng ký trước (giờ)</th>
                                <th class="sort text-center">Hủy trước (giờ)</th>
                                <th class="sort text-center">Thay đổi menu trước (giờ)</th>
                                <th class="sort text-center">Sử dụng bữa ăn</th>
                            </tr>
                        </thead>
                        <tbody class="list list_data" id="list_data">
                            @if (count($data['config_time']) > 0)
                                @foreach ($data['config_time'] as $key => $value)
                                    <tr id="{{ $key }}">
                                        <td><input class="form-control" name="config_time[{{ $key }}][name]"
                                                type="text" value="{{ $value['name'] }}" placeholder="Tên bữa ăn"></td>
                                        <td><input class="form-control" name="config_time[{{ $key }}][start_time]"
                                                type="time" value="{{ $value['start_time'] }}"
                                                placeholder="Thời gian bắt đầu"></td>
                                        <td><input class="form-control" name="config_time[{{ $key }}][end_time]"
                                                type="time" value="{{ $value['end_time'] }}"
                                                placeholder="Thời gian kết thúc"></td>
                                        <td><input class="form-control"
                                                name="config_time[{{ $key }}][register_time]" type="time"
                                                value="{{ $value['register_time'] }}" placeholder="Đăng ký trước"></td>
                                        <td><input class="form-control"
                                                name="config_time[{{ $key }}][cancel_time]" type="time"
                                                value="{{ $value['cancel_time'] }}" placeholder="Hủy trước"></td>
                                        <td><input class="form-control"
                                                name="config_time[{{ $key }}][end_change_menu_time]"
                                                type="time" value="{{ @$value['end_change_menu_time'] }}"
                                                placeholder="Thay đổi menu trước"></td>
                                        <td>
                                            <input type="hidden" class="form-check-input" value="1"
                                                name="config_time[{{ $key }}][is_use]">
                                            <div class="form-check form-switch" style="text-align: center">
                                                <input type="checkbox" class="form-check-input my-checkbox" value="2"
                                                    data_key = "{{ $key }}"
                                                    name="config_time[{{ $key }}][is_use]"
                                                    {{ @$value['is_use'] == 2 ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                    @if ($errors->has('time_eat'))
                        <div class="bg-danger text-white text-center py-1">
                            <span>{{ $errors->first('time_eat') }}</span>
                        </div>
                    @endif
                </div>

                <div class="form_input col-6">
                    <input type="hidden" name="id" value="{{ @$data->id }}">
                </div>
            </div>

            <div class="card-footer">
                <div class="col-sm-auto">
                    <button class="btn  btn-primary" type="button" onclick="checkRemoveChecked()">Cập nhật</button>
                </div>
            </div>
        </div>
    </form>
    @include('admin.config.include.modal')
    <script>
        @if (!empty(session('alert-success')))
            setTimeout(() => {
                main_layout.alert_main("{{ session('alert-success') }}", 'success');
            }, 150);
        @endif

        @if (!empty(session('alert-error')))
            setTimeout(() => {
                main_layout.alert_main("{{ session('alert-error') }}", 'error');
            }, 150);
        @endif



        let config_page = {
            getKeyChecked: function() {
                var checkboxes = document.querySelectorAll('.my-checkbox:checked');

                var dataKeys = [];

                checkboxes.forEach(function(checkbox) {
                    // console.log(checkbox.getAttribute('data_key'));
                    dataKeys.push(checkbox.getAttribute('data_key'));
                });

                return dataKeys;
            },
            start_data_checked: []
        }

        setTimeout(() => {
            config_page.start_data_checked = config_page.getKeyChecked();
        }, 150);

        function checkRemoveChecked() {
            var old_checked = config_page.start_data_checked;
            var new_checked = config_page.getKeyChecked();

            var result = old_checked.filter(value => !new_checked.includes(value));

            // console.log(result.length);
            if (result.length > 0) {
                $('#submit_alert_modal').modal('show');
            } else {
                $('#form-data').submit();
            }
        }

        function createTr() {
            var html = `<tr id="[id]">
                                <td><input class="form-control" name="[name]" type="text" value=""
                                        placeholder="Tên bữa ăn"></td>
                                <td><input class="form-control" name="[start_time]" type="time" value=""
                                        placeholder="Thời gian bắt đầu"></td>
                                <td><input class="form-control" name="[end_time]" type="time" value=""
                                        placeholder="Thời gian kết thúc"></td>
                                <td><input class="form-control" name="[register_time]" type="time" value=""
                                        placeholder="Đăng ký trước"></td>
                                <td><input class="form-control" name="[cancel_time]" type="time" value=""
                                        placeholder="Hủy trước"></td>
                                <td><input class="form-control" name="[end_change_menu_time]" type="time" value="" placeholder="Thay đổi menu trước"></td>
                                <td><input type="hidden" class="form-check-input" value="1" name="[is_use]">
                                    <div class="form-check form-switch" style="text-align: center">
                                        <input type="checkbox" class="form-check-input my-checkbox" value="2" data_key = "[id]" name="[is_use]">
                                    </div>
                                </td>
                            </tr>`;
            var currentTime = $.now();
            html = html.replaceAll('[id]', currentTime)
                .replaceAll('[name]', 'config_time[' + currentTime + '][name]')
                .replaceAll('[start_time]', 'config_time[' + currentTime + '][start_time]')
                .replaceAll('[end_time]', 'config_time[' + currentTime + '][end_time]')
                .replaceAll('[register_time]', 'config_time[' + currentTime + '][register_time]')
                .replaceAll('[cancel_time]', 'config_time[' + currentTime + '][cancel_time]')
                .replaceAll('[end_change_menu_time]', 'config_time[' + currentTime + '][end_change_menu_time]')
                .replaceAll('[is_use]', 'config_time[' + currentTime + '][is_use]')

            $('#list_data').append(html);

        }
    </script>
@endsection

{{-- @section('script')
    <script>
        @if (!empty(session('alert-success')))
            main_layout.alert_main("{{ session('alert-success') }}", 'success');
        @endif

        @if (!empty(session('alert-error')))
            main_layout.alert_main("{{ session('alert-error') }}", 'error');
        @endif
    </script>
@endsection --}}

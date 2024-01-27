@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    @include('components.breadcrumb')
    @include('multiselect.script')
    <div class="row">
        <div class="col">
            <form action="{{ route('device.update', $device->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body border border-dashed border-start-0 border-end-0 row">
                        <!-- bên trái -->
                        <div class="col-6">
                            <div class="row mb-3">

                                <div class="col-6">
                                    <div class="form_input">
                                        <label class="form-label">Chọn kiểu</label>
                                        <select name="type" onchange="switchType(this.value)" class="form-control"
                                                required>
                                            <option value="">--Chọn kiểu--</option>
                                            <option value="1" {{ $device->type == 1 ? 'selected' : '' }}>Khu vực
                                            </option>
                                            <option value="2" {{ $device->type == 2 ? 'selected' : '' }}>Dịch vụ
                                            </option>
                                            <option value="3" {{ $device->type == 3 ? 'selected' : '' }}>Điểm vui chơi
                                            </option>
                                        </select>
                                        @if ($errors->has('type'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('type') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if(!companyIdByUser())
                                    <div class="col-6">
                                        <div class="form_input">
                                            <label class="form-label">Công ty </label>
                                            <select name="company_id" class="form-control">
                                                <option value="">--Chọn công ty--</option>
                                                @foreach($allCompany as $company)
                                                    <option {{ $company->id == $device->company_id ? 'selected' : '' }} value="{{ $company->id }}">{{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                            @if ($errors->has('company_id'))
                                                <div class="bg-danger text-white text-center py-1">
                                                    <span>{{ $errors->first('company_id') }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                            </div>
                            <div class="row mb-3">
                                <div class="choose-area select-group col-6 mb-2"
                                     style="display: {{ $device->type == 1 ? 'block' : 'none' }}">
                                    <div class="input-group">
                                        <div class="input-group-text bg-primary text-white">Khu vực</div>
                                        <select class="form-select" name="area_id" id="search_area_id">
                                            <option value="">Chọn khu vực</option>
                                            @foreach ($areas as $item)
                                                <option value="{{ $item['id'] }}"
                                                        {{ $item['id'] == $device->type_id ? 'selected' : ''}}
                                                        id="area_id_{{ $item['id'] }}">
                                                    {{ $item['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="choose-service select-group col-6 mb-2"
                                     style="display: {{ $device->type == 2 ? 'block' : 'none' }}">
                                    <div class="input-group">
                                        <div class="input-group-text bg-primary text-white">Dịch vụ</div>
                                        <select class="form-select" name="service_id" id="search_service_id">
                                            <option value="">Chọn dịch vụ</option>
                                            @foreach ($services as $item)
                                                <option value="{{ $item['id'] }}"
                                                        {{ $item['id'] == $device->type_id ? 'selected' : ''}}
                                                        id="service_id_{{ $item['id'] }}">
                                                    {{ @$item->area->name. ' - ' . $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="choose-fun_spot select-group col-6 mb-2"
                                     style="display: {{ $device->type == 3 ? 'block' : 'none' }}">
                                    <div class="input-group">
                                        <div class="input-group-text bg-primary text-white">Điểm vui chơi</div>
                                        <select class="form-select" name="fun_spot_id" id="search_fun_spot_id">
                                            <option value="">Chọn điểm vui chơi</option>
                                            @foreach ($fun_spots as $item)
                                                <option value="{{ $item['id'] }}"
                                                        {{ $item['id'] == $device->type_id ? 'selected' : ''}}
                                                        id="fun_spot_id_{{ $item['id'] }}">
                                                    {{ @$item->area->name. ' - ' . $item->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row mb-3">
                                <div class="col-6 mb-2">
                                    <div class="form_input">
                                        <label class="form-label">Nhập địa chỉ IP<span
                                                style="color:red;font-size:15px;font-weight:bold"> *</span></label>
                                        <div style="float: right" id="plus_ip"
                                             class="btn btn-outline-success waves-effect waves-light btn-sm btn_plus">
                                            <i class="ri-add-line"></i></div>
                                        <div id="ip_container">
                                            @if(isset($device->deviceIp))
                                                @foreach($device->deviceIp as $ip)
                                                    <div id="ip_row" style="display: flex; align-items: center;">
                                                        <input class="form-control" name="ip[]" type="text"
                                                               value="{{ $ip->ip }}" placeholder="Nhập địa chỉ ip" required>
                                                        <div
                                                            class="btn btn-outline-danger waves-effect waves-light btn-sm btn_minus">
                                                            -
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div id="ip_row" style="display: flex; align-items: center;">
                                                    <input class="form-control" name="ip[]" type="text"
                                                           placeholder="Nhập địa chỉ ip" required>
                                                    <div id="plus_ip"
                                                         class="btn btn-outline-success waves-effect waves-light btn-sm btn_plus">
                                                        <i class="ri-add-line"></i></div>
                                                    <div
                                                        class="btn btn-outline-danger waves-effect waves-light btn-sm btn_minus">
                                                        -
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                        @if ($errors->has('ip'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('ip') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- bên trái -->
                    </div>

                </div>

                <div class="card-footer">
                    <div class="col-sm-auto">
                        <button class="btn btn-primary" type="submit">
                            Lưu
                        </button>
                    </div>
                </div>
            </form>

        </div>
        @endsection
        <style>
            .image-container {
                position: relative;
                display: inline-block;
            }

            .button-inside-image {
                position: absolute !important;
                top: 0;
                right: 0;
                z-index: 1;
            }

            .form_input {
                margin-bottom: 20px;
            }

            .navbar-brand-box {
                margin-top: 10px;
                margin-bottom: 16px;
            }

            .custom-select {
                display: inline-block;
                width: 100%;
                height: calc(1.5em + 0.75rem + 2px);
                padding: 0.375rem 1.75rem 0.375rem 0.75rem;
                font-size: 1rem;
                font-weight: 400;
                line-height: 1.5;
                color: #495057;
                vertical-align: middle;
                background: #fff;
                border: 1px solid #ced4da;
                border-radius: 0.25rem;
                -webkit-appearance: none;
                -moz-appearance: none;
                appearance: none;
            }

            .btn_plus, .btn_minus {
                display: flex;
                align-items: center; /* căn giữa theo chiều dọc */
                justify-content: center; /* căn giữa theo chiều ngang */
                width: 30px;
                height: 30px;
            }

        </style>

        @section('script')
            <script>

                $(document).ready(function () {
                    $(document).on('click', '.btn_plus', function () {
                        let newInput = '<div class="ip_row" style="display: flex; align-items: center;">' +
                            '<input class="form-control" name="ip[]" type="text" value="{{ old('ip')}}" placeholder="Nhập địa chỉ ip" required>' +
                            '<div class="btn btn-outline-danger waves-effect waves-light btn-sm btn_minus">-</div>' +
                            '</div>';

                        $(this).parent().after(newInput);
                    });

                    $(document).on('click', '.btn_minus', function () {
                        $(this).parent().remove();
                    });
                });


                setTimeout(() => {
                    $('#search_area_id').multiselect({
                        includeSelectAllOption: true,
                        enableFiltering: true,
                        buttonContainer: '<div class="btn-group w-100 h-100" style="font-size: 13px"/>',
                        enableCaseInsensitiveFiltering: true,
                    });
                    $('#search_service_id').multiselect({
                        includeSelectAllOption: true,
                        enableFiltering: true,
                        buttonContainer: '<div class="btn-group w-100 h-100" style="font-size: 13px"/>',
                        enableCaseInsensitiveFiltering: true,
                    });
                    $('#search_fun_spot_id').multiselect({
                        includeSelectAllOption: true,
                        enableFiltering: true,
                        buttonContainer: '<div class="btn-group w-100 h-100" style="font-size: 13px"/>',
                        enableCaseInsensitiveFiltering: true,
                    });
                }, 220)


                function switchType(selected) {
                    $(".select-group").hide();
                    $(".choose-" + getSelectedText(selected)).show();
                }

                function getSelectedText(selectedValue) {
                    switch (selectedValue) {
                        case "1":
                            return "area";
                        case "2":
                            return "service";
                        case "3":
                            return "fun_spot";
                        default:
                            return "";
                    }
                }

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
            </script>

@endsection

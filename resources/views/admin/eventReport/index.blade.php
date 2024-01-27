@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    @include('components.breadcrumb')
    <script src="{{ config('kztek_config.url_public') }}assets/multi-select/bootstrap-multiselect.js"></script>
    <script src="{{ config('kztek_config.url_public') }}assets/multi-select/prettify.min.js"></script>
    <script src="{{ config('kztek_config.url_public') }}assets/multi-select/bootstrap.bundle-4.5.2.min.js"></script>
    <link href="{{ config('kztek_config.url_public') }}assets/multi-select/bootstrap-multiselect.css" rel="stylesheet"
        type="text/css" />
    <link href="{{ config('kztek_config.url_public') }}assets/multi-select/prettify.min" rel="stylesheet" type="text/css" />
    {{--    <link href="{{config('kztek_config.url_public')}}assets/multi-select/bootstrap-4.5.2.min.css" rel="stylesheet" type="text/css" /> --}}
    <style>
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
    </style>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">

                @can('export_event_report')
                    <div class="card-header">
                        <form class="float-end" id="excel-form" action="{{ route('eventReport.exportExcel') }}" method="post">
                            @csrf
                            <button class="btn btn-success" id="excel-btn">Excel</button>
                        </form>
                    </div>
                @endcan

                <!-- tìm kiếm -->

                {{-- <div class="card-body border border-dashed border-end-0 border-start-0">
                    <form action="{{ url('admin/eventReport/search') }}" method="post">
                        @csrf
                        <div class="row g-3 mb-0 align-items-center">

                            <div class="col-sm-3">
                                <div class="input-group">
                                    <div class="input-group-text bg-primary text-white">Từ khoá</div>
                                    <input class="form-control" name="key_search"
                                           placeholder="Tìm mã hoá đơn, mã vé , tên K.hàng, điện thoại , email "
                                           type="text"
                                           value="{{ session('search.key_search') }}">
                                </div>
                            </div>



                            <div class="col-2 ">
                                <div class="input-group">
                                    <div class="input-group-text bg-primary text-white">Khu vực</div>
                                    <select id="area_id" class="form-select" name="area_id"
                                            style="font-size: 12px;" onchange="getServiceFunspotByArea()">
                                        <option value="">--Lựa chọn--</option>
                                        @foreach ($list_area as $item)
                                            <option value="{{ $item->id }}"
                                                {{ !empty(session('search.area_id')) ? (session('search.area_id') == $item->id ? 'selected' : '') : '' }}>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="col-2 ">
                                <div class="input-group">
                                    <div class="input-group-text bg-primary text-white">Dịch vụ</div>
                                    <select id="service_id" class="form-select" name="service_id"
                                            style="font-size: 12px;">
                                        <option value="">--Lựa chọn--</option>
                                        @foreach ($list_service as $item)
                                            <option value="{{ $item->id }}"
                                                {{ !empty(session('search.service_id')) ? (session('search.service_id') == $item->id ? 'selected' : '') : '' }}>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>

                            <div class="col-2 ">
                                <div class="input-group">
                                    <div class="input-group-text bg-primary text-white">Điểm vui chơi</div>
                                    <select id="fun_spot_id" class="form-select" name="fun_spot_id"
                                            style="font-size: 12px;">
                                        <option value="">--Lựa chọn--</option>
                                        @foreach ($list_funSpot as $item)
                                            <option value="{{ $item->id }}"
                                                {{ !empty(session('search.fun_spot_id')) ? (session('search.fun_spot_id') == $item->id ? 'selected' : '') : '' }}>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>


                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-text bg-primary text-white">Từ ngày</div>
                                    <input type="date" class="form-control" name="start_date"
                                           value="{{ session('search.start_date') }}">
                                    <div class="input-group-text bg-primary text-white">đến ngày</div>
                                    <input type="date" class="form-control" name="end_date"
                                           value="{{ session('search.end_date') }}">
                                </div>
                            </div>


                            <div class="col-sm-auto">
                                <input name="confirm_search" type="hidden" value="1"/>
                                <button class="btn btn-warning" type="submit"><i
                                        class="ri-equalizer-fill me-1 align-bottom"></i> Tìm kiếm
                                </button>
                            </div>

                        </div>

                        <!--end row-->
                    </form>
                </div> --}}
                <!-- tìm kiếm -->

                <div style="padding:5px 15px" class="text-danger"><b>Trang {{ session('search.page') }} /
                        {{ $data->lastPage() }}, tổng số {{ $total }} </b></div>

                <div class="card-body">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr class="text-center">
                                <th scope="col">STT</th>
                                <th scope="col">Khu vực</th>
                                <th scope="col">Tủ</th>
                                <th scope="col">Ngăn</th>
                                <th scope="col">Loại ngăn</th>
                                <th scope="col">Thời gian gửi</th>
                                <th scope="col">Thời gian lấy</th>
                                <th scope="col">Hình ảnh</th>
                            </tr>
                        </thead>
                        <tbody>
                            @for ($i = 1; $i < 21; $i++)
                                <tr class="text-center">
                                    <td>{{ $i }}</td>
                                    <td>Khu vực {{ $i }}</td>
                                    <td>Tủ {{ $i }}</td>
                                    <td>Ngăn {{ $i }}</td>
                                    <td>
                                        <span class="badge bg-success">Ngăn vừa</span>
                                    </td>
                                    <td>{{ date('H:i d-m-Y') }}</td>
                                    <td>{{ date('H:i d-m-Y') }}</td>
                                    <td>
                                        <img style="width:100px"
                                            src="https://www.jollyboxdesign.com/wp-content/uploads/2021/08/Administrator.png"
                                            alt="">
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>

    <!-- gọi đến các model -->
    {{-- @include('admin.warning_event.include.modal') --}}
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

        //nếu có lỗi import file excel nhân viên
        @if (!empty(session('import-error')))
            showErrorImport();
        @endif

        //nếu có lỗi import file zip ảnh
        @if (!empty(session('import-file-zip-error')))
            showErrorImportFileZipImage();
        @endif

        $(document).ready(function() {
            $('.submitForm').on('click', function(e) {
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


        // $(document).ready(function() {});
        //hiển thi lỗi  import file excel
        function showErrorImport() {
            var data = JSON.parse(`{!! session('import-error') !!}`);
            var html_append = ``;

            for (const key in data) {
                const value = data[key];

                var rowspan = 0;
                var more_td = '';
                var td = '';

                for (const keyVal in value) {
                    if (rowspan == 0) {
                        td = td + '<tr>' + `<td rowspan="[rowspan]" class="text-center">` + key + `</td><td>` + value[
                                keyVal] + `</td>` +
                            '</tr>';
                    } else {
                        td = td + '<tr>' + `<td>` + value[keyVal] + `</td>` + '</tr>';
                    }
                    rowspan++;
                }
                td = td.replaceAll('[rowspan]', rowspan);

                html_append = html_append + td;
            }

            $('#modalImportErrorTbody').append(html_append);

            setTimeout(() => {
                $('#modalImportError').modal('show');
            }, 300);

        }

        //hiển thị lỗi import file zip ảnh
        function showErrorImportFileZipImage() {
            var data = JSON.parse(`{!! session('import-file-zip-error') !!}`);
            var html_append = ``;

            for (const key in data) {
                const value = data[key];

                var rowspan = 0;
                var more_td = '';
                var td = '';

                for (const keyVal in value) {
                    if (rowspan == 0) {
                        td = td + '<tr>' + `<td rowspan="[rowspan]" class="text-left">` + keyVal + `</td><td>` + value[
                                keyVal] + `</td>` +
                            '</tr>';
                    } else {
                        td = td + '<tr>' + `<td>` + value[keyVal] + `</td>` + '</tr>';
                    }
                    rowspan++;
                }
                td = td.replaceAll('[rowspan]', rowspan);

                html_append = html_append + td;
            }

            $('#modalImportFileZipImageErrorTbody').append(html_append);

            setTimeout(() => {
                $('#modalImportFileZipImageError').modal('show');
            }, 300);

        }

        //import file excel nhân viên
        function showModal() {
            $('#import-excel-modal').modal('show');
        }

        //import file zip ảnh
        function showModalImportImage() {
            $('#import-zip-image-modal').modal('show');
        }


        //xem báo cáo từng nhân viên
        function xem_nhan_vien(report_name, report_phone, report_email, report_address) {

            $('#report_name').html(report_name);
            $('#report_phone').html(report_phone);
            $('#report_email').html(report_email);
            $('#report_address').html(report_address);
            $('#info_customer').modal('show');
        }

        //quẹt thẻ tìm kiếm theo mã thẻ ----------------------
        function keyUpCardId(event) {
            if (event.key === 'Enter') {
                var decimal = $('#card_id').val();

                var hexNumber = decimalToHex(decimal);

                $('#card_id').val(hexNumber);
            }
        }

        function decimalToHex(decimal) {
            let hexString = "";
            while (decimal > 0 || hexString.length < 8) {
                const remainder = decimal % 16;
                hexString = getHexDigit(remainder) + hexString;
                decimal = Math.floor(decimal / 16);
            }
            while (hexString.length < 8) {
                hexString = "0" + hexString;
            }
            return hexString;
        }

        function getHexDigit(remainder) {
            if (remainder < 10) {
                return String(remainder);
            } else {
                return String.fromCharCode(65 + (remainder - 10));
            }
        }

        //quẹt thẻ tìm kiếm theo mã thẻ ----------------------
    </script>


    {{--           selectbox chọn nhiều --}}
    <script>
        window.prettyPrint() && prettyPrint();
        $('#area_id').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            buttonContainer: '<div class="btn-group w-100 h-100" style="font-size: 13px"/>',
            enableCaseInsensitiveFiltering: true,
        });
        $('#service_id').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            buttonContainer: '<div class="btn-group w-100 h-100" style="font-size: 13px"/>',
            enableCaseInsensitiveFiltering: true,
        });
        $('#fun_spot_id').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            buttonContainer: '<div class="btn-group w-100 h-100" style="font-size: 13px"/>',
            enableCaseInsensitiveFiltering: true,
        });
    </script>
    {{-- selectbox chọn nhiều --}}

    {{-- thay đổi khu vục => thay đổi dịch vụ và điểm vui chơi --}}
    <script>
        function getServiceFunspotByArea() {

            var area_id = $("#area_id").val();

            $.ajax({
                type: "GET",
                data: {},
                url: "{{ url('') }}" + "/admin/area/get_service_fun_spot/" + area_id,
                success: function(responseData) {

                    if (responseData['status'] == 200) {

                        //----------------dịch vụ ---------------------------------
                        $('#service_id').children().remove();

                        let html = '<option value="">---Lựa chọn---</option>';
                        responseData['list_service'].forEach((val, key) => {

                            html += '<option value="' + val.id + '">' + val.name +
                                '</option>';

                        });

                        $('#service_id').html(html);

                        $('#service_id').multiselect('rebuild');
                        //----------------dịch vụ ---------------------------------

                        //----------------điểm vui chơi ---------------------------------
                        $('#fun_spot_id').children().remove();

                        let html_1 = '<option value="">---Lựa chọn---</option>';
                        responseData['list_fun_spot'].forEach((val, key) => {

                            html_1 += '<option value="' + val.id + '">' + val.name +
                                '</option>';

                        });

                        $('#fun_spot_id').html(html_1);

                        $('#fun_spot_id').multiselect('rebuild');
                        //----------------dịch vụ ---------------------------------

                    }
                },
            });

        }
    </script>
@endsection

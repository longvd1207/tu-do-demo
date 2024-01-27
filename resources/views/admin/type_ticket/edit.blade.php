@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    @include('components.breadcrumb')

    <div class="row">
        <div class="col">
            <form action="{{ route('type_ticket.update' , $type_ticket->id) }}"
                  enctype="multipart/form-data" method="post">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body border border-dashed border-start-0 border-end-0 row">
                        <!-- bên trái -->
                        <div class="col-6">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="form_input">
                                        <label class="form-label">Tên loại vé <span
                                                style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <input class="form-control" name="name" type="text"
                                               placeholder="Nhập tên loại vé"
                                               value="{{ old('name') ?: $type_ticket->name }}"/>
                                        @if ($errors->has('name'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('name') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <di class="col-6">
                                    <div class="form_input">
                                        <label class="form-label">Trạng thái
                                            <span style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <select name="status" class="form-control">
                                            <option class="text-success"
                                                    {{ $type_ticket->status == 1 ? 'selected' : '' }} value="1">Hoạt động
                                            </option>
                                            <option class="text-danger"
                                                    {{ $type_ticket->status == 0 ? 'selected' : '' }} value="0">Khóa
                                            </option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('status') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </di>

                                <div class="col-12">
                                    <div class="form_input">
                                        <label class="form-label">Kiểu thanh toán
                                            <span style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <select id="type" name="type" class="form-control">
                                            <option id="payment_offline" value="1"
                                                    selected {{ $type_ticket->type == 1 ? 'selected' : ''}}> Offline -
                                                Trực tiếp
                                            </option>
                                            <option id="payment_online"
                                                    value="2" {{ $type_ticket->type == 2 ? 'selected' : ''}}> Online -
                                                Trực tuyến
                                            </option>
                                            <option value="3"
                                                    id="both_payment" {{ $type_ticket->type == 3 ? 'selected' : ''}}> Cả
                                                2 phương thức
                                            </option>
                                        </select>
                                        @if ($errors->has('type'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('type') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6" id="price_online" style="display:none">
                                    <div class="form_input">
                                        <label class="form-label">Giá vé online </label>
                                        <input class="form-control" name="price_online" type="text"
                                               placeholder="Nhập giá vé online"
                                               value="{{ old('price_online') ?: $type_ticket->price_online}}"/>
                                        @if ($errors->has('price_online'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('price_online') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-6" id="price_offline">
                                    <div class="form_input">
                                        <label class="form-label">Giá vé offline</label>
                                        <input class="form-control" name="price_offline" type="text"
                                               placeholder="Nhập giá vé offline"
                                               value="{{ old('price_offline') ?: $type_ticket->price_offline}}"/>
                                        @if ($errors->has('price_offline'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('price_offline') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- bên phải -->
                        <div class="col-6">
                            <div class="row mb-3">
                                <div class="col-4">
                                    <div class="form_input">
                                        <label class="form-label">Chọn khu vực <span
                                                style="color:red;font-size:15px;font-weight:bold"> *</span>
                                        </label>
                                        <div class="form-control">
                                            <div>
                                                @foreach($areas as $area)
                                                    <div class="form-check form-switch form-switch-success" dir="ltr">
                                                        <input type="checkbox" onchange="changeArea(this)"
                                                               class="form-check-input" id="id_area_{{ $area['id'] }}"
                                                               name="area[]"
                                                               {{ in_array($area['id'], $areaByTicketTypeIds, true) ? 'checked' : '' }}
                                                               value="{{ $area['id'] }}">
                                                        <label style="padding-bottom: 5px" class="form-check-label"
                                                               for="id_area_{{ $area['id'] }}">
                                                            {{ $area['name'] }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form_input">
                                        <label class="form-label">Chọn dịch vụ<span
                                                style="color:red;font-size:15px;font-weight:bold"> *</span></label>
                                        <div class="form-control">
                                            <div id="list_services">
                                                @foreach($services as $service)
                                                    <div class="form-check form-switch form-switch-success" dir="ltr">
                                                        <input class="form-check-input"
                                                               type="checkbox" id="id_service_{{ $service['id'] }}"
                                                               name="service[]" value="{{ $service['id'] }}"
                                                            {{ in_array($service['id'], $servicesByTicketTypeIds, true) ? 'checked' : '' }}>
                                                        <label style="padding-bottom: 5px" class="form-check-label"
                                                               for="id_service_{{ $service['id'] }}">
                                                            {{ $service['name'] }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form_input">
                                        <label class="form-label">Chọn điểm vui chơi<span
                                                style="color:red;font-size:15px;font-weight:bold"> *</span></label>
                                        <div class="form-control">
                                            <div id="list_fun_spots">
                                                @foreach($funSpots as $funSpot)
                                                    <div class="form-check form-switch form-switch-success" dir="ltr">
                                                        <input class="form-check-input"
                                                               type="checkbox" id="id_funSpot_{{ $funSpot['id'] }}"
                                                               name="funSpot[]" value="{{ $funSpot['id'] }}"
                                                            {{ in_array($funSpot['id'], $funSpotsByTicketTypeIds, true) ? 'checked' : '' }}>

                                                        <label style="padding-bottom: 5px" class="form-check-label"
                                                               for="id_funSpot_{{ $funSpot['id'] }}">
                                                            {{ $funSpot['name'] }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <!-- bên phải -->
                <div class="card-footer">
                    <div class="col-sm-auto">
                        <button class="btn btn-primary" type="submit">
                            Sửa
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <!-- bên trái -->
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

</style>
@section('script')
    <script>

        function changeArea() {
            var checkboxes = document.querySelectorAll('input[name="area[]"]');
            // var checkboxesFunSpots = document.querySelectorAll('input[name="fun_spots[]"]');
            var changedValues = [];
            var changedFunSpots = [];
            var changedServices = [];
            var html = `<div class="form-check form-switch form-switch-success" dir="ltr">
                            <input class="form-check-input"
                                type="checkbox" id="id_funSpot_[id]"
                                name="funSpot[]" value="[id]" [checked] >
                                <label style="padding-bottom: 5px"
                                    class="form-check-label"
                                    for="id_funSpot_[id]"> [name]
                                </label>
                        </div>`

            var serviceHtml = `<div class="form-check form-switch form-switch-success" dir="ltr">
                            <input class="form-check-input"
                                 type="checkbox" id="id_service_[id]"
                                    name="service[]" value="[id]" [checked]>

                                 <label style="padding-bottom: 5px"
                                        class="form-check-label"
                                        for="id_service_[id]">[name]
                                 </label>
                            </div>`




            checkboxes.forEach(function (checkbox) {
                if (checkbox.checked) {
                    changedValues.push(checkbox.value);
                }
            });


            var checkboxesFunSpots = document.querySelectorAll('input[name="funSpot[]"]');
            checkboxesFunSpots.forEach(function (checkbox) {
                if (checkbox.checked) {
                    changedFunSpots.push(checkbox.value);
                }
            });


            var checkboxesService = document.querySelectorAll('input[name="service[]"]');
            checkboxesService.forEach(function (checkbox) {
                if (checkbox.checked) {
                    changedServices.push(checkbox.value);
                }
            });


            var data = {
                data: changedValues,
            }
            $.ajax({
                url: "{{ route('type-ticket.change-area') }}",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: data,
                type: "post",
                success: function (responseData) {
                    var html_append_fun = '';
                    var html_append_service = '';

                    for (const key in responseData.data.fun_spots) {
                        let checked = '';

                        if (changedFunSpots.includes(key)) {
                            checked = 'checked';
                        }

                        html_append_fun =
                            html_append_fun + html.replaceAll('[id]', key).replaceAll('[name]', responseData.data.fun_spots[key]).replaceAll('[checked]', checked);
                    }


                    for (const key in responseData.data.services) {
                        let checked = '';

                        if (changedServices.includes(key)) {
                            checked = 'checked';
                        }
                        html_append_service =
                            html_append_service + serviceHtml.replaceAll('[id]', key).replaceAll('[name]', responseData.data.services[key]).replaceAll('[checked]', checked);
                    }

                    $('#list_fun_spots').empty();
                    $('#list_services').empty();
                    $('#list_services').append(html_append_service);
                    $('#list_fun_spots').append(html_append_fun);

                }
            })

        }

    </script>


    <script>
        $(document).ready(function () {
            function togglePriceFields(selectedValue) {
                $('#price_online, #price_offline').hide();
                if (selectedValue === '1') {
                    $('#price_offline').show();
                } else if (selectedValue === '2') {
                    $('#price_online').show();
                } else if (selectedValue === '3') {
                    $('#price_online, #price_offline').show();
                }
            }

            // Xử lý sự kiện khi trang được tải
            togglePriceFields($('#type').val());

            $('#type').change(function () {
                var selectedValue = $(this).val();
                togglePriceFields(selectedValue);
            });
        });


    </script>
@endsection


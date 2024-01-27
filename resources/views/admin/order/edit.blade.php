@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    @include('components.breadcrumb')
    @include('multiselect.script')

    <div class="row">
        <div class="col">
            <form action="{{ route('order.update', $order->id) }}"
                  enctype="multipart/form-data" method="post">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body border border-dashed border-start-0 border-end-0 row">
                        <!-- bên trái -->
                        <div class="col-6">
                            <div class="row mb-3">

                                <div class="col-12 mb-4 ">
                                    <div class="input-group">
                                        <div class="input-group-text bg-primary text-white">Chọn loại vé</div>
{{--                                        <select class="form-select" required name="type_ticket_id" id="type_ticket">--}}
{{--                                            <option value="">--Chọn loại vé--</option>--}}
                                            @foreach ($type_tickets as $item)
                                                <label for="type_ticket_id" > {{ $item['name'] }}</label>
                                                <input value="{{ $item['id'] }}" id="type_ticket_id" name="type_ticket_id[]"/>
{{--                                                <option value="{{ $item['id'] }}" id="type_ticket_id_{{ $item['id'] }}">--}}
{{--                                                    {{ $item['name'] }}</option>--}}
                                            @endforeach
{{--                                        </select>--}}
                                    </div>
                                </div>



                                <div class="col-6">
                                    <div class="form_input">
                                        <label class="form-label"> Hình thức thanh toán
                                            <span style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <select name="type" required class="form-control">
                                            <option value="1" selected>Thanh toán trực tiếp</option>
                                        </select>
                                        @if ($errors->has('type'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('type') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form_input">
                                        <label for="use_date" class="form-label">Ngày sử dụng <span style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <input class="form-control" id="use_date" name="use_date" type="date"/>
                                        @if ($errors->has('description'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('description') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>



                                <div class="col-12">
                                    <div class="form_input">
                                        <label class="form-label">Ghi chú </label>
                                        <textarea class="form-control" name="note" type="text"
                                                  placeholder="Nhập ghi chút...">{{ old('note') }}</textarea>
                                        @if ($errors->has('note'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('note') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-6">
                            <div class="row mb-3">

{{--khung --}}
{{--                                end khung--}}

                                <div class="col-6">
                                    <div class="form_input">
                                        <label class="form-label"> Trạng thái thanh toán
                                            <span style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <select name="status" required class="form-control">
                                            <option selected value="1">Chưa thanh toán</option>
                                            <option value="2">Đã thanh toán</option>
                                            <option value="3">Đã hủy</option>
                                            <option value="4">Đã hoàn thành</option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('status') }}</span>
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
                        <button class="btn  btn-primary" type="submit">
                            Thêm
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

            ::-webkit-input-placeholder {
                font-style: italic;
            }
            :-moz-placeholder {
                font-style: italic;
            }
            ::-moz-placeholder {
                font-style: italic;
            }
            :-ms-input-placeholder {
                font-style: italic;
            }
        </style>
        <script>
            setTimeout(() => {
                $('#type_ticket').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    buttonContainer: '<div class="btn-group w-100 h-100" style="font-size: 13px"/>',
                    enableCaseInsensitiveFiltering: true,
                });

                $('#user_id_search').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    buttonContainer: '<div class="btn-group w-100 h-100" style="font-size: 13px"/>',
                    enableCaseInsensitiveFiltering: true,
                });
            }, 220)

        </script>
        @section('script')

            <script>

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

                @if (!empty(session('alert-success')))
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('alert-success') }}",
                    showConfirmButton: false,
                    timer: 1500,
                    showCloseButton: false
                });
            </script>
    @endif

@endsection

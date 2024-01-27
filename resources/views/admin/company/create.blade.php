@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    @include('components.breadcrumb')
    @include('multiselect.script')

    <div class="row">
        <div class="col">
            <form action="{{ route('company.store') }}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="card">
                    <div class="card-body border border-dashed border-start-0 border-end-0 row">
                        <!-- bên trái -->
                        <div class="col-6">
                            <div class="row mb-3">

                                <div class="col-12">
                                    <div class="form_input">
                                        <label class="form-label">Mã công ty<span
                                                style="color:red;font-size:15px;font-weight:bold"> *</span></label>
                                        <input class="form-control" name="code" type="text"
                                            value="{{ old('code') }}" required>

                                        @if ($errors->has('code'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('code') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>


                                <div class="col-12">
                                    <div class="form_input">
                                        <label class="form-label">Tên công ty<span
                                                style="color:red;font-size:15px;font-weight:bold"> *</span></label>
                                        <input class="form-control" name="name" type="text"
                                               value="{{ old('name') }}" placeholder="Nhập tên công ty..." required>
                                        @if ($errors->has('name'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('name') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>


                                <div class="col-6">
                                    <div class="form_input">
                                        <label class="form-label">Email</label>
                                        <input class="form-control" name="email" type="text"
                                               value="{{ old('email') }}" required>
                                        @if ($errors->has('email'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('email') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>


                                <div class="col-6">
                                    <div class="form_input">
                                        <label class="form-label">Số điện thoại</label>
                                        <input class="form-control" name="phone" type="number"
                                               value="{{ old('phone') }}">
                                        @if ($errors->has('phone'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('phone') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form_input">
                                        <label class="form-label">Địa chỉ</label>
                                        <input class="form-control" name="address" type="text"
                                               value="{{ old('address') }}" placeholder="Nhập địa chỉ...">
                                        @if ($errors->has('address'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('address') }}</span>
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
        </style>
    @endsection
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

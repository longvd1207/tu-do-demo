@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    @include('components.breadcrumb')
    <div class="row">
        <div class="col">
            <form action="{{ route('type_ticket.store') }}"
                  enctype="multipart/form-data" method="post">
                @csrf

                <div class="card">
                    <div class="card-body border border-dashed border-start-0 border-end-0 row">
                        <!-- bên trái -->
                        <div class="col-6">
                            <div class="row mb-3">


                                <div class="col-6">
                                    <div class="form_input">
                                        <label for="name_ticket" class="form-label">Tên loại vé <span style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <input id="name_ticket" required class="form-control" name="name" type="text"
                                               placeholder="Nhập tên loại vé" value="{{ old('name') }}" />
                                        @if ($errors->has('name'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('name') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form_input">
                                        <label class="form-label">Trạng thái
                                            <span style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <select name="status" class="form-control">
                                            <option class="text-success" value="1">Hoạt động</option>
                                            <option class="text-danger" value="0">Khóa</option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('status') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if(!auth()->user()->company_id)
                                    <div class="col-6">
                                        <div class="form_input">
                                            <label class="form-label">Công ty </label>
                                            <select name="company_id" class="form-control">
                                                <option value="">--Chọn công ty--</option>
                                                @foreach($allCompany as $company)
                                                    <option value="{{ $company->id }}">{{ $company->name }}</option>
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


                                <div class="col-12">
                                    <div class="form_input">
                                        <label class="form-label">Kiểu thanh toán
                                            <span style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <select id="type" name="type" class="form-control">
                                            <option id="payment_offline" value="1" selected> Offline - Trực tiếp
                                            </option>
                                            <option id="payment_online" value="2"> Online - Trực tuyến
                                            </option>
                                            <option value="3" id="both_payment" > Cả 2 phương thức
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
                                               value="{{ old('price_online') }}"/>
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
                                               value="{{ old('price_offline') }}"/>
                                        @if ($errors->has('price_offline'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('price_offline') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- bên trái -->

                        <!-- bên phải -->
                        <div class="col-6">
                            <div class="row mb-3">
                                @livewire('area-checkbox')

                                @livewire('service-checkbox')

                                @livewire('fun-spot-checkbox')
                            </div>
                        </div>
                        <!-- bên phải -->
                    </div>
                </div>


                <div class="card-footer">
                    <div class="col-sm-auto">
                        <button class="btn btn-primary" type="submit">
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
</style>
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
        @endif
    </script>

    <!-- ======================================================= Xử lý ảnh =========================================== -->

    <script>
        function delete_image() {
            $(".image-container").remove();
            $("#delete_image_id").append('<input name="delete_image" type="hidden" value="1">');
        }

        //ẩn hiện vai trò theo type là web hay api
        function showRoleBox() {
            var key = $('select[name=type]').val();
            // console.log($('#type').val());
            if (key != 1) {
                //=2 là api => ẩn vai trò
                $('#div_role').addClass('d-none');
                // $('#div_location').removeClass('d-none');
            } else {
                //=1 là web hiện vai trò lên
                $('#div_role').removeClass('d-none');
                // $('#div_location').addClass('d-none');

            }
        }


        $(document).ready(() => {
            //div hiển thị ảnh
            $("div.holder").hide();
            showRoleBox();

            //chọn ảnh
            $("#photo").change(function () {
                const file = this.files[0];
                if (file) {
                    $("div.holder").show();
                    let reader = new FileReader();
                    reader.onload = function (event) {
                        $("#imgPreview").attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
        });

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

    <!-- ================================================xử lý ảnh ======================================= -->
@endsection

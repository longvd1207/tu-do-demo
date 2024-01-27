@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    @include('components.breadcrumb')
    <div class="row">
        <div class="col">
            <form action="{{ route('cabinet.store') }}"
                  enctype="multipart/form-data" method="post">
                @csrf

                <div class="card">
                    <div class="card-body border border-dashed border-start-0 border-end-0 row">

                        <!-- bên trái -->
                        <div class="col-6">
                            <div class="row mb-3">

                                <div class="col-6">
                                    <div class="form_input">
                                        <label class="form-label">Tên tủ đồ <span
                                                style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <input class="form-control" name="name" type="text"
                                               value="{{ old('name')}}" placeholder="Nhập tên tủ đồ.." required>
                                    </div>
                                </div>

                                <div class="col-6">
                                    <div class="form_input">
                                        <label class="form-label">Trạng thái
                                            <span style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <select name="status" class="form-control">
                                            <option class="text-success" value="1">Hoạt động</option>
                                            <option class="text-danger" value="0">Bảo trì</option>
                                        </select>

                                    </div>
                                </div>


                                <div class="col-6">
                                    <div class="form_input">
                                        <label class="form-label">Khu vực
                                            <span style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <select name="status" class="form-control">
                                            <option value="0">-- Chọn khu vực --</option>
                                            <option value="0">Khu vực 1</option>
                                            <option value="0">Khu vực 2</option>
                                            <option value="0">Khu vực 3</option>
                                            <option value="0">Khu vực 4</option>
                                            <option value="0">Khu vực 5</option>
                                        </select>

                                    </div>
                                </div>


                                <div class="col-6">
                                    <div class="form_input">
                                        <label class="form-label">Ngăn tủ
                                            <span style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <select name="status" class="form-control">
                                            <option value="0">-- Chọn ngăn --</option>
                                            <option value="0">Ngăn nhỏ: 10 </option>
                                            <option value="0">Ngăn vừa: 10</option>
                                            <option value="0">Ngăn to: 10</option>
                                        </select>

                                    </div>
                                </div>

                            </div>
                        </div>

                    </div>
                    <!-- bên trái -->
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
                    showCloseButton: false,
                })
                @endif

                @if (!empty(session('alert-success')))
                Swal.fire({
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('alert-success') }}",
                    showConfirmButton: false,
                    timer: 1500,
                    showCloseButton: false,
                })
                @endif
            </script>

            <!-- ======================================================= Xử lý ảnh =========================================== -->

            <script>
                function delete_image() {
                    $('.image-container').remove()
                    $('#delete_image_id').append('<input name="delete_image" type="hidden" value="1">')
                }

                //ẩn hiện vai trò theo type là web hay api
                function showRoleBox() {
                    var key = $('select[name=type]').val()
                    // console.log($('#type').val());
                    if (key != 1) {
                        //=2 là api => ẩn vai trò
                        $('#div_role').addClass('d-none')
                        // $('#div_location').removeClass('d-none');
                    } else {
                        //=1 là web hiện vai trò lên
                        $('#div_role').removeClass('d-none')
                        // $('#div_location').addClass('d-none');

                    }
                }


                $(document).ready(() => {
                    //div hiển thị ảnh
                    $('div.holder').hide()
                    showRoleBox()

                    //chọn ảnh
                    $('#photo').change(function() {
                        const file = this.files[0]
                        if (file) {
                            $('div.holder').show()
                            let reader = new FileReader()
                            reader.onload = function(event) {
                                $('#imgPreview').attr('src', event.target.result)
                            }
                            reader.readAsDataURL(file)
                        }
                    })
                })
            </script>

            <!-- ================================================xử lý ảnh ======================================= -->
@endsection

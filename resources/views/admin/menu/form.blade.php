@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    @include('components.breadcrumb')
    <div class="row">
        <div class="col">
            <form action="{{ route($action) }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-body border border-dashed border-start-0 border-end-0">
                        <div class="row mb-3">
                            <div class="col-3">
                                <label class="form-label">Tên danh mục<span
                                        style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                <input class="form-control" name="name" type="text"
                                       value="{{ old('name', $data['name'] ?? '') }}"
                                       placeholder="Nhập tên danh mục ...">
                                @if ($errors->has('name'))
                                    <div class="bg-danger text-white text-center py-1">
                                        <span>{{ $errors->first('name') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <input type="hidden" name="id" value="{{ @$data->id }}">

                    </div>

                    <div class="card-footer">
                        <div class="col-sm-auto">
                            <a href="{{ route('resources.index') }}" class="btn btn-danger waves-effect waves-light">Quay
                                lại</a>
                            @if (!empty($data))
                                <button class="btn  btn-primary" type="submit">Cập nhật</button>
                            @else
                                <button class="btn  btn-primary" type="submit">Thêm mới</button>
                            @endif

                        </div>
                    </div>
                </div>
            </form>

        </div>
    </div>
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
    </script>

    <script>
        $(document).ready(() => {

            $("div.holder").hide();

            $("#photo").change(function() {
                const file = this.files[0];
                if (file) {
                    $("div.holder").show();
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        $("#imgPreview").attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });


        });
    </script>
@endsection

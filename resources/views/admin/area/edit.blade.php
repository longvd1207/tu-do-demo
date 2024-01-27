@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    @include('components.breadcrumb')
    <div class="row">
        <div class="col">
            <form action="{{ route('area.update' , $area->id) }}"
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
                                        <label class="form-label">Tên khu vực <span
                                                style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <input class="form-control" name="name" type="text"
                                               value="{{ old('name') ?? $area->name}}" placeholder="Nhập tên khu vực..."
                                               required>
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
                                            <option class="text-success"
                                                    value="1" {{ $area->status === 1 ? 'selected' : '' }}>Hoạt động
                                            </option>
                                            <option class="text-danger"
                                                    value="0" {{ $area->status === 0 ? 'selected' : '' }}>Khóa
                                            </option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('status') }}</span>
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
                                        <label class="form-label">Mô tả </label>
                                        <textarea class="form-control" name="description" type="text"
                                                  placeholder="Nhập mô tả...">{{ old('description') ?? $area->description }}</textarea>
                                        @if ($errors->has('description'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('description') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                    <!-- bên trái -->

                    <!-- bên phải -->
                    <div class="col-6">
                    </div>
                    <!-- bên phải -->
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


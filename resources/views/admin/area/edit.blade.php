@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    @include('components.breadcrumb')
    @include('multiselect.script')
    <div class="row">
        <div class="col">
            <form action="{{ route('fun-spot.update' , $funSpot->id) }}"
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
                                        <label class="form-label"> Điểm vui chơi <span
                                                style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <input class="form-control" name="name" type="text"
                                               value="{{ old('name') ?? $funSpot->name}}"
                                               placeholder="Nhập điểm vui chơi..." required>
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
                                                    value="1" {{ $funSpot->status === 1 ? 'selected' : '' }}>Hoạt động
                                            </option>
                                            <option class="text-danger"
                                                    value="0" {{ $funSpot->status === 0 ? 'selected' : '' }}>Khóa
                                            </option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('status') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-6 mb-2">
                                    <div class="input-group">
                                        <div class="input-group-text bg-primary text-white">Khu vực</div>
                                        <select class="form-select" name="area_id" id="search_area_id">
                                            <option value="">--Chọn khu vực--</option>
                                            @foreach ($areas as $item)
                                                <option value="{{ $item['id'] }}"
                                                        {{ $funSpot->area_id == $item->id ? 'selected' : '' }} id="area_id_{{ $item['id'] }}">
                                                    {{ $item['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                @if(!companyIdByUser())
                                    <div class="col-6 mb-2 ">
                                        <div class="input-group">
                                            <div class="input-group-text bg-primary text-white">Công ty</div>
                                            <select class="form-select" name="area_id" id="search_area_id" required>
                                                <option value="">--Chọn công ty--</option>
                                                @foreach ($allCompany as $company)
                                                    <option {{ $company->id == $funSpot->company_id ? 'selected' : '' }} value="{{ $company->id }}">
                                                        {{ $company->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @endif

                                <div class="col-12">
                                    <div class="form_input">
                                        <label class="form-label">Mô tả </label>
                                        <textarea class="form-control" name="description" type="text"
                                                  placeholder="Nhập mô tả...">{{ old('description') ?? $funSpot->description }}</textarea>
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
                            Sửa
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

<script>
    setTimeout(() => {
        $('#search_area_id').multiselect({
            includeSelectAllOption: true,
            enableFiltering: true,
            buttonContainer: '<div class="btn-group w-100 h-100" style="font-size: 13px"/>',
            enableCaseInsensitiveFiltering: true,
        })
    }, 220)

</script>

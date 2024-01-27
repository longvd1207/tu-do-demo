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
                <div class="card-header">
                    <form action="{{ route('mail_history.index') }}" method="post">
                        @csrf
                        <div class="row g-3 mb-0 align-items-center">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-text bg-primary text-white">Từ khoá</div>
                                    <input class="form-control" name="key_search"
                                        placeholder="Tìm theo mã hóa đơn và thông tin khách hàng..." type="text"
                                        value="{{ session('search.key_search') }}">
                                </div>
                            </div>
                            <div class="col-sm-3">
                                <div class="input-group">
                                    <div class="input-group-text bg-primary text-white">Ngày gửi</div>
                                    <input class="form-control" name="date" placeholder="Chọn ngày gửi mail"
                                        type="date" value="{{ session('search.date') }}">
                                </div>
                            </div>
                            <div class="col-sm-auto">
                                <input name="confirm_search" type="hidden" value="1">
                                <button class="btn btn-warning" type="submit">
                                    <i class="ri-equalizer-fill me-1 align-bottom"></i> Tìm kiếm
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <table class="table align-middle table-nowrap mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">STT</th>
                                <th scope="col">Mã hóa đơn</th>
                                <th scope="col">Tên khách hàng</th>
                                <th scope="col">Email</th>
                                <th scope="col">Số điện thoại</th>
                                <th scope="col">Thời gian gửi mail</th>
                                <th scope="col">Trạng thái</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!empty($data))
                                @foreach ($data as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td><a href="{{ route('order.index', ['code_order' => $item->order_code]) }}"
                                                class="fw-medium" target="_blank" title="Xem hoá đơn"><b>{{ @$item->order_code }}</b></a></td>
                                        <td>{{ @$item->customer->name }}</td>
                                        <td>{{ @$item->customer->email }}</td>
                                        <td>{{ @$item->customer->phone }}</td>
                                        <td>{{ date('H:i d-m-Y', strtotime($item->created_at)) }}</td>
                                        <td>
                                            @if ($item->status)
                                                <span class="badge bg-success">Gửi thành công</span>
                                            @else
                                                <span class="badge bg-danger">Gửi thất bại</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
@endsection

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
                    <div style="float: right" class="dropdown show">
                        <a class="btn btn-sm btn-success dropdown-toggle" href="#" role="button"
                            id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ri-bar-chart-grouped-line label-icon align-middle fs-16 me-2 "></i> Báo cáo chi tiết
                        </a>

                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <a class="dropdown-item" href="{{ route('revenueReport.reportWithUser') }}"><i
                                    class="ri-file-user-line label-icon align-middle fs-16 me-2"></i> Chi tiết theo người
                                bán</a>
                            <a class="dropdown-item" href="{{ route('revenueReport.reportWithTicket') }}"><i
                                    class="ri-coupon-2-line label-icon align-middle fs-16 me-2 "></i> Chi tiết theo loại
                                vé</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <x-revenue-component />
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
@endsection

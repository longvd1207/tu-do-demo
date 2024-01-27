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
                    <form class="float-end" id="excel-form" action="{{ route('revenueReport.reportWithTicket.export_excel') }}" method="post">
                        @csrf
                        <button class="btn btn-success" id="excel-btn">Excel</button>
                    </form>
                </div>

                <div class="card-body border border-dashed border-end-0 border-start-0">
                    <form action="{{ route('revenueReport.reportWithTicket') }}" method="post">
                        @csrf
                        <input type="hidden" value="1" name="confirm_search">
                        <div class="row">
                            <div class="col-sm-4">
                                <div class="input-group">
                                    <div class="input-group-text bg-primary text-white">Từ ngày</div>
                                    <input class="form-control" name="start_date" type="date" id="start-date-search"
                                        value="{{ session('search.startOfMonth') }}">

                                    <div class="input-group-text bg-primary text-white">đến</div>
                                    <input class="form-control" name="end_date" type="date" id="end-date-search"
                                        value="{{ session('search.endOfMonth') }}">
                                </div>
                            </div>
                            <div class="d-flex align-items-center col-2">
                                <div class="flex-shrink-0" style="margin-right: 5px">
                                    <button class="btn btn-warning" type="submit">
                                        <i class="ri-search-line align-bottom me-1"></i>Tìm kiếm
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        {{-- <div class="table-responsive  table-card mb-4"> --}}
                        <table class="table table-sm table-bordered align-middle table-nowrap mb-0 " id="tasksTable">
                            <thead class="table-light text-muted">
                                <tr class="text-center">
                                    <th class="sort">STT</th>
                                    <th class="sort">Tên vé</th>
                                    <th class="sort">Số lượng</th>
                                    <th class="sort">Giá online (đ)</th>
                                    <th class="sort">Giá offline (đ)</th>
                                    <th class="sort">Thành tiền (đ)</th>
                                </tr>
                            </thead>
                            <tbody class="list form-check-all">
                                @php
                                    $index = 0;
                                    $totalOfTicket = 0;
                                    $countOfTicket = 0;
                                @endphp
                                @foreach ($ticket as $keyTicket => $value)
                                    @php
                                        $index++;
                                        foreach ($ticketType as $val) {
                                            if ($val->id == $keyTicket) {
                                                $thisTicketType = $val;
                                                break;
                                            }
                                        }
                                    @endphp
                                    <tr>
                                        <td class="sort text-center" style="width: 4%">{{ $index }}</td>
                                        <td class="sort">
                                            {{ $thisTicketType->name }}
                                        </td>
                                        <td class="sort text-center" style="width: 15%">{{ count($value) }}</td>
                                        <td class="sort" style="text-align: right;width: 15%">
                                            {{ number_format($thisTicketType->price_online, 0, '.', '.') }}
                                        </td>
                                        <td class="sort" style="text-align: right;width: 15%">
                                            {{ number_format($thisTicketType->price_offline, 0, '.', '.')  }}
                                        </td>
                                        <td class="sort" style="text-align: right;width: 15%">
                                            {{ number_format($value->sum('price'), 0, '.', '.')  }}
                                        </td>
                                    </tr>
                                    @php
                                        $totalOfTicket = $totalOfTicket + $value->sum('price');
                                        $countOfTicket = $countOfTicket + count($value);
                                    @endphp
                                @endforeach

                            </tbody>
                            <tr>
                                <td colspan="2" class="sort text-center" style="width: 4%">
                                    <b>Tổng</b>
                                </td>
                                <td class="sort text-center" style="width: 4%">
                                    <b>{{ $countOfTicket }}</b>
                                </td>
                                <td colspan="3" class="sort" style="text-align: right;width: 4%">
                                    <b>{{ number_format($totalOfTicket, 0, '.', '.')  }}</b>
                                </td>
                            </tr>
                            <tr style="background-color: rgb(0, 85, 0);color:#fff">
                                <td colspan="2" class="sort text-center" style="width: 4%">
                                    <b>Tổng tiền thực thu</b>
                                </td>
                                <td colspan="4" class="sort" style="text-align: right;width: 4%">
                                    <b>{{ number_format($total, 0, '.', '.')  }}</b>
                                </td>
                            </tr>
                        </table>
                        <!--end table-->
                    </div>
                    {{-- </div> --}}
                </div>
                <!--end card-body-->
            </div>
            <!--end card-->
        </div>
        <!--end col-->
    </div>
@endsection

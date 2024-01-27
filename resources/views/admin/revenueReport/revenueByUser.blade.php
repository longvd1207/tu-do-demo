@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    @include('components.breadcrumb')
    <style>

        #tasksTable td {
            padding: 10px; /* Thêm padding 10px vào nội dung của ô */

        }
    </style>
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row mb-1">
                    <div class="col-12">
                        <div class="card">

                            <div class="card-header">
                                <form class="float-end" id="excel-form" action="{{ route('revenueReport.reportWithUser.export_excel') }}" method="post">
                                    @csrf
                                    <button class="btn btn-success" id="excel-btn">Excel</button>
                                </form>
                            </div>


                            <div class="card-body border border-dashed border-end-0 border-start-0">
                                <form action="{{ route('revenueReport.reportWithUser') }}" method="post">
                                    @csrf
                                    <div class="row g-3 mb-0 align-items-center">
                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <div class="input-group-text bg-primary text-white">Từ ngày</div>
                                                <input class="form-control" name="start_date"
                                                       type="date"
                                                       value="{{ session('search.start_date') }}">
                                            </div>
                                        </div>


                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <div class="input-group-text bg-primary text-white">Đến ngày</div>
                                                <input class="form-control" name="end_date"
                                                       type="date"
                                                       value="{{ session('search.end_date') }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-auto">
                                            <input name="confirm_search" type="hidden" value="1"/>
                                            <button class="btn btn-warning" type="submit">
                                                <i class="ri-equalizer-fill me-1 align-bottom"></i> Tìm kiếm
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive  table-card mb-4">
                                    <table class="table table-sm table-bordered align-middle table-nowrap mb-0 "
                                           id="tasksTable">
                                        <thead class="table-light text-muted">
                                        <tr class="text-center">
                                            <th class="sort" style="width: 4%">STT</th>
                                            <th class="sort">Người bán</th>
                                            <th class="sort">Số vé bán được (vé)</th>
                                            <th class="sort">Tổng tiền (đ)</th>
                                            <th class="sort">Thực thu (đ)</th>
                                        </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                        @php
                                            $sumAmount = 0;
                                            $sumRealAmount = 0;
                                            $sumQty = 0
                                        @endphp
                                        @foreach ($users as $key => $user)
                                            @php
                                                $realAmount = $user->orders->when($start_date, fn ($query) => $query->where('created_at', '>=', date($start_date).' 00:00:00'))
                                                                           ->when($end_date, fn ($query) => $query->where('created_at', '<=', date($end_date).' 23:59:59'))
                                                                           ->where('payment_status', 2)
                                                                           ->sum('real_amount');

                                                $amount = $user->orders->when($start_date, fn ($query) => $query->where('created_at', '>=', date($start_date).' 00:00:00'))
                                                                       ->when($end_date, fn ($query) => $query->where('created_at', '<=', date($end_date).' 23:59:59'))
                                                                       ->where('payment_status', 2)
                                                                       ->sum('amount');

                                                $quantity = $user->orders->flatMap->tickets
                                                                    ->when($start_date, fn ($query) => $query->where('created_at', '>=', date($start_date).' 00:00:00'))
                                                                    ->when($end_date, fn ($query) => $query->where('created_at', '<=', date($end_date).' 23:59:59'))
                                                                    ->count();
                                                $sumQty += $quantity;
                                                $sumRealAmount += $realAmount;
                                                $sumAmount += $amount;
                                            @endphp
                                            <tr>
                                                <td> {{ $key + 1 }} </td>
                                                <td> {{ $user->name }} </td>
                                                <td align="right">
                                                    {{ $quantity }}
                                                </td>

                                                <td align="right">
                                                    {{ number_format($realAmount, 0, '.', '.') }}
                                                </td>
                                                <td align="right">
                                                    {{ number_format($amount, 0, '.', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach

                                        <tr style="background-color: #1798df;color:#fff">
                                            <td>#</td>
                                            <td>Vé bán online</td>
                                            <td align="right">{{ $countTicketOnl }}</td>
                                            <td align="right"><b> {{ number_format($sumRealAmountOnl, 0, '.', '.') }} </b></td>
                                            <td align="right"><b>{{ number_format($sumAmountOnl, 0, '.', '.') }} </b></td>
                                        </tr>
                                        <tr style="background-color: rgb(0, 85, 0);color:#fff">
                                            <td colspan="2" class="text-center"><b>Tổng </b></td>
                                            <td align="right"><b> {{ $sumQty + $countTicketOnl  }} </b></td>
                                            <td align="right">
                                                <b> {{ number_format($sumRealAmount + $sumRealAmountOnl, 0, '.', '.') }} </b>
                                            </td>
                                            <td align="right">
                                                <b> {{ number_format($sumAmount + $sumAmountOnl, 0, '.', '.') }} </b>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <!--end table-->
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <div class="pagination-wrap hstack gap-2">
                                        {{ $users->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--End row -->

            </div> <!-- end .h-100-->

            <div class="overlay hidden lds-dual-ring" id="loader">
            </div>
        </div>
        @endsection

        @section('script')
            <script>


                @if (session('alert-error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: "{{ session('alert-error') }}",
                    timerProgressBar: true,
                    timer: 3000,
                    showCloseButton: false,
                    showConfirmButton: false,
                });
                @endif

                @if (session('alert-success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: "{{ session('alert-success') }}",
                    timerProgressBar: true,
                    timer: 3000,
                    showCloseButton: false,
                    showConfirmButton: false,
                });
                @endif

                $(document).ready(function () {
                    $('.submitDeleteForm').on('click', function (e) {
                        e.preventDefault();
                        var form = $(this).parents().children('form');
                        Swal.fire({
                            title: '',
                            text: "Bạn có chắc chắn muốn xoá không ?",
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Có',
                            cancelButtonText: 'Không'
                        }).then((result) => {
                            if (result.value) {
                                form.submit();
                            }
                        });
                    });
                });
            </script>
@endsection

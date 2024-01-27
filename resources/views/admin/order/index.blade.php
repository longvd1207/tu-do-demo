@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    @include('components.breadcrumb')
    @include('multiselect.script')
    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row mb-1">
                    <div class="col-12">
                        <div class="card" id="partner-list">

                            <div class="card-header">
                                <form class="float-end" id="excel-form" action="{{ route('order.export') }}" method="post">
                                    @csrf
                                    <input hidden="hidden" name="is_export" value="1">
                                    <button class="btn btn-success" id="excel-btn">Excel</button>
                                </form>
                            </div>
                            {{--                            @can('create_company') --}}
                            {{-- <div class="card-header border-0">
                                <div class="d-flex align-items-center">
                                    <div class="flex-shrink-0">
                                        <a class="btn btn-primary" href="{{ route('order.create') }}">
                                            <i class="ri-add-line align-bottom me-1"></i>Thêm mới
                                        </a>
                                    </div>
                                </div>
                            </div> --}}
                            {{--                            @endcan --}}

                            <div class="card-body border border-dashed border-end-0 border-start-0">
                                <form action="{{ route('order.index') }}" method="get">
                                    <div class="row g-3 mb-0 align-items-center">

                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <div class="input-group-text bg-primary text-white">Mã hóa đơn</div>
                                                <input class="form-control" name="code_order"
                                                       placeholder="Nhập mã hóa đơn ..." type="text"
                                                       value="{{ session('search.code_order') }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <div class="input-group-text bg-primary text-white">Người bán</div>
                                                <select class="form-select" name="user_id" id="search_user_id">
                                                    <option value="">Chọn người bán</option>
                                                    @foreach ($users as $user)
                                                        <option value="{{ $user->id }}"
                                                                id="user_id_{{ $user->id }}" {{ $user->id == session('search.user_id') ? 'selected' : '' }}>
                                                            {{ $user->name ?? '' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <div class="input-group-text bg-primary text-white">Ngày bán</div>
                                                <input class="form-control" name="created_at" type="date"
                                                       value="{{ session('search.created_at') ?? $today }}">
                                            </div>
                                        </div>


                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <div class="input-group-text bg-primary text-white">Trạng thái thanh toán
                                                </div>
                                                <select class="form-control" name="payment_status">
                                                    <option value="" selected>Chọn</option>
                                                    <option value="1" {{ session('search.payment_status') == 1 ? 'selected' : '' }}>Chưa thanh toán</option>
                                                    <option value="2" {{ session('search.payment_status') == 2 ? 'selected' : '' }}>Đã thanh toán</option>
                                                    <option value="3" {{ session('search.payment_status') == 3 ? 'selected' : '' }}>Đã hủy</option>
                                                </select>
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

                            @php
                                $currentPage = $orders->currentPage();
                                $perPage = $orders->perPage();
                                $startIndex = ($currentPage - 1) * $perPage + 1;
                            @endphp
                            <div style="margin-left: 10px">
                                <span><b>Tổng tiền thực: <span style="color: #fc3d3d">{{ number_format($sumAllOrderRealAmount, 0, '.', '.') . 'đ' }}</span> </b>
                                </span>
                                <br>
                                <span><b>Tổng tiền trả: <span style="color: #fc3d3d"> {{ number_format($sumAllOrderAmount, 0, '.', '.') . 'đ' }} </span></b>
                                </span>
                            </div>

                            <div class="card-body">
                                <div class="table-responsive  table-card mb-4">
                                    <table class="table table-sm table-bordered align-middle table-nowrap mb-0 "
                                           id="tasksTable">
                                        <thead class="table-light text-muted">
                                        <tr class="text-center">
                                            <th class="sort" style="width: 4%">STT</th>
                                            <th class="sort">Mã hóa đơn</th>
                                            <th class="sort" style="width: 15%">Tiền thực</th>
                                            <th class="sort" style="width: 15%">Tổng tiền</th>
                                            <th class="sort" style="width: 15%">Ngày bán</th>
                                            <th class="sort">Trạng thái thanh toán</th>
                                            <th class="sort">Kiểu thanh toán</th>
                                            <th class="sort">Người bán</th>
                                            <th class="sort">Thao tác</th>
                                        </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                        @isset($orders)
                                            @foreach ($orders as $key => $item)
                                                <tr id="tr_{{ $item['id'] }}">
                                                    <td class="text-center">{{ $startIndex + $key }}</td>
                                                    <td class="text-center">{{ $item->code_order }}</td>
                                                    <td class="text-end">
                                                         {{ number_format($item->real_amount, 0, '.', '.') . 'đ' }}</td>
                                                    <td class="text-end">
                                                        {{ number_format($item->amount, 0, '.', '.') . 'đ' }}</td>
                                                    <td>
                                                        {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') }}
                                                    </td>
                                                    <td class="text-center">
                                                        <select class="form-control form-select-sm"
                                                                onchange="changeStatus('{{ $item->id }}', this)"
                                                                id="payment_status">
                                                            <option value="1"
                                                                {{ $item->payment_status == 1 ? 'selected' : '' }}>
                                                                Chưa thanh toán
                                                            </option>
                                                            <option value="2"
                                                                {{ $item->payment_status == 2 ? 'selected' : '' }}>
                                                                Đã thanh toán
                                                            </option>
                                                            <option value="3"
                                                                {{ $item->payment_status == 3 ? 'selected' : '' }}>
                                                                Đã hủy
                                                            </option>
                                                        </select>
                                                    </td>
                                                    <td class="text-center">{{ $item->type == 1 ? 'Trực tiếp' : 'Trực tuyến' }}</td>

                                                    <td class="text-center">{{ $item->user->user_name ?? '' }}</td>
                                                    <td class="text-center">
                                                        <div class="flex-shrink-0 ms-4">
                                                            <ul class="list-inline tasks-list-menu mb-0">
                                                                <li class="list-inline-item">
                                                                    <div
                                                                        class="btn btn-outline-primary btn-sm waves-effect waves-light"
                                                                        onclick="findDataInModal('{{ $item['id'] }}')">
                                                                        <i class="ri-file-search-line"></i>
                                                                    </div>
                                                                </li>

                                                                <li class="list-inline-item">
                                                                    <a type="submit"
                                                                       onclick="printTicket({{ $key }})"
                                                                       id='data_{{ $key }}'
                                                                       id_order="{{ $item['id'] }}"
                                                                       class="btn btn-sm btn-outline-info waves-effect waves-light"
                                                                       title="In vé">
                                                                        <i class="ri-printer-line"></i>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                </tr>
                                            @endforeach
                                        @endisset
                                        </tbody>
                                    </table>
                                    <!--end table-->
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <div class="pagination-wrap hstack gap-2">
                                        {{ $orders->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--End row -->
            </div>
            <div class="overlay hidden lds-dual-ring" id="loader">
            </div>

            <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h3 class="modal-title text-center">Chi tiết hóa đơn</h3>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-5">
                                <div class="container">
                                    <div class="row">
                                        <div id="detail_buy" class="col-sm">
                                        </div>
                                        <div id="customer" class="col-sm">
                                        </div>
                                        {{--<div class="col-sm"> --}}
                                        {{--</div> --}}

                                    </div>
                                </div>

                                <div>
                                    <hr>
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-nowrap">
                                        <thead class="table-light">
                                        <tr>
                                            <th style="text-align: center">Mã</th>
                                            <th style="text-align: center">Tên</th>
                                            <th style="text-align: center">Giá</th>
                                            {{--<th style="text-align: center">QR Code</th>--}}
                                            <th style="text-align: center">Trạng thái</th>
                                        </tr>
                                        </thead>
                                        <tbody id="tbody_modal">
                                        {{-- dữ liệu đẩy lên từ js sẽ ăn vào đây --}}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<style>
    .form-select-sm {
        text-align: center;
    }

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
@section('script')
    <script>
        setTimeout(() => {
            $('#search_user_id').multiselect({
                includeSelectAllOption: true,
                enableFiltering: true,
                buttonContainer: '<div class="btn-group w-100 h-100" style="font-size: 13px"/>',
                enableCaseInsensitiveFiltering: true,
            });
        }, 220)

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

        function findDataInModal(order_id) {
            var data = {
                order_id: order_id
            }

            $.ajax({
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: data,
                url: "{{ route('order.detail') }}",
                beforeSend: function (request) {
                    request.setRequestHeader('token', token)
                },
                success: function (responseData) {
                    if (responseData['status'] == 200) {
                        $('#myModal').find('tbody').empty();
                        let html = '';
                        let customer = '';
                        let detail_buy = '';
                        $.each(responseData.data, function (key, val) {
                            html = html + '<tr>' +
                                `<td style="text-align: center">${val.code}</td>` +
                                `<td>${val.ticket_type_name}</td>` +
                                `<td style="text-align: center">${main_layout.formattedNumber(val.price) + 'đ'}</td>` +
                                // `<td style="text-align: center">${val.qr_code}</td>` +
                                `<td style="text-align: center">
                                    ${parseInt(val.status) == 1 ? '<span class="badge badge-soft-success">Hoạt động</span>' : '<span class="badge badge-soft-danger">Khóa</span>'}
                                    </td>` +
                                '</tr>';
                        });
                        $('#customer').empty();
                        if (responseData.customer != null) {
                            customer = customer +
                                `<h4> Thông tin khách hàng </h4>` +
                                `<span> Họ và tên: ${responseData.customer.name} </span> <br>` +
                                `<span> Email: ${responseData.customer.email} </span> <br>` +
                                `<span> Số điện thoại: ${responseData.customer.phone} </span> <br>` +
                                `<span> Giới tính: ${responseData.customer.gender == 1 ? 'Name' : 'Nữ'} </span> <br>` +
                                `<span> Địa chỉ: ${responseData.customer.address} </span> <br>`;
                            $('#customer').append(customer);
                        }

                        $('#detail_buy').empty()
                        detail_buy = detail_buy +
                            `<h4> Chi tiết mua bán </h4>` +
                            `<span>Số lượng vé: ${responseData.qty}  </span> <br>` +
                            `<span>Phương thức thanh toán: Trực tiếp</span> <br>` +
                            `<span>Tổng tiền: ${new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            }).format(responseData.amount)} </span> <br> `;

                        $('#detail_buy').append(detail_buy);
                        $('#tbody_modal').append(html);
                        $('#myModal').modal('show');
                    }

                }
            })
        }

        function printTicket(key) {
            event.preventDefault();
            var id = $('#data_' + key).attr('id_order');
            var url = "order/in-ve" + '/' + id;
            Swal.fire({
                title: 'Bạn có chắc chắn muốn in vé không ?',
                text: 'Nhấn nút "Có", vé sẽ được truyển sang trạng thái đã in và bạn sẽ không thể in lại vé này nữa!',
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Có',
                cancelButtonText: 'Không'
            }).then((result) => {
                if (result.value) {
                    var xhr = new XMLHttpRequest();
                    xhr.open('GET', url, true);
                    xhr.onreadystatechange = function () {
                        if (xhr.readyState === 4 && xhr.status === 200) {
                            window.iframe = document.createElement('iframe');
                            window.iframe.style.display = 'none';
                            document.body.appendChild(iframe);
                            var doc = window.iframe.contentWindow.document;
                            doc.open();
                            doc.write(xhr.responseText);
                            doc.close();
                            window.iframe.contentWindow.print();
                            document.body.removeChild(window.iframe);

                        }
                    };
                    xhr.send();
                    $('#data_' + key).remove();
                }
            });
            return false;
        }


        function changeStatus(order_id, selectedElement) {
            var data = {
                order_id: order_id,
                type: selectedElement.value,
                _token: '{{ csrf_token() }}'
            }
            $.ajax({
                url: "{{ route('order.change-status') }}",
                data: data,
                type: "post",
                success: function (data) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: data.message,
                        timerProgressBar: true,
                        timer: 1500,
                        showCloseButton: false,
                        showConfirmButton: false,
                    });
                }
            })

        }

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

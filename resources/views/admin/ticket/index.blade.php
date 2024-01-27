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
                                <form class="float-end" id="excel-form" action="{{ route('ticket.export') }}" method="post">
                                    @csrf
                                    <input hidden="hidden" name="is_export" value="1">
                                    <button class="btn btn-success" id="excel-btn">Excel</button>
                                </form>
                            </div>

                            <div class="card-body border border-dashed border-end-0 border-start-0">
                                <form action="{{ route('ticket.index') }}" method="get">
                                    <div class="row g-3 mb-0 align-items-center">

                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <div class="input-group-text bg-primary text-white">Mã vé</div>
                                                <input class="form-control" name="code"
                                                       placeholder="Tìm theo mã vé..." type="text"
                                                       value="{{ session('search.code') }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <div class="input-group-text bg-primary text-white">Mã hóa đơn</div>
                                                <input class="form-control" name="order_code"
                                                       placeholder="Tìm theo mã hóa đơn..." type="text"
                                                       value="{{ session('search.order_code') }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="input-group">
                                                <div class="input-group-text bg-primary text-white">Ngày sử dụng</div>
                                                <input class="form-control" name="use_date"
                                                       type="date" value="{{ session('search.use_date') ?? $today }}">
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="input-group">
                                                <div class="input-group-text bg-primary text-white">Trạng thái</div>
                                                <select class="form-control" name="status">
                                                    <option value="">Chọn trạng thái</option>
                                                    <option value="1" {{ session('search.status') == 1 ? 'selected' : '' }}>Hoạt động</option>
                                                    <option value="0">Khóa</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-sm-1">
                                            <div class="input-group">
                                                <div class="input-group-text bg-primary text-white">Trang</div>
                                                <select class="form-control" name="num_show">
                                                    <option value="5" {{ session('search.num_show') == 5 ? 'selected' : '' }}>5</option>
                                                    <option value="10" {{ session('search.num_show') == 10 ? 'selected' : '' }}>10</option>
                                                    <option value="20" {{ session('search.num_show') == 20 ? 'selected' : '' }}>20</option>
                                                    <option value="50" {{ session('search.num_show') == 50 ? 'selected' : '' }}>50</option>
                                                </select>

                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <input name="confirm_search" type="hidden" value="1"/>
                                            <button class="btn btn-warning" type="submit">
                                                <i class="ri-equalizer-fill me-1 align-bottom"></i> Tìm kiếm
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @php
                                $currentPage = $tickets->currentPage();
                                $perPage = $tickets->perPage();
                                $startIndex = ($currentPage - 1) * $perPage + 1;
                            @endphp
                            <div style="margin-left: 10px">
                                <b> Tổng tiền trang hiện tại: <span style="color: #fc3d3d"> {{ number_format($tickets->sum('price'), 0, '.', '.'). 'đ' }}</span> </b>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive  table-card mb-4">
                                    <table class="table table-sm table-bordered align-middle table-nowrap mb-0 "
                                           id="tasksTable">
                                        <thead class="table-light text-muted">
                                        <tr class="text-center">
                                            <th class="sort" style="width: 4%">STT</th>
                                            <th class="sort">Mã vé</th>
                                            <th class="sort">Mã hóa đơn</th>
                                            <th class="sort">Tên loại vé</th>
                                            <th class="sort">Ngày sử dụng</th>
                                            <th class="sort">Giá vé</th>
                                            <th style="width: 10%">Trạng thái</th>
                                            <th style="width: 15%">Thao tác</th>
                                        </tr>
                                        </thead>
                                        <tbody class="list form-check-all">
                                        @isset($tickets)
                                            @foreach ($tickets as $key => $item)
                                                <tr id="tr_{{ $item['id'] }}">
                                                    <td class="text-center">{{ $startIndex + $key }}</td>
                                                    <td class="text-center">{{ $item['code'] ?? '' }}</td>
                                                    <td class="text-center">{{ $item->order->code_order ?? '' }}</td>
                                                    <td>{{ $item['ticket_type_name'] }}</td>
                                                    <td>{{ \Carbon\Carbon::parse($item['use_date'])->format('d/m/Y') }} </td>
                                                    <td class="text-end">{{ number_format($item['price'] ?? '', 0, '.', '.') . 'đ'}} </td>
                                                    <td class="text-center">
                                                        @if($item->status == 1)
                                                            <span class="badge badge-soft-success"
                                                                  onclick="changeStatusTicket('{{ $item['id'] }}', '{{ $item->status }}')"> Hoạt động </span>
                                                        @else
                                                            <span class="badge badge-soft-danger"
                                                                  onclick="changeStatusTicket('{{ $item['id'] }}', '{{ $item->status }}')"> Khóa </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="flex-shrink-0 ms-4">
                                                            <ul class="list-inline tasks-list-menu mb-0">
                                                                {{--                                                                print ticket--}}
                                                                <li class="list-inline-item">
                                                                    <a type="submit" onclick="printTicket({{ $key }})"
                                                                       id='data_{{ $key }}' id_data="{{ $item['id'] }}"
                                                                       class="btn btn-sm btn-outline-info waves-effect waves-light"
                                                                       title="Xuất vé"><i
                                                                            class="ri-printer-line"></i></a>
                                                                </li>

                                                                {{--@can('update_ticket')--}}
                                                                <li class="list-inline-item">
                                                                    <div
                                                                        class="btn btn-outline-primary btn-sm waves-effect waves-light"
                                                                        onclick="findDataInModal('{{ $item['id'] }}')"
                                                                        id="myButton"
                                                                        data-toggle="modal"
                                                                        data-target="#myModal">
                                                                        <i class="ri-search-eye-fill"></i>
                                                                    </div>
                                                                </li>

                                                                {{--@endcan--}}
                                                                {{--@can('ticket.delete')--}}
                                                                <li class="list-inline-item">
                                                                    <button title="Delete"
                                                                            class="btn-sm btn btn-outline-danger waves-effect waves-light submitDeleteForm">
                                                                        <i class="ri-delete-bin-line"></i>
                                                                    </button>
                                                                    <form
                                                                        action="{{ route('ticket.destroy', $item['id']) }}"
                                                                        method="post">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                    </form>
                                                                </li>
                                                                {{--                                                                    @endcan--}}
                                                            </ul>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endisset
                                        </tbody>
                                    </table>
                                    <!--end table-->
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <div class="pagination-wrap hstack gap-2">
                                        {{ $tickets->links() }}
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
            <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title text-center">Chi tiết vé</h4>
                            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row mb-5">
                                <div class="row">
                                    <tbody>
                                    <div id="tbody_modal">
                                    </div>
                                    </tbody>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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

        function changeStatusTicket(ticket_id, status) {
            var data = {
                ticket_id: ticket_id,
                status: status
            }

            $.ajax({
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: data,
                url: "{{ route('ticket.change-status-ticket') }}",
                beforeSend: function (request) {
                    request.setRequestHeader('token', token)
                },
                success: function (responseData) {
                    if (responseData['status'] == 200) {
                        location.reload();
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: responseData['message'],
                            timerProgressBar: true,
                            timer: 3000,
                            showCloseButton: false,
                            showConfirmButton: false,
                        });
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: responseData['message'],
                            timerProgressBar: true,
                            timer: 3000,
                            showCloseButton: false,
                            showConfirmButton: false,
                        });
                    }
                }
            })

        }

        function findDataInModal(ticket_id) {
            var data = {
                ticket_id: ticket_id
            }
            $.ajax({
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: data,
                url: "{{ route('ticket.detail') }}",
                beforeSend: function (request) {
                    request.setRequestHeader('token', token)
                },
                success: function (responseData) {
                    if (responseData['status'] == 200) {
                        $('#myModal').find('tbody').empty();
                        let html = '';
                        html += '<span class="txt-area"> Khu vực: ' + responseData.data.area.join(', ') + '</span> <br>';
                        html += '<span class="txt-service"> Dịch vụ: ' + responseData.data.service.join(', ') + '</span> <br>';
                        html += '<span class="txt-fun-spots"> Điểm vui chơi: ' + responseData.data.fun_spots.join(', ') + '</span> <br>';
                        $('#myModal').find('#tbody_modal').html(html);
                    }
                }
            })
        }
    </script>
    <script>
        function printTicket(key) {
            event.preventDefault();
            var id = $('#data_' + key).attr('id_data');
            var url = "ticket/in-ve" + '/' + id;
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

        function sweetAlert(message, timer) {
            if (!timer) {
                timer = 1500;
            }
            Swal.fire({
                position: 'center',
                icon: 'error',
                title: 'Lỗi...!',
                text: message,
                showConfirmButton: false,
                timer: timer,
                showCloseButton: true
            });
        }

        function sweetSuccess(message) {
            Swal.fire({
                position: 'top-end',
                icon: 'success',
                title: 'Thành công!',
                text: message,
                showConfirmButton: false,
                timer: 1500,
                showCloseButton: true
            });
        }

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
@endsection


<style>
    .txt-area, .txt-service, .txt-fun-spots {
        font-size: 16px;
        line-height: 23px;
        margin-left: 10px;
    }
</style>

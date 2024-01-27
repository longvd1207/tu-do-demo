@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    @include('components.breadcrumb')
    @include('multiselect.script')

    <div class="row">
        <div class="col">
            <form action="{{ route('order.store') }}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="card">
                    <div class="card-body border border-dashed border-start-0 border-end-0 row">
                        <div class="col-3">
                            <div class="card" style="height: 500px;">
                                <div class="card-header">
                                    <b>Tìm kiếm trong danh sách:</b>
                                    <div class="input-group">
                                        <span class="btn btn-warning"><i class="ri-search-line me-1 align-bottom"></i>
                                        </span>
                                        <input class="form-control" id="searchText" name="searchText"
                                               placeholder="Nhập tên loại vé" type="text" oninput="searchAndHide()"
                                               value="">
                                    </div>
                                </div>
                                <div class="card-body scoll">
                                    <div class="row">
                                        @foreach ($type_tickets as $item)
                                            <div class="col-6 btn btn-item">
                                                <div class="card">
                                                    <div class="card-header card_header_item"
                                                         style="background-color: #f3f3f3"
                                                         onclick="choseTicketType('{{ $item->id }}')">
                                                        {{ @$item->name }}
                                                    </div>
                                                    <div class="card-body detail_item"
                                                         onclick="findDataInModal('{{ $item->id }}')">
                                                        <a href="#"><i
                                                                class="ri-eye-line label-icon align-middle"></i> Xem chi
                                                            tiết</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row mb-3">
                                <div class="row mb-3">
                                    <div class="col-12 mb-4 ">
                                        <table class="table">
                                            <thead>
                                            <tr>
                                                <th>Thao tác</th>
                                                <th>Tên loại vé</th>
                                                <th style="width: 25%">Số lượng vé</th>
                                                <th style="width: 25%">Tổng</th>
                                            </tr>
                                            </thead>
                                            <tbody id="tbody_data">

                                            </tbody>
                                            <tbody>
                                            <tr>
                                                <td></td>
                                                <td><b>Tổng tiền</b></td>
                                                <td></td>
                                                <td id="grand_total">0 đ</td>
                                            </tr>

                                            <tr>
                                                <td></td>
                                                <td><b>Thành tiền</b></td>
                                                <td></td>
                                                <td><input type="number" class="w-100 form-control text-xl-center"
                                                           placeholder="Giá tùy chọn (nếu có)" style="width: 70%;"
                                                           name="price_nhap_tay"></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                        <div class="overlay hidden lds-dual-ring" id="loader">
                                        </div>
                                        <div class="modal fade" id="myModal" tabindex="-1" aria-hidden="true"
                                             onclick="$('#myModal').modal('hide')">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title text-center">Chi tiết loại vé</h4>
                                                        <button type="button" class="btn-close" data-dismiss="modal"
                                                                aria-label="Close">
                                                        </button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row mb-5">
                                                            <div class="row">

                                                                <div id="tbody_modal">
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="form_input">
                                        <label class="form-label"> Trạng thái thanh toán<span
                                                style="color:red;font-size:15px;font-weight:bold"> *</span></label>
                                        <select name="status" required class="form-control">
                                            <option value="1">Chưa thanh toán</option>
                                            <option value="2">Đã thanh toán</option>
                                            <option value="3">Đã hủy</option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('status') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form_input">
                                        <label for="use_date" class="form-label">Ngày sử dụng <span
                                                style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <input class="form-control" id="use_date" name="use_date" type="date"
                                               min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" />
                                        @if ($errors->has('description'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('description') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form_input">
                                        <label class="form-label">Ghi chú </label>
                                        <textarea class="form-control" name="note" type="text"
                                                  placeholder="Nhập ghi chút...">{{ old('note') }}</textarea>
                                        @if ($errors->has('note'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('note') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- bên trái -->
                    </div>

                    <div class="card-footer">
                        <div class="col-sm-auto">
                            <button type="submit" class="btn btn-success btn-label"><i
                                    class="ri-coupon-3-line label-icon align-middle fs-16 me-2"></i> Lưu vé
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        @endsection
        <template id="html_tr">
            <tr id="row-ticket-type-[id]">
                <td>
                    <ul class="list-inline tasks-list-menu mb-0">
                        <li class="list-inline-item">
                            <div title="Delete" class="btn-sm btn btn-outline-danger waves-effect waves-light"
                                 onclick=" deleteRowTicketType('[id]')">
                                <i class="ri-delete-bin-line"></i>
                            </div>
                        </li>
                    </ul>

                </td>
                <td>
                    <label>[name]</label>
                </td>
                <td>
                    <input min="1" class="w-50 form-control text-xl-center input_price" type="number"
                           id="input_[id]" oninput="totalPrice('[id]')" price="[price]"
                           name="qty_ticket_type_id[[id]]" />
                </td>
                <td>
                    <span id="total_amount_[id]" data-price="">0 đ</span>
                </td>
            </tr>
        </template>
        <style>
            .btn-item {
                padding-right: 5px !important;
                padding-left: 5px !important;
                padding-top: 0px !important;
                padding-bottom: 0px !important;
            }

            .card_header_item:hover {
                background-color: #d3d3d3 !important;
            }

            .card_header_item {
                text-align: center;
                min-height: 75px;
                font-weight: 700 !important;
            }

            .scoll {
                overflow-y: auto;
            }

            .scoll::-webkit-scrollbar {
                width: 12px;
            }

            .scoll::-webkit-scrollbar-thumb {
                background-color: #888;
                border-radius: 6px;
            }

            .scoll::-webkit-scrollbar-track {
                background-color: rgba(0, 0, 0, 0.1);
            }

            .detail_item {
                padding: 0px !important;
                text-align: center;
            }

            .detail_item:hover {
                background-color: #f8f8f8;
                font-weight: 700;
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

            ::-webkit-input-placeholder {
                font-style: italic;
            }

            :-moz-placeholder {
                font-style: italic;
            }

            ::-moz-placeholder {
                font-style: italic;
            }

            :-ms-input-placeholder {
                font-style: italic;
            }
        </style>
        <script>
            setTimeout(() => {
                $('#type_ticket').multiselect({
                    includeSelectAllOption: true,
                    enableFiltering: true,
                    buttonContainer: '<div class="btn-group w-100 h-100" style="font-size: 13px"/>',
                    enableCaseInsensitiveFiltering: true,
                })
            }, 220)
        </script>


        @section('script')
            <script>
                function searchAndHide() {
                    const searchTerm = document.getElementById('searchText').value.toLowerCase()
                    const headerItems = document.querySelectorAll('.card_header_item')
                    const col6Wrapper = document.querySelector('.btn-item')

                    let found = false

                    headerItems.forEach(item => {
                        const itemText = item.textContent.toLowerCase()
                        btnItemParent = item.closest('.btn-item')

                        if (itemText.includes(searchTerm)) {
                            found = true
                            btnItemParent.classList.remove('d-none')
                        } else {
                            btnItemParent.classList.add('d-none')
                        }
                    })
                }

                function choseTicketType(id) {
                    item_ticket = $('#input_' + id).val()
                    if (item_ticket != undefined) {
                        if (item_ticket != '') {
                            total = parseInt(item_ticket) + 1

                        } else {
                            total = 1
                        }
                        $('#input_' + id).val(total)
                        totalPrice(id)
                    } else {
                        list_ticket = @json($type_tickets);
                        html_tr = $('#html_tr').html()
                        list_ticket.forEach(element => {
                            if (element.id == id) {
                                console.log(element)
                                var html = html_tr.replaceAll('[id]', element.id)
                                    .replaceAll('[price]', element.price_offline)
                                    .replaceAll('[name]', element.name)
                                $('#tbody_data').append(html)
                                return
                            }
                        })
                    }
                }

                function deleteRowTicketType(id) {
                    $('#row-ticket-type-' + id).remove()
                    totalAllPrice()
                }

                function totalPrice(id) {
                    price = $('#input_' + id).attr('price')
                    value = $('#input_' + id).val()


                    total = price * value
                    // console.log(total);
                    $('#total_amount_' + id).attr('data-price', total)
                    $('#total_amount_' + id).text(main_layout.formattedNumber(total) + 'đ')

                    totalAllPrice()
                }

                function totalAllPrice() {
                    const inputElements = document.querySelectorAll('.input_price')

                    // const inputInfoArray = [];
                    var totalAll = 0
                    inputElements.forEach(input => {
                        const value = input.value
                        const price = input.getAttribute('price')

                        // inputInfoArray.push({
                        //     value: value,
                        //     price: price
                        // });
                        totalAll = totalAll + (value * price)
                    })

                    // console.log();
                    $('#grand_total').text(main_layout.formattedNumber(totalAll) + 'đ')
                }

                function findDataInModal(ticket_type_id) {
                    var data = {
                        id: ticket_type_id,
                    }

                    $.ajax({
                        url: "{{ route('register_online.getTicketTypeDetail') }}",
                        type: 'GET',
                        data: data,
                        success: function(result) {
                            if (result.status == 200) {
                                var html = `<h6 class="modal-title">Tên vé: ` + result.ticket_name + `</h6>`
                                for (const key in result.data) {
                                    const data = result.data[key]
                                    html = html +
                                        `<div class="row"><div class="col-12 card" style="margin:20px;background-color: #f7f7f7;width: 90%;"><div class="card-header"
                                    style="background-color: #f7f7f7;padding-left: 0px !important;margin-bottom: 10px;"><b>` +
                                        data.area_name +
                                        `</b></div><div class="card-body" style="padding: 0px !important;">`

                                    var html_funSpots =
                                        `<div><label class="form-check-label" for="customSwitchsizesm1">Điểm dịch vụ</label><ul>`
                                    for (const keyData in data.getFunSpots) {
                                        const funSpots = data.getFunSpots[keyData]
                                        html_funSpots = html_funSpots + `<li>` + funSpots + `</li>`

                                    }
                                    html_funSpots = html_funSpots + `</ul></div>`

                                    var html_services =
                                        `<div><label class="form-check-label" for="customSwitchsizesm1">Dịch vụ</label><ul>`
                                    for (const keyData in data.getServices) {
                                        const funSpots = data.getServices[keyData]
                                        html_services = html_services + `<li>` + funSpots + `</li>`

                                    }
                                    html_services = html_services + `</ul></div>`


                                    html = html + html_funSpots + html_services
                                    html = html + `</div></div></div>`
                                }

                                $('#myModal').find('#tbody_modal').empty()
                                $('#myModal').find('#tbody_modal').html(html)
                                $('#myModal').modal('show')
                            } else {
                                console.log('lỗi gì đấy rồi!')
                            }
                        },
                    })
                }
            </script>

            <script>
                @if (!empty(session('alert-error')))
                main_layout.alert_main("{{ session('alert-error') }}", 'error')
                @endif

                @if (!empty(session('alert-success')))
                main_layout.alert_main("{{ session('alert-success') }}")
                @endif
            </script>
@endsection

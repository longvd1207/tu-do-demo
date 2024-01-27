<div id="list_ticket_modal" class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="myLargeModalLabel"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Danh sách vé báo tàng vũ trụ Việt nam</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body row">
                <div class="col-6">
                    <div class="card-header">
                        <h6 class="modal-title">Danh sách vé</h6>
                    </div>
                    <div class="card-body" style="margin-bottom: 0px;">
                        @foreach ($list_ticket as $item)
                            <div class="row">
                                <div class="col-8 no_margin">
                                    <div class="form-check form-switch form-switch-success"
                                        style="margin-bottom: 10px;">
                                        <input type="checkbox" class="form-check-input my-checkbox user1"
                                            value="{{ @$item->id }}" name=""
                                            onclick="getTicketTypeDetail('{{ @$item->id }}')">
                                        <label class="form-check-label"
                                            for="customSwitchsizesm1">{{ @$item->name }}</label>
                                    </div>
                                </div>
                                <div class="col-3" style="align-self: center;text-align: right">
                                    {{ number_format($item->price_online, 0, '.', '.') . 'đ' }}
                                </div>
                                <div class="col-1 no_margin" style="align-self: center;">
                                    <a id="item_{{ @$item->id }}"
                                        class="btn btn-icon btn-ghost-secondary rounded-circle"
                                        style="border: 1px solid #d7d7d7;width: 25px;height: 25px;"
                                        onclick="getTicketTypeDetail('{{ @$item->id }}')">
                                        <i class="ri-arrow-right-s-line"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-6">
                    <div class="card-header">
                        <h6 class="modal-title">Chi tiết vé</h6>
                    </div>
                    <div class="card-body" style="margin-bottom: 0px;" id="card-body-data">

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-link link-danger fw-medium" data-bs-dismiss="modal"><i
                        class="ri-close-line me-1 align-middle"></i>
                    Đóng</a>
                <a href="javascript:void(0);" class="btn btn-link link-success fw-medium"
                    onclick="addTicketPayment()"><i class="ri-check-double-line me-1 align-middle"></i>
                    Xác nhận vé đã chọn</a>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

<div id="terms_content" class="modal fade bs-example-modal-lg" tabindex="-1" aria-labelledby="myLargeModalLabel"
    style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myLargeModalLabel">Điều khoản và phương thức thanh toán</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="container">
                    <ul>
                        <li>Khách hàng cam kết bảo mật tài khoản, HrmCloud sẽ không chịu trách nhiệm cho bất kỳ tổn thất
                            hay
                            thiệt
                            hại do sơ suất trong việc duy trì sự bảo mật của tài khoản và mật khẩu của khách hàng.
                            Khách hàng sẽ chịu trách nhiệm cho tất cả các hoạt động và nội dung (dữ liệu, hình ảnh, tài
                            liệu, v.v.)
                            được thêm vào trong hệ thống HrmCloud của mình.</li>
                        <li>Khách hàng không được phép truyền tải các virus, phần mềm độc hại hoặc các mã độc hại vào hệ
                            thống.
                            HrmCloud có quyền chấm dứt tài khoản của khách hàng nếu phát hiện khách hàng vi phạm các
                            điều
                            khoản và
                            điều kiện.</li>
                        <li>Chúng tôi có thể, nhưng không có nghĩa vụ loại bỏ nội dung và tài khoản có chứa nội dung mà
                            chúng tôi đã
                            xác định là bất hợp pháp, không phù hợp đạo đức, văn hóa hay phản đối hoặc vi phạm quyền sở
                            hữu
                            trí tuệ
                            của bất kỳ bên nào hoặc các điều khoản của dịch vụ.</li>
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" class="btn btn-link link-danger fw-medium" data-bs-dismiss="modal"><i
                        class="ri-close-line me-1 align-middle"></i>
                    Đóng</a>
                {{-- <a href="javascript:void(0);" class="btn btn-link link-success fw-medium"><i
                        class="ri-check-double-line me-1 align-middle" onclick="addTicketPayment()"></i>
                    Xác nhận vé đã chọn</a> --}}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->

</div>


<!-- model báo cáo 1 nhân viên -->
<div id="modal_payment_return_momo" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
    style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content" style="width: 140% !important;">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Báo cáo thống kê suất ăn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table role="grid" class="table table-sm table-bordered align-middle table-nowrap mb-0 gridjs-table"
                    id="tasksTable">
                    <thead class="table-light text-muted">
                        <tr class="text-center">
                            <th class="sort" style="width: 40%">Tên nhân viên</th>
                            <th class="sort">T.số suất ăn</th>
                            <th class="sort">T.số suất đã ăn</th>
                            <th class="sort">T.số suất chưa ăn</th>
                        </tr>
                    </thead>
                    <tbody class="list form-check-all" id="modalImportErrorTbody">
                        <tr class="text-center">
                            <th class="sort" style="width: 40%" id="report_name"></th>
                            <th class="sort" id="report_total_meal"></th>
                            <th class="sort" id="report_total_meal_eat"></th>
                            <th class="sort" id="report_total_remaining_meal"></th>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="submit" onclick="checkSearchList()" class="btn btn-primary">Lưu</button>
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<style>
    .black_icon {
        background-color: #000 !important;
        color: #fff !important;
    }

    .input-sm {
        height: 25px;
    }

    .no_margin {
        padding: 0;
    }
</style>
<script>
    function getTicketTypeDetail(key) {
        var data = {
            id: key
        };
        $('.black_icon').removeClass('black_icon');

        $('#item_' + key).addClass('black_icon');

        $.ajax({
            url: "{{ route('register_online.getTicketTypeDetail') }}",
            type: 'GET',
            data: data,
            success: function(result) {
                if (result.status == 200) {
                    var html = `<h6 class="modal-title">Tên vé: ` + result.ticket_name + `</h6>`;
                    for (const key in result.data) {
                        const data = result.data[key];
                        html = html +
                            `<div class="row"><div class="col-12 card" style="margin:20px;background-color: #f7f7f7;width: 90%;"><div class="card-header"
                                    style="background-color: #f7f7f7;padding-left: 0px !important;margin-bottom: 10px;"><b>` +
                            data.area_name +
                            `</b></div><div class="card-body" style="padding: 0px !important;">`;

                        var html_funSpots =
                            `<div><label class="form-check-label" for="customSwitchsizesm1">Điểm dịch vụ</label><ul>`;
                        for (const keyData in data.getFunSpots) {
                            const funSpots = data.getFunSpots[keyData];
                            html_funSpots = html_funSpots + `<li>` + funSpots + `</li>`;

                        }
                        html_funSpots = html_funSpots + `</ul></div>`;

                        var html_services =
                            `<div><label class="form-check-label" for="customSwitchsizesm1">Dịch vụ</label><ul>`;
                        for (const keyData in data.getServices) {
                            const funSpots = data.getServices[keyData];
                            html_services = html_services + `<li>` + funSpots + `</li>`;

                        }
                        html_services = html_services + `</ul></div>`;


                        html = html + html_funSpots + html_services;
                        html = html + `</div></div></div>`;
                    }

                    $("#card-body-data").empty();
                    $('#card-body-data').append(html);
                } else {
                    console.log('lỗi gì đấy rồi!');
                }
            }
        });
    }

    @if (session()->has('error'))
        setTimeout(() => {
            registerOnline.alert_main("{{ session('error') }}", 'error', 'center');
        }, 200);
    @endif

    let registerOnline = {
        formattedNumber: function(numberToFormat) {
            const formattedNumber = numberToFormat.toLocaleString('vi-VN', {
                style: 'decimal'
            });
            return formattedNumber;
        },
        alert_main: function(title = '', icon = 'success', position = 'top-end') {
            Swal.fire({
                position: position,
                icon: icon,
                title: title,
                showConfirmButton: false,
                timer: 1500,
                showCloseButton: false
            });
        },
        show_loader: function() {
            document.getElementById("overlay-loader-layout").style.display = "block";
        },
        hide_loader: function() {
            document.getElementById("overlay-loader-layout").style.display = "none";
        }
    }

    setTimeout(() => {
        checkedData();
        saveInfoToLocalStorage(true);
    }, 200);

    function checkedData() {
        selectedValues = JSON.parse(localStorage.getItem("checkboxes"));
        document.querySelectorAll('.my-checkbox').forEach(function(checkbox) {
            // Kiểm tra nếu giá trị của checkbox nằm trong mảng selectedValues
            if (selectedValues.includes(checkbox.value)) {
                checkbox.checked = true; // Thêm thuộc tính checked
            } else {
                checkbox.checked = false; // Bỏ thuộc tính checked
            }
        });
        addTicketPayment();
    }

    function addTicketPayment() {
        list_ticket = @json($list_ticket);
        const selectedValues = [];
        const checkboxes = document.querySelectorAll('.my-checkbox');

        checkboxes.forEach(function(checkbox) {
            if (checkbox.checked) {
                selectedValues.push(checkbox.value);
            }
        });

        localStorage.setItem("checkboxes", JSON.stringify(selectedValues));

        html_ticket = '';
        ticket_data = JSON.parse(localStorage.getItem("ticket_data"));

        list_ticket.forEach(element => {
            if (selectedValues.indexOf(element.id) !== -1) {
                // console.log(registerOnline.formattedNumber(element.price_online), element.price_online);
                html_ticket = html_ticket +
                    `<tr><td>` + element.name +
                    `</td><td>` + registerOnline.formattedNumber(parseInt(element.price_online)) + 'đ' +
                    `</td><td><input type="number" value="" id="input_` + element.id +
                    `" price_online="` + element.price_online +
                    `" oninput="totalMoney('` + element.id +
                    `')" placeholder="0" class="form-control input-sm input_count"> </td> <td> <span id="total_amount_` +
                    element.id +
                    `" >0 đ</span> </td> </tr>`;
            }
        });
        $('#tbody_data').empty();
        $('#tbody_data').append(html_ticket);
        $('#list_ticket_modal').modal('hide');

    }

    function totalMoney(key) {
        price_online = $('#input_' + key).attr('price_online');
        count = $('#input_' + key).val();
        if (count != '') {
            total = price_online * count;
            $('#total_amount_' + key).text(registerOnline.formattedNumber(total) + 'đ');

        } else {
            $('#total_amount_' + key).text('0đ');
        }
        statistical();
    }

    function statistical() {
        const input_count = document.querySelectorAll('.input_count');

        count = 0;
        total = 0;

        input_count.forEach(function(input) {
            const value = input.value;
            const priceOnline = input.getAttribute('price_online');
            var inputString = input.id;

            if (value != '') {
                count = count + parseInt(value);
                total = total + value * priceOnline;

                // var resultString = inputString.replace("input_", "");
                // ticket_data[resultString] = value;
            }
        });

        $('#count_all').text(count);
        $('#total_all').text(registerOnline.formattedNumber(total) + 'đ');

    }

    function saveInfoToLocalStorage(getInfo = false) {
        if (getInfo) {
            form_data = JSON.parse(localStorage.getItem("form_data"));
            $('#form_name').val(form_data.form_name);
            $('#form_gender').val(form_data.form_gender);
            $('#form_phone').val(form_data.form_phone);
            $('#form_mail').val(form_data.form_mail);
            $('#form_date').val(form_data.form_date);
            $('#form_address').val(form_data.form_address);
        } else {
            var form_data = {
                form_name: $('#form_name').val(),
                form_gender: $('#form_gender').val(),
                form_phone: $('#form_phone').val(),
                form_mail: $('#form_mail').val(),
                form_date: $('#form_date').val(),
                form_address: $('#form_address').val()
            }

            localStorage.setItem("form_data", JSON.stringify(form_data));
            // console.log(localStorage.getItem("form_data"));
        }

    }
    // localStorage.getItem("ticket_data")

    function payment() {


        var form_data = {
            form_name: $('#form_name').val(),
            form_gender: $('#form_gender').val(),
            form_phone: $('#form_phone').val(),
            form_mail: $('#form_mail').val(),
            form_date: $('#form_date').val(),
            form_address: $('#form_address').val()
        }
        var ticket_data = {};
        const input_count = document.querySelectorAll('.input_count');
        input_count.forEach(function(input) {
            const value = input.value;
            const priceOnline = input.getAttribute('price_online');
            var inputString = input.id.replace('input_', '');

            if (value != '') {
                ticket_data[inputString] = value;
            }
        });

        var alert = false;
        for (const key in form_data) {
            if (form_data[key] == '') {
                alert = true;
                registerOnline.alert_main('title', 'error', 'center');
            }
        }

        count_ticket = 0;
        for (const ticket_data_key in ticket_data) {
            count_ticket = count_ticket + parseInt(ticket_data[ticket_data_key]);
        }

        if (alert) {
            registerOnline.alert_main('Bạn cần phải điền đủ thông tin khách hàng!', 'error', 'center');
        } else {
            if (count_ticket == 0) {
                registerOnline.alert_main('Bạn cần đặt ít nhất 1 vé!', 'error', 'center');
            } else {
                registerOnline.show_loader();
                var data = {
                    ticket_data: ticket_data,
                    form_data: form_data,
                }
                $.ajax({
                    url: "{{ route('register_online.payment') }}",
                    type: 'get',
                    data: data,
                    success: function(result) {

                        console.log(result);

                        registerOnline.hide_loader();

                        //NẾU trạng thái 200 là tạo đơn hàng thành công =>đưa sang link thanh toán online của momo
                        if (result.status == 200) {

                            //   window.open(result.result_api, '_blank');
                            document.location = result.result_api;
                            //   $('#modalReport').modal('modal_payment_return_momo');
                            //   console.log(result.result_api)
                            //    window.location.replace(result.data_payment.url_success);
                            // window.location.replace(result.data_payment.url_error);
                        } else {
                            //lỗi hiển thị lỗi
                            registerOnline.alert_main(result.message, 'error', 'center', 4500);

                            //  registerOnline.alert_main(result.message, 'error', 'center');
                        }
                    }
                });
            }
        }
    }
</script>

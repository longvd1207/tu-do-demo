<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BẢO TÀNG VŨ TRỤ VIỆT NAM</title>
    <link rel="stylesheet" href="{{ url('ladipage/libs/dropzone/dropzone.min.css') }}">
    <link rel="stylesheet" href="{{ url('ladipage/css/style.css') }}">

    <link href="{{ url('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ url('assets//css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ url('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="{{ url('assets/css/custom.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
</head>

<body>
    <main>
        <!-- header -->

        <!-- end header-->
        <div class="main-wrap">
            <!-- landing form -->
            <div class="lading-form">
                <div class="ldform-banner">
                    <a href="{{url('')}}" class="img">
                        <img src="{{ url('ladipage/images/banner_cut.jpg') }}" alt="" class="img-fluid">
                    </a>
                </div>
                <div class="ldform-info">
                    <div class="container">
                        <h1 class="title title-color">
                            BẢO TÀNG VŨ TRỤ VIỆT NAM
                        </h1>
                        <br>
                        <h2 class="title text-center title-color">
                            TRANG BÁN VÉ THAM QUAN TRỰC TUYẾN BẢO TÀNG VŨ TRỤ VIỆT NAM
                        </h2>
                        <div class="list-info">
                            <ul>
                                <li>
                                    <p>
                                        Để phục vụ tốt nhất cho hoạt động truyền thông và lan tỏa dự án, vui lòng điền
                                        đầy đủ và cô đọng nhất những thông tin liên quan đến
                                        dự án của bạn. Sau khi thẩm định, chúng tôi sẽ thông báo tới bạn về việc dự án
                                        có đủ điều kiện tham gia giải thưởng trong vòng 03 ngày làm việc.
                                    </p>
                                </li>
                                <li>
                                    <p>
                                        Nếu hồ sơ đủ điều kiện tham gia giải thưởng, Bạn sẽ cần thanh toán Lệ phí tham
                                        gia là 30.000.000 VNĐ (Ba mươi triệu đồng) để hoàn thành thủ tục tham gia. Sau
                                        khi nghĩa vụ tài chính được thực hiện, dự án của bạn sẽ được xuất bản trên 1
                                        trang landing page được đặt trên website Human Act Prize và đường link dự án sẽ
                                        được gửi cho bạn qua email đăng ký trong hồ sơ này.
                                    </p>
                                </li>
                                {{-- <li>
                                    <p>
                                        Ngoài ra, bạn cũng sẽ có ngay 1 bài báo chí khai thác và kể chuyện về dự án, dựa
                                        trên tư liệu bạn cung cấp cho chúng tôi trong hồ sơ này. Vì vậy, hãy chắc chắn
                                        rằng thông tin bạn cung cấp trong hồ sơ này là chính xác và hấp dẫn nhất. Với
                                        những dự án có chất lượng tốt và truyền cảm hứng, hệ thống báo chí hợp tác của
                                        chúng tôi sẽ chủ động liên hệ với bạn theo số điện thoại đăng ký để phỏng vấn và
                                        khai thác sâu hơn. Chúng tôi mong muốn lan tỏa những giá trị bền vững và tốt đẹp
                                        mà doanh nghiệp / tổ chức của bạn đã đóng góp cho cộng đồng.
                                    </p>
                                </li> --}}
                            </ul>
                        </div>
                        <div class="event-info">
                            <div class="item">
                                <span class="txt">Thời gian mở cửa:</span>
                                <b>Từ 8h tới 18h30 hằng ngày</b>
                            </div>
                            {{-- <div class="item">
                                <span class="txt">Vé đa:</span>
                                <b>30,000,000 VND</b>
                            </div> --}}
                        </div>
                        <div class="line"></div>
                        <form action="">
                            <p class="text-center title-form title-color">
                                THÔNG TIN LIÊN HỆ
                            </p>
                            <div class="form-big-group">
                                <div class="layout-two-cols">
                                    <div class="box-content">
                                        <div class="wrap-content">
                                            <h3 class="title">
                                                Thông tin khách hàng
                                            </h3>
                                            <div class="form-row">
                                                <div class="col-lg-12 col-12">
                                                    <p><b>Họ và tên: </b>{{ $dataAll['customer']->name }}</p>
                                                </div>
                                                <div class="col-lg-6 col-12">
                                                    <p><b>Giới tính:
                                                        </b>{{ $dataAll['customer']->gender == 1 ? 'Nam' : ($dataAll['customer']->gender == 2 ? 'Nữ' : 'Khác') }}
                                                    </p>
                                                </div>
                                                <div class="col-lg-6 col-12">
                                                    <p><b>Số điện thoại:
                                                        </b>{{ $dataAll['customer']->phone }}</p>
                                                </div>
                                                <div class="col-lg-6 col-12">
                                                    <b>Ngày tham quan:
                                                        </b>{{\Carbon\Carbon::createFromFormat("Y-m-d H:i:s.u",@$dataAll['data'][0]->use_date)->format("d-m-Y")}};
                                                </div>
                                                <div class="col-lg-6 col-12">
                                                    <p><b>Email:
                                                        </b>{{ $dataAll['customer']->email }}</p>
                                                </div>
                                                <div class="col-12">
                                                    <p><b>Địa chỉ:
                                                        </b>{{ $dataAll['customer']->address }}</p>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-img">
                                        <a href="javascript:;" class="img">
                                            <img src="https://vcdn-sohoa.vnecdn.net/2021/01/11/ship-2-9566-1610330320.jpg"
                                                alt="" class="img-fluid">
                                        </a>
                                    </div>

                                </div>
                            </div>
                            <div id="ticket_form">
                                @if ($dataAll['type'] == 'ticket')
                                    <p class="text-center title-form title-color">
                                        DANH SÁCH VÉ ĐÃ MUA
                                    </p>
                                    <div class="form-big-group">
                                        <div class="layout-two-cols">
                                            <div class="box-content">
                                                <div class="wrap-content row">
                                                    <div class="col-12">
                                                        <table class="table">
                                                            <thead>
                                                                <th>Mã hóa đơn: <b
                                                                        style="color: red">{{ $dataAll['order']->code_order }}</b>
                                                                </th>
                                                                <th>
                                                                    Số lượng vé: {{ count($dataAll['data']) }} vé</th>
                                                                <th>Tổng tiền:
                                                                    {{ number_format($dataAll['order']->real_amount, 0, '.', '.') . 'đ' }}
                                                                </th>
                                                                <th style="color: green">Trạng thái: Đã thanh toán!</th>
                                                            </thead>
                                                        </table>
                                                    </div>
                                                    @foreach ($dataAll['data'] as $keyItem => $item)
                                                        <div class="col-4">
                                                            <div class="card">
                                                                <div class="card-body row">
                                                                    <div class="col-12">
                                                                        <h5 class="title-item">
                                                                            {{ $item->ticket_type_name }}
                                                                        </h5>
                                                                    </div>
                                                                    <div class="col-4" style="padding: 0px;">
                                                                        <img class="qr_code"
                                                                            src="{{ url($item->qr_code) }}"
                                                                            width="100%" alt="{{ @$item->code }}"
                                                                            name_ticket= "{{ $item->ticket_type_name }}">
                                                                    </div>
                                                                    <div class="col-8 collapsed"
                                                                        id="collapsed_{{ $keyItem }}">
                                                                        @foreach ($item->accessByArea as $accessByArea)
                                                                            <b>{{ @$accessByArea['area_name'] }}</b>
                                                                            @if (!empty($accessByArea['getServices']))
                                                                                <li>
                                                                                    <b>Dịch vụ: </b>
                                                                                    @foreach ($accessByArea['getServices'] as $keyServices => $getServices)
                                                                                        @if ($keyServices == count($accessByArea['getServices']) - 1)
                                                                                            {{ $getServices }}
                                                                                        @else
                                                                                            {{ $getServices . ', ' }}
                                                                                        @endif
                                                                                    @endforeach
                                                                                </li>
                                                                            @endif
                                                                            @if (!empty($accessByArea['getFunSpots']))
                                                                                <li>
                                                                                    <b>Điểm dịch vụ: </b>
                                                                                    @foreach ($accessByArea['getFunSpots'] as $keyFunSpots => $getFunSpots)
                                                                                        @if ($keyFunSpots == count($accessByArea['getFunSpots']) - 1)
                                                                                            {{ $getFunSpots }}
                                                                                        @else
                                                                                            {{ $getFunSpots . ', ' }}
                                                                                        @endif
                                                                                    @endforeach
                                                                                </li>
                                                                            @endif
                                                                        @endforeach
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <a href="javascript:void(0);"
                                                                            style="float: right;"
                                                                            class="btn btn-link link-success fw-medium d-none"
                                                                            onclick="collapsedText({{ $keyItem }})"
                                                                            id="collapsed_btn_{{ $keyItem }}">
                                                                            Mở rộng</a>
                                                                    </div>

                                                                    <div class="col-12">
                                                                        <hr>
                                                                        <b style="color: red">
                                                                            Mã vé
                                                                        </b>
                                                                        <b style="float: right;color: red">
                                                                            {{ @$item->code }}
                                                                        </b>
                                                                    </div>
                                                                    <div class="col-12" style="color: green">
                                                                        <b>
                                                                            Giá vé
                                                                        </b>
                                                                        <b style="float: right">
                                                                            {{ number_format($item->price, 0, '.', '.') . 'đ' }}
                                                                        </b>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    <div class="modal-footer">
                                                        <a href="javascript:void(0);"
                                                            class="btn btn-link link-primary fw-medium"
                                                            onclick="downloadQRCodeImages()"><i
                                                                class="ri-download-2-line me-1 align-middle"></i>
                                                            Tải mã QR code</a>
                                                        <a href="{{ route('register_online.sendEmail', $dataAll['order']->id) }}"
                                                            class="btn btn-link link-danger fw-medium"><i
                                                                class="ri-mail-send-line me-1 align-middle"></i>
                                                            Gửi vé tới email của bạn</a>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endif
                                @if ($dataAll['type'] == 'order')
                                    <p class="text-center title-form title-color">
                                        VÉ ĐÃ MUA
                                    </p>
                                    <div class="form-big-group">
                                        <div class="layout-two-cols">
                                            <div class="box-img">
                                                <a href="javascript:;" class="img">
                                                    <img src="https://vcdn-sohoa.vnecdn.net/2021/01/11/ship-2-9566-1610330320.jpg"
                                                        alt="" class="img-fluid">
                                                </a>
                                            </div>
                                            <div class="box-content">
                                                <div class="wrap-content">
                                                    <h3 class="title">
                                                        Thông tin vé mua
                                                    </h3>
                                                    <div class="form-row">
                                                        <div class="card">
                                                            {{-- @dd($dataAll['data']) --}}
                                                            <div class="card-body row">
                                                                <div class="col-4" style="padding: 0px;">
                                                                    <img class="qr_code"
                                                                        src="{{ url($dataAll['data']->qr_code) }}"
                                                                        width="100%"
                                                                        alt="{{ $dataAll['data']->code_order }}"
                                                                        name_ticket= "">
                                                                    <center>
                                                                        <b>Mã hóa đơn:</b>
                                                                        <b
                                                                            style="color: green">{{ $dataAll['data']->code_order }}</b>
                                                                    </center>
                                                                </div>
                                                                <div class="col-8">
                                                                    <h5 class="title-item">
                                                                        Danh sách vé đã mua
                                                                    </h5>
                                                                    <table class="table">
                                                                        <thead>
                                                                            <th>Tên vé</th>
                                                                            <th style="width: 20%; text-align: center">
                                                                                Số
                                                                                lượng</th>
                                                                            <th style="width: 30%;text-align: center">
                                                                                Giá/vé</th>
                                                                        </thead>
                                                                        <tbody id="tbody_data">
                                                                            @foreach ($dataAll['ticket'] as $key => $ticket)
                                                                                <tr>
                                                                                    <td><b>{{ $key }}</b></td>
                                                                                    <td style="text-align: center">
                                                                                        {{ count($ticket) }}
                                                                                    </td>
                                                                                    <td style="text-align: right">
                                                                                        {{ number_format($ticket[0]->price, 0, '.', '.') . 'đ' }}
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                        </tbody>
                                                                    </table>
                                                                </div>

                                                                <div class="col-12">
                                                                    <hr>
                                                                    <b style="color: red">
                                                                        Tổng tiền:
                                                                    </b>
                                                                    <b style="float: right;color: red">
                                                                        {{ number_format($dataAll['data']->real_amount, 0, '.', '.') . 'đ' }}
                                                                    </b>
                                                                </div>
                                                                <div class="col-12">
                                                                    <b style="color: green">
                                                                        Trạng thái
                                                                    </b>
                                                                    <b style="float: right;color: green">
                                                                        Đã thanh toán!
                                                                    </b>
                                                                </div>
                                                                <div class="col-12">
                                                                    <hr>
                                                                    <center>
                                                                        <p style="color: red">
                                                                            Quý khách vui lòng tới quầy giao dịch để lấy
                                                                            vé!
                                                                        </p>
                                                                    </center>
                                                                </div>

                                                                <div class="col-12">
                                                                    <center>
                                                                        <a href="javascript:void(0);"
                                                                            class="btn btn-link link-primary fw-medium"
                                                                            onclick="downloadQRCodeImages()"><i
                                                                                class="ri-download-2-line me-1 align-middle"></i>
                                                                            Tải mã QR code</a>
                                                                        <a href="{{ route('register_online.sendEmail', $dataAll['data']->id) }}"
                                                                            class="btn btn-link link-danger fw-medium"><i
                                                                                class="ri-mail-send-line me-1 align-middle"></i>
                                                                            Gửi vé tới email của bạn</a>
                                                                    </center>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- en landing form -->
        </div>
        {{-- @include('registerOnline.modal') --}}

        @foreach (config('layout_libary')['css'] as $item)
            <link rel="stylesheet" href=" {{ url($item) }}" type="text/css">
        @endforeach

        @foreach (config('layout_libary')['js'] as $item)
            <script src="{{ url($item) }}"></script>
        @endforeach

        <div id="overlay-loader-layout" class="overlay">
            <div class="loader"></div>
        </div>
    </main>
</body>
<style>
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 9999;
    }

    .loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -25px;
        margin-left: -25px;
        animation: spin 2s linear infinite;
    }

    .ldform-banner {
        position: relative;
    }

    .banner-text {
        font-family: 'Beaufort', san-serif;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        color: #fff;
        /* Màu chữ */
    }

    .title-item {
        font-size: 16px;
        font-weight: 700;
    }

    .card {
        background-color: #f7f7f7;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes rotation {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .card-body .collapsed {
        max-height: 110px;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }

    .card-body .expanded {
        max-height: auto;
    }
</style>
<script src="{{ url('assets/libs/flatpickrVn/flatpickr.min.js') }}"></script>
<script src="{{ url('assets/libs/flatpickrVn/langVn.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>

<script>
    flatpickr("#form_date", {
        enableTime: false,
        dateFormat: "d/m/Y",
        "locale": "vn"
    });

    $(document).ready(function() {
        checkTextHeight();
        // goToID('ticket_form');
    });

    goToID('ticket_form')


    function message() {
        @if (!empty($message))
            @if (session()->has('message'))
                registerOnline.alert_main("{{ session('message') }}", 'success', 'center');
            @else
                registerOnline.alert_main("{{ $message }}", 'success', 'center', 2000);
            @endif
        @endif
    }

    function goToID(id) {
        var targetElement = document.getElementById(id);
        // Check if the element exists
        if (targetElement) {
            // Scroll to the element
            targetElement.scrollIntoView({
                behavior: 'smooth'
            });
        }
        setTimeout(() => {
            message();
            callback();
        }, 900);
    }

    let registerOnline = {
        formattedNumber: function(numberToFormat) {
            const formattedNumber = numberToFormat.toLocaleString('vi-VN', {
                style: 'decimal'
            });
            return formattedNumber;
        },
        alert_main: function(title = '', icon = 'success', position = 'top-end', timer = 1500) {
            Swal.fire({
                position: position,
                icon: icon,
                title: title,
                showConfirmButton: false,
                timer: timer,
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

    function downloadQRCodeImages() {
        // Chọn tất cả các phần tử có class "qr_code"
        var qrCodeImages = document.querySelectorAll('.qr_code');

        // Lặp qua mảng và tải xuống từng ảnh
        qrCodeImages.forEach(function(img, index) {
            var fileName = img.alt || 'qr_code_image_' + index;
            downloadImage(img.src, fileName + '.png');
        });
    }

    function downloadImage(url, fileName) {
        var xhr = new XMLHttpRequest();
        xhr.responseType = 'blob';
        xhr.onload = function() {
            saveAs(xhr.response, fileName);
        };
        xhr.open('GET', url);
        xhr.send();
    }

    function collapsedText(key) {

        var hiddenText = document.getElementById('collapsed_' + key);

        // Kiểm tra có lớp 'collapsed' hay không để quyết định thu gọn hoặc mở rộng
        if (hiddenText.classList.contains('collapsed')) {
            hiddenText.classList.remove('collapsed');
            hiddenText.classList.add('expanded');

            $('#collapsed_btn_' + key).text('Thu gọn');
        } else {
            hiddenText.classList.remove('expanded');
            hiddenText.classList.add('collapsed');

            $('#collapsed_btn_' + key).text('Mở rộng');
        }
    }

    function checkTextHeight() {
        max_height = 110;
        var collapsedElements = document.querySelectorAll('.collapsed');
        collapsedElements.forEach(function(element) {
            // collapsedIds.push(element.id);
            id = element.id;
            key = id.replaceAll('collapsed_', '');
            var collapsedElement = document.getElementById(id);

            var height = collapsedElement.offsetHeight;
            if (height < max_height) {
                $('#collapsed_btn_' + key).addClass('d-none');
            } else {
                $('#collapsed_btn_' + key).removeClass('d-none');
            }
        });
    }
</script>

</html>

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
                    <a href="javascript:;" class="img">
                        <img src="{{ url('ladipage/images/banner_cut.jpg') }}" alt="" class="img-fluid">
                    </a>
                </div>
                <div class="ldform-info">
                    <div class="container">
                        <h1 class="title title-color">
                            BẢO TÀNG VŨ TRỤ VIỆT NAM
                        </h1>
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
                        <div class="row">
                            <div class="col-4">
                                <p style="color: #fff">
                                    Tra cứu thông tin đơn hàng <br>
                                    <i style="font-size: 13px">( mã đơn hàng đã được đính kèm trong thành toán của bạn!
                                        )</i>
                                </p>
                                <div class="input-group">
                                    {{--                                <form method="post" action="{{route('register_online.payment_result')}}"> --}}
                                    @csrf
                                    <?php echo e(method_field('POST')); ?>
                                    <input type="text" class="form-control" placeholder="Nhập mã đơn hàng"
                                        name="code_order" required id="code_order">
                                    <button type="button" class="btn btn-success btn-label right input-group-btn"
                                        onclick="check_order(document.getElementById('code_order').value)"><i
                                            class="ri-search-eye-line label-icon align-middle fs-16 ms-2"></i>
                                        Tra cứu
                                    </button>
                                    {{--                                </form> --}}
                                </div>
                            </div>
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
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="form_name"
                                                            placeholder="Họ và tên*" oninput="saveInfoToLocalStorage()">
                                                        <span class="error__note"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-12">
                                                    <div class="form-group">
                                                        <div class="custom-select-option">
                                                            <select class="form-select" id="form_gender"
                                                                onchange="saveInfoToLocalStorage()"
                                                                aria-label="Default select example">
                                                                <option selected disabled> Giới tính*</option>
                                                                <option value="1">Nam</option>
                                                                <option value="2">Nữ</option>
                                                                <option value="3">Khác</option>
                                                            </select>
                                                        </div>
                                                        <span class="error__note"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id='form_phone'
                                                            oninput="saveInfoToLocalStorage()"
                                                            placeholder="Số điện thoại*">
                                                        <span class="error__note"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"
                                                            placeholder="Ngày tham quan*" id="form_date"
                                                            oninput="saveInfoToLocalStorage()">
                                                        <span class="error__note"></span>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6 col-12">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"
                                                            placeholder="Email*" id="form_mail"
                                                            oninput="saveInfoToLocalStorage()">
                                                        <span class="error__note"></span>
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="form_address"
                                                            oninput="saveInfoToLocalStorage()" placeholder="Địa chỉ*">
                                                        <span class="error__note"></span>
                                                    </div>
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

                            <p class="text-center title-form title-color">
                                THÔNG TIN VÉ
                            </p>
                            <div class="form-big-group">
                                <div class="layout-two-cols">

                                    {{-- <div class="box-img">
                                        <a href="javascript:;" class="img">
                                            <img src="https://vcdn-sohoa.vnecdn.net/2021/01/11/ship-2-9566-1610330320.jpg"
                                                alt="" class="img-fluid">
                                        </a>
                                    </div> --}}
                                    <div class="box-content border-end">
                                        <div class="wrap-content">
                                            <h3 class="title" style="margin-bottom: 0px;">
                                                Thông tin vé
                                            </h3>
                                            {{-- <a href="#" class="text-left" style="color: #b69325;"
                                            data-bs-toggle="modal" data-bs-target="#list_ticket_modal">Xem và
                                            chọn danh sách vé tại đây!</a> --}}
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Danh sách vé đã chọn</h5>
                                                    <a href="#" class="text-left" style="color: #b69325;"
                                                        data-bs-toggle="modal" data-bs-target="#list_ticket_modal">Xem
                                                        và
                                                        chọn danh sách vé tại đây!</a>
                                                </div>
                                                <div class="modal-body">
                                                    {{-- <div class="col-12">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control"
                                                            placeholder="Email*" id="form_mail"
                                                            oninput="saveInfoToLocalStorage()">
                                                        <span class="error__note"></span>
                                                    </div>
                                                </div> --}}
                                                    <table class="table">
                                                        <thead>
                                                            <th>Tên loại vé</th>
                                                            <th>Giá/vé</th>
                                                            <th style="width: 20%">Số lượng</th>
                                                            <th style="width: 20%">Tổng</th>
                                                        </thead>
                                                        <tbody id="tbody_data">
                                                        </tbody>
                                                        <tr>
                                                            <th colspan="2">
                                                                <b>Tổng tiền</b>
                                                            </th>
                                                            <th id="count_all">
                                                                0
                                                            </th>
                                                            <th id="total_all">
                                                                0đ
                                                            </th>
                                                        </tr>
                                                    </table>
                                                    <a style="float: right" href="javascript:void(0);"
                                                        class="btn btn-link link-danger fw-medium"
                                                        data-bs-toggle="modal" data-bs-target="#terms_content"><i
                                                            class="ri-alert-line me-1 align-middle"></i> Điều khoản
                                                        và
                                                        phương thức thanh toán</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="box-content">
                                        <div class="wrap-content">
                                            <h3 class="title">
                                                Phương thức thanh toán
                                            </h3>
                                            <div class="card">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-11 no_margin">
                                                            <div class="form-check form-switch form-switch-success"
                                                                style="margin-bottom: 10px;">
                                                                
                                                                <img width="15%"
                                                                    src="{{ url('ladipage/images/primary-momo-ddd662b09e94d85fac69e24431f87365.png') }}"
                                                                    alt="" rounded avatar-xl>
                                                                <b class="form-check-label"
                                                                    style="font-size: 16px;margin-left: 10px">Thanh toán bằng ví
                                                                    MoMo</b>
                                                            </div>
                                                        </div>
                                                        <div class="col-1 no_margin" style="align-self: center;">
                                                            <input type="radio" name="" checked>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @csrf
                            <p class="text-center">
                                <button type="button" class="btn-submit" onclick="payment()">
                                    Xác nhận thông tin và thanh toán!
                                </button>
                            </p>
                        </form>
                    </div>
                    <!-- static bg -->
                    <div class="static-bg static-bg-first">
                        <img src="images/static-bg-1.svg" alt="" class="img-fluid">
                    </div>
                    <div class="static-bg static-bg-second">
                        <img src="images/static-bg-2.svg" alt="" class="img-fluid">
                    </div>
                    <div class="static-bg static-bg-third">
                        <img src="images/static-bg-3.svg" alt="" class="img-fluid">
                    </div>
                    <div class="static-bg static-bg-fourth">
                        <img src="images/static-bg-4.svg" alt="" class="img-fluid">
                    </div>
                    <!-- end static bg-->
                </div>
            </div>
            <!-- en landing form -->
        </div>
        @include('registerOnline.modal')

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
</style>
<script src="{{ url('assets/libs/flatpickrVn/flatpickr.min.js') }}"></script>
<script src="{{ url('assets/libs/flatpickrVn/langVn.js') }}"></script>
<script>
    const today = new Date();
    flatpickr("#form_date", {
        enableTime: false,
        dateFormat: "d/m/Y",
        "locale": "vn",
        minDate: today,
    });
</script>

<script>
    @if (!empty(session('alert-success')))
        // sweetSuccess('{{ session('alert-success') }}');
        Swal.fire({
            position: 'top-end',
            icon: 'success',
            title: "{{ session('alert-success') }}",
            showConfirmButton: false,
            timer: 1500,
            showCloseButton: false
        });
    @endif

    @if (!empty(session('alert-error')))
        Swal.fire({
            position: 'center',
            icon: 'error',
            title: "{{ session('alert-error') }}",
            showConfirmButton: false,
            timer: 1500,
            showCloseButton: false
        });
    @endif
</script>

<!-- gọi check trạng thái đơn hàng momo -->
<script>
    function check_order(code_order) {
        registerOnline.show_loader();
        let data = {
            code_order: code_order
        };
        $.ajax({
            url: "{{ route('register_online.payment_search') }}",
            type: 'POST',
            data: data,
            beforeSend: function(request) {
                request.setRequestHeader("X-CSRF-TOKEN", '{{ csrf_token() }}')
            },
            success: function(result) {

                //   console.log();

                if (result.status == 200) {

                    //hoá đơn đã sang momo: có thể đã thanh toán hoặc thế nào đấy
                    document.location = result.url_redirect;
                    //    document.location="https://dantri.com.vn";
                    registerOnline.hide_loader();
                } else if (result.status == 90) {
                    //mã đơn hàng ko tồn tại
                    // Swal.fire({
                    //     position: 'center',
                    //     icon: 'error',
                    //     title: 'Lỗi...!',
                    //     text: result.error ,
                    //     showConfirmButton: false,
                    //     timer: 4500,
                    //     showCloseButton: true
                    // });
                    registerOnline.hide_loader();
                    registerOnline.alert_main(result.error, 'error', 'center', 4500);

                } else {
                    console.log("server trả về trạng thái chưa định nghĩa !!!");
                }

            }
        });


    }
</script>
<!-- gọi check trạng thái đơn hàng momo -->

</html>

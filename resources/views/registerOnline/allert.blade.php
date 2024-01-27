<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Đăng ký tham gia giải bơi</title>
        <link
            rel="shortcut icon"
            href="{{ url('public/assets/images/logo-light.png') }}"
        />
        <link
            rel="stylesheet"
            href="{{ asset('assets/ladipage_libs/libs/dropzone/dropzone.min.css') }}"
        />
        <link
            rel="stylesheet"
            href="{{ asset('assets/ladipage_libs/css/style.css') }}"
        />
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css"
            integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm"
            crossorigin="anonymous"
        />
        <link rel="stylesheet" href="{{ asset('assets/css/icons.min.css') }}" />
        <script src="{{ asset('assets/libs/feather-icons/feather-icons.min.js') }}"></script>
        <script src="{{ asset('assets/js/pages/plugins/lord-icon-2.1.0.min.js') }}"></script>
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
                            <img
                                src="{{ asset('assets/ladipage_libs/images/banner01.jpg') }}"
                                alt=""
                                class="img-fluid"
                            />
                        </a>
                    </div>
                    <div class="ldform-info">
                        <div class="container">
                            <h1 class="title">GIẢI BƠI ĐÃ HẾT HẠN ĐĂNG KÝ!</h1>
                            <div class="event-info">
                                <div class="item">
                                    <span class="txt"
                                        >Thời gian nhận hồ sơ:</span
                                    >
                                    <b>15/12/2022 - 20/03/2023</b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- en landing form -->
            </div>
        </main>
        <style>
            .ldform-info .event-info .item {
                background: #14539a;
            }

            .title-form {
                color: #14539a;
            }

            .btn_search {
                background: #14539a;
                color: #ffffff;
            }

            .btn_search:hover {
                /* Màu nền khi hover vào */
                background-color: #122085;
            }

            .image-container {
                position: relative;
                display: inline-block;
            }

            .button-wrapper {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                opacity: 0;
                visibility: hidden;
                transition: opacity 0.3s ease, visibility 0.3s ease;
            }

            .image-container:hover .button-wrapper {
                opacity: 1;
                visibility: visible;
            }

            .button {
                background-color: #ffffff00;
                display: inline-block;
                color: #14539a;
                border: none;
                cursor: pointer;
                margin: 5px;
                font-size: 70px;
            }

            .button:hover {
                background-color: #ffffff00;
                color: white;
                /* border: none; */
            }

            .form-big-group .layout-two-cols .img {
                width: 550px;
            }

            .text-overlay {
                position: absolute;
                bottom: 0;
                left: 0;
                width: 100%;
                color: red;
                padding: 5px;
                text-align: center;
            }

            /* The switch - the box around the slider */
            .switch {
                position: relative;
                display: inline-block;
                width: 43px;
                height: 17px;
                float: left;
                margin-right: 20px;
                padding-top: 2px;
            }

            /* Hide default HTML checkbox */
            .switch input {
                display: none;
            }

            /* The slider */
            .slider {
                position: absolute;
                cursor: pointer;
                top: 2px;
                left: 0;
                right: 0;
                bottom: -2px;
                background-color: #ccc;
                -webkit-transition: 0.4s;
                transition: 0.4s;
            }

            .slider:before {
                position: absolute;
                content: "";
                height: 13px;
                width: 13px;
                left: 2px;
                bottom: 2px;
                background-color: white;
                -webkit-transition: 0.4s;
                transition: 0.4s;
            }

            input.primary:checked + .slider {
                background-color: #2196f3;
            }

            input:checked + .slider:before {
                -webkit-transform: translateX(26px);
                -ms-transform: translateX(26px);
                transform: translateX(26px);
            }

            /* Rounded sliders */
            .slider.round {
                border-radius: 34px;
            }

            .slider.round:before {
                border-radius: 50%;
            }

            .title {
                margin-bottom: 40px;
            }

            .ldform-footer {
                background: #14539a;
            }
        </style>
    </body>
</html>

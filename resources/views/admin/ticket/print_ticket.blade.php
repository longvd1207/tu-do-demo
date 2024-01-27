<!DOCTYPE html>

<html lang="en" xmlns="http://www.w3.org/1999/xhtml">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<head>
    <meta charset="utf-8"/>
    <title></title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <style>
        /* @page {
            size: A4 landscape;
        } */

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        table {
            border-collapse: collapse;
        }

        @media print {

            /* Ẩn tiêu đề */
            title {
                display: none;
            }

            /* Ẩn số trang */
            .page-number::before {
                content: none !important;
            }

            /* Ẩn đường dẫn */
            .url::after {
                content: none !important;
            }
        }

        /* @media print {
            @page {
                size: landscape
            }
        } */
    </style>

</head>
<body>
<center>
    <section>
        <table>
            <!-- TIEU DE -->
            <tr>
                <td>
                    <div style="text-align: center;">
                        <p style="font-size: 20px;">Bảo tàng vũ trụ</p>
                    </div>
                </td>
            </tr>

            <tr>
                <td>
                    <table class="Title" , border="0" style="width: 100%; margin-top: 30px;">
                        {{-- QR CODE--}}
                        <tr>
                            <td>
                                <center>
                                    {{ $qrCode }}
                                </center>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <center>
                                    <p style="font-size: 18px;"><b>{{ $data->ticket_type_name }}</b>
                                    </p>
                                    <h2 style="font-size: 14px; margin-bottom: 15px">
                                        Ngày {{ \Carbon\Carbon::parse($data->use_date)->format(' d/m/Y') }}
                                    </h2>
                                </center>
                            </td>
                        </tr>
                        <tr>
                            <td style="display: flex">
                                <div style="font-size: 14px">
                                    @if($access['area'])
                                        <p style="padding: 2px">
                                            <b>Khu vực: </b>
                                            {{ implode(', ', $access['area']) }}
                                        </p>
                                    @endif
                                    @if($access['service'])
                                        <p style="padding: 2px">
                                            <b>Dịch vụ : </b>
                                            {{ implode(', ', $access['service']) }}
                                        </p>
                                    @endif
                                    @if($access['fun_spots'])
                                        <p style="padding: 2px">
                                            <b>Điểm vui chơi: </b>
                                            {{ implode(', ', $access['fun_spots']) }}
                                        </p>
                                    @endif
                                </div>

                            </td>
                        </tr>


                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; padding: 10px 0 10px 0; font-size: 14px;">
                                    <p>Mã vé:</p>
                                    <p style="margin-left: auto;"><b>{{ $data->code }}</b></p>
                                </div>
                                <hr>


                                <div style="display: flex; align-items: center; padding: 20px 0 20px 0; font-size: 16px;">
                                    <p>Giá vé:</p>
                                    <p style="margin-left: auto;">
                                        <b>{{ number_format($data->price, 0, ',', '.') . 'đ' }}</b></p>
                                </div>
                            </td>
                        </tr>

                    </table>


                    <table border="0" style="width: 100%">

                        <tr>
                            <td>
                                <center>
                                    <p style="font-size: 12px;">HOTLINE: 19001009
                                    </p>
                                </center>
                            </td>
                        </tr>

                        <tr>
                            <td>
                                <div style="text-align: center;">
                                    <p style="font-size: 12px;">Số 11 Nguyễn Công Trứ, Hoàn Kiếm, Hà Nội
                                    </p>
                                </div>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>
        </table>

    </section>
</center>


</body>

</html>

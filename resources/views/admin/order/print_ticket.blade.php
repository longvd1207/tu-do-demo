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

@foreach ($order->tickets as $ticket)
    <body>
    <center>
        <section >
            <table >
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
                        <table class="Tittle" , border="0" style="width: 100%;margin-top: 30px;">
                            <tr>
                                <td>
                                    <center>
                                        <div>
                                            {{ \SimpleSoftwareIO\QrCode\Facades\QrCode::size(100)->generate($ticket->code) }}
                                        </div>
                                    </center>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <center>
                                        <p style="font-size: 25px;"><b>{{ $ticket->ticket_type_name }}</b>
                                        </p>
                                        <h2 style="margin-top: 25px; font-size: 20px; margin-bottom: 25px">
                                            Ngày {{ \Carbon\Carbon::parse($ticket->use_date)->format(' d/m/Y') }}
                                        </h2>
                                    </center>
                                </td>
                            </tr>
                            <tr>
                                <td style="display: flex">
                                    @if($ticket->maps)
                                        @php
                                            $areas = [];
                                            $services = [];
                                            $fun_spots = [];
                                        @endphp
                                        @foreach ($ticket->maps as $map)
                                            @if($map->type == 1)
                                                @php
                                                    $areas[] = $map->getAreas->name ?? '';
                                                @endphp
                                            @endif
                                            @if($map->type == 2)
                                                @php
                                                    $services[] = $map->getServices->name ?? '';
                                                @endphp
                                            @endif

                                            @if($map->type == 3)
                                                @php
                                                    $fun_spots[] = $map->getFunSpots->name ?? '';
                                                @endphp
                                            @endif
                                        @endforeach
                                    @endif


                                    <div style="font-size: 14px">
                                        @if($areas)
                                            <p style="padding: 2px;"><b>Khu vực: </b>
                                                {{ implode(', ', $areas)  }}
                                            </p>
                                        @endif

                                        @if($services)
                                            <p style="padding: 2px;">
                                                <b>Dịch vụ : </b>
                                                {{ implode(', ', $services) }}
                                            </p>
                                        @endif

                                        @if($fun_spots)
                                            <p style="padding: 2px;">
                                                <b>Điểm vui chơi: </b>
                                                {{ implode(', ', $fun_spots) }}
                                            </p>
                                        @endif
                                    </div>

                                </td>
                            </tr>


                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center; padding: 10px 0 10px 0; font-size: 14px;">
                                        <p>Mã vé:</p>
                                        <p style="margin-left: auto;"><b>{{ $ticket->code }}</b></p>
                                    </div>
                                    <hr>


                                    <div style="display: flex; align-items: center; padding: 20px 0 20px 0; font-size: 16px;">
                                        <p>Giá vé:</p>
                                        <p style="margin-left: auto;">
                                            <b>{{ number_format($ticket->price, 0, ',', '.') . 'đ' }}</b></p>
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
@endforeach

</html>

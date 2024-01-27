<!DOCTYPE html>
<html lang="en">

<head>
    <style>
    </style>
</head>

<body style="width: ">
    <main>
        <div class="main-wrap">
            <div class="lading-form">
                <div class="ldform-info">
                    <div class="container">
                        <div class="form-big-group">
                            <div class="layout-two-cols">
                                <div class="box-content">
                                    <div class="wrap-content">
                                        <div class="card">
                                            <div class="card-body">
                                                <h3 class="title">Thông tin thanh toán</h3>
                                                <table class="table" style="text-align: left">
                                                    <thead>
                                                        <th>Mã hóa đơn: <b
                                                                style="color: red">{{ $order->code_order }}</b>
                                                        </th>
                                                    </thead>
                                                    <thead>
                                                        <th>
                                                            Số lượng vé: {{ count($ticket) }} vé</th>
                                                    </thead>
                                                    <thead>
                                                        <th>Tổng tiền:
                                                            {{ number_format($order->real_amount, 0, '.', '.') . 'đ' }}
                                                        </th>
                                                    </thead>
                                                    <thead style="color: blue">
                                                        <th>Phương thức thanh toán:
                                                            Thanh toán online
                                                        </th>
                                                    </thead>
                                                    <thead>
                                                        <th style="color: green">Trạng thái: Đã thanh toán!</th>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-big-group">
                                            <div class="layout-two-cols">
                                                <div class="box-content" style="background-color: #fff;">
                                                    <div class="wrap-content">
                                                        <h3 class="title">
                                                            Thông tin khách hàng
                                                        </h3>
                                                        <div class="form-row">
                                                            <div class="col-lg-12 col-12">
                                                                <p><b>Họ và tên: </b>{{ $customer->name }}
                                                                </p>
                                                            </div>
                                                            <div class="col-lg-6 col-12">
                                                                <p><b>Giới tính:
                                                                    </b>{{ $customer->gender == 1 ? 'Nam' : ($customer->gender == 2 ? 'Nữ' : 'Khác') }}
                                                                </p>
                                                            </div>
                                                            <div class="col-lg-6 col-12">
                                                                <p><b>Số điện thoại:
                                                                    </b>{{ $customer->phone }}</p>
                                                            </div>
                                                            <div class="col-lg-6 col-12">
                                                                <p><b>Ngày tham quan:
                                                                    </b>{{ date('d/m/Y') }}</p>
                                                            </div>
                                                            <div class="col-lg-6 col-12">
                                                                <p><b>Email:
                                                                    </b>{{ $customer->email }}</p>
                                                            </div>
                                                            <div class="col-12">
                                                                <p><b>Địa chỉ:
                                                                    </b>{{ $customer->address }}</p>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div style="padding: 5px;background-color: #ffffff8c;border-radius: 8px;">
                                            <h3 class="title">
                                                Thông tin vé đã mua
                                            </h3>
                                            @foreach ($ticket as $item)
                                                <div class="card">
                                                    <div class="card-body row">
                                                        <div class="col-12">
                                                            <h5 class="title-item">
                                                                {{ $item->ticket_type_name }}
                                                            </h5>
                                                        </div>
                                                        <div class="col-4" style="padding: 0px;">
                                                            <img class="qr_code" src="{{ url($item->qr_code) }}"
                                                                width="100%" alt="{{ @$item->code }}"
                                                                name_ticket= "{{ $item->ticket_type_name }}">
                                                        </div>
                                                        <div class="col-8">
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
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
<style>
    /* .form-big-group .layout-two-cols {
        background: #ffffff00;
    } */

    .lading-form {
        background-image: url("{{ url('ladipage/images/94179.jpg') }}");
        background-size: 100% auto;
        background-repeat: repeat;
        background-position: center center;
    }

    .card {
        background-color: #fff;
        width: 70%;
    }
</style>

<table class="table-bordered table-responsive">
    <thead>

    <tr>
        <td colspan="5" align="left" valign="middle" style="font-size:12px; text-align: center">
            <p><b>Bảo tàng vũ trụ</b></p>
        </td>
    </tr>

    <tr>
        <td colspan="5" align="center" valign="middle" style="font-size:16px"><b>BÁO CÁO TỔNG HỢP VÉ</b>
        </td>
    </tr>

    <tr>
        <td colspan="5" align="center" valign="middle"><i>Ngày báo cáo : {{date("G:i d-m-Y")}}</i></td>
    </tr>


    <tr>
        <td colspan="5" align="left" valign="middle"></td>
    </tr>
    <tr>
        <td colspan="5" align="left" valign="middle"></td>
    </tr>
    <tr>
        <td colspan="5" align="left" valign="middle"></td>
    </tr>

    <tr>
        <td align="center" valign="middle" style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">STT</td>
        <td align="center" valign="middle" style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Mã hoá đơn</td>
        <td align="center" valign="middle" style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Tiền thực</td>
        <td align="center" valign="middle" style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Tổng tiền</td>
        <td align="center" valign="middle" style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Ngày bán</td>
        <td align="center" valign="middle" style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Trạng thái thanh toán</td>
        <td align="center" valign="middle" style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Kiểu thanh toán</td>
        <td align="center" valign="middle" style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Người bán</td>
    </tr>

    </thead>
    <tbody>
    @foreach($data as $key => $order)
        <tr>
            <td style="text-align: center; white-space: normal; vertical-align: middle;">{{$key + 1}}</td>
            <td style="white-space: normal; vertical-align: middle;">{{ $order->code_order }} </td>
            <td style="text-align: right; white-space: normal; vertical-align: middle;">{{ number_format($order->real_amount ?? '', 0, '.', '.') . 'đ'}}</td>
            <td style="text-align: right; white-space: normal; vertical-align: middle;">{{ number_format($order->amount ?? '', 0, '.', '.') . 'đ'}}</td>
            <td style="white-space: normal; vertical-align: middle;">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
            <td style="white-space: normal; vertical-align: middle;">
                @if($order->payment_status == 1)
                    {{ 'Chưa thanh toán' }}
                @elseif($order->payment_status == 2)
                    {{ 'Đã thanh toán' }}
                @elseif($order->payment_status == 3)
                    {{ 'Đã huỷ' }}
                @endif
            </td>
            <td class="text-center">{{ $order->type == 1 ? 'Trực tiếp' : 'Trực tuyến' }}</td>
            <td style="white-space: normal; vertical-align: middle;">{{ $order->user->user_name ?? '' }}</td>
        </tr>
    @endforeach

    </tbody>
</table>


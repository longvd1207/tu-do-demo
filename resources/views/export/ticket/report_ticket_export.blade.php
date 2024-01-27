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
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">STT
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Mã vé
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Mã hoá đơn
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Tên loại vé
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Ngày sử dụng
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Giá vé
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Trạng thái
        </td>
    </tr>

    </thead>
    <tbody>
    @foreach($data as $key => $ticket)
        <tr>
            <td style="text-align: center; white-space: normal; vertical-align: middle;">{{$key + 1}}</td>
            <td style="text-align: center; white-space: normal; vertical-align: middle;">{{ $ticket->code }} </td>
            <td style="text-align: center; white-space: normal; vertical-align: middle;">{{ $ticket->order->code_order ?? '' }}</td>
            <td style="white-space: normal; vertical-align: middle;">{{ $ticket->ticket_type_name }}</td>
            <td style="white-space: normal; vertical-align: middle;">{{ \Carbon\Carbon::parse($ticket->use_date)->format('d/m/Y') }}</td>
            <td style="text-align: right; white-space: normal; vertical-align: middle;">{{ number_format($ticket->price ?? '', 0, '.', '.') . 'đ'}}</td>
            <td style="white-space: normal; vertical-align: middle;">{{ $ticket->status == 1 ? 'Hoạt động' : 'Khoá'}}</td>
        </tr>
    @endforeach

    </tbody>
</table>


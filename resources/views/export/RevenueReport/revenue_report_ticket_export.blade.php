<table class="table-bordered table-responsive">
    <thead>

    <tr>
        <td colspan="6" align="left" valign="middle" style="font-size:12px; text-align: center">
            <p><b>Bảo tàng vũ trụ</b></p>
        </td>
    </tr>

    <tr>
        <td colspan="6" align="center" valign="middle" style="font-size:16px"><b>BÁO CÁO DANH THU THEO LOẠI VÉ</b>
        </td>
    </tr>

    <tr>
        <td colspan="6" align="center" valign="middle"><i>Ngày báo cáo : {{date("G:i d-m-Y")}}</i></td>
    </tr>


    <tr>
        <td colspan="6" align="left" valign="middle"></td>
    </tr>

    <tr>
        <td colspan="1" align="center" valign="middle"></td>
        <td colspan="1" align="center" valign="middle">Từ ngày</td>
        <td colspan="1" align="center"
            valign="middle">{{substr(session('search.start_date'),8,2)."-".substr(session('search.start_date'),5,2)."-".substr(session('search.start_date'),0,4)}}</td>
        <td colspan="1" align="center" valign="middle">Đến ngày</td>
        <td colspan="1" align="center"
            valign="middle">{{substr(session('search.end_date'),8,2)."-".substr(session('search.end_date'),5,2)."-".substr(session('search.end_date'),0,4)}}</td>
    </tr>


    <tr>
        <td colspan="6" align="left" valign="middle"></td>
    </tr>


    <tr>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">STT
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Tên vé
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Số lượng (vé)
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Giá online (đ)
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Giá offline (đ)
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Thành tiền (đ)
        </td>

    </tr>

    </thead>
    <tbody>
    @isset($data)
        @php
            $index = 0;
            $totalOfTicket = 0;
            $countOfTicket = 0;
        @endphp

        @foreach ($data as $keyTicket => $value)

            @php
                $index++;
                foreach (session('search.ticketType') as $val) {
                    if ($val->id == $keyTicket) {
                        $thisTicketType = $val;
                        break;
                    }
                }
            @endphp
            <tr>
                <td style="text-align: center; white-space: normal; vertical-align: middle;">{{ $index }}</td>
                <td style="text-align: center; white-space: normal; vertical-align: middle;">{{$thisTicketType->name }}</td>
                <td style="text-align: center; white-space: normal; vertical-align: middle;">{{ count($value) }}</td>
                <td style="text-align: right; white-space: normal; vertical-align: middle;">{{ number_format($thisTicketType->price_online, 0, '.', ',') }}</td>
                <td style="text-align: right; white-space: normal; vertical-align: middle;">{{ number_format($thisTicketType->price_offline, 0, '.', ',') }}</td>
                <td style="text-align: right; white-space: normal; vertical-align: middle;">{{ number_format($value->sum('price'), 0, '.', ',') }}</td>
            </tr>
            @php
                $totalOfTicket = $totalOfTicket + $value->sum('price');
                $countOfTicket = $countOfTicket + count($value);
            @endphp
        @endforeach
        <tr>
            <td colspan="2" style="text-align: center; white-space: normal; vertical-align: middle;" >
                <b>Tổng</b>
            </td>
            <td style="text-align: center; white-space: normal; vertical-align: middle;">
                <b>{{ $countOfTicket }}</b>
            </td>
            <td colspan="3" style="text-align: right; white-space: normal; vertical-align: middle;">
                <b>{{ number_format($totalOfTicket, 0, '.', ',')  }}</b>
            </td>
        </tr>
        <tr style="background-color: rgb(0, 85, 0);color:#fff">
            <td colspan="2" style="text-align: center; white-space: normal; vertical-align: middle;">
                <b>Tổng tiền thực thu</b>
            </td>
            <td colspan="4" style="text-align: right; white-space: normal; vertical-align: middle;">
                <b>{{ number_format(session('search.total'), 0, '.', ',')  }}</b>
            </td>
        </tr>
    @endisset

    </tbody>
</table>


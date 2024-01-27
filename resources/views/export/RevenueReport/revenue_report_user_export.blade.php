<table class="table-bordered table-responsive">
    <thead>

    <tr>
        <td colspan="5" align="left" valign="middle" style="font-size:12px; text-align: center">
            <p><b>Bảo tàng vũ trụ</b></p>
        </td>
    </tr>

    <tr>
        <td colspan="5" align="center" valign="middle" style="font-size:16px"><b>BÁO CÁO DANH THU THEO NGƯỜI BÁN</b>
        </td>
    </tr>

    <tr>
        <td colspan="5" align="center" valign="middle"><i>Ngày báo cáo : {{date("G:i d-m-Y")}}</i></td>
    </tr>


    <tr>
        <td colspan="5" align="left" valign="middle"></td>
    </tr>

    <tr>
        <td colspan="1" align="center" valign="middle"></td>
        <td colspan="1" align="center" valign="middle">Từ ngày</td>
        <td colspan="1" align="center" valign="middle">{{substr(session('search.start_date'),8,2)."-".substr(session('search.start_date'),5,2)."-".substr(session('search.start_date'),0,4)}}</td>
        <td colspan="1" align="center" valign="middle">Đến ngày</td>
        <td colspan="1" align="center" valign="middle">{{substr(session('search.end_date'),8,2)."-".substr(session('search.end_date'),5,2)."-".substr(session('search.end_date'),0,4)}}</td>
    </tr>


    <tr>
        <td colspan="5" align="left" valign="middle"></td>
    </tr>


    <tr>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">STT
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Người bán
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Số vé bán được (vé)
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Tổng tiền (đ)
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Thực thu (đ)
        </td>

    </tr>

    </thead>
    <tbody >
    @php
        $sumAmount = 0;
        $sumRealAmount = 0;
        $sumQty = 0
    @endphp

    @isset($data)
        @foreach($data as $key => $user)

            @php
                $realAmount = $user->orders->when(session('search.startOfMonth'), fn ($query) => $query->where('created_at', '>=', date(session('search.startOfMonth')).' 00:00:00'))
                                           ->when(session('search.endOfMonth'), fn ($query) => $query->where('created_at', '<=', date(session('search.endOfMonth')).' 23:59:59'))
                                           ->where('payment_status', 2)
                                           ->sum('real_amount');

                $amount = $user->orders->when(session('search.startOfMonth'), fn ($query) => $query->where('created_at', '>=', date(session('search.startOfMonth')).' 00:00:00'))
                                       ->when(session('search.endOfMonth'), fn ($query) => $query->where('created_at', '<=', date(session('search.endOfMonth')).' 23:59:59'))
                                       ->where('payment_status', 2)
                                       ->sum('amount');

                $quantity = $user->orders->flatMap->tickets
                                    ->when(session('search.startOfMonth'), fn ($query) => $query->where('created_at', '>=', date(session('search.startOfMonth')).' 00:00:00'))
                                    ->when(session('search.endOfMonth'), fn ($query) => $query->where('created_at', '<=', date(session('search.endOfMonth')).' 23:59:59'))
                                    ->count();
                $sumQty += $quantity;
                $sumRealAmount += $realAmount;
                $sumAmount += $amount;
            @endphp

            <tr>
                <td style="text-align: center; white-space: normal; vertical-align: middle;">{{$key + 1}}</td>
                <td style="text-align: center; white-space: normal; vertical-align: middle;">{{ $user->name }} </td>
                <td style="text-align: center; white-space: normal; vertical-align: middle;">{{ $quantity }}</td>
                <td style="text-align: right; white-space: normal; vertical-align: middle;"> {{ number_format($realAmount, 0, '.', ',') }}</td>
                <td style="text-align: right; white-space: normal; vertical-align: middle;">{{ number_format($amount, 0, '.', ',') }}</td>
            </tr>
        @endforeach

        <tr style="background-color: #1798df;color:#fff">
            <td style="text-align: center; white-space: normal; vertical-align: middle;">#</td>
            <td style="text-align: center; white-space: normal; vertical-align: middle;">Vé bán online</td>
            <td style="text-align: center; white-space: normal; vertical-align: middle;">{{ session('search.countTicketOnl') }}</td>
            <td style="text-align: right; white-space: normal; vertical-align: middle;">{{ number_format(session('search.sumRealAmountOnl'), 0, '.', ',') }} </td>
            <td style="text-align: right; white-space: normal; vertical-align: middle;">{{ number_format(session('search.sumAmountOnl'), 0, '.', ',') }} </td>
        </tr>
        <tr style="background-color: rgb(0, 85, 0);color:#fff">
            <td colspan="2" class="text-center" style="text-align: center; white-space: normal; vertical-align: middle;"><b>Tổng </b></td>
            <td style="text-align: center; white-space: normal; vertical-align: middle;"><b> {{ $sumQty + session('search.countTicketOnl') }} </b></td>
            <td style="text-align: right; white-space: normal; vertical-align: middle;">
                <b> {{ number_format($sumRealAmount + session('search.sumRealAmountOnl'), 0, '.', ',') }} </b>
            </td>
            <td style="text-align: right; white-space: normal; vertical-align: middle;">
                <b> {{ number_format($sumAmount + session('search.sumAmountOnl'), 0, '.', ',') }} </b>
            </td>
        </tr>
    @endisset

    </tbody>
</table>


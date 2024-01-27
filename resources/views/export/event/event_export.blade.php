<table class="table-bordered table-responsive">
    <thead>

    <tr>
        <td colspan="8" align="left" valign="middle" style="font-size:12px; text-align: center">
            <p><b>Bảo tàng vũ trụ</b></p>
        </td>
    </tr>

    <tr>
        <td colspan="8" align="center" valign="middle" style="font-size:30px"><b>BÁO CÁO SỰ KIỆN VÀO</b></td>
    </tr>

    <tr>
        <td colspan="8" align="center" valign="middle"><i>Ngày báo cáo : {{date("G:i d-m-Y")}}</i></td>
    </tr>


    <tr>
        <td colspan="8" align="left" valign="middle"></td>
    </tr>

    <tr>
        <td colspan="1" align="left" valign="middle"></td>
        <td colspan="1" align="center" valign="middle"><b>Từ khoá</b></td>
        <td colspan="1" align="center" valign="middle">{{session("search.key_search")}}</td>
        <td colspan="1" align="left" valign="middle"></td>
        <td colspan="1" align="center" valign="middle"><b>Khu vực</b></td>
        <td colspan="1" align="center" valign="middle">{{session("search.area_name")}}</td>
        <td colspan="2" align="left" valign="middle"></td>
    </tr>

    <tr>
        <td colspan="1" align="left" valign="middle"></td>
        <td colspan="1" align="center" valign="middle"><b>Dịch vụ</b></td>
        <td colspan="1" align="center" valign="middle">{{session("search.service_name")}}</td>
        <td colspan="1" align="left" valign="middle"></td>
        <td colspan="1" align="center" valign="middle"><b>Điểm vui chơi</b></td>
        <td colspan="1" align="center" valign="middle">{{ session('search.fun_spot_name')}}</td>
        <td colspan="2" align="left" valign="middle"></td>
    </tr>

    <tr>
        <td colspan="1" align="left" valign="middle"></td>
        <td colspan="1" align="center" valign="middle"><b>Từ ngày</b></td>
        <td colspan="1" align="center" valign="middle">{{session("search.start_date")}}</td>
        <td colspan="1" align="left" valign="middle"></td>
        <td colspan="1" align="center" valign="middle"><b>Đến ngày</b></td>
        <td colspan="1" align="center" valign="middle">{{session("search.end_date")}}</td>
        <td colspan="2" align="left" valign="middle"></td>
    </tr>

    <tr>
        <td colspan="8" align="left" valign="middle"></td>
    </tr>

    <tr>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">STT
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Mã hóa đơn
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Mã vé
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Loại vé
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Khu vực
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Dịch vụ
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Tên khách (nếu có)
        </td>
        <td align="center" valign="middle"
            style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Thời gian sử dụng
        </td>
    </tr>

    </thead>
    <tbody style="border: 1px solid">
    @isset($data)
        @foreach($data as $key => $item)
            <tr>
                <td style="text-align: center; white-space: normal; vertical-align: top;">{{$key + 1}}</td>
                <td style="text-align: center; white-space: normal; vertical-align: top;">{{$item->order->code_order}}</td>
                <td style="text-align: center; white-space: normal; vertical-align: top;">{{$item->ticket->code}}</td>
                <td style="text-align: center; white-space: normal; vertical-align: top;">{{$item->ticketType->name}}</td>
                <td style="text-align: center; white-space: normal; vertical-align: top;">{{$item->getNameArea()}}</td>
                <td style="text-align: center; white-space: normal; vertical-align: top;">{{$item->type_name}}</td>
                <td style="text-align: center; white-space: normal; vertical-align: top;">
                    @if(!empty($item->order->customer->name))
                        {{@$item->order->customer->name}}
                    @endif

                    @if(!empty($item->order->customer->phone))
                        - {{@$item->order->customer->phone}}
                    @endif

                    @if(!empty($item->order->customer->email))
                        - {{@$item->order->customer->email}}
                    @endif

                    @if(!empty($item->order->customer->address))
                        - {{@$item->order->customer->address}}
                    @endif

                </td>
                <td style="text-align: center; white-space: normal; vertical-align: top;">{{ date('H:i d-m-Y', strtotime($item->time_in)) }}</td>

            </tr>
        @endforeach
    @endisset
    </tbody>
</table>


<table class="table-bordered table-responsive">
    <thead>
        <tr>
            <td colspan="11" align="left" valign="middle" style="font-size: 12px; text-align: center">
                <p><b>LOTTE - BẾP ĂN</b></p>
            </td>
        </tr>
        <tr>
            <td colspan="11" align="center" valign="middle" style="font-size: 16px">
                <b>DANH SÁCH ĐĂNG KÝ</b>
            </td>
        </tr>
        <tr>
            <td colspan="11" align="center" valign="middle"><i>Ngày báo cáo : {{ $today }}</i></td>
        </tr>
        <tr></tr>
        <tr>
            <td colspan="11" align="left" valign="middle"></td>
        </tr>

        <tr>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #e2efd11; vertical-align: middle">
                STT
            </td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #e2efd11; vertical-align: middle">
                Tên công ty
            </td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #e2efd11; vertical-align: middle">
                Mã thẻ
            </td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #e2efd11; vertical-align: middle">
                Mã nhân viên
            </td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #e2efd11; vertical-align: middle">
                Họ và tên
            </td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #e2efd11; vertical-align: middle">
                Mã suất ăn
            </td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #e2efd11; vertical-align: middle">
                Tên suất ăn
            </td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #e2efd11; vertical-align: middle">
                Số lượng suất ăn
            </td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #e2efd11; vertical-align: middle">
                Hạn sử dụng
            </td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #e2efd11; vertical-align: middle">
                Đơn giá
            </td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #e2efd11; vertical-align: middle">
                Tổng tiền
            </td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #e2efd11; vertical-align: middle">
                Thời gian đăng ký
            </td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #e2efd11; vertical-align: middle">
                Trạng thái
            </td>
        </tr>
    </thead>
    <tbody style="border: 1px solid">
        @if (!empty($data))
            @foreach ($data as $key => $val)
                <tr>
                    <td style="text-align: center; white-space: normal; vertical-align: top">{{ $key + 1 }}</td>
                    <td style="text-align: center; white-space: normal; vertical-align: top">
                        {{ $val->staff->company->name }}</td>
                    <td class="sort" style="text-align: center; vertical-align: top">{{ $val->staff->card_id }}</td>

                    <td class="sort" style="text-align: center; vertical-align: top">{{ $val->staff->code }}</td>

                    <td class="sort" style="text-align: left; vertical-align: top">{{ $val->staff->name }}</td>

                    <td class="sort" style="text-align: center; vertical-align: top">{{ $val->eat->code }}</td>
                    <td class="sort" style="text-align: center; vertical-align: top">{{ $val->eat->name }}</td>
                    <td class="sort" style="text-align: center; vertical-align: top">{{ $val->number_of_meals }}</td>
                    <td class="sort" style="text-align: center; vertical-align: top">
                        @if (isset($val->start_date) && isset($val->end_date))
                            {{ date('d/m/Y', strtotime($val->start_date)) }}
                            -
                            {{ date('d/m/Y', strtotime($val->end_date)) }}
                        @endif
                    </td>


                    <td class="sort" style="text-align: center; vertical-align: top">{{ $val->eat->price }}
                    </td>
                    <td class="sort" style="text-align: center; vertical-align: top">{{ $val->total_money }}</td>
                    <td class="sort" style="text-align: center; vertical-align: top">
                        {{ date('H:i:s d/m/Y', strtotime($val->created_at)) }}</td>
                    <td class="sort" style="text-align: center; vertical-align: top">
                        @if (isset($val->start_date) && isset($val->end_date))
                            @if ($val->is_expired == 1)
                                Đã hết hạn
                            @else
                                @if ($val->is_use == 1)
                                    Đang được sử dụng
                                @else
                                    Còn hạn sử dụng
                                @endif
                            @endif
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

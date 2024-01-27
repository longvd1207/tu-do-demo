<table class="table-bordered table-responsive">
    <thead>
        <tr>
            <td colspan="12" align="left" valign="middle" style="font-size:12px; text-align: center">
                <p><b>LOTTE - BẾP ĂN</b></p>
            </td>
        </tr>
        <tr>
            <td colspan="12" align="center" valign="middle" style="font-size:16px"><b>BÁO CÁO TỔNG HỢP</b></td>
        </tr>
        <tr>
            <td colspan="12" align="center" valign="middle"><i>Ngày báo cáo : {{ date('d/m/Y') }}</i></td>
        </tr>

        <tr>
            <td colspan="12" align="left" valign="middle"></td>
        </tr>

        <tr>
            <td colspan="12" align="left" valign="middle"></td>
        </tr>

        <tr>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">STT</td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Tên nhân viên</td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Công ty</td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Tổng số suất ăn đăng ký
            </td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Tổng số lần đã ăn trong
                tháng</td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Đơn giá suất ăn</td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Vị trí cửa ăn</td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Ngày quẹt thẻ</td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Giờ quẹt thẻ</td>
            <td align="center" valign="middle"
                style="text-align: center; background-color: #E2EFD9; vertical-align: middle ">Bữa ăn</td>
        </tr>

    </thead>

    <tbody style="border: 1px solid">
        @if (count($companies) > 0)
            @foreach ($companies as $key1 => $company)
                <tr>
                    <td style="text-align: center; background: #b7bab3; color: #333; vertical-align: middle;  font-weight: bold;"
                        colspan="10">
                        {{ $company->name }}</td>
                </tr>
                {{-- @dd($company->staff) --}}
                @if (count($company->staff))
                    <?php $count_index = 0; ?>
                    @foreach ($company->staff as $staff)
                        {{-- @dd($staff->staffEat) --}}
                        <?php $count_index++; ?>
                        <tr>
                            <td style="vertical-align: middle; text-align: center;" align="center"
                                rowspan="{{ count($staff->staffEat) > 0 ? count($staff->staffEat) : 1 }}">
                                {{ $count_index }}
                            </td>
                            <td style="vertical-align: middle; text-align: left;" align="center"
                                rowspan="{{ count($staff->staffEat) > 0 ? count($staff->staffEat) : 1 }}">
                                {{ $staff->name }}</td>
                            <td style="vertical-align: middle; text-align: center;" align="center"
                                rowspan="{{ count($staff->staffEat) > 0 ? count($staff->staffEat) : 1 }}">
                                {{ $company->name }}</td>
                            <td style="vertical-align: middle; text-align: center;" align="center"
                                rowspan="{{ count($staff->staffEat) > 0 ? count($staff->staffEat) : 1 }}">
                                @if ($company->type == 1)
                                    -
                                @else
                                    {{ $staff->countRegisterEat() }}
                                @endif
                            </td>
                            <td style="vertical-align: middle;text-align: center;" align="center"
                                rowspan="{{ count($staff->staffEat) > 0 ? count($staff->staffEat) : 1 }}">
                                {{ count($staff->staffEat) }}
                            </td>
                            @if (count($staff->staffEat) > 0)
                                @foreach ($staff->staffEat as $key4 => $item)
                                    @if ($key4 == 0)
                                        <td style="text-align: right;">
                                            {{ !empty($item->eat()->get()[0]->price) ? number_format($item->eat()->get()[0]->price) : '--' }}
                                        </td>
                                        <td style="vertical-align: middle;text-align: center;">
                                            {{ $item['location']['name'] ?? '-' }} </td>
                                        <td>{{ date('d-m-Y', strtotime($item['created_at'])) ?? '-' }}</td>
                                        <td>{{ date('H:i:s', strtotime($item['created_at'])) ?? '-' }}</td>

                                        <td style="vertical-align: middle;text-align: center;">
                                            {{ $item->getTimeEat() }}</td>
                                    @endif
                                @endforeach
                            @else
                                <td style="vertical-align: middle;text-align: center;">-</td>
                                <td style="vertical-align: middle;text-align: center;">-</td>
                                <td style="vertical-align: middle;text-align: center;">-</td>
                                <td style="vertical-align: middle;text-align: center;">-</td>
                                <td style="vertical-align: middle;text-align: center;">-</td>
                            @endif
                        </tr>
                        @if (count($staff->staffEat) > 1)
                            @foreach ($staff->staffEat as $key4 => $item)
                                @if ($key4 != 0)
                                    <tr>
                                        <td style="text-align: right;">
                                            {{ !empty($item->eat()->get()[0]->price) ? number_format($item->eat()->get()[0]->price) : '--' }}
                                        </td>
                                        <td style="vertical-align: middle;text-align: center;">
                                            {{ $item['location']['name'] ?? '-' }} </td>
                                        <td>{{ date('d-m-Y', strtotime($item['created_at'])) ?? '-' }}</td>
                                        <td>{{ date('H:i:s', strtotime($item['created_at'])) ?? '-' }}</td>

                                        <td style="vertical-align: middle;text-align: center;">
                                            {{ $item->getTimeEat() }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endif
                    @endforeach
                @else
                    <tr>
                        <td style="text-align: center;color: #ff0000; vertical-align: middle;  font-weight: bold;"
                            colspan="10">Không có dữ liệu</td>
                    </tr>
                @endif
            @endforeach
        @endif
    </tbody>
</table>

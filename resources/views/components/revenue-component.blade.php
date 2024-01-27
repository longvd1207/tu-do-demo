<div class="row" style="background-color: #fff;border-top-left-radius: 15px;
border-top-right-radius: 15px;">
    <div class="col-sm-9" style="margin-top: 16px; margin-bottom: 16px;">
        <h4 class="card-title mb-0 flex-grow-1">Thống kê: <b class="text-danger text_date"></b><br>
            Tổng doanh thu: <b class="text-danger" id="total_order"></b>
        </h4>
    </div>
    <div class="col-sm-3" style="margin-top: 16px;
    margin-bottom: 16px;">
        <div style="float: right" class="input-group">
            <div class="input-group-text bg-primary text-white">Chọn tháng</div>
            <input class="form-control" id="month_home" name="month" type="month" value="{{ date('Y-m') }}"
                onchange="choseMonth()">
        </div>
    </div>
</div>


<div class="row" style="background-color: #fff;">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header border-0 align-items-center d-flex" style="background-color: #405189;">
                <h4 class="card-title mb-0 flex-grow-1" style="color: #fff">Thống kê lượt mua vé
                    {{-- Tổng doanh thu: <b class="text-danger" id="total_order"></b> --}}
                </h4>

            </div><!-- end card header -->

            <div class="card-header p-0 border-0 bg-soft-light">
                <div class="row g-0 text-center">
                    <div class="col-6 col-sm-6">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1"><span class="counter-value" data-target="7585"
                                    id="registerEat_total">0</span></h5>
                            <p class="text-muted mb-0">Thanh toán Offline</p>
                        </div>
                    </div>
                    <!--end col-->
                    <div class="col-6 col-sm-6">
                        <div class="p-3 border border-dashed border-start-0">
                            <h5 class="mb-1"><span class="counter-value" data-target="7585"
                                    id="staffEat_total">0</span></h5>
                            <p class="text-muted mb-0">Thanh toán Online</p>
                        </div>
                    </div>
                </div>
            </div><!-- end card header -->

            <div class="card-body p-0 pb-2">
                <div class="w-100">
                    <div id="customer_impression_charts" data-colors='["--vz-success", "--vz-primary", "--vz-danger"]'
                        class="apex-charts" dir="ltr"></div>
                </div>
            </div><!-- end card body -->
        </div><!-- end card -->
    </div><!-- end col -->
    <div class="col-xl-4">
        <div class="card card-height-100">
            <div class="card-header align-items-center d-flex" style="background-color: #3577f1;">
                <h4 class="card-title mb-0 flex-grow-1" style="color: #fff">Thống kê lượt mua vé</h4>

            </div><!-- end card header -->

            <div class="card-body">
                <div id="store-visits-source"
                    data-colors='["--vz-danger","--vz-primary", "--vz-success",  "--vz-info", "--vz-warning","--vz-secondary"]'
                    class="apex-charts" dir="ltr">
                </div>
            </div>
        </div> <!-- .card-->

    </div> <!-- .col-->
</div> <!-- end row-->

<style>
    /* .card-header:first-child {
        background-color: #dfdfdf;
    } */
</style>

<script src="{{ url('/assets/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ url('/assets/libs/jsvectormap/jsvectormap.min.js') }}"></script>
<script src="{{ url('assets/libs/swiper/swiper.min.js') }}"></script>
<!-- dashboard init -->
<script src="{{ url('/assets/js/pages/dashboard-ecommerce.init.js') }}"></script>
<script>
    $(document).ready(() => {
        choseMonth();
        // pie_chart()
    });

    let home = {
        validateDate: function() {
            var dateInput = document.getElementById('month_home').value;

            // Kiểm tra định dạng yyyy-mm bằng biểu thức chính quy
            const dateFormat = /^\d{4}-\d{2}$/;
            if (!dateFormat.test(dateInput)) {
                // validationResult.textContent = 'Định dạng ngày tháng không hợp lệ. Hãy nhập theo định dạng yyyy-mm.';
                console.log('Định dạng ngày tháng không hợp lệ. Hãy nhập theo định dạng yyyy-mm.');
                return {
                    'status': false
                };
            }

            const [year, month] = dateInput.split('-');

            // Kiểm tra tính hợp lệ của năm và tháng
            if (isNaN(year) || isNaN(month) || year < 1900 || year > 2099 || month < 1 || month > 12) {
                console.log('Năm hoặc tháng không hợp lệ.');
                return {
                    'status': false
                };
            }

            var data_return = {
                'status': true,
                'time': dateInput
            };

            return data_return;
        },
        formattedMonth: function(dateStr) {
            var year = dateStr.substring(0, 4);
            var month = parseInt(dateStr.substring(5, 7));

            // Mảng tên các tháng trong năm
            var monthNames = [
                "Tháng 1", "Tháng 2", "Tháng 3", "Tháng 4", "Tháng 5", "Tháng 6",
                "Tháng 7", "Tháng 8", "Tháng 9", "Tháng 10", "Tháng 11", "Tháng 12"
            ];

            // Định dạng lại theo yêu cầu
            var formattedDate = monthNames[month - 1] + " năm " + year;
            return formattedDate;
        }
    }

    function choseMonth() {
        var data_affter_validate = home.validateDate()
        if (data_affter_validate.status) {
            var month = data_affter_validate.time;
            $('.text_date').text(home.formattedMonth(month));

            $.ajax({
                url: "{{ route('order.getDataForChart') }}",
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    month: month
                },
                success: function(result) {
                    console.log(result);
                    column_graph(result);
                    pie_chart(result);
                }
            });
        }
    }

    function pie_chart(data) {
        var series = data.pie_chart.count;
        var labels = data.pie_chart.key;

        var storeVisitsDiv = document.getElementById('store-visits-source');
        storeVisitsDiv.innerHTML = '';

        if (series.length == 0 && labels.length == 0) {
            $('#store-visits-source').append('<center><h3 style="color: red">Không có dữ liệu</h3></center>');
        } else {
            var chartDonutBasicColors = getChartColorsArray("store-visits-source");
            if (chartDonutBasicColors) {
                var options = {
                    series: series,
                    labels: labels,
                    chart: {
                        height: 333,
                        type: "donut",
                    },
                    legend: {
                        position: "bottom",
                    },
                    stroke: {
                        show: false
                    },
                    dataLabels: {
                        dropShadow: {
                            enabled: false,
                        },
                    },
                    colors: chartDonutBasicColors,
                };
                var chart = new ApexCharts(
                    document.querySelector("#store-visits-source"),
                    options
                );

                let meals = options.series;
                let sum = 0;
                meals.forEach(num => {
                    sum += num;
                })
                // let totalMealData = sum;
                // let divElement = document.getElementById("total-meal");
                // divElement.innerHTML = totalMealData;

                chart.render();
            }
        }
    }

    function column_graph(data) {
        var offline = [];
        var online = [];
        var dayInMonth = [];

        var storeVisitsDiv = document.getElementById('customer_impression_charts');
        storeVisitsDiv.innerHTML = '';

        var total_offline = 0;
        for (var i in data.offline) {
            offline.push(data.offline[i]);
            total_offline = total_offline + data.offline[i];
            dayInMonth.push(i);
        }

        var total_online = 0;
        for (var i in data.online) {
            online.push(data.online[i]);
            total_online = total_online + data.online[i];
        }

        $('#registerEat_total').text(main_layout.formattedNumber(total_offline) + ' đ');
        $('#staffEat_total').text(main_layout.formattedNumber(total_online) + ' đ');
        $('#total_order').text(main_layout.formattedNumber(total_online + total_offline) + ' đ');


        var linechartcustomerColors = getChartColorsArray("customer_impression_charts");
        if (linechartcustomerColors) {
            var options = {
                series: [{
                        name: "Offline",
                        type: "area",
                        data: offline,
                    },
                    {
                        name: "Online",
                        type: "bar",
                        data: online,
                    },
                    // {
                    //     name: "Refunds",
                    //     type: "line",
                    //     data: [8, 12, 7, 17, 21, 11, 5, 9, 7, 29, 12, 35, 8, 12, 7, 17, 21, 11, 5, 9, 7, 29, 12,
                    //         35
                    //     ],
                    // },
                ],
                chart: {
                    height: 370,
                    type: "line",
                    toolbar: {
                        show: false,
                    },
                },
                stroke: {
                    curve: "straight",
                    dashArray: [0, 0, 8],
                    width: [2, 0, 2.2],
                },
                fill: {
                    opacity: [0.1, 0.9, 1],
                },
                markers: {
                    size: [0, 0, 0],
                    strokeWidth: 2,
                    hover: {
                        size: 4,
                    },
                },
                xaxis: {
                    categories: dayInMonth,
                    axisTicks: {
                        show: false,
                    },
                    axisBorder: {
                        show: false,
                    },
                },
                grid: {
                    show: true,
                    xaxis: {
                        lines: {
                            show: true,
                        },
                    },
                    yaxis: {
                        lines: {
                            show: false,
                        },
                    },
                    padding: {
                        top: 0,
                        right: -2,
                        bottom: 15,
                        left: 10,
                    },
                },
                legend: {
                    show: true,
                    horizontalAlign: "center",
                    offsetX: 0,
                    offsetY: -5,
                    markers: {
                        width: 9,
                        height: 9,
                        radius: 6,
                    },
                    itemMargin: {
                        horizontal: 10,
                        vertical: 0,
                    },
                },
                plotOptions: {
                    bar: {
                        columnWidth: "30%",
                        barHeight: "70%",
                    },
                },
                colors: linechartcustomerColors,
                tooltip: {
                    shared: true,
                    y: [{
                            formatter: function(y) {
                                if (typeof y !== "undefined") {
                                    return y.toFixed(0);
                                }
                                return y;
                            },
                        },
                        {
                            formatter: function(y) {
                                if (typeof y !== "undefined") {
                                    return y.toFixed(0);
                                }
                                return y;
                            },
                            // formatter: function(y) {
                            //     if (typeof y !== "undefined") {
                            //         return "$" + y.toFixed(2) + "k";
                            //     }
                            //     return y;
                            // },
                        },
                        {
                            formatter: function(y) {
                                if (typeof y !== "undefined") {
                                    return y.toFixed(0) + " Sales";
                                }
                                return y;
                            },
                        },
                    ],
                },
            };
            var chart = new ApexCharts(
                document.querySelector("#customer_impression_charts"),
                options
            );
            chart.render();
        }
    }
</script>

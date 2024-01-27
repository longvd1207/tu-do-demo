@extends('layouts.master')
@section('title') @lang('translation.dashboards') @endsection
@section('content')
    @component('components.breadcrumb')
        @slot('li_1') Ticket @endslot
        @slot('title') Log @endslot
    @endcomponent

    <!-- view-json -->
    <script src="{{config('kztek_config.url_public').'assets/libs/view-json/jquery.json-editor.min.js'}}"></script>
    <link rel="stylesheet" href="{{config('kztek_config.url_public').'assets/libs/view-json/jquery.json-viewer.css'}}">


    <div class="row">
        <div class="col">
            <div class="h-100">
                <div class="row mb-1">
                    <div class="col-12">
                        <div class="card" id="ticket-list">
                            <div class="card-header">
                                <h4 class="mb-0 flex-grow-1" style="color:#EE877C">Quản lý log</h4>
                            </div>

                            <div class="card-header border border-dashed border-end-0 border-start-0">
{{--                                <form action="{{config('kztek_config.url_client')}}log/search" method="post">--}}
                               <form action="{{ url('admin/log/search') }}" method="post">
                                    @csrf
                                    <div class="row g-3 mb-0 align-items-center">
                                        <div class="col-sm-auto">
                                            <div class="input-group">
                                                <div class="input-group-text  bg-primary text-white">
                                                    <span>Từ ngày</span>
                                                </div>
                                                <input type="date" class="form-control" name="start_date"
                                                       value="{{ session('search.start_date') }}">
                                                <div
                                                    class="input-group-text bg-primary border-primary text-white">
                                                    <i class="ri-calendar-2-line"></i>
                                                </div>
                                            </div>
                                        </div>
                                        {{--End col--}}
                                        <div class="col-sm-auto">
                                            <div class="input-group">
                                                <div class="input-group-text  bg-primary text-white">
                                                    <span>Đến ngày</span>
                                                </div>
                                                <input type="date" class="form-control" name="end_date"
                                                       value="{{ session('search.end_date') }}">
                                                <div
                                                    class="input-group-text bg-primary border-primary text-white">
                                                    <i class="ri-calendar-2-line"></i>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-sm-auto">
                                            <input name="confirm_search" type="hidden" value="1"/>
                                            <button class="btn btn-danger" type="submit"><i class="ri-equalizer-fill me-1 align-bottom"></i> Tìm kiếm</button>
                                        </div>
                                    </div>
                                    <!--end row-->
                                </form>
                            </div>

                            <div style="padding:5px 15px" class="text-danger"><b>Trang {{session('search.page')}} / {{$logs->lastPage()}}, tổng số {{$total}} </b></div>

                            <div class="card-body" style="padding-top: 0px !important;">
                                <div class="table-responsive table-card mt-3 mb-4"style="width: auto">
                                    <table class="table table-sm table-bordered table-hover" id="customerTable" >
                                        <thead class="table-light">
                                        <tr class="text-center">
                                            <th scope="col" border="2px"style="width: 2%; text-align: center" >Stt</th>
                                            <th scope="col" style="min-width: 15%" >Bảng </th>
{{--                                            <th scope="col" style="min-width: 10%" >ID</th>--}}
                                            <th scope="col" style="min-width: 100px" >Ngày tạo</th>
                                            <th scope="col">Giá trị tạo</th>
                                            <th scope="col" style="">Giá trị cũ</th>
                                            <th scope="col" style="">Giá trị thay đổi</th>
                                        </tr>
                                        </thead>
                                        @isset($logs)
                                            @foreach($logs as $k=>$item)
                                                <tbody>
                                                <tr >
                                                    <td class="text-center"> <?php echo (session('search.page') - 1) * $limit + ($k + 1); ?></td>
                                                    <td >
                                                        <span style="color: red; font-size: 15px">{{$item['table_name']}}</span><br>
                                                        <span>{{$item["user"]['user_name']}}</span><br>
{{--                                                        <span>{{$item['sender']}}</span><br>--}}
                                                        <span>{{$item['action']}}</span><br>
                                                    </td>
{{--                                                    <td>{{$item['object_id']}}</td>--}}
                                                    <td>{{\Carbon\Carbon::parse($item['created_at'], 'UTC')->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i')}}</td>
                                                    {{--                                                   <td>{{\Carbon\Carbon::parse($item['date_updated'], 'UTC')->setTimezone('Asia/Ho_Chi_Minh')->format('d-m-Y H:i')}}</td>--}}
                                                    <td >
                                                        <span class="json-input" id="{{'json-input'.$k}}"  >{{$item['data_new']}}</span>
                                                        <span class="json-display" id="{{'json-display'.$k}}"></span>
                                                        @if(isset($item['data_new']))
                                                            <script>
                                                                function getJson() {
                                                                    try {
                                                                        $('#{{'json-input'.$k}}').hide();
                                                                        return JSON.parse($('#{{'json-input'.$k}}').text());
                                                                    } catch (ex) {

                                                                    }
                                                                }
                                                                var editor = new JsonEditor('#{{'json-display'.$k}}', getJson());
                                                            </script>
                                                        @else
                                                            <script>
                                                                $('#{{'json-input'.$k}}').hide();
                                                                $('#{{'json-display'.$k}}').hide();
                                                            </script>
                                                        @endif
                                                    </td>
                                                    <td >
                                                        <span class="json-input" id="{{'json-input-old'.$k}}"  >{{$item['data_old']}}</span>
                                                        <span class="json-display" id="{{'json-display-old'.$k}}"></span>
                                                        @if(isset($item['data_old']))
                                                            <script>
                                                                function getJson() {
                                                                    try {
                                                                        $('#{{'json-input-old'.$k}}').hide();
                                                                        return JSON.parse($('#{{'json-input-old'.$k}}').text());
                                                                    } catch (ex) {

                                                                    }
                                                                }
                                                                var editor = new JsonEditor('#{{'json-display-old'.$k}}', getJson());
                                                            </script>
                                                        @else
                                                            <script>
                                                                $('#{{'json-input-old'.$k}}').hide();
                                                                $('#{{'json-display-old'.$k}}').hide();
                                                            </script>
                                                        @endif
                                                    </td>
                                                    <td >
                                                        <span class="json-input" id="{{'json-input-compare'.$k}}"  >{{$item['data_compare']}}</span>
                                                        <span class="json-display" id="{{'json-display-compare'.$k}}"></span>
                                                        @if(isset($item['data_compare']))
                                                            <script>
                                                                function getJson() {
                                                                    try {
                                                                        $('#{{'json-input-compare'.$k}}').hide();
                                                                        return JSON.parse($('#{{'json-input-compare'.$k}}').text());
                                                                    } catch (ex) {

                                                                    }
                                                                }
                                                                var editor = new JsonEditor('#{{'json-display-compare'.$k}}', getJson());
                                                            </script>
                                                        @else
                                                            <script>
                                                                $('#{{'json-input-compare'.$k}}').hide();
                                                                $('#{{'json-display-compare'.$k}}').hide();
                                                            </script>
                                                        @endif
                                                    </td>
                                                </tr>
                                                </tbody>
                                            @endforeach
                                        @endisset
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    <div class="pagination-wrap hstack gap-2">
                                        {{ $logs->links() }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!--End row -->

            </div> <!-- end .h-100-->
        </div>
        @endsection
        @section('script')
            <!-- apexcharts -->
            <script src="{{ config('kztek_config.url_public').('assets/libs/swiper/swiper.min.js')}}"></script>
            <!--Init -->
            <link rel="stylesheet" href="{{config('kztek_config.url_public').('assets/css/log/index.css')}}">
            <script>
                @if(!empty(session('alert-success')))
                sweetSuccess('{{session('alert-success')}}');
                @endif
                @if(!empty(session('alert-error')))
                Swal.fire({
                    position: 'top-end',
                    icon: 'error',
                    title: "{{session('alert-error')}}",
                    showConfirmButton: false,
                    timer: 1500,
                    showCloseButton: false
                });
                @endif
            </script>
@endsection

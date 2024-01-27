@extends('layouts.master')
@section('title')
    @lang('translation.dashboards')
@endsection
@section('content')
    @include('components.breadcrumb')
    <div class="row">
        <div class="col">
            <form action="{{ route('ticket.update' , $ticket->id) }}"
                  enctype="multipart/form-data" method="post">
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-body border border-dashed border-start-0 border-end-0 row">

                        <!-- bên trái -->
                        <div class="col-6">
                            <div class="row mb-3">


                                <div class="col-6">
                                    <div class="form_input">
                                        <label for="name_ticket" class="form-label">Tên vé
                                            <span style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <input class="form-control" disabled id="name_ticket"
                                               value="{{ $ticket->ticket_type_name }}">
                                    </div>
                                </div>


                                <div class="col-6">
                                    <div class="form_input">
                                        <label class="form-label">Trạng thái
                                            <span style="color:red;font-size:15px;font-weight:bold">*</span></label>
                                        <select name="status" class="form-control">
                                            <option class="text-success"
                                                    value="1" {{ $ticket->status == 1 ? 'selected' : '' }}>Hoạt động
                                            </option>
                                            <option class="text-danger"
                                                    value="2" {{ $ticket->status == 2 ? 'selected' : '' }}>Khóa
                                            </option>
                                        </select>
                                        @if ($errors->has('status'))
                                            <div class="bg-danger text-white text-center py-1">
                                                <span>{{ $errors->first('status') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>


                            </div>
                        </div>
                        <!-- bên trái -->

                        <!-- bên phải -->

                        <div class="col-6">
                            <div class="row mb-3">
                                <div class="col-4">
                                    <div class="form_input">
                                        <label class="form-label">Các khu vực </label>
                                        @if($ticket->maps)
                                            <ol class="list-area mb-3">
                                                @foreach($ticket->maps as $map)
                                                    @if($map->type == 1)
                                                        <li>{{ $map->getAreas->name ?? '' }}</li>
                                                    @endif
                                                @endforeach
                                            </ol>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form_input">
                                        <label class="form-label">Các dịch vụ</label>
                                        @if($ticket->maps)
                                            <ol class="list-service">
                                            @foreach($ticket->maps as $map)
                                                    @if($map->type == 2)
                                                        <li>{{ $map->getServices->name ?? '' }}</li>
                                                    @endif
                                            @endforeach
                                            </ol>
                                        @endif
                                    </div>
                                </div>

                                <div class="col-4">
                                    <div class="form_input">
                                        <label class="form-label">Các điểm vui chơi</label>
                                        @if($ticket->maps)
                                            <ol class="list-fun-spot">
                                            @foreach($ticket->maps as $map)
                                                @if($map->type == 3)
                                                    <li>{{ $map->getFunSpots->name ?? '' }}</li>
                                                @endif
                                            @endforeach
                                            </ol>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- bên phải -->

                    </div>

                </div>

                <div class="card-footer">
                    <div class="col-sm-auto">
                        <button class="btn btn-primary" type="submit">
                            Sửa
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
@endsection
        <style>
            .list-area, .list-service, .list-fun-spot{
                background-color: #eff2f7;
                border-radius: 5px;
            }
            .image-container {
                position: relative;
                display: inline-block;
            }

            .button-inside-image {
                position: absolute !important;
                top: 0;
                right: 0;
                z-index: 1;
            }

            .form_input {
                margin-bottom: 20px;
            }

            .navbar-brand-box {
                margin-top: 10px;
                margin-bottom: 16px;
            }
        </style>


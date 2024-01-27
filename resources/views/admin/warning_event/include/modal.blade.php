<!-- modal import file excel nhân viên-->
<div class="modal fade" tabindex="-1" id="import-excel-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('test') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <label for="">Nhập excel</label>
                    <a href="{{ url('Excel\Format\import_staff.xlsx') }}"
                       download="mẫu danh sách nhân viên.xlsx" class="btn btn-success btn-sm"><i
                            class="ri-file-excel-2-line align-bottom me-1"></i> Tải
                        mẫu file
                        excel</button>
                    </a>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Chọn file excel</label>
                            <input type="file" name="file_list_staff"/>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Thoát
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btn-led-save"
                            value="import">Import Excel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- modal import file zip ảnh nhân viên-->
<div class="modal fade" tabindex="-1" id="import-zip-image-modal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="post" action="{{ route('test') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <label for="">Nhập File</label>
                    {{--                                <a href="{{ url('Excel\Format\import_staff.xlsx') }}" --}}
                    {{--                                   download="mẫu danh sách nhân viên.xlsx" class="btn btn-success btn-sm"><i --}}
                    {{--                                        class="ri-file-excel-2-line align-bottom me-1"></i> Tải --}}
                    {{--                                    mẫu file --}}
                    {{--                                    excel</button> --}}
                    {{--                                </a> --}}
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Chọn file zip</label>
                            <input type="file" name="zip_file"/>
                        </div>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Thoát
                    </button>
                    <button type="submit" class="btn btn-primary btn-sm" id="btn-led-save"
                            value="import">Import File Zip
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- model thông báo lỗi import file excel -->
<div id="modalImportError" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Hiển thị lỗi Import dữ liệu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table role="grid"
                       class="table table-sm table-bordered align-middle table-nowrap mb-0 gridjs-table"
                       id="tasksTable">
                    <thead class="table-light text-muted">
                    <tr class="text-center">
                        <th class="sort" style="width: 5%">Dòng</th>
                        <th class="sort">Lỗi</th>
                    </tr>
                    </thead>
                    <tbody class="list form-check-all" id="modalImportErrorTbody">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
                {{-- <button type="button" class="btn btn-primary ">Save Changes</button> --}}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<!-- model thông báo lỗi import file zip ảnh -->
<div id="modalImportFileZipImageError" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel"
     aria-hidden="true" style="display: none;">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Hiển thị lỗi Import dữ liệu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table role="grid"
                       class="table table-sm table-bordered align-middle table-nowrap mb-0 gridjs-table"
                       id="tasksTable">
                    <thead class="table-light text-muted">
                    <tr class="text-center">
                        <th class="sort" style="width:35%">Tên file</th>
                        <th class="sort">Lỗi</th>
                    </tr>
                    </thead>
                    <tbody class="list form-check-all" id="modalImportFileZipImageErrorTbody">

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
                {{-- <button type="button" class="btn btn-primary ">Save Changes</button> --}}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<!-- Thông tin người mua vé -->
<div id="info_customer" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
     style="display: none;">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">Thông tin người mua vé</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table role="grid"
                       class="table table-sm table-bordered align-middle table-nowrap mb-0 gridjs-table"
                       id="tasksTable">
                    <thead class="table-light text-muted">
                    <tr class="text-center">
                        <th class="sort" style="width: 40%">Tên</th>
                        <th class="sort">Điện thoại</th>
                        <th class="sort">Email</th>
                        <th class="sort">Địa chỉ</th>
                    </tr>
                    </thead>
                    <tbody class="list form-check-all" id="modalImportErrorTbody">
                    <tr class="text-center">
                        <td class="sort" style="width: 30%" id="report_name"></td>
                        <td class="sort" id="report_phone"></td>
                        <td class="sort" id="report_email"></td>
                        <td class="sort" id="report_address"></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
                {{-- <button type="button" class="btn btn-primary ">Save Changes</button> --}}
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- Thông tin người mua vé -->

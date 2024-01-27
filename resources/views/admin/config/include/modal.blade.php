<div id="note_modal" class="modal fade bs-example-modal-sm" tabindex="-1" aria-labelledby="exampleModalgridLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="title">Lưu ý: <a class="text-danger">Cấu hình bữa ăn</a>
                </h5>
            </div>
            <div class="modal-body">
                <table>
                    <tr>
                        <td>
                            <li class="text-align">Thời gian bắt đầu lớn hơn thời gian kết thúc sẽ được hiểu là: ngày
                                bắt
                                đầu vào ngày hôm trước và kết thúc vào ngày hôm sau.
                            </li>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <li class="text-align">Thời gian đăng ký lớn hơn thời gian bắt đầu sẽ được hiểu là: đăng ký
                                ngày hôm trước và bắt đầu ăn vào ngày hôm sau.
                            </li>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <li class="text-align">Thời gian hủy lớn hơn thời gian bắt đầu sẽ được hiểu là: hủy vào ngày
                                hôm trước và bắt đầu ăn vào ngày hôm sau.
                            </li>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Thoát</button>
            </div>
        </div>
    </div>
</div>
<div id="submit_alert_modal" class="modal fade bs-example-modal-center" tabindex="-1"
    aria-labelledby="mySmallModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <lord-icon src="https://cdn.lordicon.com/tdrtiskw.json" trigger="loop"
                    colors="primary:#25a0e2,secondary:#00bd9d" style="width:130px;height:130px">
                </lord-icon>
                <div class="mt-4">
                    <h4 class="mb-3">Bạn vừa đóng bữa ăn</h4>
                    <p class="text-muted mb-4">Việc bạn đóng bữa ăn đồng nghĩa với việc bạn đang hủy hết suất ăn của bữa này
                        trong tương lai!</p>
                    <div class="hstack gap-2 justify-content-center">
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Không đồng ý</button>

                        <a href="javascript:void(0);" id="submit-form" onclick="submitForm()" class="btn btn-success">Đồng
                            ý</a>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<style>
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 9999;
    }

    .loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-top: -25px;
        margin-left: -25px;
        animation: spin 2s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    @keyframes rotation {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }
</style>

<script>
    $(document).ready(function() {
        main.token = '{{ csrf_token() }}'
    });
    let main_layout = {
        alert_main: function(title = '', icon = 'success', position = 'top-end') {
            Swal.fire({
                position: position,
                icon: icon,
                title: title,
                showConfirmButton: false,
                timer: 1500,
                showCloseButton: false
            });
        },
        show_loader: function() {
            document.getElementById("overlay-loader-layout").style.display = "block";
        },
        hide_loader: function() {
            document.getElementById("overlay-loader-layout").style.display = "none";
        }
    }

    function submitForm() {
        $('#form-data').submit();
        $('#submit_alert_modal').hide();
        main_layout.show_loader();
    }
</script>

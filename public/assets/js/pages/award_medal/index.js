$(document).ready(function () {
    /**
     * Chọn giải đấu
     */
    getQuantityMedal();
    getColorSelectedMedal();
    $('select[name="tournament"]').change(function () {
        let tournament_id = $(this).val();
        $('select[name="tournament_competition"]').parent().hide();
        $('select[name="participant_sub_group"]').parent().hide();
        $('select[name="participant_sub_group"]').val('');
        $('select[name="participant_group"]').parent().hide();
        $('select[name="participant_group"]').val('');
        if (tournament_id) {
            $('#loader').removeClass('hidden');
            $.ajax({
                type: "GET",
                headers: headersClient,
                data: {},
                crossDomain: true,
                secure: true,
                url: apiUrl + "matches/get_tournament_competition/" + tournament_id + "/" + 3,
                beforeSend: function (request) {
                    request.setRequestHeader("token", token)
                },
                success: function (responseData) {
                    if (responseData['status'] == 200) {
                        tournamentCompetition = responseData['result'];
                        $('select[name="tournament_competition"]').children().remove();
                        let html = '<option value="">---Chọn môn thi đấu---</option>';
                        responseData['result'].forEach((val, key) => {
                            html += '<option value="' + val.id + '">' + val.name + '</option>';
                        })
                        $('select[name="tournament_competition"]').append(html);
                        $('select[name="tournament_competition"]').parent().show();

                    } else {
                        sweetAlert('Không tìm thấy môn thi đấu phu hợp');
                    }
                },
                error: function (jqXhr, textStatus, errorMessage) {
                    $('#loader').addClass('hidden');
                    Swal.fire({
                        text: errorMessage,
                        icon: 'error',
                        timer: 1000
                    });
                }
            }).done(function (responseData) {
                $('#loader').addClass('hidden');
            });
        }
        $('select[name="competition_configuration"]').val('');
        $('select[name="competition_configuration"]').parent().hide();
    });
    $('.btn-result').on('click', function () {
        let name = $(this).parents().eq(1).children('td[name="name"]').text();
        let medal_id = $(this).parents().eq(1).children('td[name="medal"]').attr('medal_id');
        let id =  $(this).attr('match_detail_id');
        $('#btn-save-modal').attr('match_detail_id', id);
        $('#result-modal').find('.modal-header span').text(name);
        $('#result-modal').find('.modal-body select').val(medal_id);
        $('#result-modal').modal('show');
    });
    /**
     * Xem danh sách vận động viên trong 1 nhóm
     *
     * */
    $('.btn-view').on('click', function () {
        let id = $(this).attr('athlete_group_id');
        $('#loader').removeClass('hidden');
        $.ajax({
            type: "GET",
            headers: headersClient,
            data: {},
            crossDomain: true,
            secure: true,
            url: apiUrl + "athlete-group/"+id,
            beforeSend: function (request) {
                request.setRequestHeader("token", token)
            },
            success: function (responseData) {
                if (responseData['status'] == 200){
                    let athleteGroup = responseData['result'];
                    $('#view-athlete-group-modal .modal-header').text('Nhóm: ' + athleteGroup['name']);
                    $('#view-athlete-group-modal #body-view-table-modal').children().remove();

                    if(athleteGroup['athlete'] && athleteGroup['athlete'].length){
                        let html = '';
                        athleteGroup['athlete'].forEach((v, k) => {
                            html += '<tr>' +
                                '<td class="text-center">' + v['pivot']['order'] + '</td>' +
                                '<td>' + v['athlete_code'] + '</td>' +
                                '<td>' + v['name'] + '</td>' +
                                '<td>' +( v['participant_class'] &&  v['participant_class']['participant_group_name'] ? v['participant_class']['participant_group_name'] : '' ) + '</td>' +
                                '<td>' + ( v['participant_class'] &&  v['participant_class']['name'] ? v['participant_class']['name'] : '') + '</td>' +
                                '<td class="text-center">' + v['height'] + '</td>' +
                                '<td class="text-center">' + v['weight'] + '</td>' +
                                '<td class="text-center">' +(  v['phone'] ? v['phone'] : '' )+ '</td>' +
                                '</tr>';
                        });
                        $('#view-athlete-group-modal #body-view-table-modal').append(html);
                    }
                    $('#view-athlete-group-modal').modal('show');
                } else {
                    sweetAlert('Không tồn taj');
                }
            },
            error: function (jqXhr, textStatus, errorMessage) {
                $('#loader').addClass('hidden');
                Swal.fire({
                    text: errorMessage,
                    icon: 'error',
                    timer: 1000
                });
            }
        }).done(function (responseData){
            $('#loader').addClass('hidden');
        });
    });
    /**
     * Chọn huy chương
     */
    $('select.select-medal').on('change', function () {
        getColorSelectedMedal();
        getQuantityMedal();
    });
    /**
     * Tự động gán huy chương
     */
    $('#btn-auto-setup-medal').on('click', function () {
        swal.fire({
            title: 'Huy chương !',
            text: 'Tự động gán huy chương ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: 'rgba(113,157,91,0.93)',
            cancelButtonColor:  'rgba(134,137,155,0.93)',
            confirmButtonText: 'Setup',
        }).then(result => {
            if(!result.isDismissed) {
                autoSetMedal();
            }
        });
    });
    /**
     * Reset huy chương
     */
    $('#btn-reset').on('click', function () {
        swal.fire({
            title: 'Huy chương !',
            text: 'Reset lại huy chương?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#f14e4e',
            cancelButtonColor:  'rgba(134,137,155,0.93)',
            confirmButtonText: 'Reset',
        }).then(result => {
            if(!result.isDismissed) {
                $('select.select-medal').val('');
                getColorSelectedMedal();
                getQuantityMedal();
            }
            sweetSuccess('Reset thành công');
        });
    });
    /**
     * Lưu kết quả
     */
    $('#btn-save').on('click', function () {
        saveMedal();
    });
    $('#excel_tournament_competitions').on('click', function (){
        debugger;
        console.log(222)
        $('#modal_excel_tournament_competitions').modal('show');
        console.log(333)
    })
});

/**
 * Tính số lương huy chương
 */
function getQuantityMedal(){
    $('.medal_quantity').each((key, val) => {
        let quantity = 0;
        let medal_id = $(val).attr('medal_id');
        $('.select-medal').each((k, v) => {
            if($(v).val() == medal_id){
                quantity++;
            }
        });
        $(val).text(quantity);

        if(quantity > Number($(val).parent().find('span.medal_total').text())){
            $(val).parent().addClass('bg-danger text-white');
        } else {
            $(val).parent().removeClass('bg-danger text-white');
        }
    });
}
/**
 * Chọn màu cho huy chương
 *
 * */
function getColorSelectedMedal(){
    $('.select-medal').each( (key, val) => {
        let order = $(val).find(':selected').attr('order');
        $(val).css('background-color', '#ffffff');
        if(Number(order) == 1){
            $(val).css('background-color', '#f1d53e');
        }
        if(Number(order) == 2){
            $(val).css('background-color', 'rgba(156,159,177,0.93)');
        }
        if(Number(order) == 3){
            $(val).css('background-color', 'rgb(148,131,68)');
        }
        if(Number(order) == 4){
            $(val).css('background-color', 'rgba(107,222,42,0.93)');
        }

    })
}

/**
 * Tự động gán huy chương
 */
function autoSetMedal(){
    $('select.select-medal').val('');
    let x = 0;
    $('.medal').each((key, val) => {
        let medal_id = $(val).find('span.medal_quantity').attr('medal_id');
        let totalMedal = Number($(val).find('span.medal_total').text());
        for(let i = 0; i < totalMedal; i ++){
            $('select.select-medal').eq(x).val(medal_id);
            x++;
        }
    });
    getColorSelectedMedal();
    getQuantityMedal();
    sweetSuccess('Gán huy chương thàng công');
}

/**
 * Lưu kết quả
 */
function saveMedal(){
    let promise = new Promise((resolve, reject) => {
        $('.medal').each((key, val) => {
            if($(val).hasClass('bg-danger')) {
                sweetAlert( $(val).find('span').eq(0).text() + ' vượt quá số lượng');
                reject(0);
            }
        });
        resolve(1);
    });
    promise.then(value =>  {
        swal.fire({
            title: 'Huy chương !',
            text: 'Lưu kết quả ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: 'rgba(67,151,238,0.7)',
            cancelButtonColor: 'rgba(134,137,155,0.93)',
            confirmButtonText: 'Lưu',
        }).then(result => {
            if (!result.isDismissed) {
                let match_details = [];
                let tournament_competition_id = $('#btn-save').attr('tournament_competition_id');
                $('tbody tr').each((key, tr) => {
                    let match_detail_id = $(tr).attr('match_detail_id');
                    let medalId = $(tr).find('select.select-medal').val();
                    if (medalId) {
                        let match_detail = {
                            match_detail_id: match_detail_id,
                            medal_id: medalId,
                        }
                         match_details.push(match_detail);
                    }
                });
                $('#loader').removeClass('hidden');
                $.ajax({
                    type: "POST",
                    headers: headersClient,
                    data: {
                        tournament_competition_id: tournament_competition_id,
                        match_details: match_details,
                    },
                    crossDomain: true,
                    secure: true,
                    url: apiUrl + "getMedal",
                    beforeSend: function (request) {
                        request.setRequestHeader("token", token)
                    },
                    success: function (responseData) {
                        if (responseData['status'] == 200) {
                            sweetSuccess(responseData['result']);
                        }else if(responseData['status'] == 90) {
                            sweetAlert((responseData['errors']['match_details'][0]), 3000);
                        } else {
                            sweetAlert(responseData['result'], 5000 );
                        }
                    },
                    error: function (jqXhr, textStatus, errorMessage) {
                        $('#loader').addClass('hidden');
                        Swal.fire({
                            text: errorMessage,
                            icon: 'error',
                            timer: 1000
                        });
                    }
                }).done(function (responseData){
                    $('#loader').addClass('hidden');
                });

            }
        });
    });
}



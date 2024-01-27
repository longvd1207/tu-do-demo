$('.cleave-time').each((k, v)=>{
    let cleave = new Cleave($(v), {
        delimiter: ':',
        blocks: [2,2,2],
        numericOnly: true,
    })
})
$(document).ready(function () {
    /**
     *
     * khi thay đổi kết quả thi đấu
     */
    $('td[name="result_match_detail"]').find('input.cleave-time').on('change', function (e){
        // $('td[name="result_match_detail"]').find('input.cleave-time').each((k, v) => {
        //     $(v).removeClass('bg-danger text-white');
        // });
        // $('td[name="result_match_detail"]').find('input.cleave-time').each((key, val) => {
        //     let result = $(val).val();
        //     let count = 0;
        //     $('td[name="result_match_detail"]').find('input.cleave-time').each((k, v) => {
        //         if($(v).val() == result){
        //             count++;
        //         }
        //     });
        //     if(count > 1){
        //         $('td[name="result_match_detail"]').find('input.cleave-time').each((k, v) => {
        //             if($(v).val() == result && result){
        //                 $(v).addClass('bg-danger text-white');
        //             }
        //         });
        //     }
        // });

        let self = this;
        let result = $(self).val();
        let tournamentCompetitionID = $('select[name="tournament_competition"]').val();
        $('#loader').removeClass('hidden');
        $.ajax({
            type: "POST",
            headers: headersClient,
            data: {
                result: result,
                tournament_competition_id : tournamentCompetitionID
            },
            crossDomain: true,
            secure: true,
            url: apiUrl + "matches/group/check_double_result",
            beforeSend: function (request) {
                request.setRequestHeader("token", token)
            },
            success: function (responseData) {
                if (responseData['status'] == 200) {
                    $(self).addClass('bg-danger text-white');
                    sweetAlert(responseData['result'], 3000);
                } else {
                    $(self).removeClass('bg-danger text-white')
                };
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

    })
    /**
     * Chọn giải đấu
     */

    $('select[name="tournament"]').change(function () {
        let tournament_id = $(this).val();
        $('select[name="tournament_competition"]').parent().hide();
        if (tournament_id) {
            $('#loader').removeClass('hidden');
            $.ajax({
                type: "GET",
                headers: headersClient,
                data: {},
                crossDomain: true,
                secure: true,
                url: apiUrl + "matches/get_tournament_competition/" + tournament_id + "/" + 2,
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
    /**
     * Update từn lượt thi đấu
     *
     * */
    $('.btn-edit-matches').on('click', function () {
        let matches_id = $(this).attr('matches_id');
        let matches_index = $(this).attr('matches_index');
        let match_details = [];
        $('tr[matches_id="' + matches_id + '"]').each((k, v) => {
            let match_detail = {
                id: $(v).attr('id'),
                result: $(v).find('td[name="result_match_detail"]').find('input').val(),
            }
            match_details.push(match_detail);
        });
        swal.fire({
            title: 'Update !',
            text: 'Update kết quả lượt '+matches_index+' ? ',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#107fea',
            cancelButtonColor:  'rgba(134,137,155,0.93)',
            confirmButtonText: 'update',
        }).then(result => {
            if(!result.isDismissed){
                $('#loader').removeClass('hidden');
                $.ajax({
                    type: "PUT",
                    headers: headersClient,
                    data: {
                        'match_details': match_details
                    },
                    crossDomain: true,
                    secure: true,
                    url: apiUrl + "matches/result-detail/edit",
                    beforeSend: function (request) {
                        request.setRequestHeader("token", token)
                    },
                    success: function (responseData) {
                        if (responseData['status'] == 200) {
                            sweetSuccess(responseData['result']);
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

    /**
     *
     * update tất cả lượt thi đấu
     */
    $('#btn-edit-matches-all').on('click', function () {
        let match_details = [];
        $('tr.match_detail').each((k, v) => {
            let match_detail = {
                id: $(v).attr('id'),
                result: $(v).find('td[name="result_match_detail"]').find('input').val(),
            }
            match_details.push(match_detail);
        });
        swal.fire({
            title: 'Update !',
            text: 'Update kết quả lượt tất cả ?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#107fea',
            cancelButtonColor:  'rgba(134,137,155,0.93)',
            confirmButtonText: 'update',
        }).then(result => {
            if(!result.isDismissed){
                $('#loader').removeClass('hidden');
                $.ajax({
                    type: "PUT",
                    headers: headersClient,
                    data: {
                        'match_details': match_details
                    },
                    crossDomain: true,
                    secure: true,
                    url: apiUrl + "matches/result-detail/edit",
                    beforeSend: function (request) {
                        request.setRequestHeader("token", token)
                    },
                    success: function (responseData) {
                        if (responseData['status'] == 200) {
                            sweetSuccess(responseData['result']);
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

    /**
     * Xem danh sách vận động viên
     */
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
                                '<td>' + v['pivot']['order'] + '</td>' +
                                '<td>' + v['athlete_code'] + '</td>' +
                                '<td>' + v['name'] + '</td>' +
                                '<td>' + ( v['participant_class'] && v['participant_class']['participant_group_name'] ? v['participant_class']['participant_group_name'] : '')  + '</td>' +
                                '<td class="text-center">' + ( v['participant_class'] ? v['participant_class']['name'] : '')  + '</td>' +
                                '<td class="text-center">' + v['height'] + '</td>' +
                                '<td class="text-center">' + v['weight'] + '</td>' +
                                '<td class="text-center">' + v['phone'] + '</td>' +
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
});


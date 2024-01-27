$('.cleave-time').each((k, v) => {
    let cleave = new Cleave($(v), {
        delimiter: ':',
        blocks: [2, 2, 2],
        numericOnly: true,

    })
});

$(document).ready(function () {
    /**
     *
     * Khi thay đổi kết quả thi đấu
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
            url: apiUrl + "matches/check_double_result",
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
        $('select[name="participant_sub_group"]').parent().hide();
        $('select[name="participant_sub_group"]').val('');
        $('select[name="participant_group"]').parent().hide();
        $('select[name="participant_group"]').val('');
        $('select[name="participant_class"]').parent().hide();
        $('select[name="participant_class"]').val('');

        if (tournament_id) {
            $('#loader').removeClass('hidden');
            $.ajax({
                type: "GET",
                headers: headersClient,
                data: {},
                crossDomain: true,
                secure: true,
                url: apiUrl + "matches/get_tournament_competition/" + tournament_id + "/" + 1,
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
                        $('select[name="participant_sub_group"]').parent().show();
                        $('select[name="participant_group"]').parent().show();
                        $('select[name="participant_class"]').parent().show();
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
     * Chọn môn thi đấu
     */
    $('select[name="tournament_competition"]').change(function () {
        $('select[name="turn"]').children().remove();
        $('select[name="turn"]').parent().hide();
        let idSelect = $(this).val();
    });
    /**
     * Tìm lớp theo đơn vị và khối
     */
    $('select[name="participant_group"]').on('change', function () {
        searchParticipantClass();
    });
    $('select[name="participant_sub_group"]').on('change', function (){
        searchParticipantClass();
    })
    $('.btn-result').on('click', function () {
        let name = $(this).parents().eq(1).children('td').eq(1).text();
        let result = $(this).parents().eq(1).children('td').eq(8).text();
        let id =  $(this).parents().eq(1).attr('id');
        $('#btn-save-modal').attr('match_detail_id', id);
        $('#result-modal').find('.modal-header').text('Vận động viên: ' + name);
        $('#result-modal').find('.modal-body').find('input').val(result);
        $('#result-modal').modal('show');
    });
    /**
     * Update từn lượt thi đấu
     *
     * */
    $('.btn-edit-matches').on('click', function () {
        debugger;
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


});
// ===================================== End main() =========================================================
/**
 * Tìm kiếm lớp theo đơn vị và khối
 *
 */
function searchParticipantClass(){
    let participantGroupId = $('select[name="participant_group"]').val();
    let participantSubGroupId = $('select[name="parcitipant_sub_group"]').val();
    $('#loader').removeClass('hidden');
    $.ajax({
        type: "POST",
        headers: headersClient,
        data: {
            'participant_group_id' : participantGroupId ? participantGroupId : null,
            'participant_sub_group_id': participantSubGroupId ? participantSubGroupId : null,
        },
        crossDomain: true,
        secure: true,
        url: apiUrl + "matches/search-participant-class",
        beforeSend: function (request) {
            request.setRequestHeader("token", token)
        },
        success: function (responseData) {
            if (responseData['status'] == 200) {
                $('select[name="participant_class"]').children().remove();
                let html = ' <option value="">-- Chọn lớp --</option>';
                let result = responseData['result'];
                result.forEach((val, key) => {
                    html += '<option value="' + val.id + '">' + val.name + '</option>';
                });
                $('select[name="participant_class"]').append(html);
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





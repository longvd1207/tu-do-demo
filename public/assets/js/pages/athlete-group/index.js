$(document).ready(function () {
    $('select[name="tournament"]').change(function () {
        let tournament_id = $(this).val();
        $('#competition_configuration_detail').children().remove();
        $('select[name="tournament_competition"]').children().remove();
        $('select[name="tournament_competition"]').parent().hide();
        $('#athlete-setup-list').children().remove();
        $('#athlete-list').children().remove();
        $('#btn-search').parent().hide();
        if (tournament_id) {
            $('#loader').removeClass('hidden');
            $.ajax({
                type: "GET",
                headers: headersClient,
                data: {},
                crossDomain: true,
                secure: true,
                url: apiUrl + "matches/get_tournament_competition/" + tournament_id + "/2",
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

    });
    $('select[name="tournament_competition"]').change(function () {
        $('#btn-search').parent().hide();
        if ($(this).val()) {
            $('#btn-search').parent().show();
        }
    });
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
});

/**
 * Main
 */
$(document).ready(function (e) {
    /**
     * Bắt sự kiển theo thay đổi
     *
    * */
    $('#athlete-setup-list').on('change', function () {
        alertWhenChane();
    });

    $('#athlete-list').on('change', function () {
        alertWhenChane();
    });
    $('#max_number_athlete').on('change', function () {
        alertWhenChane();
    });
    if($('select[name="tournament_competition"]').val() != ''){
        let tournament_competition_id = $('select[name="tournament_competition"]').val();
        tournamentCompetitionSelected = tournamentCompetition.find(function (obj){
            return obj.id == tournament_competition_id;
        })
        getMatches();
    }
    /**
     * Chọn giải đấu
     */

    $('select[name="tournament"]').change(function (){
        let tournament_id = $(this).val();

        $('#competition_configuration_detail').children().remove();
        $('#competition_configuration_detail').hide();
        $('select[name="tournament_competition"]').children().remove();
        $('select[name="tournament_competition"]').parent().hide();
        $('#athlete-setup-list').children('tbody').remove();
        $('#athlete-list').children().remove();
        if(tournament_id){
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
                        responseData['result'].forEach((val, key) =>{
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
            }).done(function (responseData){
                $('#loader').addClass('hidden');
            });
        }
        $('select[name="competition_configuration"]').val('');
        $('select[name="competition_configuration"]').parent().hide();
    })
    /**
     * Chọn môn bơi
     */
    $('select[name="tournament_competition"]').change(function ()   {
        $('#max_number_athlete').val('');
        $('#athlete-setup-list').children('tbody').remove();
        $('#athlete-list').children().remove();

        $('select[name="competition_configuration"]').val('');
        competitionConfigurationSelect = [];
        $('select[name="competition_configuration"]').val('');
        $('select[name="competition_configuration"]').parent().hide();
        let idSelect = $(this).val();
        if(idSelect){
            $('#export-excel').attr('href', clientUrl + '/admin/matches/export_excel_personal/' + idSelect);
        } else {
            $('#export-excel').attr('href', '');
        }

        if(idSelect){
            tournamentCompetitionSelected = tournamentCompetition.find(function (obj){
                return obj.id == idSelect;
            })
            getMatches();
            $('select[name="competition_configuration"]').val(competitionConfigurationSelect && competitionConfigurationSelect['id'] ? competitionConfigurationSelect['id'] : '');
            $('select[name="competition_configuration"]').parent().show();
        }

        $('#competition_configuration_detail').hide();

    });
    /**
     * Chọn cấu hình thi bơi
     */
    $('select[name="competition_configuration"]').change(function () {
        let idSelect = $(this).val();
        competitionConfigurationSelect = competitionConfiguration.find(function (obj){
            return obj.id == idSelect;
        })
        if(competitionConfigurationSelect){
            $('#competition_configuration_detail').children().remove();
            let html ='<div class="col-sm-auto">'+ 'Chiều cao: từ ' + competitionConfigurationSelect.min_athlete_height + '  đến ' + competitionConfigurationSelect.max_athlete_height + '</div>'
                + '<div class="col-sm-auto">'+ 'Cân nặng: từ ' + competitionConfigurationSelect.min_athlete_weight + '  đến ' + competitionConfigurationSelect.max_athlete_weight  + '</div>'
                + '<div class="col-sm-auto">'+' Số làn bơi: ' + competitionConfigurationSelect.max_number_athlete  + '</div>';
            $('#competition_configuration_detail').append(html);
            $('#competition_configuration_detail').show();
        } else {
            $('#competition_configuration_detail').hide();
        }
    });
    /**
     * Setup lượt bơi mặc định theo cấu hình
     */
    $('#btn-setup').on('click', function (e) {
        getMatches();
    });
    /**
     * Thêm mới lượt bơi
     */
    $('#create-turn').on('click', function (e) {

        let height = 0;
        $('#athlete-setup-list').children('tbody').each((k, v) => {
            height += $(v).height();
        });

        $('#table-right').animate({scrollTop: height}, 1000);
        let countTurn = $('#athlete-setup-list').children('tbody').length + 1;
        let html = '<tbody class="nested-list nested-sortable">'
            + '<tr>'
            +'<td colspan="10" class="td-header" >'
            + '<div class="d-flex">'
            + '<div class="flex-grow-1 bg-warning">'
            + '<div class="row">'
            + '<div class="col-2">'
            + '<div class="input-group input-group-sm"><div class="input-group-text">Lượt</div><input class="form-control order_athlete_turn" type="number" value="'+countTurn+'"></div>'
            + '</div>'
            + '<div class="col-10 pt-1">'
            + '<span class="number_athlete_turn" style="font-size: 13px">(Số vận động viên: 0)</span>'
            + '</div>'
            + '</div>'
            + '</div>'
            + '<div class="flex-shrink-0"><button class="btn btn-sm btn-danger delete-turn" >Xóa lượt</button></div>'
            + '</div>'
            + '</td>'
            + '</tr>';
        html += '</tbody>';
        $('#athlete-setup-list').append(html);
        sweetSuccess('Thêm lượt thành công');
        loadNested();
    });
    /**
    * Reset cấu hình
    */
    $('#btn-reset').on('click', (e) => {
        resetTurn();
    });
    /**
     * Lưu cấu hình lượt bơi
     */
    $('#btn-save').on('click', (e) => {
        saveTurns();
    });
    /**
     * Xóa 1 lượt bơi
     */
    $(document).on('click', '.delete-turn', function () {
        console.log('Xóa')
        swal.fire({
            title: 'Xóa lượt?',
            text: 'Bạn có chắc chắn xóa lượt',
            icon: 'warning',
            showCancelButton: true,
            showDenyButton: true,
            confirmButtonColor: '#107fea',
            denyButtonColor: '#d33',
            cancelButtonColor:  'rgba(134,137,155,0.93)',
            denyButtonText: `Xóa lượt`,
            confirmButtonText: 'Xóa và đặt lại lượt',
        }).then(result => {
            if (result.isConfirmed) {
                let child = $(this).parents().eq(4).children().not(':eq(0)');
                $('#athlete-list').append(child);
                $(this).parents().eq(4).remove();
                $('#athlete-setup-list').children('tbody').each((key, val) => {
                    $(val).children('tr').eq(0).find('.order_athlete_turn').val( key+1 );
                });
                let height = $('#athlete-list').height();
                $('#table-left').animate({scrollTop: height}, 1000);
                alertWhenChane();
                sweetSuccess('Xóa và đặt lại lượt thành công');

            } else if (result.isDenied) {
                let child = $(this).parents().eq(4).children().not(':eq(0)');
                $('#athlete-list').append(child);
                $(this).parents().eq(4).remove();
                let height = $('#athlete-list').height();
                $('#table-left').animate({scrollTop: height}, 1000);
                alertWhenChane();
                sweetSuccess('Xóa lượt thành công');
            }

        });
    });
    /**
     * Xóa toàn bộ lượt bơi
     */
    $('#delete-all').on('click', function () {
        swal.fire({
            title: 'Xóa lượt?',
            text: 'Bạn có chắc chắn xóa lượt',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ce1e1e',
            cancelButtonColor:  'rgba(134,137,155,0.93)',
            confirmButtonText: 'Xóa tất cả lượt',
        }).then(result => {
            if (result.isConfirmed) {
                let child = $('#athlete-setup-list').find('.item-nested');
                $('#athlete-list').append(child);
                $('#athlete-setup-list').find('tbody').remove();
                let height = $('#athlete-list').height();
                $('#table-left').animate({scrollTop: height}, 1000);
                let i = 1;
                $('#athlete-list').children('.item-nested').each((k, v) => {
                    if(!$(v).hasClass('hidden')){
                        $(v).find('td').eq(0).text(i);
                        i++;
                    }
                });
                sweetSuccess('Xóa thành công');
            }
        });
    });
    /**
     * Tìm kiếm vận động viên bên table trái
     */
    $('#btn-search-left-table').on('click', function () {
        searchLeftTable();
    });
    var searchLeftTableEvent = $('.search-left-table');
    searchLeftTableEvent.each(function (k, v) {
        v.addEventListener("keypress", function (event) {
            if (event.key === "Enter") {
                event.preventDefault();
                searchLeftTable();
            }
        });
    });
    /**
     * Xem ảnh vận động viên
     */
    $(document).on('click', '.view-image', function () {
        $('#view-image-modal .modal-header').text('Vận động viên: ' + $(this).attr('athlete_name'));
        $('#view-image-modal .modal-body').find('img').attr('src', clientUrl + '/' + $(this).attr('link-image'));
        $('#view-image-modal').modal('show');
    });
    /**
     * Sinh lop ra theo yêu cầu
     */
    $('select[name="participant-group-left-table"]').on('change', function () {
        searchParticipantClass();
    });
    $('select[name="parcitipant-left-table"]').on('change', function () {
        searchParticipantClass();
    });
    $('select[name="participant-class-left-table"]').on('change', function () {
        searchLeftTable();
    });
    /**
     *
     * Xuất excel
     * */

});

/**
 * Render tab cấu hình lượt bơi
 */
function  getMatches(){
    var result = 'giá trị ban đầu';
    let self = this;
    if(!tournamentCompetitionSelected || !tournamentCompetitionSelected.id){
        sweetAlert('Chọn cấu hình')
        return;
    }
    let promise = new Promise((resolve, reject) => {
        $('#loader').removeClass('hidden');
        $.ajax({
            type: "POST",
            headers: headersClient,
            data: {
                'competitionConfigurationSelected': competitionConfigurationSelect,
                'tournament_competition_id': tournamentCompetitionSelected.id,
            },
            crossDomain: true,
            secure: true,
            url: apiUrl + "matches/personal",
            beforeSend: function (request) {
                request.setRequestHeader("token", token)
            },
            success: function (responseData) {
                if (responseData['status'] == 200) {
                    resolve(responseData['result']);
                } else {
                    reject('lỗi truy vấn');
                    sweetAlert('Lỗi truy vấn 1')
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
    promise.then(value => {
        getAthleteSetupByHtml();
        competitionConfigurationSelect = value['competitionConfigurationSelected'];
        if(!athleteSetup || athleteSetup.length == 0){
            if(!$('#max_number_athlete').val()){
                if(competitionConfigurationSelect && competitionConfigurationSelect.id){
                    $('#max_number_athlete').val(competitionConfigurationSelect.max_number_athlete);
                    $('#max_number_athlete').parent().show();
                }
                if(value['athleteTournamentCompetition'] && value['athleteTournamentCompetition'].length){
                    $('#max_number_athlete').val(value['athleteTournamentCompetition'][0]['max_athlete_number']);
                }
            }
            athleteSetup = [];
            let athleteSetupList = [];
            if(value['athleteTournamentCompetition'] && value['athleteTournamentCompetition'].length){
                value['athleteTournamentCompetition'].forEach((match, key) => {
                    let obj = {
                        turn: match['index'],
                        athlete_number: match['athlete_number'],
                        athlete: [],
                    }
                    match['match_detail'].forEach((matchDetail, k) => {
                        obj.athlete.push(matchDetail['athlete']);
                        athleteSetupList.push(matchDetail['athlete']);
                    });
                    athleteSetup.push(obj);
                });
            } else  {
                /*Chọn ưu tiên*/
                athleteSetup = [];
                athleteSetupList = [];
                if( $('#table-left').find('input[type="checkbox"]:checked').length){
                    $('#table-left').find('input[type="checkbox"]:checked').each((key, val) => {
                        let objAthlete = value['athlete'].find((ath => {
                            return $(val).attr('athlete_id') == ath.id
                        }));
                        objAthlete.checked = true;
                        athleteSetupList.push(objAthlete);
                    });
                    value['athleteCompetition'].forEach((val, key) => {
                        if(val && !athleteSetupList.find( v => {return v.id == val.id})) {
                            val.checked = false
                            athleteSetupList.push(val);
                        }
                    });

                } else {
                    /*Không chọn ưu tiên*/
                    athleteSetupList = value['athleteCompetition'];

                }
                let i = 1;
                for (let x = 0; x < athleteSetupList.length; x += Number(value['competitionConfigurationSelected']['max_number_athlete'])) {
                    let end = x + Number(value['competitionConfigurationSelected']['max_number_athlete']);
                    let obj = {
                        turn: i,
                        athlete_number: athleteSetupList.slice(x, end).length,
                        athlete: athleteSetupList.slice(x, end),
                    }
                    i++;
                    athleteSetup.push(obj);
                }
            }


            athlete = [];
            value['athlete'].forEach((v, k) => {
                if(!value['athleteCompetition'].find(vCompetition => {return v.id == vCompetition.id}) && !athleteSetupList.find(vAthleteSetup => {return v.id == vAthleteSetup.id}) ){
                    v.satisfy = 0;
                    athlete.push(v);
                }
            });
            renderListAthlete(athlete);
            return athleteSetup;
        } else {
            athlete = [];
            $('.satisfy-item').removeClass('satisfy-item');
            let leftMenu = $('#athlete-list .nested-1');
            let satisfyList = [];
            let notSatisfyList = [];
            /*chỉnh sửa lại left-menu cho tất cả các phần tử thỏa mãn yêu cầu lên đầu */
            leftMenu.each((key, val) => {
                if(!value['athleteCompetition'].find(vCompetition => {return $(val).attr('id') == vCompetition.id})){
                    $(val).removeClass('satisfy-item');
                    notSatisfyList.push(val);
                } else {
                    $(val).addClass('satisfy-item');
                    satisfyList.push(val);
                }
            });
            $('#athlete-list .nested-1').remove();
            satisfyList.forEach((val, key) => {
                $('#athlete-list').append($(val));
            });
            notSatisfyList.forEach((val, key) => {
                $('#athlete-list').append($(val));
            });
        }

    }).then(value => {
        if(value) {
            value.forEach(function (match, key) {
                let html = '<tbody class="nested-list nested-sortable">'
                    + '<tr>'
                        +'<td colspan="10" class="td-header" >'
                            + '<div class="d-flex">'
                                + '<div class="flex-grow-1 bg-success text-white">'
                                    + '<div class="row">'
                                        + '<div class="col-2">'
                                            + '<div class="input-group input-group-sm"><div class="input-group-text">Lượt</div><input class="form-control order_athlete_turn" type="number" value="'+ match['turn']+'" ></div>'
                                        + '</div>'
                                        + '<div class="col-10 pt-1">'
                                            + '<span class="number_athlete_turn" style="font-size: 13px">( Số vận động viên: ' + match['athlete_number'] + ' )</span>'
                                        + '</div>'
                                    + '</div>'
                                + '</div>'
                                + '<div class="flex-shrink-0"><button class="btn btn-sm btn-danger delete-turn" >Xóa lượt</button></div>'
                            + '</div>'
                        + '</td>'
                    + '</tr>';
                match['athlete'].forEach((v, k) => {
                    html += '<tr class="nested-1 item-nested" id="' + v.id + '" name="' + v.name + '">'
                        + '<td class="text-center">'+ (k+1)+'</td>'
                        + '<td class="text-center">' + v.athlete_code + '</td>'
                        + '<td>' + v.name + '</td>'
                        + '<td>' + (v['participant_class'] && v['participant_class']['id'] ? v['participant_class']['participant_group_name'] : '') + '</td>'
                        + '<td>' + (v['participant_class'] && v['participant_class']['id'] ? v['participant_class']['parcitipant_sub_group_name'] : '') + '</td>'
                        + '<td>' + (v['participant_class'] && v['participant_class']['id'] ? v['participant_class']['name'] : '') + '</td>'
                        + '<td class="text-center">' + v.height + '</td>'
                        + '<td class="text-center">' + v.weight + '</td>'
                        + '<td class="text-center"><icon class="ri-eye-line view-image" style="cursor: pointer" link-image="' + v.image_path + '" athlete_name="' + v.name + '"></icon></td>'
                        + '<td class="text-center"><input type="checkbox" athlete_id="' + v.id + '" '+ (v.checked ? 'checked' : '') +'></td>'
                        + ' </tr>';
                });
                html += '</tbody>';
                $('#athlete-setup-list').append(html);
                loadNested();

            });
        }
        return 1;
    }).then( value => {
        let i = 1;
        $('#athlete-list').children('.item-nested').each((k, v) => {
            if(!$(v).hasClass('hidden')){
                $(v).find('td').eq(0).text(i);
                i++;
            }
        });
    });

}


/**
 * Js gọi kéo thả
 */
function loadNested(){
    let nestedSortables = [].slice.call(document.querySelectorAll('.nested-sortable')); // Loop through each nested sortable element

    if (nestedSortables) nestedSortables.forEach(function (nestedSort) {
        new Sortable(nestedSort, {
            group: 'nested',
            animation: 150,
            fallbackOnBody: true,
            swapThreshold: 0.65,
            draggable: ".item-nested",
        });
    }); // Nested sortable handle demo

    let nestedSortablesHandles = [].slice.call(document.querySelectorAll('.nested-sortable-handle'));
    if (nestedSortablesHandles) // Loop through each nested sortable element
        nestedSortablesHandles.forEach(function (nestedSortHandle) {
            new Sortable(nestedSortHandle, {
                handle: '.handle',
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
                draggable: ".item-nested",
            });
        });
}

/**
 * render tab danh sách vận động viên
 * @param athlete
 */
function renderListAthlete(athlete) {
    $('#athlete-list').children().remove();
    let html = '';
    athlete.forEach(function (v, k) {
        html += '<tr class="nested-1 item-nested" id="' + v.id + '" name="' + v.name + '">'
            + '<td class="text-center"> ' + (k+1) + '</td>'
            + '<td class="text-center">' + v.athlete_code + '</td>'
            + '<td>' + v.name + '</td>'
            + '<td>' + (v['participant_class'] && v['participant_class']['id'] ? v['participant_class']['participant_group_name'] : '') + '</td>'
            + '<td>' + (v['participant_class'] && v['participant_class']['id'] ? v['participant_class']['parcitipant_sub_group_name'] : '') + '</td>'
            + '<td>' + (v['participant_class'] && v['participant_class']['id'] ? v['participant_class']['name'] : '') + '</td>'
            + '<td class="text-center">' + v.height + '</td>'
            + '<td class="text-center">' + v.weight + '</td>'
            + '<td class="text-center"><icon class="ri-eye-line view-image" style="cursor: pointer" link-image="' + v.image_path + '" athlete_name="' + v.name + '"></icon></td>'
            + '<td class="text-center"><input type="checkbox" athlete_id="' + v.id + '"></td>'
            + '</tr>';
    });
    $('#athlete-list').append(html);
}
function resetTurn(){
    swal.fire({
        title: 'Reset cấu hình?',
        text: 'Xóa toàn bộ cấu hình và danh sách vận động viên',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor:  'rgba(134,137,155,0.93)',
        confirmButtonText: 'Reset',
    }).then(result => {
        if(!result.isDismissed){
            $('#max_number_athlete').val('');

            athleteSetup = null;
            athlete = null;
            $('#athlete-setup-list').children('tbody').remove();
            $('#athlete-list').children().remove();

            $('select[name="competition_configuration"]').val('');
            $('select[name="competition_configuration"]').parent().hide();
            $('select[name="tournament"]').val('');
            $('select[name="tournament_competition"]').val('');
            $('select[name="tournament_competition"]').parent().hide() ;
            $('#competition_configuration_detail').children().remove;
            $('#competition_configuration_detail').hide();
        }
    });
}

/**
 * Lấy danh sách vậng động viên theo form html đang nhập
 */
function getAthleteSetupByHtml(){
    athleteSetup = [];
    $('#athlete-setup-list').children('tbody').each((key, val) => {

        let obj = {
            'turn': key + 1,
            'athlete': []
        };
        $(val).children('.nested-1').each((k, v) => {
            let objAthlete = {
                'id': $(v).attr('id'),
                'name': $(v).attr('name'),
            }
            obj.athlete.push(objAthlete);
        });
        if (obj.athlete.length > 0) {
            athleteSetup.push(obj);
        }

    });
}

/**
 * Lưu cấu hình lượt bơi đã setup
 */
function saveTurns() {
    athleteSetup = [];

    let promise = new Promise((resolve, reject) => {
        swal.fire({
            title: 'Lưu?',
            text: 'Lưa cấu hình đã chọn',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#107fea',
            cancelButtonColor:  'rgb(241,78,78)',
            confirmButtonText: 'Lưu',
        }).then(result => {
            if(!result.isDismissed){
                $('#athlete-setup-list').children('tbody').each((key, val) => {
                    let turn = $(val).find('.order_athlete_turn').val();
                    if(!turn){
                        sweetAlert('Nhập lượt bơi');
                        reject(0);
                    }
                    if($(val).children('.item-nested').length > Number($('#max_number_athlete').val())) {
                        sweetAlert('Lượt bơi ' + turn + ': vận động viên vượt quá ' + $('#max_number_athlete').val() + ' người', 2500);
                        reject(0);
                    }
                    let obj = {
                        'turn': Number(turn),
                        'athlete': []
                    };
                    $(val).children('.item-nested').each((k, v) => {
                        let objAthlete = {
                            'id': $(v).attr('id'),
                            'name': $(v).attr('name'),
                        }
                        obj.athlete.push(objAthlete);
                    });
                    if (obj.athlete.length > 0) {
                        athleteSetup.push(obj);
                    }

                });

                resolve(1);
            }
        });
    });
    promise.then(value => {
        $('#loader').removeClass('hidden');
        $.ajax({
            type: "POST",
            headers: headersClient,
            data: {
                'athlete_setup': athleteSetup,
                'tournament_id': $('select[name="tournament"]').val(),
                'tournament_competition_id': $('select[name="tournament_competition"]').val(),
                'competition_id': tournamentCompetitionSelected.competition_id,
                'max_athlete_number': Number($('#max_number_athlete').val())
            },
            crossDomain: true,
            secure: true,
            url: apiUrl + "matches/personal/create",
            beforeSend: function (request) {
                request.setRequestHeader("token", token)
            },
            success: function (responseData) {
                if (responseData['status'] == 200) {
                    sweetSuccess(responseData['result']);
                }else if(responseData['status'] == 90) {
                    sweetAlert(Object.values(responseData['result'])[0][0], 3000);
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
    });
}

function searchLeftTable(){
    let name = $('input[name="name-left-table"]').val();
    let code = $('input[name="code-left-table"]').val();
    let participantGroup = $('select[name="participant-group-left-table"]').val();
    let participantSubGroup = $('select[name="parcitipant-left-table"]').val();
    let participantClass = $('select[name="participant-class-left-table"]').val();
    if($('#athlete-list').children().length == 0){
        sweetAlert('Chọn giải đấu và môn thi đấu');
        return;
    }
    $('#loader').removeClass('hidden');
    $.ajax({
        type: "POST",
        headers: headersClient,
        data: {
            'code': code ? code:  null,
            'name' : name ? name : null,
            'participantGroup' : participantGroup ? participantGroup : null,
            'participantSubGroup': participantSubGroup ? participantSubGroup : null,
            'participantClass': participantClass ? participantClass : null,
        },
        crossDomain: true,
        secure: true,
        url: apiUrl + "matches/search-left-table",
        beforeSend: function (request) {
            request.setRequestHeader("token", token)
        },
        success: function (responseData) {
            $('#athlete-list').children('.nested-1').removeClass('hidden');
            if (responseData['status'] == 200) {
                let searchAthlete = responseData['result'];
                $('#athlete-list').children().addClass('hidden');
                $('#athlete-list').children().each((key, val) => {
                    let index = searchAthlete.findIndex((v) => {
                        return v.id == $(val).attr('id');
                    });
                    if( index > -1 ){
                        $(val).removeClass('hidden');
                    }
                });
                let i = 1;
                $('#athlete-list').children('.item-nested').each((k, v) => {
                    if(!$(v).hasClass('hidden')){
                        $(v).find('td').eq(0).text(i);
                        i++;
                    }
                });
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

/**
 * Tìm kiếm lớp theo đơn vị và khối
 *
 */
function searchParticipantClass(){
    let participantGroupId = $('select[name="participant-group-left-table"]').val();
    let participantSubGroupId = $('select[name="parcitipant-left-table"]').val();
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
            $('#athlete-list').children('.nested-1').removeClass('hidden');
            if (responseData['status'] == 200) {
                $('select[name="participant-class-left-table"]').children().remove();
                let html = ' <option value="">-- Lớp --</option>';
                let result = responseData['result'];
                result.forEach((val, key) => {
                    html += '<option value="' + val.id + '">' + val.name + '</option>';
                });
                $('select[name="participant-class-left-table"]').append(html);
                searchLeftTable();
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
function alertWhenChane(){
    $('#athlete-setup-list').children('tbody').each((key, val) => {
        let html = '(Số vận động viên: ' + $(val).children('.nested-1').length + ')';
        $(val).children('tr').eq(0).find('.number_athlete_turn').text('');
        $(val).children('tr').eq(0).find('.number_athlete_turn').append(html);
        if( $(val).children('.item-nested').length > Number($('#max_number_athlete').val()) || $(val).children('.nested-1').length == 0) {
            $(val).children('tr').eq(0).find('.flex-grow-1').addClass('bg-warning');
            $(val).children('tr').eq(0).find('.flex-grow-1').removeClass('bg-success text-white');
        } else {
            $(val).children('tr').eq(0).find('.flex-grow-1').removeClass('bg-warning');
            $(val).children('tr').eq(0).find('.flex-grow-1').addClass('bg-success text-white');
        }
        $(val).children('.item-nested').each((k, v) => {
            $(v).find('td').eq(0).text(k + 1);
        });
    });
    let i = 1;
    $('#athlete-list').children('.item-nested').each((k, v) => {
        if(!$(v).hasClass('hidden')){
            $(v).find('td').eq(0).text(i);
            i++;
        }
    });
}

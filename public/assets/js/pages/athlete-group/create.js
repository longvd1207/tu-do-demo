/**
 * Main
 */
$(document).ready(function (e) {
    if($('select[name= "tournament_competition"]').val()){
        tournamentCompetitionSelected = tournamentCompetition.find(function (obj){
            return obj.id == $('select[name= "tournament_competition"]').val();
        })
        getGroups();
    }
    $('#athlete-setup-list').on('change', function () {
        alertWhenChange();
    });

    $('#athlete-list').on('change', function () {
        alertWhenChange();
    });
    $('#number_in_group').on('change', function () {
        alertWhenChange();
    });
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
                url: apiUrl + "matches/get_tournament_competition/" + tournament_id + "/2",
                beforeSend: function (request) {
                    request.setRequestHeader("token", token);
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
        $('#max_number_athlete').text('');
        $('#max_number_athlete').parent().hide();
        $('#athlete-setup-list').children('tbody').remove();
        $('#athlete-list').children().remove();
        $('select[name="competition_configuration"]').val('');
        competitionConfigurationSelect = [];
        $('select[name="competition_configuration"]').val('');
        $('select[name="competition_configuration"]').parent().hide();
        let idSelect = $(this).val();
        if(idSelect){
            tournamentCompetitionSelected = tournamentCompetition.find(function (obj){
                return obj.id == idSelect;
            })
            getGroups();
            $('select[name="competition_configuration"]').val(competitionConfigurationSelect && competitionConfigurationSelect['id'] ? competitionConfigurationSelect['id'] : '');
            $('select[name="competition_configuration"]').parent().show();
        }

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
                + '<div class="col-sm-auto">'+' Số vận động viên trong nhóm: ' + competitionConfigurationSelect.number_in_group  + '</div>';
            $('#competition_configuration_detail').append(html);
            $('#competition_configuration_detail').show();
        } else {
            $('#competition_configuration_detail').hide();
        }
    });
    /**
     * Setup nhóm bơi mặc định theo cấu hình
     */
    $('#btn-setup').on('click', function (e) {
        if(tournamentCompetitionSelected && tournamentCompetitionSelected.id){
            getGroups();
            $('#create-turn').show();
            $('#btn-save').show();
        } else {
            sweetAlert('Vui lòng chọn môn thi đấu', 2500);
        }

    });
    /**
     * Thêm nhóm mới
     */
    $(document).on('click', '#create-turn', function (e) {
            let height = 0;
            $('#athlete-setup-list').children('tbody').each((k, v) => {
                height += $(v).height();
            });

            $('#table-right').animate({scrollTop: height}, 1000);
            let countTurn = $('#athlete-setup-list').children('tbody').length + 1;
            let html = '<tbody class="nested-list nested-sortable">'
                + '<tr>'
                +'<td colspan="9" class="td-header" >'
                + '<div class="d-flex">'
                + '<div class="input-group input-group-sm flex-grow-1 pr-2">'
                + '<div class="input-group-text">Tên nhóm</div>'
                + '<input type="text" class="form-control name-group" value="Nhóm ' + countTurn  +'">'
                + '<div class="input-group-text show-length-athlete bg-warning">0 Vận động viên</div>'
                + '</div>'
                + '<div class="flex-shrink-0"><button class="btn btn-sm btn-danger delete-turn" >Xóa nhóm</button></div>'
                + '</div>'
                + '</td>'
                + '</tr>';
            html += '</tbody>';
            $('#athlete-setup-list').append(html);
            sweetSuccess('Thêm nhóm thành công');
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
     * Xóa nhóm
     */
    $(document).on('click', '.delete-turn', function () {
        swal.fire({
            title: 'Reset cấu hình?',
            text: 'Bạn có chắc chắn xóa nhóm',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor:  'rgba(134,137,155,0.93)',
            confirmButtonText: 'Xóa',
        }).then(result => {
            if(!result.isDismissed){
                let child = $(this).parents().eq(4).children().not(':eq(0)');
                $('#athlete-list').append(child);
                $(this).parents().eq(4).remove();

                let height = $('#athlete-list').height();
                $('#table-left').animate({scrollTop: height}, 1000);
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
});

/**
 * Render tab cấu hình lượt bơi
 */
function    getGroups(){
    var result = 'giá trị ban đầu';
    let self = this;

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
            url: apiUrl + "athlete-group",
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
        /*Setup số lượng vận động viên tối đa*/

        if(!$('#number_in_group').val()){
            if(competitionConfigurationSelect && competitionConfigurationSelect.id){
                $('#number_in_group').val(competitionConfigurationSelect.number_in_group);
            }
        }
        /*Sử lý bảng bên phải - table left render*/
        athlete = [];
        $('.satisfy-item').removeClass('satisfy-item');
        let leftTable = $('#athlete-list .nested-1');
        if(leftTable.length) {
            let satisfyList = [];
            let notSatisfyList = [];
            /*chỉnh sửa lại left-menu cho tất cả các phần tử thỏa mãn yêu cầu lên đầu */
            leftTable.each((key, val) => {
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
        } else {
            athlete = [];
            value['athlete'].forEach((v, k) => {
                if(!value['athleteTournamentCompetition'].find(vAthleteTournamentCompetition => { return v.id == vAthleteTournamentCompetition.athlete_id})){
                    athlete.push(v);
                }
            });
            renderListAthlete(athlete);
        }
        /*Render cấu hình bên phải table- right menu*/
        if(!athleteSetup || athleteSetup.length == 0){
            if(value['athlete_setup']) {
                value.forEach(function (athlete, key) {
                    let html = '<tbody class="nested-list nested-sortable">'
                        + '<tr>'
                        +'<td colspan="9" class="td-header" >'
                        + '<div class="d-flex">'
                        + '<h4 style="font-size: 15px; font-weight: bold" class="text-white bg-success flex-grow-1 p-1" turn="' + (key + 1) + '"> <i class="las la-swimmer "></i> ' + 'Lượt ' + (key + 1) + '( Số vận động viên: ' + (athlete.length) + ' )' + '</h4>'
                        + '<div class="flex-shrink-0"><button class="btn btn-sm btn-danger delete-turn" >Xóa lượt</button></div>'
                        + '</div>'
                        + '</td>'
                        + '</tr>';
                    athlete.forEach((v, k) => {
                        html += '<tr class="nested-1" id="' + v.id + '" name="' + v.name + '">'
                            + '<td class="text-center">' + (k + 1) + '</td>'
                            + '<td class="text-center">' + v.athlete_code + '</td>'
                            + '<td>' + v.name + '</td>'
                            + '<td>' + (v['participant_group'] && v['participant_group']['name'] ? v['participant_group']['name'] : '') + '</td>'
                            + '<td>' + (v['participant_sub_group'] && v['participant_sub_group']['name'] ? v['participant_sub_group']['name'] : '') + '</td>'
                            + '<td>' + v.height + '</td>'
                            + '<td>' + v.weight + '</td>'
                            + ' </tr>';
                    });
                    html += '</tbody>';
                    $('#athlete-setup-list').append(html);
                    loadNested();
                });
            }
        }

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
            + '<td class="text-center">' + (k +1 ) + '</td>'
            + '<td class="text-center">' + v.athlete_code + '</td>'
            + '<td>' + v.name + '</td>'
            + '<td>' + (v['participant_class'] && v['participant_class']['id'] ? v['participant_class']['participant_group_name'] : '') + '</td>'
            + '<td>' + (v['participant_class'] && v['participant_class']['id'] ? v['participant_class']['parcitipant_sub_group_name'] : '') + '</td>'
            + '<td>' + (v['participant_class'] && v['participant_class']['id'] ? v['participant_class']['name'] : '') + '</td>'
            + '<td class="text-center">' + v.height + '</td>'
            + '<td class="text-center">' + v.weight + '</td>'
            + '<td class="text-center"><icon class="ri-eye-line view-image" style="cursor: pointer" link-image="' + v.image_path + '" athlete_name="' + v.name + '"></icon></td>'
            + '</tr>';
    });
    $('#athlete-list').append(html);
}
function resetTurn(){
    swal.fire({
        title: 'Reset cấu hình?',
        text: 'Đặt lại và thêm mới',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor:  'rgba(134,137,155,0.93)',
        confirmButtonText: 'Reset',
    }).then(result => {
        if(!result.isDismissed){
            $('#btn-save').show();
            $('#create-turn').show();
            $('#athlete-setup-list').children('tbody').remove();
            getGroups();
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
        $(val).children('.nested-sortable').children().each((k, v) => {
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
                    if(!$(val).find('.name-group').val()){
                        sweetAlert('Nhóm bơi không được bỏ trống tên', 2000);
                        reject(0);
                    }
                    if($(val).children('.nested-1').length != Number($('#number_in_group').val())) {
                        sweetAlert('Số vận động viên trong nhóm không phù hợp', 2500);
                        reject(0);
                    }
                    let obj = {
                        'group_name': $(val).find('.name-group').val(),
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
                'number_in_group': Number($('#number_in_group').val())
            },
            crossDomain: true,
            secure: true,
            url: apiUrl + "athlete-group/create",
            beforeSend: function (request) {
                request.setRequestHeader("token", token)
            },
            success: function (responseData) {
                if (responseData['status'] == 200) {
                    sweetSuccess(responseData['result']);
                    $('#btn-save').hide();
                    $('#create-turn').hide();
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
function alertWhenChange(){
    $('#athlete-setup-list').children('tbody').each((key, val) => {
        let turn = $(val).children('tr').eq(0).find('.show-length-athlete').attr('turn');
        $(val).children('tr').eq(0).find('.show-length-athlete').text('');
        $(val).children('tr').eq(0).find('.show-length-athlete').text('');
        $(val).children('tr').eq(0).find('.show-length-athlete').text($(val).children('.nested-1').length + ' vận động viên');
        if( ($(val).children('.nested-1').length ) != Number($('#number_in_group').val())) {
            $(val).children('tr').eq(0).find('.show-length-athlete').addClass('bg-warning');
            $(val).children('tr').eq(0).find('.show-length-athlete').removeClass('bg-success text-white');
        } else {
            $(val).children('tr').eq(0).find('.show-length-athlete').removeClass('bg-warning');
            $(val).children('tr').eq(0).find('.show-length-athlete').addClass('bg-success text-white');
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


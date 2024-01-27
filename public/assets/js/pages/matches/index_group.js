/**
 * Main
 */
$(document).ready(function (e) {
    $('#athlete-setup-list').on('change', function () {
        alertWhenChange();
    });

    $('#athlete-list').on('change', function () {
        alertWhenChange()
    });
    $('#max_number_group').on('change', function () {
        alertWhenChange()
    });
    /**
    * Nếu giải đấu đã chọn
    */
    if($('select[name="tournament_competition"]').val() != ''){
        getGroups();
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
                url: apiUrl + "matches/get_tournament_competition/" + tournament_id + "/2",
                beforeSend: function (request) {
                    request.setRequestHeader("token", token)
                },
                success: function (responseData) {
                    if (responseData['status'] == 200) {
                        $('#create-turn').hide();
                        $('#btn-save').hide();
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
        $('#athlete-setup-list').children('tbody').remove();
        $('#athlete-list').children('.nested-1').remove();
        $('#create-turn').hide();
        $('#btn-save').hide();
        $('select[name="competition_configuration"]').val('');
        $('select[name="competition_configuration"]').val('');
        $('select[name="competition_configuration"]').parent().hide();
        let idSelect = $(this).val();
        if(idSelect){
            getGroups();
            $('select[name="competition_configuration"]').parent().show();
            $('#export-excel').attr('href', clientUrl + '/admin/matches/export_excel_group/' + idSelect);
        } else {
            $('#export-excel').attr('href', '');
        }


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
            +'<td colspan="11" class="td-header" >'
            + '<div class="d-flex">'
            + '<div class="flex-grow-1 bg-warning">'
            + '<div class="row">'
            + '<div class="col-2">'
            + '<div class="input-group input-group-sm"><div class="input-group-text">Lượt</div><input class="form-control order_athlete_turn" type="number" value="'+countTurn+'"></div>'
            + '</div>'
            + '<div class="col-10 pt-1">'
            + '<span class="number_athlete_turn" style="font-size: 13px">(Số nhóm: 0)</span>'
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
     * Xóa 1 lượt bơi
     */
    $(document).on('click', '.delete-turn', function () {
        swal.fire({
            title: 'Reset cấu hình?',
            text: 'Bạn có chắc chắn xóa lượt',
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
                alertWhenChange();
                sweetSuccess('Xóa thành công');
            }
        });
    });

    $(document).on('click', '.btn-view', function () {
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
                'tournament_id': $('select[name="tournament"]').val(),
                'tournament_competition_id':  $('select[name="tournament_competition"]').val(),
            },
            crossDomain: true,
            secure: true,
            url: apiUrl + "matches/group",
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
        if (value['athleteGroup']) {
            $('#create-turn').show();
            $('#btn-save').show();
            let athleteGroupLeft = [];
            let athleteGroupRight = [];
            /**
             * athleteGroupOld : danh sach van dong vien co da duoc gan trong lượt thi đấu
             */
            let athleteGroupOld = [];
            $('#max_number_group').val(value['max_number_group']);
            if (value['athleteGroupTournamentCompetition'].length > 0) {
                value['athleteGroupTournamentCompetition'].forEach((matches, kMatches) => {
                    /*Lấy danh sách vận đọng viên đã đã setup*/
                    matches['match_detail'].forEach((matchDetail, kMatchDetail) => {
                        athleteGroupOld.push(matchDetail['athlete_group']);
                    });

                    /*Render danh sách vận động viêt bên trái */
                    let  html = '<tbody class="nested-list nested-sortable">'
                        +'<td colspan="6" class="td-header" >'
                        + '<div class="d-flex">'
                        + '<div class="flex-grow-1 bg-success text-white">'
                        + '<div class="row">'
                        + '<div class="col-2">'
                        + '<div class="input-group input-group-sm"><div class="input-group-text">Lượt</div><input class="form-control order_athlete_turn" type="number" value="'+ (matches['index'])+'" ></div>'
                        + '</div>'
                        + '<div class="col-10 pt-1">'
                        + '<span class="number_athlete_turn" style="font-size: 13px">( Số nhóm: ' + matches['match_detail'].length + ' )</span>'
                        + '</div>'
                        + '</div>'
                        + '</div>'
                        + '<div class="flex-shrink-0"><button class="btn btn-sm btn-danger delete-turn" >Xóa lượt</button></div>'
                        + '</div>'
                        + '</td>'
                        + '</tr>';
                    matches['match_detail'].forEach((matchDetail, kMatchDetail) => {
                        html += '<tr class="nested-1 item-nested" id="' + matchDetail['athlete_group'].id + '" name="' + matchDetail['athlete_group'].name + '">'
                            + '<td class="text-center">' + matchDetail['swimming_lane'] + '</td>'
                            + '<td class="text-center">' + matchDetail['athlete_group'].code + '</td>'
                            + '<td>' + matchDetail['athlete_group'].name + '</td>'
                            + '<td style="width: 40%">' + matchDetail['athlete_group'].participant_group_name + '</td>'
                            + '<td>' + ( matchDetail['athlete_group'].description ? matchDetail['athlete_group'].description : '')    + '</td>'
                            + '<td class="text-center"><button class="btn btn-sm btn-warning btn-view" athlete_group_id="'+matchDetail['athlete_group'].id+'" title="Xem"><i class="ri-eye-line"></i></button></td>'
                            + ' </tr>'
                    });
                    html += '</tbody>'
                    $('#athlete-setup-list').append(html);
                });

            }
            /*Render bên Phải*/
            value['athleteGroup'].forEach((item, key) => {
                if (!athleteGroupOld.find(i => {
                    return i.id == item.id
                })) {
                    athleteGroupLeft.push(item);
                }
            });
            renderListAthlete(athleteGroupLeft);
            loadNested();
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
            + '<td class="text-center">' + (k+1) + '</td>'
            + '<td class="text-center">' + v.code + '</td>'
            + '<td>' + v.name + '</td>'
            + '<td style="width: 40%">' + v.participant_group_name + '</td>'
            + '<td>' + ( v.description ? v.description : '')    + '</td>'
            + '<td class="text-center"><button class="btn btn-sm btn-warning btn-view" athlete_group_id="'+v.id+'" title="Xem"><i class="ri-eye-line"></i></button></td>'
            + ' </tr>'
    });
    $('#athlete-list').append(html);
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
                let athleteSetup = [];
                $('#athlete-setup-list').children('tbody').each((key, val) => {
                    let turn = $(val).find('.order_athlete_turn').val();
                    if($(val).children('.item-nested').length > Number($('#max_number_group').val())){
                        sweetAlert('Lượt ' + turn + ': số vậng động viên không phù hợp');
                        reject(0);
                    }

                    let obj = {
                        'turn': Number(turn),
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
                resolve(athleteSetup);
            }
        });
    });
    promise.then(value => {
        $('#loader').removeClass('hidden');
        $.ajax({
            type: "POST",
            headers: headersClient,
            data: {
                'athleteGroup_setup': value,
                'tournament_id': $('select[name="tournament"]').val(),
                'tournament_competition_id': $('select[name="tournament_competition"]').val(),
                'max_number_group': Number($('#max_number_group').val()),
            },
            crossDomain: true,
            secure: true,
            url: apiUrl + "matches/group/create",
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
        },
        crossDomain: true,
        secure: true,
        url: apiUrl + "matches/search-left-table-group",
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
function alertWhenChange(){
    $('#athlete-setup-list').children('tbody').each((key, val) => {
        let html = '(Số nhóm: ' + $(val).children('.nested-1').length + ')';
        $(val).children('tr').eq(0).find('.number_athlete_turn').text('');
        $(val).children('tr').eq(0).find('.number_athlete_turn').append(html);
        if( $(val).children('.item-nested').length > Number($('#max_number_group').val()) || $(val).children('.nested-1').length == 0) {
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



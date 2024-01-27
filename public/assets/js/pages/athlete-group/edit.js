 $(document).ready(function () {
     $('#athlete-setup-list').on('change', function () {
         alertWhenChange();
     });

     $('#athlete-list').on('change', function () {
         alertWhenChange();
     });
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
                if(  $('#athlete-setup-list').children('.nested-1').length  !=  Number($('#number_in_group').text()) ) {
                    sweetAlert('Số vận động viên không phù hợp');
                    reject(0);
                }
                let athlete = [];
                $('#athlete-setup-list').children('.item-nested').each((key, val) => {
                    let obj = {
                        name: $(val).text(),
                        id : $(val).attr('id'),
                    }
                    athlete.push(obj);
                });

                resolve(athlete);
            }
        });
    });
    promise.then(value => {
        debugger;
        $('#loader').removeClass('hidden');
        $.ajax({
            type: "PUT",
            headers: headersClient,
            data: {
                'athlete' : value,
            },
            crossDomain: true,
            secure: true,
            url: apiUrl + "athlete-group/"+athleteGroupId + '/edit',
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


/**
 * Tìm kiếm danh sách vận động viên bảng trái
 */
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
     $('#athlete-setup-list').children('.item-nested').each((k, v) => {
         $(v).find('td').eq(0).text(k + 1);
     });
     let i = 1;
     $('#athlete-list').children('.item-nested').each((k, v) => {
         if(!$(v).hasClass('hidden')){
             $(v).find('td').eq(0).text(i);
             i++;
         }
     });
     if( $('#athlete-setup-list').children('.item-nested').length != Number($('#number_in_group').text())){
         $('.td-alert').show();
     } else {
         $('.td-alert').hide();
     }
 }

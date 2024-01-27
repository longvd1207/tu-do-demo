let main = {
  token: '',
  timestamp: function () {
    return new Date().getTime()
  },
}

function deleteItems(id, param, url, text_alert = 'Bạn có chắc chắn muốn xoá không ?') {
  Swal.fire({
    title: '',
    text: text_alert,
    type: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Có',
    cancelButtonText: 'Không',
  }).then(result => {
    if (result.value) {
      main_layout.show_loader()
      $.ajax({
        url: url,
        type: 'DELETE',
        data: {
          _token: main.token,
        },
        success: function (result) {
          main_layout.hide_loader()
          if ('success' in result) {
            $('#' + param + id).remove()
            Swal.fire({
              text: 'Xóa thành công !',
              icon: 'success',
              timer: 1500,
            })
          } else {
            Swal.fire({
              text: result['error'],
              icon: 'error',
              timer: 1500,
            })
          }
        },
        Error: function (result) {
          main_layout.hide_loader()
          Swal.fire({
            text: 'Xóa không thành công!',
            icon: 'error',
            timer: 1500,
          })
        },
      })
    }
  })
}

$(document).on('click', '.hapus-btn', function (e) {
  e.preventDefault();

  var userId = $(this).data('id') || $(this).attr('value');
  var deleteUrl = $(this).data('url') || ('/delete-user/' + userId);
  var namaKaryawan = $(this).data('nama');

  if (!userId) {
    alert("Gagal mendapatkan ID!");
    return;
  }

  $('#user_id').val(userId);
  $('#spb_id').val(userId);
  $('#deleteForm').attr('action', deleteUrl);
  if (namaKaryawan) {
    $('#deleteModalText').text('Apakah ingin menghapus data karyawan ' + namaKaryawan + '?');
  } else {
    $('#deleteModalText').text('Apakah ingin menghapus data karyawan?');
  }

  $('#deleteModal').modal('show');
});

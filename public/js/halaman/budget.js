
$(document).ready(function () {
  $('.hapus-btn').click(function (e) {
    e.preventDefault();

    var anggaran_id = $(this).attr('value');
    $('#anggaran_id').val(anggaran_id);
    $('#deleteForm').attr('action', '/delete-budget/' + anggaran_id); // Update the form action URL dynamically

    $('#deleteModal').modal('show');
  });
});

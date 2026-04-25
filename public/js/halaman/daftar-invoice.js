
$(document).ready(function () {
  $('.hapus-btn').click(function (e) {
    e.preventDefault();

    var inv_id = $(this).attr('value');
    $('#inv_id').val(inv_id); // Mengatur nilai inv_id
    $('#deleteForm').attr('action', 'invoice/deleteInvoice/' + inv_id); // Update the form action URL dynamically
    $('#deleteModal').modal('show');
  });
});
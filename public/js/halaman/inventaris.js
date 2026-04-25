window.onload = function () {
}
$(document).ready(function () {
  $('.hapus-btn').click(function (e) {
    e.preventDefault();

    var inventaris_id = $(this).attr('value');
    $('#inventaris_id').val(inventaris_id);
    $('#deleteForm').attr('action', '/invoice/deleteInventaris/' + inventaris_id); // Update the form action URL dynamically
    // var barangId = $(this).closest('tr').find('input[name="barang_delete_id"]').data('barang-id');
    // $('#deleteForm').attr('action', '/invoice/deleteBarang/' + barangId);
    $('#deleteModal').modal('show');
  });
});

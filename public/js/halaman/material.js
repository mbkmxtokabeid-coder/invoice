$(document).on('click', '.hapus-btn', function (e) {
    e.preventDefault();

    var material_id = $(this).data('id'); // Ambil ID dari data-id
    console.log("Material ID: " + material_id); // Debugging untuk cek apakah ID terbaca

    if (!material_id) {
        alert("Gagal mendapatkan ID!"); // Debugging jika ID kosong
        return;
    }

    // Update form action
    $('#deleteForm').attr('action', '/invoice/deleteMaterial/' + material_id);
    console.log("Form Action: " + $('#deleteForm').attr('action')); // Debugging untuk cek apakah action sudah benar

    $('#deleteModal').modal('show');
});


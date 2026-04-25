document.addEventListener("DOMContentLoaded", function () {
  if (session('status') === 'success')
    Swal.fire({
      position: "top-middle",
      icon: "success",
      title: "SPB Berhasil ditambahkan",
      showConfirmButton: false,
      timer: 1500,
      showCloseButton: true,
    });
});
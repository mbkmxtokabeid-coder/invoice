document.addEventListener('DOMContentLoaded', function () {
  var selectPerusahaan = document.getElementById('perusahaanChoice');
  var changePerusahaan = document.getElementById('changePerusahaan');

  selectPerusahaan.addEventListener('change', function () {
    var selectedOption = selectPerusahaan.options[selectPerusahaan.selectedIndex].text;
    changePerusahaan.textContent = 'Daftar Pelanggan  ' + selectedOption
    // var selectedOption = selectPerusahaan.value;
    
   
     
   
   
  });
  });


  document.addEventListener('DOMContentLoaded', function () {
    var perusahaanSelect = document.getElementById('perusahaanChoice');
    var invoiceSelect = document.getElementById('invoiceSelect');

    perusahaanSelect.addEventListener('change', function () {
        var selectedPerusahaan = perusahaanSelect.value;
       console.log(selectedPerusahaan);
        if (selectedPerusahaan) {
            invoiceSelect.disabled = false;

            // Mengambil data invoice berdasarkan perusahaan yang dipilih
            fetch('/invoice/getInvoices/' + selectedPerusahaan)
                .then(response => response.json())
                .then(data => {
                    // Menghapus opsi yang ada sebelumnya
                    invoiceSelect.innerHTML = '<option value="" disabled selected>Select Invoice</option>';

                    // Menambahkan opsi-opsi baru berdasarkan data invoice
                    data.forEach(invoice => {
                        var option = document.createElement('option');
                        option.value = invoice.id;
                        option.text = invoice.nama_invoice;
                        invoiceSelect.add(option);
                    });
                })
                .catch(error => console.error('Error:', error));
        } else {
            // Menonaktifkan dan menghapus opsi jika perusahaan tidak dipilih
            invoiceSelect.disabled = true;
            invoiceSelect.innerHTML = '<option value="" disabled selected>Select Invoice</option>';
        }
    });
});






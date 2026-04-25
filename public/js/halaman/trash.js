const { Controller } = require("swiper");

document.addEventListener('DOMContentLoaded', function () {
  var choicesInvoice = new Choices('#choices-invoice');
  var productNameSelect = document.getElementById('productName-1');

  // Mengatur pilihan pada choices-invoice sebagai filter
  choicesInvoice.passedElement.element.addEventListener('change', function (event) {
    var selectedInvoice = event.target.value;

    // Menghapus semua pilihan pada productName
    while (productNameSelect.firstChild) {
      productNameSelect.firstChild.remove();
    }

    // Mengambil data jenis barang melalui AJAX
    fetch('/jenis-barang')
      .then(function (response) {
        return response.json();
      })
      .then(function (jenis) {
        jenis.forEach(function (jns) {
          var namaKategori = jns.jenis_barang;
          var selectedKategori = namaKategori.toLowerCase().replace(/\s/g, "");
          var invoiceKeyword = selectedInvoice.toLowerCase().replace(/\s/g, "");

          // Memeriksa apakah selectedInvoice mengandung kata kunci yang cocok dengan nama_kategori
          if (selectedKategori.includes(invoiceKeyword)) {
            var option = document.createElement('option');
            option.value = namaKategori;
            option.text = namaKategori;
            productNameSelect.appendChild(option);
          }
        });
      });
  });
});


// function remove() {
//   Array.from(document.querySelectorAll(".product-removal a")).forEach(function (
//     e
//   ) {
//     e.addEventListener("click", function (e) {
//       removeItem(e), resetRow();
//     });
//   });
// }
// function resetRow() {
//   Array.from(document.getElementById("newlink").querySelectorAll("tr")).forEach(
//     function (e, t) {
//       t += 1;
//       e.querySelector(".product-id").innerHTML = t;
//     }
//   );
// }
// function removeItem(e) {
//   e.target.closest("tr").remove(), recalculateCart();
// }


// Controller Penjualan
// Load Invoice
 // public function loadInvoice()
    // {
    //     $no_invoice = $this->generateNoInvoice();
    //     $invoice = Invoice::all();
    //     $admin = User::where('role', 'Admin')->get();
    //     $order = Order::all();
    //     $jenisBarang = $this->jenisBarang;

    //     $id = 1; // Assign a value to the $id variable

    //     return view('pages.invoices.invoice', compact('invoice', 'admin', 'order', 'no_invoice', 'jenisBarang', 'id'));
    // }

// Batas Untuk filtering data
    //     public function loadInvoice(Request $request)
    // {
    //     $no_invoice = $this->generateNoInvoice();
    //     // $jenisBarang = Barang::query();
    //     $jenisBarang = collect();
    //     if ($request->has('choices-invoice')) {
    //         $selectedKategori = $request->input('choices-invoice');
    //         $jenisBarang = Barang::join('kategori_barang', 'barang.kategori_id', '=', 'kategori_barang.id')
    //             ->where('kategori_barang.nama_kategori', $selectedKategori)
    //             // ->pluck('barang.jenis_barang');
    //             ->select('barang.*')
    //             ->get();
    //     }
    //     $invoice = Invoice::all();
    //     $admin = User::where('role', 'Admin')->get();
    //     $order = Order::all();
    //     $jenis = Barang::pluck('jenis_barang');


    //     return view('pages.invoices.invoice', compact('invoice', 'admin', 'order', 'no_invoice', 'jenis', 'jenisBarang'));
    // }

// untuk kode unik
// Get the last invoice number for the current date
        // $lastInvoice = Penjualan::whereDate('created_at', $now->toDateString())
        //     ->orderBy('created_at', 'desc')
        //     ->first();

// $urutan = $lastInvoice ? ($lastInvoice->urutan + 1) : 1; // Get the next sequence number

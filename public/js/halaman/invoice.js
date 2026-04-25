// formatting
var cleaveDelimiters, cleaveBlocks, hargaModal, hargaJual, cleaveNumeral;
document.querySelector("#cleave-delimiters") && (cleaveDelimiters = new Cleave("#cleave-delimiters", { delimiters: [".", ".", "-"], blocks: [3, 3, 3, 2], uppercase: !0 })),
  document.querySelector("#cleave-prefix") && (cleavePrefix = new Cleave("#cleave-prefix", { prefix: "PREFIX", delimiter: "-", blocks: [6, 4, 4, 4], uppercase: !0 })),
  // Numeral Mask
  document.querySelector("#cleave-numeral") && (cleaveNumeral = new Cleave("#cleave-numeral", { numeral: !0, numeralThousandsGroupStyle: "thousand" })),
  document.querySelector("#phone-number") && (cleaveNumeral = new Cleave("#phone-number", { delimiters: [" "], blocks: [4, 4, 4, 1] }))
  ;
document.querySelectorAll('[data-cleave]').forEach(function (element) {
  var cleaveConfig = JSON.parse(element.getAttribute('data-cleave'));
  new Cleave(element, cleaveConfig);
});


document.addEventListener('DOMContentLoaded', function () {
  var selectInvoice = document.getElementById('choices-invoice');
  var invoicenoInput = document.getElementById('invoicenoInput');

  selectInvoice.addEventListener('change', function () {
    var selectedOption = selectInvoice.value;

    fetch('invoices/' + selectedOption)
      .then(response => response.json())
      .then(data => {
        invoicenoInput.value = data.kode_invoice;
      })
      .catch(error => {
        console.error(error);
      });
  });
});

!(function () {
  "use strict";
  window.addEventListener(
    "load",
    function () {
      var t = document.getElementsByClassName("needs-validation");
      t &&
        Array.prototype.filter.call(t, function (e) {
          e.addEventListener(
            "submit",
            function (t) {
              !1 === e.checkValidity() &&
                (t.preventDefault(), t.stopPropagation()),
                e.classList.add("was-validated");
            },
            !1
          );
        });
    },
    !1
  );
})();

// Baru 2
document.addEventListener('DOMContentLoaded', function () {
  var selectInvoice = document.getElementById('choices-invoice');
  var productNames = document.querySelectorAll('[id^="productName-"]');

  selectInvoice.addEventListener('change', function () {
    var selectedOption = selectInvoice.value;

    productNames.forEach(function (productName) {
      var id = productName.id.split('-')[1]; // Ambil ID dinamis

      fetch('/invoice/barang/' + selectedOption, {
        method: 'POST', // Ubah metode pengiriman data menjadi POST
        body: JSON.stringify({ selected: selectedOption }),
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
      }) // Perbarui URL dengan pilihan
        .then(response => response.json())
        .then(data => {
          var jenisBarang = data.jenis_barang;

          // Perbarui elemen <select> dengan id dinamis
          var selectElement = document.getElementById('productName-' + id);
          selectElement.innerHTML = ''; // Kosongkan elemen sebelumnya

          // Tambahkan opsi baru ke elemen <select>
          jenisBarang.forEach(function (jenis) {
            var option = document.createElement('option');
            option.value = jenis;
            option.textContent = jenis;
            selectElement.appendChild(option);
          });
        })
        .catch(error => {
          console.error(error);
        });
    });
  });
});





document.addEventListener("DOMContentLoaded", function () {
  var selectElement = document.getElementById("choices-orderBy");
  var divElement = document.getElementById("divSales");

  selectElement.addEventListener("change", function () {
    if (selectElement.value === "Sales") {
      divElement.style.display = "block";
    } else {
      divElement.style.display = "none";
    }
  });
});
// METODE PEMBAYARAN
document.addEventListener("DOMContentLoaded", function () {
  var selectElement = document.getElementById("choices-payment-type");
  var divElement = document.getElementById("alamatPembayaran");
  var divElement2 = document.getElementById("alamatPembayaran2");
  var divElement3 = document.getElementById("alamatPembayaran3");

  selectElement.addEventListener("change", function () {
    if (selectElement.value.includes("Transfer") || selectElement.value.includes("PO")) {
      divElement.style.display = "block";
      divElement2.style.display = "block";
      divElement3.style.display = "block";

    } else {
      divElement.style.display = "none";
      divElement2.style.display = "none";
      divElement3.style.display = "none";
    }
  });
});


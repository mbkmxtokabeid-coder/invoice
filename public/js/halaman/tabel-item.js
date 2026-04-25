
function autoCalc(v) {

  var row = v.closest('tr');
  var index = Array.from(row.parentNode.children).indexOf(row) + 1;

  var harga = document.getElementById('productRate-' + index).value;

  var unformattedHarga = parseFloat(harga.replace(/,/g, ''));
  var qty = parseFloat(document.getElementById('product-qty-' + index).value);

  if (!isNaN(unformattedHarga) && !isNaN(qty)) {
    var jumlah = unformattedHarga * qty;
    // formattedJumlah = jumlah.toLocaleString();

    document.getElementById('productPrice-' + index).value = jumlah.toLocaleString();
    getTotal();
  }
}


function getTotal() {
  var sum = 0;
  var semuaHarga = document.querySelectorAll('[id^="productPrice-"]');

  for (let i = 0; i < semuaHarga.length; i++) {
    const harga = parseFloat(semuaHarga[i].value.replace(/,/g, ''));

    if (!isNaN(harga)) {
      sum += harga;
    }
  }

  document.getElementById('total-harga').value = sum.toLocaleString();
  document.getElementById('total-pembelian').value = sum.toLocaleString();
  dP();
  totalPembayaran()
  sisaPembayaran();
}

function dP() {
  var divLabel = document.getElementById("div-label");
  var divElement = document.getElementById("dp");
  var totalHarga = parseFloat(document.getElementById('total-harga').value.replace(/,/g, ''));
  var jnsPem = document.getElementById('choices-payment-type').value;

  if (totalHarga >= 500000) {
    if (jnsPem == 'Cash Belum Lunas' || jnsPem == 'Transfer DP') {
      divLabel.style.display = "block";
      divElement.style.display = "block";
    } else {
      divLabel.style.display = "none";
      divElement.style.display = "none";
    }
  } else {
    divLabel.style.display = "none";
    divElement.style.display = "none";
  }
  // sisaPembayaran();
}

function totalPembayaran() {
  var total;
  var selectLain = document.getElementById('choices-potongan');
  var inputPotongan = parseFloat(document.getElementById('input-potongan').value.replace(/,/g, ''));
  var totalHarga = parseFloat(document.getElementById('total-harga').value.replace(/,/g, ''));
  // Menambahkan logika berdasarkan pilihan pengguna
  if (selectLain != null) {
    if (selectLain.value == "-") {
      document.getElementById('input-potongan').innerHTML = 0;
      if (totalHarga) {
        total = totalHarga;
      } else {
        total = 0;
      }
      document.getElementById('total-pembelian').value = total.toLocaleString();
      // Lakukan sesuatu jika value "-" dipilih
    } else if (selectLain.value == "Diskon") {
      var diskon = inputPotongan;
      var potongan = diskon * totalHarga / 100;
      total = totalHarga - potongan;
      document.getElementById('total-pembelian').value = total.toLocaleString();
      // Lakukan sesuatu dengan nilai diskon yang telah dipilih
    } else if (selectLain.value == "Potongan") {
      var potongan = inputPotongan;
      total = totalHarga - potongan;
      document.getElementById('total-pembelian').value = total.toLocaleString();
      // Lakukan sesuatu dengan nilai potongan yang telah dipilih
    } else if (selectLain.value == "PPN") {
      var ppn = inputPotongan;
      var tambahan = ppn * totalHarga / 100;
      total = totalHarga + tambahan;
      document.getElementById('total-pembelian').value = total.toLocaleString();
      // Lakukan sesuatu dengan nilai PPN yang telah dipilih
    }
  }
  sisaPembayaran();
}
document.getElementById('total-pembelian').addEventListener('change', sisaPembayaran);
document.getElementById('input-dp').addEventListener('change', sisaPembayaran);

function sisaPembayaran() {
  var totalPem = parseFloat(document.getElementById('total-pembelian').value.replace(/,/g, ''));
  var dp = parseFloat(document.getElementById('input-dp').value.replace(/,/g, ''));

  // var jenis_pem = document.getElementById('choices-payment-type').value;

  if (!isNaN(dp)) {
    var sisaPem = totalPem - dp;
    document.getElementById('sisa-pembayaran').value = sisaPem.toLocaleString();
  }
  else {

    document.getElementById('sisa-pembayaran').value = totalPem.toLocaleString();
  }

  // if (jenis_pem == 'Cash Lunas' || jenis_pem == 'Transfer Lunas') {
  //   document.getElementById('sisa-pembayaran').value = 0;
  // }
}

// Event listener
document.getElementById('total-harga').addEventListener('input', dP);
document.getElementById('choices-payment-type').addEventListener('change', dP);
document.getElementById('input-potongan').addEventListener('change', totalPembayaran);
document.getElementById('choices-potongan').addEventListener('change', totalPembayaran);
document.getElementById('choices-payment-type').addEventListener('change', sisaPembayaran);
// document.getElementById('choices-invoice').addEventListener('change', new_link);

var count = 1;
var usedIds = [];
function new_link() {
  Array.from(document.querySelectorAll('.product')).forEach(function (element) {
    var id = parseInt(element.id);
    usedIds.push(id);
  });

  // Menemukan ID terkecil yang belum digunakan
  var smallestUnusedId = 1;
  while (usedIds.includes(smallestUnusedId)) {
    smallestUnusedId++;
  }

  count = smallestUnusedId; // Mengatur ulang nilai count


  var e = document.createElement("tr");
  e.id = count;
  e.className = "product";

  var t =
    '<th scope="row" class="product-id">' +
    count +
    '</th><td class="text-start"><div class="mb-2"><label class="visually-hidden" for="productName" >Item</label><select class="form-select " data-choices data-choices-sorting="true" id="productName-' +
    count +
    '" name="barang_id[]"><option selected disabled>Pilih Item</option>';

  fetch('/invoice/jenis-barang')
    .then(function (response) {
      return response.json();
    })
    .then(function (jenis) {
      jenis.forEach(function (jns) {
        t += '<option value="' + jns.id + '">' + jns.jenis_barang + '</option>';
      });

      t +=
        '</select></div><textarea class="form-control bg-light border-0" id="productDetails-' +
        count +
        '" name="deskripsi_item[]" rows="2" placeholder="Deskripsi Item"></textarea></td><td><select class="form-control bg-light border-0" id="satuan-' + count + '" name="satuan[]" required><option value="">Pilih</option><option value="Pcs">Pcs</option><option value="Set">Set</option><option value="Und">Und</option><option value="Blok">Blok</option><option value="Rim">Rim</option><option value="Lbr">Lbr</option><option value="Ktk">Ktk</option><option value="Unit">Unit</option><option value="Kg">Kg</option><option value="Lainnya">Lainnya</option></select></td><td><input type="text" class="form-control product-price bg-light border-0" id="productRate-' 
        +count +
        '" name="hrg[]" data-cleave=\'{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }\' placeholder="Rp. 0.000" onChange="autoCalc(this)" required /></td><td><div class="input-step"><input type="number" class="text-center" id="product-qty-' +
        count +
        '" name="qty[]" onChange="autoCalc(this)" value="1"></div></td><td class="text-end"><div><input type="text" class="form-control bg-light border-0 product-line-price" id="productPrice-' +
        count +
        '" name="jlh_hrg[]"  placeholder="Rp.0.000" onChange="autoCalc(this)" readonly/></div></td><td class="product-removal"><a class="btn btn-success">Delete</a></td>';

      e.innerHTML = t;
      document.getElementById("newlink").appendChild(e);
      usedIds.push(count);
      
      var choicesElement = document.getElementById("satuan-" + count);
      new Choices(choicesElement, {
        placeholderValue: "This is a placeholder set in the config",
        searchPlaceholderValue: "This is a search placeholder",
        searchEnabled: false
      });

      var choicesElements = document.querySelectorAll("[data-choices]");
      Array.from(choicesElements).forEach(function (element) {
        new Choices(element, {
          placeholderValue: "This is a placeholder set in the config",
          searchPlaceholderValue: "This is a search placeholder",
        });
      });

      var inputElement = document.getElementById("productRate-" + count);
      var cleaveConfig = JSON.parse(inputElement.getAttribute('data-cleave'));
      new Cleave(inputElement, cleaveConfig);
      remove();
      resetRow();
    });
}

document.addEventListener("DOMContentLoaded", function () {
  var selectElement = document.getElementById("choices-potongan");
  var divElement = document.getElementById("harga-potongan");
  var divInput = document.getElementById("input-potongan");

  selectElement.addEventListener("change", function () {
    if (selectElement.value != "-") {
      if (selectElement.value == "Diskon") {
        divElement.style.display = "block";
        divInput.class = "form-control bg-light border-0";
        divInput.row = "1";
        divInput.name = "dskn";
        divInput.placeholder = "Diskon";
      }
      if (selectElement.value == "Potongan") {
        divElement.style.display = "block";
        divInput.class = "form-control bg-light border-0";
        divInput.row = "1";
        divInput.name = "ptg";
        divInput.placeholder = "Potongan";
      }
      if (selectElement.value == "PPN") {
        divElement.style.display = "block";
        divInput.class = "form-control bg-light border-0";
        divInput.row = "1";
        divInput.name = "ppn";
        divInput.placeholder = "PPN";
      }
    } else {
      divElement.style.display = "none";
    }
  });
});
totalPembayaran();
remove();

// Batas
function remove() {
  Array.from(document.querySelectorAll(".product-removal a")).forEach(function (e) {
    e.addEventListener("click", function (e) {
      removeItem(e);
    });
  });
}

function resetRow() {
  Array.from(document.getElementById("newlink").querySelectorAll("tr")).forEach(function (e, t) {
    t += 1;
    e.querySelector(".product-id").innerHTML = t;
    e.id = t; // Menetapkan ulang ID elemen

    // Mengubah ID semua elemen dalam row
    e.querySelectorAll("[id^='productName-'], [id^='productDetails-'], [id^='satuan-'], [id^='productRate-'], [id^='product-qty-'],[id^='productPrice-']").forEach(function (elem) {
      var oldId = elem.id;
      var newId = oldId.replace(/-\d+$/, '-' + t);
      elem.id = newId;
    });
  });
}

function removeItem(e) {
  var row = e.target.closest("tr");
  var id = parseInt(row.id);
  row.remove();
  resetRow();
  updateValues();

  // Menghapus ID dari daftar usedIds
  var index = usedIds.indexOf(id);
  if (index !== -1) {
    usedIds.splice(index, 1);
  }
}

function updateValues() {
  getTotal();
  sisaPembayaran();
}

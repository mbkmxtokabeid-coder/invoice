var cleaveDelimiters, cleaveBlocks, inputTerbayar, totalJual, cleaveNumeral;
document.querySelector("#cleave-delimiters") && (cleaveDelimiters = new Cleave("#cleave-delimiters", { delimiters: [".", ".", "-"], blocks: [3, 3, 3, 2], uppercase: !0 })),
  document.querySelector("#cleave-prefix") && (cleavePrefix = new Cleave("#cleave-prefix", { prefix: "PREFIX", delimiter: "-", blocks: [6, 4, 4, 4], uppercase: !0 })),
  // Numeral Mask
  document.querySelector("#input_bayar") && (inputTerbayar = new Cleave("#input_bayar", { numeral: !0, numeralThousandsGroupStyle: "thousand" })),
  document.querySelector("#total-harga") && (totalJual = new Cleave("#total-harga", { numeral: !0, numeralThousandsGroupStyle: "thousand" })),
  document.querySelector("#cleave-numeral") && (cleaveNumeral = new Cleave("#cleave-numeral", { numeral: !0, numeralThousandsGroupStyle: "thousand" })),
  document.querySelector("#phone-number") && (cleaveNumeral = new Cleave("#phone-number", { delimiters: [" "], blocks: [4, 4, 4, 1] }))
  ;
document.querySelectorAll('[data-cleave]').forEach(function (element) {
  var cleaveConfig = JSON.parse(element.getAttribute('data-cleave'));
  new Cleave(element, cleaveConfig);
});

// SHOW FORM
document.addEventListener("DOMContentLoaded", function () {
  var selectElement = document.getElementById('statuss')
  var divElement = document.getElementById('terbayar')
  selectElement.addEventListener('change', function () {
    if (selectElement.value.includes('Belum Lunas')) {
      divElement.style.display = 'block'
    }
    else if (selectElement.value.includes('Lunas')) {
      divElement.style.display = 'none'

    }
  })
})

var count = 1;
var usedIds = [];
function new_link() {
  Array.from(document.querySelectorAll('.product')).forEach(function (element) {
    var id = parseInt(element.id);
    usedIds.push(id);
  });

  var smallestUnusedId = 1;
  while (usedIds.includes(smallestUnusedId)) {
    smallestUnusedId++;
  }

  count = smallestUnusedId;

  var e = document.createElement("tr");
  e.id = count;
  e.className = "product";

  var t =
    '<th scope="row" class="product-id">' +
    count +
    '</th><td class="text-start"><div class="mb-2"><textarea class="form-control bg-light border-0" id="productDetails-' +
    count +
    '" name="deskripsi[]" rows="1" placeholder="Deskripsi Item"></textarea></div></td><td><input type="text" class="form-control product-price bg-light border-0" id="productRate-' +
    count +
    '" step="0" name="harga[]" data-cleave=\'{ "numeral": true, "numeralThousandsGroupStyle": "thousand" }\' placeholder="Rp. 0.000" onchange="autoCalc(this)" required /><div class="invalid-feedback">Tolong masukkan Harga</div></td><td><div class="input-step"><input type="text" class="text-center product-quantity" id="product-qty-' +
    count +
    '" name="qty[]" onchange="autoCalc(this)" value="1"></div></td>' +

    // Tambahan: Select untuk satuan
    '<td><select class="form-select" id="satuan-' + count + '" name="satuan[]" required>' +
    '<option value="">Pilih</option>' +
    '<option value="Pcs">Pcs</option>' +
    '<option value="Set">Set</option>' +
    '<option value="Und">Und</option>' +
    '<option value="Blok">Blok</option>' +
    '<option value="Rim">Rim</option>' +
    '<option value="Lbr">Lbr</option>' +
    '<option value="Ktk">Ktk</option>' +
    '<option value="Unit">Unit</option>' +
    '<option value="Kg">Kg</option>' +
    '<option value="Lainnya">Lainnya</option>' +
    '</select></td>' +

    '<td class="text-end"><div><input type="text" class="form-control bg-light border-0 product-line-price" name="jumlah[]" id="productPrice-' +
    count +
    '" placeholder="Rp.0.000" readonly/></div></td><td class="product-removal"><a class="btn btn-success">Delete</a></td>';

  e.innerHTML = t;
  document.getElementById("newlink").appendChild(e);
  usedIds.push(count);


  var choicesElements = document.querySelectorAll("[data-choices]");
  Array.from(choicesElements).forEach(function (element) {
    new Choices(element, {
      placeholderValue: "This is a placeholder set in the config",
      searchPlaceholderValue: "This is a search placeholder",
    });
  });

  // isData();
  var inputElement = document.getElementById("productRate-" + count);
  var cleaveConfig = JSON.parse(inputElement.getAttribute('data-cleave'));
  new Cleave(inputElement, cleaveConfig);

  remove();
  resetRow();
};

function autoCalc(v) {
  var row = v.closest('tr');
  var index = Array.from(row.parentNode.children).indexOf(row) + 1;
  var harga = document.getElementById('productRate-' + index).value;
  var formattedHarga = parseFloat(harga.replace(/,/g, ''));
  var qty = parseFloat(document.getElementById('product-qty-' + index).value);

  if (!isNaN(formattedHarga) && !isNaN(qty)) {
    var jumlah = formattedHarga * qty;

    document.getElementById('productPrice-' + index).value = jumlah.toLocaleString();
    getTotal();
  }
}

function getTotal() {
  var sum = 0;
  var semuaHarga = document.querySelectorAll('[id^="productPrice-"]');
  for (let i = 0; i < semuaHarga.length; i++) {
    const hargaValue = semuaHarga[i].value || '0'; // Use '0' as the default value if value is undefined or null
    const harga = parseFloat(hargaValue.replace(/,/g, ''));
    if (!isNaN(harga)) {
      sum += harga;
    }
  }
  document.getElementById('total-harga').value = sum.toLocaleString();
  totalPembayaran();
}

var semuaHargaElements = document.querySelectorAll('[id^="productPrice-"]');
semuaHargaElements.forEach(function (element) {
  element.addEventListener('change', getTotal);
});


function totalPembayaran() {
  var total = 0
  var inputTerbayar = document.getElementById('input_bayar')
  var inputTotal = parseFloat(document.getElementById('total-harga').value.replace(/,/g, ''))
  var inputTerbayarFormatted = parseFloat(inputTerbayar.value.replace(/,/g, ''))
  var sisaPembayaran = document.getElementById('sisa_bayar')
  if (!isNaN(inputTerbayarFormatted)) {
    total = inputTotal - inputTerbayarFormatted
    sisaPembayaran.value = total.toLocaleString()
  }
  else {
    inputTerbayar.value = total.toLocaleString()
    sisaPembayaran.value = inputTotal.toLocaleString()
  }
}
document.getElementById('input_bayar').addEventListener('change', totalPembayaran)
remove()

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
    e.id = t
    e.querySelectorAll("[id^='productName-'], [id^='productDetails-'], [id^='satuan-'], [id^='productRate-'], [id^='product-qty-'],[id^='productPrice-']").forEach(function (elem) {
      var oldId = elem.id
      var newId = oldId.replace(/-\d+$/, '-' + t)
      elem.id = newId
    })
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

}
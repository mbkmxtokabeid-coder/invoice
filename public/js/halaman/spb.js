var cleaveBlocks;
document.querySelector("#phone-number") && (cleaveNumeral = new Cleave("#phone-number", { delimiters: [" "], blocks: [4, 4, 4, 1] }));


var count = 1;
function new_link() {
  count++;
  var e = document.createElement("tr");
  e.id = count;
  e.className = "product";

  var t =
    '<th scope="row" class="product-id">' +
    count +
    '</th><td class="text-start"><div class="mb-2"><label class="visually-hidden" for="productName" >Item</label><select class="form-select " data-choices data-choices-sorting="true" id="productName-' +
    count +
    '" name="barang_id[]"><option selected disabled>Pilih Item</option>';

  fetch('/invoice/kategori-barang')
    .then(function (response) {
      return response.json();
    })
    .then(function (kategori) {
      kategori.forEach(function (ktg) {
        t += '<option value="' + ktg.id + '">' + ktg.nama_kategori + '</option>';
      });

      t +=
        '</select></div><textarea class="form-control bg-light border-0" id="productDetails-' +
        count +
        '" name="deskripsi_item[]" rows="2" placeholder="Deskripsi Item"></textarea></td><td><select class="form-select bg-light border-0" id="satuan-' + count + '" name="satuan[]" required><option value="">Pilih</option><option value="Set">Set</option><option value="Pcs">Pcs</option><option value="Kotak">Kotak</option><option value="Blok">Blok</option><option value="Lbr">Lbr</option></select></td><td><div class="input-step"><input type="number" class="text-center" id="product-qty-' +
        count +
        '" name="qty[]" value="0"></div></td><td class="text-end"><div><textarea id="productKeterangan-'
        + count +
        '"class="form-control bg-light border-0" name="keterangan[]" cols="2" placeholder="Keterangan"></textarea></div></td><td class="product-removal"><a class="btn btn-success">Delete</a></td>';

      e.innerHTML = t;
      document.getElementById("newlink").appendChild(e);

      // var choicesElement = document.getElementById("satuan-" + count);
      // new Choices(choicesElement, {
      //   placeholderValue: "This is a placeholder set in the config",
      //   searchPlaceholderValue: "This is a search placeholder",
      //   searchEnabled: false
      // });

      var choicesElements = document.querySelectorAll("[data-choices]");
      Array.from(choicesElements).forEach(function (element) {
        new Choices(element, {
          placeholderValue: "This is a placeholder set in the config",
          searchPlaceholderValue: "This is a search placeholder",
        });
      });

      // isData();
      remove();
      resetRow();
    });
}

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
  });
}
function removeItem(e) {
  var row = e.target.closest("tr");
  row.remove();
  resetRow();
}


$(document).ready(function () {
  $('.hapus-btn').on('click', function(e) {
    e.preventDefault();
    var spb_id = $(this).attr('value');
    var delete_url = $(this).attr('data-url');
    $('#spb_id').val(spb_id);
    
    if (delete_url) {
      $('#deleteForm').attr('action', delete_url);
    } else {
      $('#deleteForm').attr('action', '/delete-spb/' + spb_id); // Update the form action URL dynamically
    }

    $('#deleteModal').modal('show');
  });
});



// // Mengambil tombol hapus dan menambahkan event listener
// var hapusBtn = document.getElementById('hapus-btn');
// hapusBtn.addEventListener('click', hapus);


document.querySelector('select[name="spj"]').addEventListener('change', function() {
    const spjValue = this.value;
    if (!spjValue) {
      document.querySelector('input[name="nomor_spj"]').value = '';
      return;
    }

    fetch(`/invoice/generate-spj-number?spj=${spjValue}`)
      .then(response => response.json())
      .then(data => {
        if (data.nomor_spj) {
          document.querySelector('input[name="nomor_spj"]').value = data.nomor_spj;
        }
      })
      .catch(err => {
        console.error('Error fetching nomor SPJ:', err);
      });
  });

  fetch(`/invoice/generate-spj-number?spj=${spjValue}`)
  .then(response => response.text())
  .then(text => {
    console.log('Response text:', text);
    try {
      const data = JSON.parse(text);
      if (data.nomor_spj) {
        document.querySelector('input[name="nomor_spj"]').value = data.nomor_spj;
      }
    } catch (e) {
      console.error('Parsing JSON failed:', e);
    }
  })
  .catch(err => {
    console.error('Error fetching nomor SPJ:', err);
  });





 

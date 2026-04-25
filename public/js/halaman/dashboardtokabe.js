window.onload = function () {
  timeFrame('month');
  timeRange('year');
  timeRangePO('month');
}
function getChartColorsArray(e) {
  if (null !== document.getElementById(e)) {
    var o = document.getElementById(e).getAttribute("data-colors");
    if (o)
      return (o = JSON.parse(o)).map(function (e) {
        var o = e.replace(" ", "");
        return -1 === o.indexOf(",")
          ? getComputedStyle(
            document.documentElement
          ).getPropertyValue(o) || o
          : 2 == (e = e.split(",")).length
            ? "rgba(" +
            getComputedStyle(
              document.documentElement
            ).getPropertyValue(e[0]) +
            "," +
            e[1] +
            ")"
            : o;
      });
    // console.warn("data-colors atributes not found on", e);
  }
}

// console.log(totalPerHari);
// CHART BAR UNTUK UMUM (DILUAR PO)
var options,
  chart,
  linechartBasicColors = getChartColorsArray("stacked_column_chart"),
  barchartColors =
    (linechartBasicColors &&
      ((options = {
        chart: {
          height: 365,
          type: "bar",
          toolbar: { show: false },
          zoom: { enabled: true },
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: "60%",
            endingShape: "rounded",
          },
        },
        dataLabels: { enabled: false },
        labels: hourArrayJS,
        series: [
          {
            name: "Income",
            data: totalJamArrayJS,
          },
        ],
        xaxis: {
          categories: hourArrayJS,
        },
        yaxis: {
          min: -100000, // Nilai minimum di sumbu Y
          max: 5000000, // Nilai maksimum di sumbu Y (Rp 5.000.000)
          // forceNiceScale: true, // Paksa agar skala terlihat lebih rapi 
          tickAmount: 5, // Dibagi menjadi 5 bagian (setiap 1.000.000) 
          axisBorder: { show: false },
          axisTicks: { show: false },
          labels: {
            show: true,
            formatter: function (val) {
              // let ticks = [500000, 1000000, 2000000, 3000000, 4000000, 5000000, 10000000, 20000000];
              return "Rp " + val.toLocaleString();
            },
          },
        },
        grid: {
          xaxis: { lines: { show: true } },
          yaxis: { lines: { show: false } },
        },
        colors: linechartBasicColors,
        legend: { show: false },
        fill: { opacity: 1 },
      }),
      (chart = new ApexCharts(
        document.querySelector("#stacked_column_chart"),
        options
      )).render()));

var options, charts,
  donutchartColors = getChartColorsArray("structure_widget"),
  chartColors =
    (donutchartColors &&
      ((options = {
        chart: { height: 300, type: "donut" },
        series: datasMonthJS,
        labels: ["Terbayar", "Belum Bayar"],
        colors: ["#28a745", "#dc3545"],
        plotOptions: { pie: { startAngle: 10, donut: { size: "78%" } } },
        legend: { show: !1 },
        dataLabels: {
          style: {
            fontSize: "11px",
            fontFamily: "DM Sans,sans-serif",
            colors: void 0,
          },
          background: {
            enabled: !0,
            foreColor: "#fff",
            padding: 4,
            borderRadius: 2,
            borderWidth: 1,
            borderColor: "#fff",
            opacity: 1,
          },
        },
        responsive: [
          {
            breakpoint: 600,
            options: { chart: { height: 240 }, legend: { show: !1 } },
          },
        ],
      }),
        (charts = new ApexCharts(
          document.querySelector("#structure_widget"),
          options
        )).render())
    );

// TimeFrame untuk button
function timeFrame(period) {
  const elementHarga = document.getElementById('harga');
  const buttons = document.getElementsByClassName('tombol');
  for (let i = 0; i < buttons.length; i++) {
    buttons[i].classList.remove('btn-info');
    buttons[i].classList.add('btn-soft-info');
  }
  const selectedButton = document.getElementById('btn-' + period);
  selectedButton.classList.remove('btn-soft-info');
  selectedButton.classList.add('btn-info');

  const now = new Date();
  const startOfDay = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 15, 0); //start pukul 8 karena indonesia gmt+7 maka 8+7
  const endOfDay = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 24, 0); //end pukul 17 karena Indonesia gmt+7 maka 17+7
  const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1, 0, 1, 0, 0);
  const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);
  const startOfYear = new Date(now.getFullYear(), 0, 1);
  const endOfYear = new Date(now.getFullYear(), 10, 31, 23, 59, 59);
  const currentYear = now.getFullYear();
  const currentMonth = ("0" + (now.getMonth() + 1)).slice(-2); // Format dua digit

  // Sinkronisasi panjang array
// Fungsi untuk membuat array jam otomatis dari startHour ke endHour
if (period === 'day') {
  chart.updateOptions({
    dataLabels: { enabled: false },
    labels: hourArrayJS,
    series: [
      {
        name: "Income",
        data: totalJamArrayJS.map((value, index) => {
          // Konversi waktu ke WIB secara langsung
          let utcTime = new Date(hourArrayJS[index]);
          let localTime = new Date(utcTime.setHours(utcTime.getHours() + 7));
          return { x: localTime.toISOString().slice(11, 16), y: value };
        }),
        color:'#28A745',
      },
    ],
    xaxis: {
      type: 'category',
      categories: hourArrayJS.map(time => {
        let utcTime = new Date(time);
        let localTime = new Date(utcTime.setHours(utcTime.getHours()));
        return localTime.toISOString().slice(11, 16);
      }), // Konversi waktu ke WIB
      labels: {
        formatter: function (val) {
          return val; // Menampilkan jam dalam format HH:mm di zona waktu WIB
        },
      },
    },
    
    yaxis: {
      min: 0, // Nilai minimum di sumbu Y
      max: 5000000, // Nilai maksimum di sumbu Y (Rp 5.000.000)
      tickAmount: 5, // Dibagi menjadi 5 bagian (setiap 1.000.000) 
      axisBorder: { show: false },
      axisTicks: { show: false },
      labels: {
        show: true,
        formatter: function (val) {
          return "Rp " + val.toLocaleString();
        },
      },
    },
    plotOptions: {
      bar: {
        distributed: true, // Membuat tiap bar punya warna berbeda
        borderRadius: 2, // Menambahkan border radius di ujung bar
        columnWidth: "50%", // Ukuran lebar bar
        endingShape: "rounded", // Ujung bar terlihat lebih smooth
      },
    },
  });

  if (elementHarga) {
    elementHarga.innerHTML = `Rp. ${totalPerHari.toLocaleString()}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Incomes</span>`;
  }
}



else if (period === 'week') {
  console.log("🚀 Running Weekly Chart Update...");
  console.log("🟢 Entered Weekly Chart Update Block");

  // 🔹 Ambil tanggal hari ini dengan zona waktu Indonesia (GMT+7)
  let today = new Date();
  let dayOfWeek = today.getDay(); // 0 = Minggu, 1 = Senin, ..., 6 = Sabtu

  // 🔹 Start of Week HARUS Senin (Waktu Indonesia WIB)
  let startOfWeek = new Date(today);
  startOfWeek.setDate(today.getDate() - (dayOfWeek === 0 ? 6 : dayOfWeek - 1));
  startOfWeek.setHours(0, 0, 0, 0);
  startOfWeek.setTime(startOfWeek.getTime() + (7 * 60 * 60 * 1000)); // Tambahkan GMT+7

  // 🔹 End of Week HARUS Hari Ini (Waktu Indonesia WIB)
  let endOfWeek = new Date(today);
  endOfWeek.setHours(23, 59, 59, 999);
  endOfWeek.setTime(endOfWeek.getTime() + (7 * 60 * 60 * 1000)); // Tambahkan GMT+7

  console.log("📅 Start of Week (Senin, WIB):", startOfWeek.toISOString());
  console.log("📅 End of Week (Hari Ini, WIB):", endOfWeek.toISOString());

  // 🔹 1. Buat daftar tanggal dari Senin hingga hari ini
  let fullWeekDates = [];
  let currentDate = new Date(startOfWeek);

  while (currentDate <= endOfWeek) {
    let formattedDate = currentDate.toISOString().split("T")[0]; // Format YYYY-MM-DD
    fullWeekDates.push(formattedDate);
    currentDate.setDate(currentDate.getDate() + 1);
  }

  // 🔹 2. Mapping data dari backend
  let dataMap = new Map(weekArrayJS.map((date, index) => [date, Number(totalMingguanArrayJS[index])]));

  // 🔹 3. Total transaksi asli tanpa dikurangi beban
  let totalTransaksiAsli = fullWeekDates.reduce((total, date) => {
    return total + (dataMap.get(date) ?? 0);
  }, 0);

  // 🔹 4. Buat data mingguan setelah dikurangi beban 500.000/hari
  let finalWeeklyData = fullWeekDates.map(date => {
    const transaksi = dataMap.get(date);
    return transaksi !== undefined ? transaksi - 500000 : -500000;
  });

  // 🔹 5. Hitung income mingguan
  let totalPositif = finalWeeklyData.filter(val => val > 0).reduce((acc, val) => acc + val, 0);
  let totalNegatif = finalWeeklyData.filter(val => val < 0).reduce((acc, val) => acc + val, 0);
  let netIncome = totalPositif + totalNegatif;

  console.log("✅ Total Transaksi Asli (tanpa beban):", totalTransaksiAsli);
  console.log("✅ Total Positif:", totalPositif);
  console.log("✅ Total Negatif:", totalNegatif);
  console.log("✅ Total Income Setelah Perhitungan:", netIncome);

  // 🔹 6. Update Chart
  chart.updateOptions({
    dataLabels: { enabled: false },
    labels: fullWeekDates,
    series: [{
      name: "Income",
      data: finalWeeklyData,
    }],
    xaxis: {
      type: 'category',
      categories: fullWeekDates,
      labels: {
        format: 'dd MMM',
      },
    },
    yaxis: {
      min: -1000000,
      max: 5000000,
      tickAmount: 5,
      axisBorder: { show: false },
      axisTicks: { show: false },
      labels: {
        formatter: function (val) {
          return "Rp " + val.toLocaleString();
        },
      },
    },
    plotOptions: {
      bar: {
        colors: {
          ranges: [
            { from: -1000000, to: 0, color: '#F15B46' },
            { from: 0, to: 50000000, color: '#28A745' }
          ]
        }
      }
    },
  });

  // 🔹 7. Update tampilan total income
  if (elementHarga) {
    elementHarga.innerHTML = `
      Total Transaksi: <strong>Rp. ${totalTransaksiAsli.toLocaleString()}</strong><br>
      Income Setelah Beban Rp.500.000/hari:<br>
      Positif Rp. ${totalPositif.toLocaleString()} - Negatif Rp. ${Math.abs(totalNegatif).toLocaleString()} = 
      <strong>Rp. ${netIncome.toLocaleString()}</strong>
    `;
  }
}
// Kondisi per bulan
else if (period === 'month') {
  let now = new Date();
  let currentYear = now.getFullYear();
  let currentMonth = ("0" + (now.getMonth() + 1)).slice(-2);

  // Buat array tanggal penuh sampai hari ini
  let fullDateArray = Array.from({ length: now.getDate() }, (_, i) =>
    `${currentYear}-${currentMonth}-${("0" + (i + 1)).slice(-2)}`
  );

  // Mapping data transaksi berdasarkan tanggal
  let dataMap = new Map(dateArrayJS.map((date, index) => [date, Number(totalHarianArrayJS[index])]));

  // 💵 Tambahkan ini: total semua transaksi asli (tanpa dikurangi)
  let totalTransaksiAsli = fullDateArray.reduce((total, date) => {
    return total + (dataMap.get(date) ?? 0);
  }, 0);

  // Modifikasi: jika ada transaksi, kurangi 500.000. Jika tidak, isi -500.000
  let filteredTotalHarianArray = fullDateArray.map(date => {
    const transaksi = dataMap.get(date);
    return transaksi !== undefined ? (transaksi - 500000) : -500000;
  });

  // Hitung total positif dan negatif dari data yang sudah dikurangi
  let totalPositif = filteredTotalHarianArray
    .filter(val => val > 0)
    .reduce((acc, val) => acc + val, 0);

  let totalNegatif = filteredTotalHarianArray
    .filter(val => val < 0)
    .reduce((acc, val) => acc + val, 0);

  let totalIncome = totalPositif + totalNegatif;

  // Logging
  console.log("Total Transaksi Asli:", totalTransaksiAsli);
  console.log("Filtered Harian Array (sudah dikurangi 500rb):", filteredTotalHarianArray);
  console.log("Total Positif:", totalPositif);
  console.log("Total Negatif:", totalNegatif);
  console.log("Total Income Setelah Perhitungan:", totalIncome);

  // Update chart
  chart.updateOptions({
    series: [{
      name: "Income",
      data: filteredTotalHarianArray
    }],
    xaxis: {
      type: 'category',
      categories: fullDateArray,
      labels: {
        formatter: function (value) {
          return value.split("-")[2];
        }
      }
    },
    yaxis: {
      min: -1000000,
      max: 5000000,
      tickAmount: 6,
      forceNiceScale: true,
      labels: {
        formatter: function (val) {
          return "Rp " + val.toLocaleString();
        }
      }
    },
    plotOptions: {
      bar: {
        colors: {
          ranges: [
            { from: -1000000, to: 0, color: '#F15B46' },
            { from: 0, to: 50000000, color: '#28A745' }
          ]
        }
      }
    },
    dataLabels: { enabled: false }
  });

  // ✅ Tampilkan total transaksi asli
  if (elementHarga) {
    elementHarga.innerHTML = `
      Total Transaksi: <strong>Rp. ${totalTransaksiAsli.toLocaleString()}</strong><br>
      Income Setelah Beban Rp.500.000/hari:<br>
      Positif Rp. ${totalPositif.toLocaleString()} - Negatif Rp. ${Math.abs(totalNegatif).toLocaleString()} = <strong>Rp. ${totalIncome.toLocaleString()}</strong>
    `;
  }
}

// Buat grafik pendapatan bulanan
else if (period === 'year') {
  let now = new Date();
  let currentYear = now.getFullYear();
  let currentMonth = now.getMonth() + 1; // Bulan sekarang (1-12)
  let currentDate = now.getDate(); // Tanggal hari ini

  console.log("Current Year:", currentYear);

  // ✅ Buat array dari Januari hingga bulan saat ini (YYYY-MM)
  let fullMonthArray = Array.from({ length: currentMonth }, (_, i) =>
    `${currentYear}-${("0" + (i + 1)).slice(-2)}`
  );

  console.log("Full Month Array:", fullMonthArray);

  // ✅ Hitung total pendapatan bulanan (tanpa formula)
  let monthlyTotalMap = new Map();

  monthArrayJS.forEach((month, index) => {
    let year = month.slice(0, 4); // Ambil tahun
    let income = Number(totalBulananArrayJS[index]);

    if (isNaN(income) || !/\d{4}-\d{2}/.test(month)) return;

    if (year === String(currentYear)) {
      if (monthlyTotalMap.has(month)) {
        monthlyTotalMap.set(month, monthlyTotalMap.get(month) + income);
      } else {
        monthlyTotalMap.set(month, income);
      }
    }
  });

  console.log("Monthly Total Map:", monthlyTotalMap);

  // ✅ Buat array total pendapatan asli (tanpa formula)
  let totalBulananArray = fullMonthArray.map(month => {
    return monthlyTotalMap.has(month) ? monthlyTotalMap.get(month) : 0;
  });

  console.log("Total Bulanan Array:", totalBulananArray);

  // ✅ Hitung total negatif di setiap bulan (dihitung dari jumlah hari x -500.000)
  let totalNegatifPerBulan = Array.from({ length: currentMonth }, (_, month) => {
    let daysInMonth = (month + 1 === currentMonth)
      ? currentDate // kalau bulan sekarang, hanya sampai hari ini
      : new Date(currentYear, month + 1, 0).getDate(); // jumlah hari dalam bulan

    let totalNegatif = daysInMonth * -500000;
    console.log(`Total Negatif di Bulan ${month + 1}:`, totalNegatif);
    return totalNegatif;
  });

  console.log("Total Negatif Per Bulan:", totalNegatifPerBulan);

  // ✅ Buat array income setelah dikurangi beban harian (WITH FORMULA)
  let totalWithFormulaArray = totalBulananArray.map((value, index) =>
    value + totalNegatifPerBulan[index]
  );

  // ✅ Ambil bulan ini (untuk ditampilkan di bawah chart)
  let totalIncomeBulanIni = totalBulananArray[currentMonth - 1] || 0;
  let totalNegatifBulanIni = totalNegatifPerBulan[currentMonth - 1];
  let totalIncomeBulanan = totalIncomeBulanIni + totalNegatifBulanIni;

  console.log("Total Income Bulan Ini (Original):", totalIncomeBulanIni);
  console.log("Total Negatif Bulan Ini:", totalNegatifBulanIni);
  console.log("Total Income Bulan Ini (With Formula):", totalIncomeBulanan);

  // ✅ Update Chart
  chart.updateOptions({
    series: [
      {
        name: "Monthly Income (With Formula)",
        data: totalWithFormulaArray,
        color: '#FFC107',
      },
      {
        name: "Monthly Income (Original)",
        data: totalBulananArray,
        color: '#28A745',
      }
    ],
    xaxis: {
      type: 'category',
      categories: fullMonthArray,
      labels: {
        formatter: function (value) {
          return new Date(value + "-01").toLocaleString('en-US', { month: 'short' });
        }
      }
    },
    yaxis: {
      min: Math.min(...totalWithFormulaArray, 0),
      max: Math.max(...totalWithFormulaArray) + 1000000,
      tickAmount: 6,
      forceNiceScale: true,
      labels: {
        formatter: function (val) {
          return "Rp " + val.toLocaleString();
        }
      }
    },
    plotOptions: {
      bar: {
        borderRadius: 2,
        columnWidth: "50%",
        endingShape: "rounded",
        colors: {
          ranges: [
            { from: -Infinity, to: -1, color: '#DC3545' } // Merah jika negatif
          ]
        }
      }
    },
    dataLabels: { enabled: false }
  });

  // ✅ Update Element Teks Income
  if (elementHarga) {
  const totalOriginalTahunIni = totalBulananArray.reduce((acc, val) => acc + val, 0);
  const totalNegatifTahunIni = totalNegatifPerBulan.reduce((acc, val) => acc + val, 0);
  const totalWithFormulaTahunIni = totalOriginalTahunIni + totalNegatifTahunIni;

  elementHarga.innerHTML = `
    Total Income Tahun Ini: <strong>Rp ${totalOriginalTahunIni.toLocaleString()}</strong><br>
    Total Beban Harian: <strong>Rp ${Math.abs(totalNegatifTahunIni).toLocaleString()}</strong><br>
    Total After Formula: <strong>Rp ${totalWithFormulaTahunIni.toLocaleString()}</strong>
  `;
}
}

else if (period === 'lastYear') {

  console.log(" Month Array:", monthArrayLastYearJS);
  console.log(" Month Array:", totalBulananArrayLastYearJS);


  
  let now = new Date();
  let lastYear = now.getFullYear() - 1;

  // ✅ Buat array Januari - Desember tahun lalu
  let fullMonthArrayLastYear = Array.from({ length: 12 }, (_, i) =>
    `${lastYear}-${("0" + (i + 1)).slice(-2)}`
  );

  // ✅ Petakan month & income tahun lalu ke Map
  let monthlyTotalMapLastYear = new Map();
  monthArrayLastYearJS.forEach((month, index) => {
    if (month.startsWith(`${lastYear}-`)) {
      monthlyTotalMapLastYear.set(month, Number(totalBulananArrayLastYearJS[index]));
    }
  });

  // ✅ Susun array total bulanan (isi 0 kalau kosong)
  let totalBulananArrayLastYear = fullMonthArrayLastYear.map(month => {
    return monthlyTotalMapLastYear.get(month) || 0;
  });

  // ✅ Hitung negatif per bulan (berdasarkan jumlah tanggal yang gak ada)
  let totalNegatifPerBulanLastYear = Array.from({ length: 12 }, (_, month) => {
    let daysInMonth = new Date(lastYear, month + 1, 0).getDate();
    let totalNegatif = 0;

    for (let day = 1; day <= daysInMonth; day++) {
      let formattedDate = `${lastYear}-${("0" + (month + 1)).slice(-2)}-${("0" + day).slice(-2)}`;
      if (!dateArrayLastYearJS.includes(formattedDate)) {
        totalNegatif += -500000;
      }
    }

    return totalNegatif;
  });

  // ✅ Total income Desember (bulan ke-12)
  let totalIncomeBulanDes = totalBulananArrayLastYear[11] || 0;
  let totalNegatifDes = totalNegatifPerBulanLastYear[11];
  let totalIncomeFixDes = totalIncomeBulanDes + totalNegatifDes;

  chart.updateOptions({
    series: [
      {
        name: "Monthly Income Last Year (With Formula)",
        data: totalBulananArrayLastYear.map((v, i) => v + totalNegatifPerBulanLastYear[i]),
        color: '#FFC107',
      },
      {
        name: "Monthly Income Last Year (Original)",
        data: totalBulananArrayLastYear,
        color: '#28A745',
      }
    ],
    xaxis: {
      categories: fullMonthArrayLastYear,
      labels: {
        formatter: value => new Date(value + "-01").toLocaleString('en-US', { month: 'short' })
      }
    },
    yaxis: {
      min: Math.min(...totalBulananArrayLastYear.map((v, i) => v + totalNegatifPerBulanLastYear[i]), 0),
      max: Math.max(...totalBulananArrayLastYear.map((v, i) => v + totalNegatifPerBulanLastYear[i])) + 1000000,
      tickAmount: 6,
      forceNiceScale: true,
      labels: {
        formatter: val => "Rp " + val.toLocaleString()
      }
    },
    plotOptions: {
      bar: {
        borderRadius: 2,
        columnWidth: "50%",
        endingShape: "rounded",
        colors: {
          ranges: [{ from: -Infinity, to: -1, color: '#DC3545' }]
        }
      }
    },
    dataLabels: { enabled: false }
  });

  if (elementHarga) {
    elementHarga.innerHTML = `Total Income Desember Tahun Lalu Rp. ${totalIncomeBulanDes.toLocaleString()} - Negatif Rp. ${totalNegatifDes.toLocaleString()} = Rp. ${totalIncomeFixDes.toLocaleString()}`;
  }
}
}

function timeRange(period) {
  const periodeElement = document.getElementById('periode');
  const terbayarElement = document.getElementById('terbayar');
  const belumTerbayarElement = document.getElementById('belumTerbayar');
  const batalElement = document.getElementById('batal');
  const persenTerbayarElement = document.getElementById('persenTerbayar');
  const persenBlmTerbayarElement = document.getElementById('persenBlmTerbayar');
  const persenBatalElement = document.getElementById('persenBatal');

  switch (period) {
    case 'year':
      charts.updateOptions({
        series: [datasYearJS[0], datasYearJS[1]],
      });
      periodeElement.innerText = 'Yearly';
      terbayarElement.innerText = 'Rp.' + datasYearJS[0].toLocaleString();
      belumTerbayarElement.innerText = ' Rp.' + datasYearJS[1].toLocaleString();
      batalElement.innerText = 'Rp.' + datasYearJS[2].toLocaleString();
      persenTerbayarElement.innerText = formatPersentaseTerbayar(percentInYearJS) + '%';
      persenBlmTerbayarElement.innerText = formatPersentase(percentNotPaidYearJS) + '%';
      persenBatalElement.innerText = formatPersentaseBatal(percentCanceledYearJS) + '%';
      break;
    case 'month':
      charts.updateOptions({
        series: [datasMonthJS[0], datasMonthJS[1]],
      });
      periodeElement.innerText = 'Monthly';
      terbayarElement.innerText = 'Rp.' + datasMonthJS[0].toLocaleString();
      belumTerbayarElement.innerText = ' Rp.' + datasMonthJS[1].toLocaleString();
      batalElement.innerText = 'Rp.' + datasMonthJS[2].toLocaleString();
      persenTerbayarElement.innerText = formatPersentaseTerbayar(percentInMonthJS) + '%';
      persenBlmTerbayarElement.innerText = formatPersentase(percentNotPaidMonthJS) + '%';
      persenBatalElement.innerText = formatPersentaseBatal(percentCanceledMonthJS) + '%';
      break;
    default:
      charts.updateOptions({
        series: [datasWeekJS[0], datasWeekJS[1]],
      });
      periodeElement.innerText = 'Weekly';
      terbayarElement.innerText = 'Rp.' + datasWeekJS[0].toLocaleString();
      belumTerbayarElement.innerText = ' Rp.' + datasWeekJS[1].toLocaleString();
      batalElement.innerText = 'Rp.' + datasWeekJS[2].toLocaleString();
      persenTerbayarElement.innerText = formatPersentaseTerbayar(percentInWeekJS) + '%';
      persenBlmTerbayarElement.innerText = formatPersentase(percentNotPaidWeekJS) + '%';
      persenBatalElement.innerText = formatPersentaseBatal(percentCanceledWeekJS) + '%';
      break;
  }
  // if (period == 'year') {


  // } else if (period == 'month') {

  // } else if (period == 'week') {

  // }
}

function formatPersentaseTerbayar(persen) {
  const persenTerbayarElement = document.getElementById('persenTerbayar');
  if (persen < 0) {
    persenTerbayarElement.classList.remove('badge', 'bg-primary');
    persenTerbayarElement.classList.add('badge', 'bg-danger');
    return persen;
  } else {
    persenTerbayarElement.classList.remove('badge', 'bg-danger');
    persenTerbayarElement.classList.add('badge', 'bg-primary');
    return '+' + persen;
  }
}

function formatPersentase(persen) {
  const persenBlmTerbayarElement = document.getElementById('persenBlmTerbayar');

  if (persen > 0) {
    persenBlmTerbayarElement.classList.remove('badge', 'bg-primary');
    persenBlmTerbayarElement.classList.add('badge', 'bg-danger');
    return '+' + persen;
  } else {
    persenBlmTerbayarElement.classList.remove('badge', 'bg-danger');
    persenBlmTerbayarElement.classList.add('badge', 'bg-primary');
    return persen;
  }
}

function formatPersentaseBatal(persen) {
  const persenBatalElement = document.getElementById('persenBatal');
  if (persen > 0) {
    persenBatalElement.classList.remove('badge', 'bg-primary');
    persenBatalElement.classList.add('badge', 'bg-danger');
    return '+' + persen;
  }
  persenBatalElement.classList.remove('badge', 'bg-danger');
  persenBatalElement.classList.add('badge', 'bg-primary');

  return persen
}

// CHART PO

var options,
  chartPO,
  linechartBasicColorsPO = getChartColorsArray("stacked_column_chart_po"),
  barchartColors =
    (linechartBasicColorsPO &&
      ((options = {
        chart: {
          height: 362,
          type: "bar",
          toolbar: { show: !1 },
          zoom: { enabled: !0 },
        },
        plotOptions: {
          bar: {
            horizontal: !1,
            columnWidth: "35%",
            endingShape: "rounded",
          },
        },
        dataLabels: { enabled: !1 },
        labels: hourArrayJSPO,
        series: [
          {
            name: "Income",
            data: totalJamArrayJSPO,
          },

        ],
        xaxis: {

        },
        grid: {
          xaxis: { lines: { show: !1 } },
          yaxis: { lines: { show: !1 } },
        },
        colors: linechartBasicColorsPO,
        legend: { show: !1 },
        fill: { opacity: 1 },
      }),
        (chartPO = new ApexCharts(
          document.querySelector("#stacked_column_chart_po"),
          options
        )).render())
    );

var options, chartsPO,
  donutchartColorsPO = getChartColorsArray("structure_widget_po"),
  chartColors =
    (donutchartColorsPO &&
      ((options = {
        chart: { height: 300, type: "donut" },
        series: datasMonthJSPO,
        labels: ["Terbayar", "Belum Bayar", "Batal"],
        colors: donutchartColorsPO,
        plotOptions: { pie: { startAngle: 25, donut: { size: "78%" } } },
        legend: { show: !1 },
        dataLabels: {
          style: {
            fontSize: "11px",
            fontFamily: "DM Sans,sans-serif",
            colors: void 0,
          },
          background: {
            enabled: !0,
            foreColor: "#fff",
            padding: 4,
            borderRadius: 2,
            borderWidth: 1,
            borderColor: "#fff",
            opacity: 1,
          },
        },
        responsive: [
          {
            breakpoint: 600,
            options: { chart: { height: 240 }, legend: { show: !1 } },
          },
        ],
      }),
        (chartsPO = new ApexCharts(
          document.querySelector("#structure_widget_po"),
          options
        )).render())
    );

function timeFramePO(period) {
  const buttons = document.getElementsByClassName('tombolPO');
  for (let i = 0; i < buttons.length; i++) {
    buttons[i].classList.remove('btn-info');
    buttons[i].classList.add('btn-soft-info');
  }
  const elementHarga = document.getElementById('jumlahPO');
  const selectedButton = document.getElementById('btn_' + period);
  selectedButton.classList.remove('btn-soft-info');
  selectedButton.classList.add('btn-info');

  const now = new Date();
  const startOfDay = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 15, 0); //start pukul 8 karena indonesia gmt+7 maka 8+7
  const endOfDay = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 24, 0); //end pukul 17 karena Indonesia gmt+7 maka 17+7
  const startOfWeek = new Date(now.getFullYear(), now.getMonth(), now.getDate());
  startOfWeek.setDate(now.getDate() - now.getDay() + 1);
  const endOfWeek = new Date(now.getFullYear(), now.getMonth(), now.getDate());
  endOfWeek.setDate(now.getDate() - now.getDay() + 6);
  const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1, 0, 1, 0, 0);
  const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);
  const startOfYear = new Date(now.getFullYear(), 0, 1);
  const endOfYear = new Date(now.getFullYear(), 10, 31, 23, 59, 59);
  
  switch (period) {
    case 'day':

      chartPO.updateOptions({
        dataLabels: { enabled: !1 },
        labels: hourArrayJSPO,
        series: [
          {
            name: "Income",
            data: totalJamArrayJSPO,
          },

        ],
        xaxis: {
          type: 'datetime',
          min: startOfDay.getTime(),
          max: endOfDay.getTime(),
          labels: {
            format: 'HH:mm',
          },
        },
      });
      elementHarga.innerHTML = `Rp. ${totalPerHariPO}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">PO</span>`;
      break;
    case 'week':
      // const formattedWeek = totalMingguanArrayJSPO.map(val => parse(val).toLocaleString());
      // console.log(formattedWeek)
      chartPO.updateOptions({
        dataLabels: { enabled: !1 },
        labels: weekArrayJSPO,
        // {
        //   show: true,
        //   formatter: function (val) {
        //     return "Rp " + val.toLocaleString();
        //   }
        // },
        series: [
          {
            name: "Income",
            data: totalMingguanArrayJSPO,
          },

        ],
        xaxis: {
          type: 'datetime',
          min: startOfWeek.getTime(),
          max: endOfWeek.getTime(),
          labels: {
            format: 'dd MMM',
          },
        },
      });
      elementHarga.innerHTML = `Rp. ${totalPerMingguPO}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">PO</span>`;
      break;
    case 'month':
      chartPO.updateOptions({
        dataLabels: { enabled: !1 },
        labels: dateArrayJSPO,
        series: [
          {
            name: "Income",
            data: totalHarianArrayJSPO,
          },

        ],
        xaxis: {
          type: 'datetime',
          min: startOfMonth.getTime(),
          max: endOfMonth.getTime(),
          labels: {
            format: 'dd',
          },
        },
      });
      if (elementHarga) {
        elementHarga.innerHTML = `Rp. ${totalPerBulanPO}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">PO</span>`;
      }
      break;
    case 'year':
      chartPO.updateOptions({
        dataLabels: { enabled: !1 },
        labels: monthArrayJSPO,
        series: [
          {
            name: "Income",
            data: totalBulananArrayJSPO,
          },

        ],
        xaxis: {
          type: 'datetime',
          min: startOfYear.getTime(),
          max: endOfYear.getTime(),
          labels: {
            format: 'MMM',
          },
        },
      });
      elementHarga.innerHTML = `Rp. ${totalPerTahunPO}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">PO</span>`;
      break;
    default:
      Alert('error')
      break;
  }


}

// Chart Outstanding Barang

var options = {
  series: [{
    name: 'Jumlah Sisa',
    data: totalSisa
  }],
  chart: {
    height: 350,
    type: 'bar',
    toolbar: { show: !1 },
  },
  plotOptions: {
    bar: {
      horizontal: !1,
      borderRadius: 0,
      columnWidth: "35%",
      endingShape: "rounded",
      dataLabels: {
        position: 'top', // top, center, bottom
      },
    }
  },
  dataLabels: { enabled: !1 },

  // dataLabels: {
  //   enabled: true,
  //   formatter: function (val) {
  //     return "Rp " + val.toLocaleString();
  //   },
  //   offsetY: -20,
  //   style: {
  //     fontSize: '12px',
  //     colors: ["#304758"]
  //   }
  // },

  xaxis: {
    categories: namaVendor,
    position: 'bottom',
    axisBorder: {
      show: true
    },
    axisTicks: {
      show: false
    },
    crosshairs: {
      fill: {
        type: 'gradient',
        gradient: {
          colorFrom: '#D8E3F0',
          colorTo: '#BED1E6',
          stops: [0, 100],
          opacityFrom: 0.4,
          opacityTo: 0.5,
        }
      }
    },
    tooltip: {
      enabled: false,
    }
  },
  yaxis: {
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false,
    },
    labels: {
      show: true,
      formatter: function (val) {
        return "Rp " + val.toLocaleString();
      }
    }

  },
  // title: {
  //   text: 'Nama Vendor',
  //   floating: true,
  //   offsetY: 330,
  //   align: 'center',
  //   style: {
  //     color: '#444'
  //   }
  // },
  colors: ['#438a7a'],
};
var chartOut = new ApexCharts(document.querySelector("#vendorChart"), options);
chartOut.render();



// function timeRangePO(period) {
//   const periodeElement = document.getElementById('periodePO');
//   const terbayarElement = document.getElementById('terbayarPO');
//   const belumTerbayarElement = document.getElementById('belumTerbayarPO');
//   const batalElement = document.getElementById('batalPO');
//   const persenTerbayarElement = document.getElementById('persenTerbayarPO');
//   const persenBlmTerbayarElement = document.getElementById('persenBlmTerbayarPO');
//   const persenBatalElement = document.getElementById('persenBatalPO');

//   switch (period) {
//     case 'year':
//       chartsPO.updateOptions({
//         series: datasYearJSPO,
//       });
//       periodeElement.innerText = 'Yearly';
//       terbayarElement.innerText = 'Rp.' + datasYearJSPO[0].toLocaleString();
//       belumTerbayarElement.innerText = ' Rp.' + datasYearJSPO[1].toLocaleString();
//       batalElement.innerText = 'Rp.' + datasYearJSPO[2].toLocaleString();
//       persenTerbayarElement.innerText = formatPersentaseTerbayarPO(percentInYearJSPO) + '%';
//       persenBlmTerbayarElement.innerText = formatPersentasePO(percentNotPaidYearJSPO) + '%';
//       persenBatalElement.innerText = formatPersentaseBatalPO(percentCanceledYearJSPO) + '%';
//       break;
//     case 'month':
//       chartsPO.updateOptions({
//         series: datasMonthJSPO,
//       });
//       periodeElement.innerText = 'Monthly';
//       terbayarElement.innerText = 'Rp.' + datasMonthJSPO[0].toLocaleString();
//       belumTerbayarElement.innerText = ' Rp.' + datasMonthJSPO[1].toLocaleString();
//       batalElement.innerText = 'Rp.' + datasMonthJSPO[2].toLocaleString();
//       persenTerbayarElement.innerText = formatPersentaseTerbayarPO(percentInMonthJSPO) + '%';
//       persenBlmTerbayarElement.innerText = formatPersentasePO(percentNotPaidMonthJSPO) + '%';
//       persenBatalElement.innerText = formatPersentaseBatalPO(percentCanceledMonthJSPO) + '%';
//       break;
//     default:
//       chartsPO.updateOptions({
//         series: datasWeekJS,
//       });
//       periodeElement.innerText = 'Weekly';
//       terbayarElement.innerText = 'Rp.' + datasWeekJSPO[0].toLocaleString();
//       belumTerbayarElement.innerText = ' Rp.' + datasWeekJSPO[1].toLocaleString();
//       batalElement.innerText = 'Rp.' + datasWeekJSPO[2].toLocaleString();
//       persenTerbayarElement.innerText = formatPersentaseTerbayarPO(percentInWeekJSPO) + '%';
//       persenBlmTerbayarElement.innerText = formatPersentasePO(percentNotPaidWeekJSPO) + '%';
//       persenBatalElement.innerText = formatPersentaseBatalPO(percentCanceledWeekJSPO) + '%';
//       break;
//   }

// }

// function formatPersentaseTerbayarPO(persen) {
//   const persenTerbayarElement = document.getElementById('persenTerbayarPO');
//   if (persen < 0) {
//     persenTerbayarElement.classList.remove('badge', 'bg-primary');
//     persenTerbayarElement.classList.add('badge', 'bg-danger');
//     return persen;
//   } else {
//     persenTerbayarElement.classList.remove('badge', 'bg-danger');
//     persenTerbayarElement.classList.add('badge', 'bg-primary');
//     return '+' + persen;
//   }
// }

// function formatPersentasePO(persen) {
//   const persenBlmTerbayarElement = document.getElementById('persenBlmTerbayarPO');

//   if (persen > 0) {
//     persenBlmTerbayarElement.classList.remove('badge', 'bg-primary');
//     persenBlmTerbayarElement.classList.add('badge', 'bg-danger');
//     return '+' + persen;
//   } else {
//     persenBlmTerbayarElement.classList.remove('badge', 'bg-danger');
//     persenBlmTerbayarElement.classList.add('badge', 'bg-primary');
//     return persen;
//   }
// }

// function formatPersentaseBatalPO(persen) {
//   const persenBatalElement = document.getElementById('persenBatalPO');
//   if (persen > 0) {
//     persenBatalElement.classList.remove('badge', 'bg-primary');
//     persenBatalElement.classList.add('badge', 'bg-danger');
//     return '+' + persen;
//   }
//   persenBatalElement.classList.remove('badge', 'bg-danger');
//   persenBatalElement.classList.add('badge', 'bg-primary');

//   return persen
// }
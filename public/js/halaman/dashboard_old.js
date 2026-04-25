window.onload = function () {
  timeFrame('month');
//   timeFramePO('year');
  timeRange('month');
  
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

  }
}


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
          xaxis: { lines: { show: false } },
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
  const startOfWeek = new Date(now.getFullYear(), now.getMonth(), now.getDate());
  startOfWeek.setDate(now.getDate() - now.getDay() + 1);
  const endOfWeek = new Date(now.getFullYear(), now.getMonth(), now.getDate());
  endOfWeek.setDate(now.getDate() - now.getDay() + 6);
  const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1, 0, 1, 0, 0);
  const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);
  const startOfYear = new Date(now.getFullYear(), 0, 1);
  const endOfYear = new Date(now.getFullYear(), 10, 31, 23, 59, 59);
  if (period === 'day') {
    chart.updateOptions({
      dataLabels: { enabled: !1 },
      labels: hourArrayJS,
      series: [
        {
          name: "Income",
          data: totalJamArrayJS,
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
       colors: ['#FF5733', '#33FF57', '#3357FF', '#FF33A8', '#33FFF5', '#F5FF33', '#FF8C33', '#33A8FF', '#A833FF', '#57FF33', '#FF3333', '#33FF8C'],
        yaxis: {
        min: 0, // Nilai minimum di sumbu Y
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
      plotOptions: {
        bar: {
          distributed: true, // Membuat tiap bar punya warna berbeda
          borderRadius: 2, // Menambahkan border radius di ujung bar
          columnWidth: "50%", // Ukuran lebar bar
          endingShape: "rounded", // Ujung bar terlihat lebih smooth
        },
      },
    });
    elementHarga.innerHTML = `Rp. ${totalPerHari}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Incomes</span>`;
  } else if (period === 'week') {
    console.log("🚀 Running Weekly Chart Update...");
    console.log("🟢 Entered Weekly Chart Update Block");

    let today = new Date();
    console.log("📌 Today:", today.toISOString());

    let startOfWeek = new Date(today);
    startOfWeek.setDate(today.getDate() - today.getDay() + 1); // Senin
    startOfWeek.setHours(0, 0, 0, 0);
    console.log("📅 Start of Week:", startOfWeek.toISOString());

    let endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6); // Minggu
    endOfWeek.setHours(23, 59, 59, 999);
    console.log("📅 End of Week:", endOfWeek.toISOString());

    console.log("Before Processing - weekArrayJS:", weekArrayJS);
    console.log("Before Processing - totalMingguanArrayJS:", totalMingguanArrayJS);

    // 🔹 1. Buat daftar semua hari dalam minggu ini
    let fullWeekDates = [];
    let currentDate = new Date(startOfWeek);

    while (currentDate <= endOfWeek) {
        let formattedDate = currentDate.toISOString().split("T")[0]; // Format YYYY-MM-DD
        fullWeekDates.push(formattedDate);
        currentDate.setDate(currentDate.getDate() + 1);
    }
    console.log("🔹 Full Week Dates (X-Axis):", fullWeekDates);

    // 🔹 2. Buat Map untuk menyimpan data transaksi
    let weekDataMap = new Map(fullWeekDates.map(date => [date, -500000])); // Default -500000

    // 🔹 3. Isi data transaksi dari weekArrayJS
    weekArrayJS.forEach((date, index) => {
        weekDataMap.set(date, Number(totalMingguanArrayJS[index]));
    });

    // 🔹 4. Ambil kembali data yang sudah diurutkan sesuai minggu ini
    let finalWeeklyData = fullWeekDates.map(date => weekDataMap.get(date));

    console.log("✅ Final Processed Weekly Data (X-Axis):", fullWeekDates);
    console.log("✅ Final Processed Weekly Data (Y-Axis):", finalWeeklyData);

    // 🔹 5. Tentukan warna berdasarkan nilai
    let barColors = finalWeeklyData.map(value => value >= 0 ? '#28A745' : '#F15B46');

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
                show: true,
                formatter: function (val) {
                    return "Rp " + val.toLocaleString();
                },
            },
        },
        plotOptions: {
            bar: {
                distributed: true, // Supaya setiap bar bisa punya warna berbeda
                borderRadius: 2,
                columnWidth: "50%",
                colors: {
                    ranges: [
                        { from: -10000000, to: 0, color: '#F15B46' }, // Merah untuk negatif
                        { from: 0, to: 100000000, color: '#28A745' } // Hijau untuk positif
                    ]
                }
            }
        },
        colors: barColors, // Mengubah warna bar sesuai nilai
    });

    elementHarga.innerHTML = `Rp. ${totalPerMinggu}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Incomes</span>`;
}
else if (period === 'month') {
    let now = new Date();
    let currentYear = now.getFullYear();
    let currentMonth = ("0" + (now.getMonth() + 1)).slice(-2);
    let daysInMonth = new Date(currentYear, now.getMonth() + 1, 0).getDate();

    // ✅ Pastikan semua tanggal dalam bulan ini ada
    let fullDateArray = Array.from({ length: daysInMonth }, (_, i) => 
        `${currentYear}-${currentMonth}-${("0" + (i + 1)).slice(-2)}`
    );

    // ✅ Mapping data untuk tanggal yang ada, jika tidak ada isi -500000
    let dataMap = new Map(dateArrayJS.map((date, index) => [date, totalHarianArrayJS[index] ?? -500000]));
    let filteredTotalHarianArray = fullDateArray.map(date => dataMap.get(date) ?? -500000);

    // ✅ Tentukan batas waktu agar tidak error
    let startOfMonth = new Date(currentYear, now.getMonth(), 1).getTime();
    let endOfMonth = new Date(currentYear, now.getMonth() + 1, 0).getTime();

    // ✅ Gunakan "category" agar tidak error dengan "datetime"
    chart.updateOptions({
        series: [{
            name: "Income",
            data: filteredTotalHarianArray
        }],
        xaxis: {
          type: 'category', // Pakai kategori, bukan datetime
          categories: fullDateArray, // Array lengkap berisi 'YYYY-MM-DD'
          labels: {
              formatter: function (value) {
                  return value.split("-")[2]; // Ambil tanggal saja (dd)
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
                        { from: -1000000, to: 0, color: '#F15B46' }, // Merah untuk negatif
                        { from: 0, to: 50000000, color: '#28A745' } // Hijau untuk positif
                    ]
                }
            }
        },
        dataLabels: { enabled: false }
    });

    if (elementHarga) {
        elementHarga.innerHTML = `Rp. ${totalPerBulan}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Incomes</span>`;
    }

  } else if (period === 'year') {
    chart.updateOptions({
      dataLabels: { enabled: false },
      labels: monthArrayJS,
      series: [
        {
          name: "Income",
          data: totalBulananArrayJS,
        },
      ],
      colors: ['#FF5733', '#33FF57', '#3357FF', '#FF33A8', '#33FFF5', '#F5FF33', '#FF8C33', '#33A8FF', '#A833FF', '#57FF33', '#FF3333', '#33FF8C'],
      xaxis: {
        type: 'datetime',
        min: startOfYear.getTime(),
        max: endOfYear.getTime(),
        labels: {
          format: 'MMM',
        },
      },
      
      yaxis: {
        min: 0, 
        max: 100000000,
        tickAmount: 8, 
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
    elementHarga.innerHTML = `Rp. ${totalPerTahun}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Incomes</span>`;
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
  const elementOutstandingPO = document.getElementById('jumlahOutstandingPO');
  const elementSettlePO = document.getElementById('jumlahSettlePO');
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
      elementHarga.innerHTML = `Rp. ${totalPerHariPO}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Today All PO</span>`;
      elementOutstandingPO.innerHTML = `Rp. ${blmLunasTodayPO}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Today Outstanding PO</span>`;
      elementSettlePO.innerHTML = `Rp. ${lunasTodayPO}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Today Settled PO</span>`;
      break;
    case 'week':
      chartPO.updateOptions({
        dataLabels: { enabled: !1 },
        labels: weekArrayJSPO,
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
      elementHarga.innerHTML = `Rp. ${totalPerMingguPO}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Week All PO</span>`;
      elementOutstandingPO.innerHTML = `Rp. ${blmLunasWeekPO}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Week Outstanding PO</span>`;
      elementSettlePO.innerHTML = `Rp. ${lunasWeekPO}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Week Settled PO</span>`;
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
        elementHarga.innerHTML = `Rp. ${totalPerBulanPO}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Month All PO</span>`;
        elementOutstandingPO.innerHTML = `Rp. ${blmLunasMonthPO}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Month Outstanding PO</span>`;
        elementSettlePO.innerHTML = `Rp. ${lunasMonthPO}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Month Settled PO</span>`;
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
      elementHarga.innerHTML = `Rp. ${totalPerTahunPO}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Year All PO</span>`;
      elementOutstandingPO.innerHTML = `Rp. ${blmLunasYearPO}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Year Outstanding PO</span>`;
      elementSettlePO.innerHTML = `Rp. ${lunasYearPO}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Year Settled PO</span>`;
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
    toolbar: { show: false },
  },
  plotOptions: {
    bar: {
      horizontal: false,
      borderRadius: 2,
      
      endingShape: "rounded",
      distributed: true, // Membuat setiap bar memiliki warna yang berbeda
      dataLabels: {
        position: 'top', // top, center, bottom
      },
    }
  },
  dataLabels: { enabled: false },

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

  // Warna-warna berbeda untuk setiap bar
  colors: ['#FF5733', '#33FF57', '#3357FF', '#FF33A8', '#33FFF5', '#F5FF33', '#FF8C33', '#33A8FF', '#A833FF', '#57FF33', '#FF3333', '#33FF8C'],
  
  // Menghilangkan keterangan warna di bawah chart
  legend: { show: false },
};

// Render chart
var chartOut = new ApexCharts(document.querySelector("#vendorChart"), options);
chartOut.render();


window.onload = function () {
  timeFrame('week')
}

$(document).ready(function () {
  $('.hapus-btn').click(function (e) {
    e.preventDefault();

    var spk_id = $(this).attr('value');
    $('#spb_id').val(spk_id);
    $('#deleteForm').attr('action', '/delete-spk/' + spk_id); // Update the form action URL dynamically

    $('#deleteModal').modal('show');
  });
});


var options = {
  series: [{
    name: 'Waktu Desain',
    data: tlDesignsMonth.map(val => val.toFixed(0) + ' jam')
  }, {
    name: 'Waktu Produksi',
    data: tlProductionsMonth.map(val => val.toFixed(0) + ' jam')
  }, {
    name: 'Waktu Keseluruhan',
    data: tlKeseluruhanMonth.map(val => val.toFixed(0) + ' jam')
  }],
  chart: {
    type: 'bar',
    height: 350
  },
  plotOptions: {
    bar: {
      horizontal: false,
      columnWidth: '55%',
      endingShape: 'rounded'
    },
  },
  dataLabels: {
    enabled: false
  },
  stroke: {
    show: true,
    width: 2,
    colors: ['transparent']
  },
  xaxis: {
    categories: nomorInvMonth,
  },
  yaxis: {
    title: {
      text: 'Waktu (Jam)'
    }
  },
  fill: {
    opacity: 1
  },
  tooltip: {
    y: {
      formatter: function (val) {
        console.log(val)
        var days = Math.floor(val / 24); // Menghitung jumlah hari
        var hours = Math.floor(val % 24); // Menghitung jumlah jam
        var minutes = Math.floor((val * 60) % 60); // Menghitung jumlah menit
        console.log(minutes)
        var formattedTime = '';

        if (days > 0) {
          formattedTime += days + ' hari ';
        }
        if (hours > 0) {
          formattedTime += hours + ' jam ';
        }
        if (minutes >= 0) {
          formattedTime += minutes + ' menit';
        }

        return formattedTime;
      },
    },
  },
  colors: ['#ffff00', '#e74c3c', '#3498db'],
};
var chart = new ApexCharts(document.querySelector("#column_chart"), options);
chart.render()
function timeFrame(period) {
  const buttons = document.getElementsByClassName('tombol')
  for (let i = 0; i < buttons.length; i++) {
    buttons[i].classList.remove('btn-info')
    buttons[i].classList.add('btn-soft-info')
  }
  const selectedButton = document.getElementById('btn-' + period)
  // alert(period);
  selectedButton.classList.remove('btn-soft-info')
  selectedButton.classList.add('btn-info')

  switch (period) {
    case 'week':
      chart.updateOptions({
        series: [{
          name: 'Waktu Desain',
          data: tlDesignsWeek.map(val => val.toFixed(0) + ' jam')
        }, {
          name: 'Waktu Produksi',
          data: tlProductionsWeek.map(val => val.toFixed(0) + ' jam')
        }, {
          name: 'Waktu Keseluruhan',
          data: tlKeseluruhanWeek.map(val => val.toFixed(0) + ' jam')
        }],
        chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: nomorInvWeek,
        },
      })
      break;
    case ('month'):
      chart.updateOptions({
        series: [{
          name: 'Waktu Desain',
          data: tlDesignsMonth.map(val => val.toFixed(0) + ' jam')
        }, {
          name: 'Waktu Produksi',
          data: tlProductionsMonth.map(val => val.toFixed(0) + ' jam')
        }, {
          name: 'Waktu Keseluruhan',
          data: tlKeseluruhanMonth.map(val => val.toFixed(0) + ' jam')
        }],
        chart: {
          type: 'bar',
          height: 350
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '55%',
            endingShape: 'rounded'
          },
        },
        dataLabels: {
          enabled: false
        },
        stroke: {
          show: true,
          width: 2,
          colors: ['transparent']
        },
        xaxis: {
          categories: nomorInvMonth,
        },
      })
      break
    default:
      break;
  }
}

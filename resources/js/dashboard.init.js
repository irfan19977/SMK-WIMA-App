/*
Template Name: Tocly -  Admin & Dashboard Template
Author: Themesdesign
Contact: themesdesign.in@gmail.com
File: Dashboard Init Js File
*/



// column chart

var options = {
  series: [{
  name: 'Siswa Tepat Waktu',
  data: window.lateStatistics ? window.lateStatistics.onTimeCount : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
}, {
  name: 'Siswa Terlambat',
  data: window.lateStatistics ? window.lateStatistics.lateCount : [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0]
}],
  chart: {
  type: 'bar',
  height: 350,
  stacked: true,
  toolbar: {
    show: false
  },
  zoom: {
    enabled: true
  }
},

plotOptions: {
  bar: {
    horizontal: false,
    columnWidth: '42%'
  },
},
dataLabels: {
  enabled: false
},

legend: {
 show:true,
  position: 'top'
},
xaxis: {
  categories: window.lateStatistics ? window.lateStatistics.months : ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'July', 'Aug', 'Sep', 'Oct', 'Nov', 'Des' ],
},
colors: ['#39c685', '#ed5d49'],
fill: {
  opacity: 1
}
};

var chart = new ApexCharts(document.querySelector("#column-chart"), options);
chart.render();


// donut chart

var options = {
    series: window.donutStatistics ? window.donutStatistics.data : [0, 0, 0],
    labels: window.donutStatistics ? window.donutStatistics.labels : ["Tepat Waktu", "Terlambat", "Lainnya"],
    chart: {
      type: "donut",
      height: 350,
    },

    plotOptions: {
      pie: {
        size: 100,
        offsetX: 0,
        offsetY: 0,
        donut: {
          size: "77%",
          labels: {
            show: true,
            name: {
              show: true,
              fontSize: "18px",
              offsetY: -5,
            },
            value: {
              show: true,
              fontSize: "24px",
              color: "#343a40",
              fontWeight: 500,
              offsetY: 10,
              formatter: function (val) {
                return val;
              },
            },
            total: {
              show: true,
              fontSize: "16px",
              label: "Total Kehadiran Bulan Ini",
              color: "#9599ad",
              fontWeight: 400,
              formatter: function (w) {
                return w.globals.seriesTotals.reduce(function (a, b) {
                  return a + b;
                }, 0);
              },
            },
          },
        },
      },
    },
    dataLabels: {
      enabled: false,
    },
    legend: {
      show: true,
      position: 'bottom',
    },
    stroke: {
      lineCap: "round",
      width: 2,
    },
    colors: ['#39c685', '#ed5d49', '#daeaee'],
  };
  var chart = new ApexCharts(document.querySelector("#donut-chart"), options);
  chart.render();


// world map with line & markers
var worldlinemap = new jsVectorMap({
  map: "world_merc",
  selector: "#world-map-markers",
  zoomOnScroll: false,
  zoomButtons: false,
  markerStyle:{
    initial: { fill: "#0c768a" },
    selected: { fill: "#0c768a" }
  },
  markers: [{
          name: "Greenland",
          coords: [72, -42]
      },
      {
          name: "Canada",
          coords: [56.1304, -106.3468]
      },
      {
          name: "Brazil",
          coords: [-14.2350, -51.9253]
      },
      {
          name: "Egypt",
          coords: [26.8206, 30.8025]
      },
      {
          name: "Russia",
          coords: [61, 105]
      },
      {
          name: "China",
          coords: [35.8617, 104.1954]
      },
      {
          name: "United States",
          coords: [37.0902, -95.7129]
      },
      {
          name: "Norway",
          coords: [60.472024, 8.468946]
      },
      {
          name: "Ukraine",
          coords: [48.379433, 31.16558]
      },
  ],
  lines: [{
          from: "Canada",
          to: "Egypt"
      },
      {
          from: "Russia",
          to: "Egypt"
      },
      {
          from: "Greenland",
          to: "Egypt"
      },
      {
          from: "Brazil",
          to: "Egypt"
      },
      {
          from: "United States",
          to: "Egypt"
      },
      {
          from: "China",
          to: "Egypt"
      },
      {
          from: "Norway",
          to: "Egypt"
      },
      {
          from: "Ukraine",
          to: "Egypt"
      },
  ],
  regionStyle: {
      initial: {
          stroke: "#daeaee",
          strokeWidth: 0.25,
          fill: "#daeaee",
          fillOpacity: 1,
      },
  },
  lineStyle: {
      animation: true,
      strokeDasharray: "6 3 6",
  },
})



// radialBar
var options = {
  labels: window.donutStatistics ? window.donutStatistics.labels : ["Tepat Waktu", "Terlambat", "Lainnya"],
  series: window.donutStatistics ? window.donutStatistics.data : [0, 0, 0],
  chart: {
      height: 402,
  type: 'donut',
},
plotOptions: {
  pie: {
    startAngle: -90,
    endAngle: 90,
    offsetY: 10,
    donut: {
      size: '80%',
    },
  }
},
colors: ['#39c685', '#ed5d49', '#daeaee'],
grid: {
  padding: {
    bottom: -190
  }
},

legend: {
  show: false,
},

responsive: [{
  breakpoint: 320,
  options: {
    chart: {
      width: 180
    },
    legend: {
      position: 'bottom'
    }
  }
}]
};

var chart = new ApexCharts(document.querySelector("#social-source"), options);
chart.render();
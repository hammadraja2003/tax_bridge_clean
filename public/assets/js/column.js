//  **------column_chart 1*
var options = {
    series: [
        {
            name: "Sales Tax",
            data: salesTaxData,
        },
        {
            name: "Further Tax",
            data: furtherTaxData,
        },
        {
            name: "Extra Tax",
            data: extraTaxData,
        },
    ],
    chart: {
        type: "bar",
        height: 400,
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: "55%",
            endingShape: "rounded",
        },
    },
    colors: [
        getLocalStorageItem("color-primary", "#1d61d8"),
        getLocalStorageItem("color-secondary", "#0fbc66"),
        "#f39c12",
    ],

    dataLabels: {
        enabled: false,
    },
    stroke: {
        show: true,
        width: 2,
        colors: ["transparent"],
    },
    xaxis: {
        categories: monthLabels,
    },
    yaxis: {
        title: {
            text: "",
        },
    },
    fill: {
        opacity: 1,
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return "Rs. " + val;
            },
        },
    },
};

var chart = new ApexCharts(document.querySelector("#column1"), options);
chart.render();

document.addEventListener("DOMContentLoaded", function () {
    const data = invoiceMonthlyStats;

    if (data.series.length === 0) {
        data.series = [{ name: "No Data", data: Array(12).fill(0) }];
    }

    var options = {
        series: data.series,
        chart: {
            type: "bar",
            height: 400,
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "50%",
            },
        },
        xaxis: {
            categories: data.months,
        },
        colors: ["#1d61d8", "#0fbc66"],

        legend: {
            position: "bottom",
            offsetY: 10,
        },
        dataLabels: {
            enabled: true,
            style: {
                fontSize: "10px",
                fontWeight: "bold",
            },
        },
        tooltip: {
            y: {
                formatter: function (val) {
                    return val + " invoices";
                },
            },
        },
    };

    var chart = new ApexCharts(document.querySelector("#column4"), options);
    chart.render();
});
//  **------column_chart 10**
Apex = {
    chart: {
        toolbar: {
            show: false,
        },
    },
    tooltip: {
        shared: false,
    },
    legend: {
        show: false,
    },
};

var colors = [
    getLocalStorageItem("color-primary", "#056464"),
    getLocalStorageItem("color-secondary", "#74788D"),
    "#06b38b",
    "#eb565a",
    "#fbc05b",
    "#29b0f2",
    "#e8eaf2",
];

/**
 * Randomize array element order in-place.
 * Using Durstenfeld shuffle algorithm.
 */
function shuffleArray(array) {
    for (var i = array.length - 1; i > 0; i--) {
        var j = Math.floor(Math.random() * (i + 1));
        var temp = array[i];
        array[i] = array[j];
        array[j] = temp;
    }
    return array;
}

var arrayData = [
    {
        y: 400,
        quarters: [
            {
                x: "Q1",
                y: 120,
            },
            {
                x: "Q2",
                y: 90,
            },
            {
                x: "Q3",
                y: 100,
            },
            {
                x: "Q4",
                y: 90,
            },
        ],
    },
    {
        y: 430,
        quarters: [
            {
                x: "Q1",
                y: 120,
            },
            {
                x: "Q2",
                y: 110,
            },
            {
                x: "Q3",
                y: 90,
            },
            {
                x: "Q4",
                y: 110,
            },
        ],
    },
    {
        y: 448,
        quarters: [
            {
                x: "Q1",
                y: 70,
            },
            {
                x: "Q2",
                y: 100,
            },
            {
                x: "Q3",
                y: 140,
            },
            {
                x: "Q4",
                y: 138,
            },
        ],
    },
    {
        y: 470,
        quarters: [
            {
                x: "Q1",
                y: 150,
            },
            {
                x: "Q2",
                y: 60,
            },
            {
                x: "Q3",
                y: 190,
            },
            {
                x: "Q4",
                y: 70,
            },
        ],
    },
    {
        y: 540,
        quarters: [
            {
                x: "Q1",
                y: 120,
            },
            {
                x: "Q2",
                y: 120,
            },
            {
                x: "Q3",
                y: 130,
            },
            {
                x: "Q4",
                y: 170,
            },
        ],
    },
    {
        y: 580,
        quarters: [
            {
                x: "Q1",
                y: 170,
            },
            {
                x: "Q2",
                y: 130,
            },
            {
                x: "Q3",
                y: 120,
            },
            {
                x: "Q4",
                y: 160,
            },
        ],
    },
];

function makeData() {
    var dataSet = shuffleArray(arrayData);

    var dataYearSeries = [
        {
            x: "2011",
            y: dataSet[0].y,
            color: colors[0],
            quarters: dataSet[0].quarters,
        },
        {
            x: "2012",
            y: dataSet[1].y,
            color: colors[1],
            quarters: dataSet[1].quarters,
        },
        {
            x: "2013",
            y: dataSet[2].y,
            color: colors[2],
            quarters: dataSet[2].quarters,
        },
        {
            x: "2014",
            y: dataSet[3].y,
            color: colors[3],
            quarters: dataSet[3].quarters,
        },
        {
            x: "2015",
            y: dataSet[4].y,
            color: colors[4],
            quarters: dataSet[4].quarters,
        },
        {
            x: "2016",
            y: dataSet[5].y,
            color: colors[5],
            quarters: dataSet[5].quarters,
        },
    ];

    return dataYearSeries;
}

function updateQuarterChart(sourceChart, destChartIDToUpdate) {
    var series = [];
    var seriesIndex = 0;
    var colors = [];

    if (sourceChart.w.globals.selectedDataPoints[0]) {
        var selectedPoints = sourceChart.w.globals.selectedDataPoints;
        for (var i = 0; i < selectedPoints[seriesIndex].length; i++) {
            var selectedIndex = selectedPoints[seriesIndex][i];
            var yearSeries = sourceChart.w.config.series[seriesIndex];
            series.push({
                name: yearSeries.data[selectedIndex].x,
                data: yearSeries.data[selectedIndex].quarters,
            });
            colors.push(yearSeries.data[selectedIndex].color);
        }

        if (series.length === 0)
            series = [
                {
                    data: [],
                },
            ];

        return ApexCharts.exec(destChartIDToUpdate, "updateOptions", {
            series: series,
            colors: colors,
            fill: {
                colors: colors,
            },
        });
    }
}

chart.addEventListener("dataPointSelection", function (e, chart, opts) {
    var quarterChartEl = document.querySelector("#chart-quarter");
    var yearChartEl = document.querySelector("#chart-year");

    if (opts.selectedDataPoints[0].length === 1) {
        if (quarterChartEl.classList.contains("active")) {
            updateQuarterChart(chart, "barQuarter");
        } else {
            yearChartEl.classList.add("chart-quarter-activated");
            quarterChartEl.classList.add("active");
            updateQuarterChart(chart, "barQuarter");
        }
    } else {
        updateQuarterChart(chart, "barQuarter");
    }

    if (opts.selectedDataPoints[0].length === 0) {
        yearChartEl.classList.remove("chart-quarter-activated");
        quarterChartEl.classList.remove("active");
    }
});

chart.addEventListener("updated", function (chart) {
    updateQuarterChart(chart, "barQuarter");
});

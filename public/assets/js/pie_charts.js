// **------ pie_charts 1**
document.addEventListener("DOMContentLoaded", function () {
    setTimeout(function () {
        const rawNames = window.topClientData?.names || [];
        const rawTotals = window.topClientData?.totals || [];

        const names = [];
        const totals = [];

        // Filter valid entries
        for (let i = 0; i < rawNames.length; i++) {
            const name = rawNames[i];
            const total = rawTotals[i];

            if (name && total !== null && !isNaN(total)) {
                names.push(name);
                totals.push(total);
            }
        }

        if (!names.length || !totals.length) {
            console.warn("No data available for Top Clients chart.");
            return;
        }
        const options = {
            series: totals,
            chart: {
                height: 340,
                type: "pie",
            },
            labels: names,
            colors: [
                getLocalStorageItem("color-primary", "#1d61d8"), // bold brand blue
                getLocalStorageItem("color-secondary", "#0fbc66"), // vivid brand green
                "#f39c12", // warm amber / orange for contrast
                "#92f4c3ff", // red accent (good for negative or warning data)
                "#6b9aecff", // purple accent for neutral/other data
            ],

            legend: {
                position: "bottom",
                show: true,
            },
            responsive: [
                {
                    breakpoint: 1366,
                    options: {
                        chart: {
                            height: 250,
                        },
                        legend: {
                            show: true,
                        },
                    },
                },
            ],
        };

        const pieContainer = document.querySelector("#pie1");
        if (pieContainer) {
            // If chart is already rendered, destroy it
            if (pieContainer._chartInstance) {
                pieContainer._chartInstance.destroy();
            }

            const chart = new ApexCharts(pieContainer, options);
            chart.render();

            // Save reference for future cleanup
            pieContainer._chartInstance = chart;
        }
    }, 200); // Allow Blade to inject window.topClientData
});

//  **------pie_charts 2**

document.addEventListener("DOMContentLoaded", function () {
    if (typeof window.topServicesRevenueData !== "undefined") {
        const names = window.topServicesRevenueData.names || [];
        const totals = window.topServicesRevenueData.totals || [];

        const options = {
            series: totals,
            chart: {
                type: "donut",
                height: 340,
            },
            labels: names,
            legend: {
                show: true,
                position: "bottom",
                horizontalAlign: "center",
                fontSize: "14px",
                labels: {
                    colors: "#333",
                },
            },

            colors: [
                getLocalStorageItem("color-primary", "#1d61d8"), // bold brand blue
                getLocalStorageItem("color-secondary", "#0fbc66"), // vivid brand green
                "#f39c12", // warm amber / orange for contrast
                "#92f4c3ff", // red accent (good for negative or warning data)
                "#6b9aecff", // purple accent for neutral/other data
            ],
            tooltip: {
                y: {
                    formatter: function (val) {
                        return new Intl.NumberFormat().format(val);
                    },
                },
            },
            responsive: [
                {
                    breakpoint: 1366,
                    options: {
                        chart: {
                            height: 250,
                        },
                        legend: {
                            show: true,
                            position: "bottom",
                        },
                    },
                },
            ],
        };

        const chart = new ApexCharts(document.querySelector("#pie2"), options);
        chart.render();
    }
});

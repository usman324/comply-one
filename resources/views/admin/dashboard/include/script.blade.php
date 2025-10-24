<script>
    $(function() {
        saleGraph('date');
    })
    cardColor = config.colors.cardColor;
    headingColor = config.colors.headingColor;
    labelColor = config.colors.textMuted;
    legendColor = config.colors.bodyColor;
    borderColor = config.colors.borderColor;
    const chartColors = {
        column: {
            series1: '#826af9',
            series2: '#d2b0ff',
            bg: '#f8d3ff'
        },
        donut: {
            series1: '#fee802',
            series2: '#3fd0bd',
            series3: '#826bf8',
            series4: '#2b9bf4'
        },
        area: {
            series1: '#29dac7',
            series2: '#60f2ca',
            series3: '#a5f8cd'
        }
    };

    function saleGraph(graph_type) {
        $('#barChart').html('');
        $.ajax({
            url: '{{ url('dashboard-metas') }}',
            data: {
                lead_graph: 1,
            },
            success: function(r) {
                // Check if r.datasets and r.labels are defined and not empty
                if (!r.datasets || r.datasets.length === 0 || !r.labels || r.labels.length === 0) {
                    console.error('Invalid data received:', r);
                    return;
                }
                let options = {
                    chart: {
                        height: 400,
                        type: 'bar',
                        stacked: true,
                        parentHeightOffset: 0,
                        toolbar: {
                            show: false
                        }
                    },
                    plotOptions: {
                        bar: {
                            columnWidth: '15%',
                            colors: {
                                backgroundBarRadius: 10
                            }
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                    legend: {
                        show: true,
                        position: 'top',
                        horizontalAlign: 'start',
                        labels: {
                            colors: legendColor, // Ensure legendColor is defined
                            useSeriesColors: false
                        }
                    },
                    colors: r.colors, // Adjust as per your color requirements
                    stroke: {
                        show: true,
                        colors: ['transparent']
                    },
                    grid: {
                        borderColor: borderColor,
                        xaxis: {
                            lines: {
                                show: true
                            }
                        }
                    },
                    series: r.datasets,
                    xaxis: {
                        categories: r.labels,
                        axisBorder: {
                            show: false
                        },
                        axisTicks: {
                            show: false
                        },
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '13px'
                            }
                        }
                    },
                    yaxis: {
                        labels: {
                            style: {
                                colors: labelColor,
                                fontSize: '13px'
                            }
                        }
                    },
                    fill: {
                        opacity: 1
                    }
                };

                console.log('Options:', options); // Log options for debugging

                // Render the chart
                var chart = new ApexCharts(document.querySelector("#barChart"), options);
                chart.render();
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    }



    function reloadGraph(event, type) {
        saleGraph(type);
    }
</script>

<div class="col-xl-12 col-md-12">
    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
        <div class="card-header">
            <h2>@lang('app.course_in_year')</h2>
        </div>
        <div class="card-body p-5" style="height: 450px;">
            <canvas id="barChart" class="chartjs"></canvas>
        </div>
    </div>
</div>
<script>
    var cUser = document.getElementById("barChart");
    if (cUser !== null) {
        var myUChart = new Chart(cUser, {
            type: "bar",
            data: {
                labels: [
                    "{{ __('month.jan') }}",
                    "{{ __('month.feb') }}",
                    "{{ __('month.mar') }}",
                    "{{ __('month.apr') }}",
                    "{{ __('month.may') }}",
                    "{{ __('month.jun') }}",
                    "{{ __('month.jul') }}",
                    "{{ __('month.aug') }}",
                    "{{ __('month.sep') }}",
                    "{{ __('month.oct') }}",
                    "{{ __('month.nov') }}",
                    "{{ __('month.dec') }}",
                ],
                datasets: [
                    {
                        label: "{{ __('app.onl_course') }}",
                        data: [{{ implode(',',$chart['online']) }}],
                        backgroundColor: "#8b1409",
                    },
                    {
                        label: "{{ __('app.off_course') }}",
                        data: [{{ implode(',',$chart['offline']) }}],
                        backgroundColor: "#FEF200"
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: true
                },
                scales: {
                    xAxes: [
                        {
                            gridLines: {
                                drawBorder: true,
                                display: true,
                            },
                            ticks: {
                                fontColor: "#686f7a",
                                fontFamily: "Roboto, sans-serif",
                                display: true, // hide main x-axis line
                                beginAtZero: true,
                                // callback: function(tick, index, array) {
                                //     return index % 2 ? "" : tick;
                                // }
                            },
                            barPercentage: 1,
                            categoryPercentage: 0.5
                        }
                    ],
                    yAxes: [
                        {
                            gridLines: {
                                drawBorder: true,
                                display: true,
                                color: "#efefef",
                                zeroLineColor: "#efefef"
                            },
                            ticks: {
                                fontColor: "#686f7a",
                                fontFamily: "Roboto, sans-serif",
                                display: true,
                                beginAtZero: true,
                            },
                        }
                    ]
                },

                tooltips: {
                    mode: "index",
                    titleFontColor: "#333",
                    bodyFontColor: "#686f7a",
                    titleFontSize: 12,
                    bodyFontSize: 14,
                    backgroundColor: "rgba(256,256,256,0.95)",
                    displayColors: true,
                    xPadding: 10,
                    yPadding: 7,
                    borderColor: "rgba(220, 220, 220, 0.9)",
                    borderWidth: 2,
                    caretSize: 6,
                    caretPadding: 5
                }
            }
        });
    }
</script>

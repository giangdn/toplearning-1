<div class="col-xl-6 col-md-12">
    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
        <div class="card-header">
            <h2>@lang('app.online_in_year')</h2>
        </div>
        <div class="card-body p-5">
            <canvas id="total_onl" class="chartjs"></canvas>
        </div>
    </div>
</div>
<div class="col-xl-6 col-md-12">
    <div class="card card-default analysis_card p-0 mt-2" data-scroll-height="400">
        <div class="card-header">
            <h2>@lang('app.offline_in_year')</h2>
        </div>
        <div class="card-body p-5">
            <canvas id="total_off" class="chartjs"></canvas>
        </div>
    </div>
</div>

<script>
    var onl = document.getElementById("total_onl").getContext('2d');
    var off = document.getElementById("total_off").getContext('2d');
    if (onl !== null) {
        var dataOnl = {
            labels: ["{{ __('app.uncomplete') }}", "{{ __('app.completed') }}"],
            datasets: [{
                backgroundColor: [
                    "#FEF200",
                    "#8b1409",
                ],
                fill: false,
                data: [{{ implode(',',$chart['onl_year']) }}],
            }]
        };
        console.log(dataOnl);
        var optionsOnl = {
            legend: {
                display: true,
                position: 'bottom',

            },
            // tooltips: false,
            showTooltips: true,
            elements: {
                arc: {
                    backgroundColor: "#8b1409",
                    hoverBackgroundColor: '#8b1409'
                },
            },
        };
        var chartOnl = new Chart(onl, {
            type: 'pie',
            data: dataOnl,
            options: optionsOnl
        })
    }
    if (off !== null) {
        var dataOff = {
            labels: ["{{ __('app.uncomplete') }}", "{{ __('app.completed') }}"],
            datasets: [{
                backgroundColor: [
                    "#FEF200",
                    "#8b1409",
                ],
                fill: false,
                data: [{{ implode(',',$chart['off_year']) }}],
            }]
        };

        var optionsOff = {
            legend: {
                display: true,
                position: 'bottom',

            },
            // tooltips: true,
            showTooltips: true,
            elements: {
                arc: {
                    backgroundColor: "#8b1409",
                    hoverBackgroundColor: '#8b1409'
                },
            }
        };
        var chart = new Chart(off, {
            type: 'pie',
            data: dataOff,
            options: optionsOff
        })
    }
</script>

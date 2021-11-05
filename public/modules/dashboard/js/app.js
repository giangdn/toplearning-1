$(function () {
    $(".knob").knob();
    var lineChartData = {
        labels: [t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12],
        datasets: [
            {
                label: offline_course,
                borderColor: "#FEF200",
                fill: false,
                data: data_course_offline
            },
            {
                label: online_course,
                borderColor: "#1b4486",
                fill: false,
                data: data_course_online
            }
        ]
    };
    var lineChartOptions = {
        responsive: true,
        legend: {
            position: 'bottom'
        },
        title: {
            display: false,
            text: 'Thống kê khóa học'
        }
    };
    var canvas = document.getElementById("lineChart");
    var lineChartCanvas = canvas.getContext('2d');
    var lineChart = new Chart(lineChartCanvas, {
        type: 'line',
        data: lineChartData,
        options: lineChartOptions
    });
    /**************************** bar char quiz *******************************/

    var barChartData = {
        labels: [t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12],
        datasets: [{
            label: 'Tổ chức',
            backgroundColor: "#1b4486",
            data: data_quiz,
        }]
    };
    var barCanvasQuiz = document.getElementById("barChartQuiz");
    var barChartCtx = barCanvasQuiz.getContext('2d');
    var barChartOptions = {
        responsive: true,
        legend: {
            display: false
        },
        scales: {
            yAxes: [{
                display: true,
                ticks: {
                    beginAtZero: true,
                    // stepSize: 1,
                    min: 0
                }
            }]
        }
    };
    var barChartQuiz = new Chart(barChartCtx, {
        type: 'bar',
        data: barChartData,
        options: barChartOptions
    });
    /**************************** stacked char quiz *******************************/
    var stackedChartData = {
        labels: [t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12],
        datasets: [{
            label: completed,
            backgroundColor: "#1b4486",
            data: data_course_result_finish,
        },
        {
            label: incomplete,
            backgroundColor: "#FEF200",
            data: data_course_result_fail,
        },

        ]
    };
    var stackedChartCanvasQuiz = document.getElementById("stackedChartQuiz");
    var stackedChartCtx = stackedChartCanvasQuiz.getContext('2d');
    var stackedChartOptions = {
        responsive: true,
        scales: {
            xAxes: [{
                stacked: true,
                gridLines: {
                    display: false,
                }
            }],
            yAxes: [{
                stacked: true,
                type: 'linear',
            }]
        }
    };
    var stackedChartQuiz = new Chart(stackedChartCtx, {
        type: 'bar',
        data: stackedChartData,
        options: stackedChartOptions
    });
    /*************************** pie char user online ************************/
    var pieChartCanvasUser = document.getElementById("pieChart");
    var pieChartCtx = pieChartCanvasUser.getContext('2d');
    var pieDataUser = {
        labels: ['Desktop', 'Mobile', 'Tablet'],
        datasets: [{
            labels: {
                render: 'percentage',
                fontColor: function (data) {
                    var rgb = hexToRgb(data.dataset.backgroundColor[data.index]);
                    var threshold = 140;
                    var luminance = 0.299 * rgb.r + 0.587 * rgb.g + 0.114 * rgb.b;
                    return luminance > threshold ? 'black' : 'yellow';
                },
                precision: 2
            },
            data: data_device_category,
            backgroundColor: [
                '#3c8dbc',
                'rgba(0,172,95,0.94)',
                'rgba(249,88,25,0.89)',
            ],
        }]
    };
    var pieOptions = {
        responsive: true,
        legend: {
            display: false
        },
    };
    var pieChartUser = new Chart(pieChartCtx, {
        type: 'doughnut',
        data: pieDataUser,
        options: pieOptions
    });
    /****************  chart user***********************/
    var lineChartDataUser = {
        labels: [t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12],
        datasets: [
            {
                borderColor: "#1b4486",
                fill: false,
                data: data_visit_statistic,
                borderDash: [5, 5]
            }
        ]
    };
    var lineChartOptionsUser = {
        responsive: true,
        legend: {
            position: 'bottom',
            display: false
        },
        responsive: true,
        title: {
            display: false,
            text: 'Thống kê khóa học'
        },
        maintainAspectRatio: false,
        spanGraps: false,
        elements: {
            line: {
                tension: 0.000001
            }
        },

    };
    var lineChartCanvasUser = document.getElementById("lineChartUser").getContext('2d');
    var lineChartUser = new Chart(lineChartCanvasUser, {
        type: 'line',
        data: lineChartDataUser,
        options: lineChartOptionsUser
    });

    /****************  Thống kê truy cập khóa học online***********************/
    var lineChartDataOnline = {
        labels: [t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12],
        datasets: [
            {
                backgroundColor: "#1b4486",
                fill: false,
                data: data_visit_statistic_online,
            }
        ]
    };
    var lineChartOptionsOnline = {
        responsive: true,
        legend: {
            position: 'bottom',
            display: false
        },
        responsive: true,
        title: {
            display: false,
            text: 'Thống kê truy cập khóa học online'
        },
    };
    var lineChartCanvasOnline = document.getElementById("statistic_access_online").getContext('2d');
    var lineChartUserOnline = new Chart(lineChartCanvasOnline, {
        type: 'bar',
        data: lineChartDataOnline,
        options: lineChartOptionsOnline
    });

    /****************  Thống kê truy cập tin tức, video, hình ảnh***********************/
    var lineChartDataNews = {
        labels: [t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12],
        datasets: [
            {
                label: 'Số lượng truy cập',
                backgroundColor: "#1b4486",
                fill: false,
                data: data_visit_statistic_news,
            },
        ]
    };
    var lineChartOptionsNews = {
        responsive: true,
        legend: {
            position: 'bottom',
            display: false
        },
        responsive: true,
        title: {
            display: false,
            text: 'Thống kê truy cập tin tức, video, hình ảnh'
        },
    };
    var lineChartCanvasNews = document.getElementById("statistic_access_news").getContext('2d');
    var lineChartUserNews = new Chart(lineChartCanvasNews, {
        type: 'bar',
        data: lineChartDataNews,
        options: lineChartOptionsNews
    });

    /****************  Thống kê truy cập tài liệu, ebook, sách giấy, audio, video ***********************/
    var lineChartDataLibraries = {
        labels: [t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12],
        datasets: [
            {
                label: 'Số lượng truy cập',
                backgroundColor: "#1b4486",
                fill: false,
                data: data_visit_statistic_libraries,
            },
        ]
    };
    var lineChartOptionsLibraries = {
        responsive: true,
        legend: {
            position: 'bottom',
            display: false
        },
        responsive: true,
        title: {
            display: false,
            text: 'Thống kê truy cập, số lượng tài liệu, ebook, sách giấy, audio, video'
        },
    };
    var lineChartCanvasLibraries = document.getElementById("statistic_access_libraries").getContext('2d');
    var lineChartLibraries = new Chart(lineChartCanvasLibraries, {
        type: 'bar',
        data: lineChartDataLibraries,
        options: lineChartOptionsLibraries
    });

    /****************  Thống kê truy cập diễn đàn ***********************/
    var lineChartDataForums = {
        labels: [t1, t2, t3, t4, t5, t6, t7, t8, t9, t10, t11, t12],
        datasets: [
            {
                label: 'Số lượng truy cập',
                backgroundColor: "#1b4486",
                fill: false,
                data: data_visit_statistic_forums,
            },
        ]
    };
    var lineChartOptionsForums = {
        responsive: true,
        legend: {
            position: 'bottom',
            display: false
        },
        responsive: true,
        title: {
            display: false,
            text: 'Thống kê truy cập, số lượng tài liệu, ebook, sách giấy, audio, video'
        },
    };
    var lineChartCanvasForums = document.getElementById("statistic_access_forums").getContext('2d');
    var lineChartForums = new Chart(lineChartCanvasForums, {
        type: 'bar',
        data: lineChartDataForums,
        options: lineChartOptionsForums
    });
});

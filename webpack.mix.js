const mix = require('laravel-mix');
require('laravel-mix-merge-manifest');
mix.mergeManifest();

mix.styles([
    'resources/styles/vendor/unicons-2.0.1/css/unicons.css',
    'resources/styles/css/vertical-responsive-menu.min.css',
    'resources/styles/css/style.css',
    // 'resources/styles/css/responsive.css',
    'resources/styles/css/night-mode.css',
    'resources/styles/css/custom.css',
    'resources/styles/css/instructor-dashboard.css',
    'resources/styles/css/instructor-responsive.css',
    'resources/styles/css/jquery-steps.css',
    'resources/styles/css/media.css',
    'resources/styles/css/pnotify.css',
    // 'resources/styles/css/student_dashboard.css',
    // 'resources/styles/css/student_responsive.css',
    'resources/styles/vendor/fontawesome-free/css/all.min.css',
    'resources/styles/vendor/OwlCarousel/assets/owl.carousel.min.css',
    'resources/styles/vendor/OwlCarousel/assets/owl.theme.default.min.css',
    'resources/styles/vendor/bootstrap/css/bootstrap.min.css',
    'resources/styles/vendor/semantic/semantic.min.css',
    'resources/styles/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
    'resources/styles/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css',
    'resources/styles/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css',
    'resources/styles/vendor/bootstrap-table/bootstrap-table.min.css',
    'resources/styles/vendor/bxslider/jquery.bxslider.css',
    'resources/styles/vendor/select2/select2.min.css',
    'resources/styles/vendor/notiflix/notiflix-2.3.2.min.css',
    'resources/styles/css/frontend/forum.css',
    'resources/styles/vendor/fullcalendar/main.css',
], 'public/css/theme.css');

mix.combine([
    //'resources/styles/js/jquery-3.3.1.min.js',
    'resources/styles/js/jquery-3.5.1.min.js',
    'resources/styles/vendor/jquery-ui/jquery-ui.min.js',
    'resources/styles/vendor/bootstrap-timepicker/js/bootstrap-timepicker.js',
    'resources/styles/js/moment.min.js',
    'resources/styles/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js',
    'resources/styles/vendor/bootstrap-table/bootstrap-table.min.js',
    'resources/styles/vendor/bootstrap-table/bootstrap-table-vi-VN.js',
    'resources/styles/vendor/bxslider/jquery.bxslider.min.js',
    'resources/styles/vendor/sweetalert2/sweetalert2.js',
    'resources/styles/vendor/select2/select2.min.js',
    'resources/styles/vendor/jscroll/jquery.jscroll.min.js',
    'resources/styles/module/quiz/js/clock.js',
    'resources/styles/module/quiz/js/tether.min.js',
    'resources/styles/js/lazyload.min.js',
    'resources/styles/js/LoadBootstrapTable.js',
    'resources/styles/js/load-ajax.js',
    'resources/styles/js/form-ajax.js',
    'resources/styles/js/frontend/form-ajax.js',
    'resources/styles/vendor/bootstrap-datetimepicker/js/load-datetimepicker.js',
    'resources/styles/vendor/charts/Chart.min.js',
    'resources/styles/vendor/fullcalendar/main.js',
    'resources/styles/js/load-select2.js',
    'resources/styles/js/custom.js',
], 'public/js/theme.js');

mix.combine([
    'resources/styles/vendor/notiflix/notiflix-2.3.2.min.js',
], 'public/js/jquery-theme.js');

mix.combine([
    'resources/styles/js/vertical-responsive-menu.min.js',
    'resources/styles/vendor/bootstrap/js/bootstrap.bundle.min.js',
    'resources/styles/vendor/OwlCarousel/owl.carousel.min.js',
    'resources/styles/vendor/semantic/semantic.min.js',
    'resources/styles/js/night-mode.js',
], 'public/js/theme2.js');

mix.styles([
    'resources/styles/vendor/unicons-2.0.1/css/unicons.css',
    'resources/styles/css/vertical-responsive-menu.min.css',
    'resources/styles/css/style.css',
    'resources/styles/css/responsive.css',
    'resources/styles/css/night-mode.css',
    'resources/styles/css/custom.css',
    'resources/styles/vendor/fontawesome-free/css/all.min.css',
    'resources/styles/vendor/OwlCarousel/assets/owl.carousel.min.css',
    'resources/styles/vendor/OwlCarousel/assets/owl.theme.default.min.css',
    'resources/styles/vendor/bootstrap/css/bootstrap.min.css',
    'resources/styles/vendor/semantic/semantic.min.css',
    'resources/styles/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css',
    'resources/styles/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css',
    'resources/styles/vendor/bootstrap-table/bootstrap-table.min.css',
    'resources/styles/vendor/select2/select2.min.css',
    'resources/styles/vendor/bootstrap-datepicker/css/bootstrap-datepicker.min.css',
    'resources/styles/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css',
    'resources/styles/vendor/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
    'resources/styles/css/backend/category/css/category.css',
], 'public/css/backend.css');

mix.combine([
    //'resources/styles/js/jquery-3.3.1.min.js',
    'resources/styles/js/jquery-3.5.1.min.js',
    'resources/styles/vendor/jquery-ui/jquery-ui.min.js',
    'resources/styles/vendor/bootstrap/js/popper.min.js',
    'resources/styles/vendor/bootstrap/js/bootstrap.min.js',
    'resources/styles/vendor/bootstrap-timepicker/js/bootstrap-timepicker.js',
    'resources/styles/vendor/bxslider/jquery.bxslider.min.js',
    'resources/styles/vendor/sweetalert2/sweetalert2.js',
    'resources/styles/vendor/select2/select2.min.js',
    'resources/styles/vendor/jscroll/jquery.jscroll.min.js',
    'resources/styles/module/quiz/js/clock.js',
    'resources/styles/module/quiz/js/tether.min.js',
    'resources/styles/vendor/bootstrap-table/bootstrap-table.min.js',
    'resources/styles/vendor/bootstrap-table/bootstrap-table-vi-VN.js',
    'resources/styles/vendor/jquery-validate/jquery.validate.min.js',
    'resources/styles/js/moment.min.js',
    'resources/styles/vendor/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js',
    'resources/styles/js/BootstrapTable.js',
    'resources/styles/js/LoadBootstrapTable.js',
    //'resources/styles/vendor/bootstrap-datetimepicker/js/load-datetimepicker.js',
    'resources/styles/js/load-ajax.js',
    'resources/styles/js/form-ajax.js',
    'resources/styles/js/customs-frontend.js',
    'resources/styles/js/config.js',
], 'public/js/backend.js').version();

mix.combine([
    'resources/styles/js/vertical-responsive-menu.min.js',
    'resources/styles/vendor/bootstrap/js/bootstrap.bundle.min.js',
    'resources/styles/vendor/OwlCarousel/owl.carousel.min.js',
    'resources/styles/vendor/semantic/semantic.min.js',
    'resources/styles/js/custom-backend.js',
    'resources/styles/js/night-mode.js',
    'resources/styles/vendor/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
    'resources/styles/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.vi.min.js',
    'resources/styles/vendor/bootstrap-datepicker/js/load-datepicker.js',
    'resources/styles/js/load-select2-backend.js',
    'resources/styles/js/common.js',
    'resources/styles/js/inputmask.js',
], 'public/js/backend2.js');

mix.styles([
    'resources/styles/vendor/bootstrap/css/bootstrap.min.css',
    'resources/styles/vendor/fontawesom/css/font-awesome.min.css',
    'resources/styles/file-manager/css/jquery-ui.min.css',
    'resources/styles/file-manager/css/cropper.min.css',
    'resources/styles/file-manager/css/lfm.css',
    'resources/styles/file-manager/css/mfb.css',
    'resources/styles/file-manager/css/dropzone.css',
], 'public/css/lfm.css');

mix.combine([
    //'resources/styles/js/jquery-3.3.1.min.js',
    'resources/styles/js/jquery-3.5.1.min.js',
    'resources/styles/file-manager/js/popper.min.js',
    'resources/styles/vendor/bootstrap/js/bootstrap.min.js',
    'resources/styles/file-manager/js/bootbox.min.js',
    'resources/styles/file-manager/js/jquery-ui.min.js',
    'resources/styles/file-manager/js/cropper.min.js',
    'resources/styles/file-manager/js/jquery.form.min.js',
    'resources/styles/file-manager/js/dropzone.js',
    'resources/styles/file-manager/js/script.js',
], 'public/js/lfm.js');

/**login**/
mix.combine([
    'resources/styles/vendor/bootstrap/js/bootstrap.bundle.min.js',
    'resources/styles/vendor/OwlCarousel/owl.carousel.min.js',
    'resources/styles/vendor/semantic/semantic.min.js',
    'resources/styles/js/night-mode.js',
], 'public/js/theme3.js');

mix.js('resources/js/app.js', 'public/js');
/*****/

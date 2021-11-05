$(document).ready(function () {

    $('.hover-backend-menu').closest('.menu--item__has_sub_menu').addClass('menu--subitens__opened');

    let form = $('.config-menu .list-group-item').last().data('form');
    if (form)
        load_config_form(form);
    $('.config-menu .list-group-item').last().addClass('active');

    $('.config-menu').on('click', '.list-group-item', function () {
        load_config_form($(this).data('form'));
        $(this).addClass('active');
    });

    function load_config_form(form) {
        $.ajax({
            type: 'GET',
            url: '/admin-cp/config/get-form',
            dataType: 'html',
            data: {
                form: form
            }
        }).done(function(response) {

            $('#form-config').html(response);

            return false;
        }).fail(function(response) {

            show_message('DATA ERROR', 'error');
            return false;
        });
    }
});


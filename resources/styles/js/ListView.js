class ListView {

    constructor(e) {
        this.url = e.url;
        this.template = document.getElementById(e.template).innerHTML;
        this.render = $(e.render);
        this.init();
    }

    init() {
        let template = this.template;
        let url = this.url;
        let render = this.render;
        render.html('Đang tải dữ liệu');

        $.ajax({
            type: 'GET',
            url: url,
            dataType: 'json',
            cache: false,
            contentType: false,
            processData: false
        }).done(function(data) {

            if (data.total > 0) {
                let rows = data.rows;
                render.html('');
                $.each(rows, function (i, item) {
                    let html = replace_template(template, item);
                    render.append(html);
                });
            }
            else {
                render.html('Không có dữ liệu');
            }

            return false;

        }).fail(function(data) {
            show_message('Lỗi dữ liệu', 'error');
            return false;
        });
    }
}
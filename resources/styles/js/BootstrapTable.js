class BootstrapTable {

    constructor(e) {
        this.url = e.url;
        this.table = (e.table) ? $(e.table) : $('table.bootstrap-table');
        this.field_id = (e.field_id) ? e.field_id : 'id';
        this.form_search = (e.form_search) ? e.form_search : "#form-search";
        this.sort_name = (e.sort_name) ? e.sort_name : 'id';
        this.sort_order = (e.sort_order) ? e.sort_order : 'desc';
        this.page_size = (e.page_size) ? e.page_size: 20;
        this.search = (e.search) ? e.search : false;
        this.data =(e.data) ? e.data: false;
        this.init();
    }
    init() {
        let table = this.table;
        let form_search = this.form_search;
        let data_url = this.url;
        let field_id = this.field_id;
        let data = this.data;
        table.bootstrapTable({
            url: data_url,
            locale: 'vi-VN',
            sidePagination: 'server',
            pagination: true,
            sortName: this.sort_name,
            sortOrder: this.sort_order,
            toggle: 'table',
            search: this.search,
            pageSize: this.page_size,
            idField: this.field_id,
            queryParams: function (params) {
                let field_search = $(form_search).serializeArray();
                $.each(field_search, function (i, item) {
                    params[item.name] = item.value;
                });
                $.each(data, function (i,e) {
                    params[e.name] = e.value;
                })
                return params;
            }
        });
    }
    submit(){
        this.table.bootstrapTable('refresh');
    }
    refresh() {
        this.table.bootstrapTable('refresh');
    }
}

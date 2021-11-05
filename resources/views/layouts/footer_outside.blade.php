<div class="footer_outside row">
    <div class="col-12 pl-3">
        <div class="row">
            <div class="col-md-8 col-12 pt-3 pr-0 get_infomation_company">
                <h6 class="name_info_company">{{ $get_infomation_company ? $get_infomation_company->title : '' }}</h6>
            </div>
            @php
                $user_online = \App\User::countUsersOnline();
                $count_user_login = \App\LoginHistory::where(\DB::raw('year(created_at)'), '=', date('Y'))->count();
            @endphp
            <div class="col-md-4 col-12">
                <div class="row">
                    <div class="col-12 user_count pb-2">
                        <h6 class="m-0">SỐ LƯỢNG ĐANG TRUY CẬP: <span>{{$users_online}}</span></h6>
                    </div>
                    <div class="col-12 user_count">
                        <h6 class="m-0">SỐ LƯỢNG TRUY CẬP: <span>{{$count_user_login}}</span></h6>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>

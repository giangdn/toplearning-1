<!DOCTYPE html>
<html  dir="ltr" lang="vi" xml:lang="vi">
<head>
    <title>{{ $title }}</title>
    <link rel="shortcut icon" href="{{ image_file(\App\Config::getFavicon()) }}" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" type="text/css" href="{{ asset('modules/online/scorm/css/yui_combo.css') }}" />
    <script id="firstthemesheet" type="text/css">/** Required in order to fix style inclusion problems in IE with YUI **/</script>
    <link rel="stylesheet" type="text/css" href="{{ asset('modules/online/scorm/css/all.css') }}" />

    <script type="text/javascript">
        //<![CDATA[
        var M = {}; M.yui = {};
        M.pageloadstarttime = new Date();
        M.cfg = {"wwwroot":"https:\/\/hsg-mysql.toplearning.vn\/moodle","sesskey":"AtKAMfiooM","themerev":"1604893707","slasharguments":1,"theme":"boost","iconsystemmodule":"core\/icon_system_fontawesome","jsrev":"1604893707","admin":"admin","svgicons":true,"usertimezone":"Asia\/Ho_Chi_Minh","contextid":30};var yui1ConfigFn = function(me) {if(/-skin|reset|fonts|grids|base/.test(me.name)){me.type='css';me.path=me.path.replace(/\.js/,'.css');me.path=me.path.replace(/\/yui2-skin/,'/assets/skins/sam/yui2-skin')}};
        var yui2ConfigFn = function(me) {var parts=me.name.replace(/^moodle-/,'').split('-'),component=parts.shift(),module=parts[0],min='-min';if(/-(skin|core)$/.test(me.name)){parts.pop();me.type='css';min=''}
            if(module){var filename=parts.join('-');me.path=component+'/'+module+'/'+filename+min+'.'+me.type}else{me.path=component+'/'+component+'.'+me.type}};
        YUI_config = {"debug":false,"base":"https:\/\/hsg-mysql.toplearning.vn\/moodle\/lib\/yuilib\/3.17.2\/","comboBase":"https:\/\/hsg-mysql.toplearning.vn\/moodle\/theme\/yui_combo.php?","combine":true,"filter":null,"insertBefore":"firstthemesheet","groups":{"yui2":{"base":"https:\/\/hsg-mysql.toplearning.vn\/moodle\/lib\/yuilib\/2in3\/2.9.0\/build\/","comboBase":"https:\/\/hsg-mysql.toplearning.vn\/moodle\/theme\/yui_combo.php?","combine":true,"ext":false,"root":"2in3\/2.9.0\/build\/","patterns":{"yui2-":{"group":"yui2","configFn":yui1ConfigFn}}},"moodle":{"name":"moodle","base":"https:\/\/hsg-mysql.toplearning.vn\/moodle\/theme\/yui_combo.php?m\/1604893707\/","combine":true,"comboBase":"https:\/\/hsg-mysql.toplearning.vn\/moodle\/theme\/yui_combo.php?","ext":false,"root":"m\/1604893707\/","patterns":{"moodle-":{"group":"moodle","configFn":yui2ConfigFn}},"filter":null,"modules":{"moodle-core-dragdrop":{"requires":["base","node","io","dom","dd","event-key","event-focus","moodle-core-notification"]},"moodle-core-handlebars":{"condition":{"trigger":"handlebars","when":"after"}},"moodle-core-maintenancemodetimer":{"requires":["base","node"]},"moodle-core-lockscroll":{"requires":["plugin","base-build"]},"moodle-core-actionmenu":{"requires":["base","event","node-event-simulate"]},"moodle-core-dock":{"requires":["base","node","event-custom","event-mouseenter","event-resize","escape","moodle-core-dock-loader","moodle-core-event"]},"moodle-core-dock-loader":{"requires":["escape"]},"moodle-core-formchangechecker":{"requires":["base","event-focus","moodle-core-event"]},"moodle-core-event":{"requires":["event-custom"]},"moodle-core-popuphelp":{"requires":["moodle-core-tooltip"]},"moodle-core-checknet":{"requires":["base-base","moodle-core-notification-alert","io-base"]},"moodle-core-chooserdialogue":{"requires":["base","panel","moodle-core-notification"]},"moodle-core-blocks":{"requires":["base","node","io","dom","dd","dd-scroll","moodle-core-dragdrop","moodle-core-notification"]},"moodle-core-languninstallconfirm":{"requires":["base","node","moodle-core-notification-confirm","moodle-core-notification-alert"]},"moodle-core-notification":{"requires":["moodle-core-notification-dialogue","moodle-core-notification-alert","moodle-core-notification-confirm","moodle-core-notification-exception","moodle-core-notification-ajaxexception"]},"moodle-core-notification-dialogue":{"requires":["base","node","panel","escape","event-key","dd-plugin","moodle-core-widget-focusafterclose","moodle-core-lockscroll"]},"moodle-core-notification-alert":{"requires":["moodle-core-notification-dialogue"]},"moodle-core-notification-confirm":{"requires":["moodle-core-notification-dialogue"]},"moodle-core-notification-exception":{"requires":["moodle-core-notification-dialogue"]},"moodle-core-notification-ajaxexception":{"requires":["moodle-core-notification-dialogue"]},"moodle-core-tooltip":{"requires":["base","node","io-base","moodle-core-notification-dialogue","json-parse","widget-position","widget-position-align","event-outside","cache-base"]},"moodle-core_availability-form":{"requires":["base","node","event","event-delegate","panel","moodle-core-notification-dialogue","json"]},"moodle-backup-backupselectall":{"requires":["node","event","node-event-simulate","anim"]},"moodle-backup-confirmcancel":{"requires":["node","node-event-simulate","moodle-core-notification-confirm"]},"moodle-course-modchooser":{"requires":["moodle-core-chooserdialogue","moodle-course-coursebase"]},"moodle-course-formatchooser":{"requires":["base","node","node-event-simulate"]},"moodle-course-util":{"requires":["node"],"use":["moodle-course-util-base"],"submodules":{"moodle-course-util-base":{},"moodle-course-util-section":{"requires":["node","moodle-course-util-base"]},"moodle-course-util-cm":{"requires":["node","moodle-course-util-base"]}}},"moodle-course-management":{"requires":["base","node","io-base","moodle-core-notification-exception","json-parse","dd-constrain","dd-proxy","dd-drop","dd-delegate","node-event-delegate"]},"moodle-course-dragdrop":{"requires":["base","node","io","dom","dd","dd-scroll","moodle-core-dragdrop","moodle-core-notification","moodle-course-coursebase","moodle-course-util"]},"moodle-course-categoryexpander":{"requires":["node","event-key"]},"moodle-form-shortforms":{"requires":["node","base","selector-css3","moodle-core-event"]},"moodle-form-showadvanced":{"requires":["node","base","selector-css3"]},"moodle-form-dateselector":{"requires":["base","node","overlay","calendar"]},"moodle-form-passwordunmask":{"requires":[]},"moodle-question-qbankmanager":{"requires":["node","selector-css3"]},"moodle-question-searchform":{"requires":["base","node"]},"moodle-question-chooser":{"requires":["moodle-core-chooserdialogue"]},"moodle-question-preview":{"requires":["base","dom","event-delegate","event-key","core_question_engine"]},"moodle-availability_completion-form":{"requires":["base","node","event","moodle-core_availability-form"]},"moodle-availability_date-form":{"requires":["base","node","event","io","moodle-core_availability-form"]},"moodle-availability_grade-form":{"requires":["base","node","event","moodle-core_availability-form"]},"moodle-availability_group-form":{"requires":["base","node","event","moodle-core_availability-form"]},"moodle-availability_grouping-form":{"requires":["base","node","event","moodle-core_availability-form"]},"moodle-availability_profile-form":{"requires":["base","node","event","moodle-core_availability-form"]},"moodle-mod_assign-history":{"requires":["node","transition"]},"moodle-mod_forum-subscriptiontoggle":{"requires":["base-base","io-base"]},"moodle-mod_quiz-util":{"requires":["node","moodle-core-actionmenu"],"use":["moodle-mod_quiz-util-base"],"submodules":{"moodle-mod_quiz-util-base":{},"moodle-mod_quiz-util-slot":{"requires":["node","moodle-mod_quiz-util-base"]},"moodle-mod_quiz-util-page":{"requires":["node","moodle-mod_quiz-util-base"]}}},"moodle-mod_quiz-repaginate":{"requires":["base","event","node","io","moodle-core-notification-dialogue"]},"moodle-mod_quiz-questionchooser":{"requires":["moodle-core-chooserdialogue","moodle-mod_quiz-util","querystring-parse"]},"moodle-mod_quiz-dragdrop":{"requires":["base","node","io","dom","dd","dd-scroll","moodle-core-dragdrop","moodle-core-notification","moodle-mod_quiz-quizbase","moodle-mod_quiz-util-base","moodle-mod_quiz-util-page","moodle-mod_quiz-util-slot","moodle-course-util"]},"moodle-mod_quiz-toolboxes":{"requires":["base","node","event","event-key","io","moodle-mod_quiz-quizbase","moodle-mod_quiz-util-slot","moodle-core-notification-ajaxexception"]},"moodle-mod_quiz-quizbase":{"requires":["base","node"]},"moodle-mod_quiz-modform":{"requires":["base","node","event"]},"moodle-mod_quiz-autosave":{"requires":["base","node","event","event-valuechange","node-event-delegate","io-form"]},"moodle-message_airnotifier-toolboxes":{"requires":["base","node","io"]},"moodle-filter_glossary-autolinker":{"requires":["base","node","io-base","json-parse","event-delegate","overlay","moodle-core-event","moodle-core-notification-alert","moodle-core-notification-exception","moodle-core-notification-ajaxexception"]},"moodle-filter_mathjaxloader-loader":{"requires":["moodle-core-event"]},"moodle-editor_atto-editor":{"requires":["node","transition","io","overlay","escape","event","event-simulate","event-custom","node-event-html5","node-event-simulate","yui-throttle","moodle-core-notification-dialogue","moodle-core-notification-confirm","moodle-editor_atto-rangy","handlebars","timers","querystring-stringify"]},"moodle-editor_atto-plugin":{"requires":["node","base","escape","event","event-outside","handlebars","event-custom","timers","moodle-editor_atto-menu"]},"moodle-editor_atto-menu":{"requires":["moodle-core-notification-dialogue","node","event","event-custom"]},"moodle-editor_atto-rangy":{"requires":[]},"moodle-report_eventlist-eventfilter":{"requires":["base","event","node","node-event-delegate","datatable","autocomplete","autocomplete-filters"]},"moodle-report_loglive-fetchlogs":{"requires":["base","event","node","io","node-event-delegate"]},"moodle-gradereport_grader-gradereporttable":{"requires":["base","node","event","handlebars","overlay","event-hover"]},"moodle-gradereport_history-userselector":{"requires":["escape","event-delegate","event-key","handlebars","io-base","json-parse","moodle-core-notification-dialogue"]},"moodle-tool_capability-search":{"requires":["base","node"]},"moodle-tool_lp-dragdrop-reorder":{"requires":["moodle-core-dragdrop"]},"moodle-tool_monitor-dropdown":{"requires":["base","event","node"]},"moodle-assignfeedback_editpdf-editor":{"requires":["base","event","node","io","graphics","json","event-move","event-resize","transition","querystring-stringify-simple","moodle-core-notification-dialog","moodle-core-notification-alert","moodle-core-notification-warning","moodle-core-notification-exception","moodle-core-notification-ajaxexception"]},"moodle-atto_accessibilitychecker-button":{"requires":["color-base","moodle-editor_atto-plugin"]},"moodle-atto_accessibilityhelper-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_align-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_bold-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_charmap-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_clear-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_collapse-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_emoticon-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_equation-button":{"requires":["moodle-editor_atto-plugin","moodle-core-event","io","event-valuechange","tabview","array-extras"]},"moodle-atto_html-beautify":{},"moodle-atto_html-button":{"requires":["promise","moodle-editor_atto-plugin","moodle-atto_html-beautify","moodle-atto_html-codemirror","event-valuechange"]},"moodle-atto_html-codemirror":{"requires":["moodle-atto_html-codemirror-skin"]},"moodle-atto_image-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_indent-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_italic-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_link-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_managefiles-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_managefiles-usedfiles":{"requires":["node","escape"]},"moodle-atto_media-button":{"requires":["moodle-editor_atto-plugin","moodle-form-shortforms"]},"moodle-atto_noautolink-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_orderedlist-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_recordrtc-button":{"requires":["moodle-editor_atto-plugin","moodle-atto_recordrtc-recording"]},"moodle-atto_recordrtc-recording":{"requires":["moodle-atto_recordrtc-button"]},"moodle-atto_rtl-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_strike-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_subscript-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_superscript-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_table-button":{"requires":["moodle-editor_atto-plugin","moodle-editor_atto-menu","event","event-valuechange"]},"moodle-atto_title-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_underline-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_undo-button":{"requires":["moodle-editor_atto-plugin"]},"moodle-atto_unorderedlist-button":{"requires":["moodle-editor_atto-plugin"]}}},"gallery":{"name":"gallery","base":"https:\/\/hsg-mysql.toplearning.vn\/moodle\/lib\/yuilib\/gallery\/","combine":true,"comboBase":"https:\/\/hsg-mysql.toplearning.vn\/moodle\/theme\/yui_combo.php?","ext":false,"root":"gallery\/1604893707\/","patterns":{"gallery-":{"group":"gallery"}}}},"modules":{"core_filepicker":{"name":"core_filepicker","fullpath":"https:\/\/hsg-mysql.toplearning.vn\/moodle\/lib\/javascript.php\/1604893707\/repository\/filepicker.js","requires":["base","node","node-event-simulate","json","async-queue","io-base","io-upload-iframe","io-form","yui2-treeview","panel","cookie","datatable","datatable-sort","resize-plugin","dd-plugin","escape","moodle-core_filepicker","moodle-core-notification-dialogue"]},"core_comment":{"name":"core_comment","fullpath":"https:\/\/hsg-mysql.toplearning.vn\/moodle\/lib\/javascript.php\/1604893707\/comment\/comment.js","requires":["base","io-base","node","json","yui2-animation","overlay","escape"]},"mathjax":{"name":"mathjax","fullpath":"https:\/\/cdnjs.cloudflare.com\/ajax\/libs\/mathjax\/2.7.2\/MathJax.js?delayStartupUntil=configured"}}};
        M.yui.loader = {modules: {}};

        //]]>
    </script>
    <script type="text/javascript">
        //<![CDATA[
        var scormplayerdata = {"launch":false,"currentorg":"","sco":0,"scorm":0,"courseid":"2","cwidth":"100","cheight":"500","popupoptions":"scrollbars=0,directories=0,location=0,menubar=0,toolbar=0,status=0"};var online_course = {"id":"1"};
        //]]>
    </script>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body  id="page-mod-scorm-player" class="forcejavascript format-topics  path-mod path-mod-scorm chrome dir-ltr lang-vi yui-skin-sam yui3-skin-sam hsg-mysql-toplearning-vn--moodle pagelayout-embedded course-2 context-30 cmid-5 category-1 ">

<div>
    <a class="sr-only sr-only-focusable" href="#maincontent">Chuyển tới nội dung chính</a>
</div>
<script type="text/javascript" src="https://hsg-mysql.toplearning.vn/moodle/theme/yui_combo.php?rollup/3.17.2/yui-moodlesimple-min.js"></script><script type="text/javascript" src="https://hsg-mysql.toplearning.vn/moodle/lib/javascript.php/1604893707/lib/javascript-static.js"></script>
<script type="text/javascript" src="https://hsg-mysql.toplearning.vn/moodle/lib/javascript.php/1604893707/mod/scorm/request.js"></script>
<script type="text/javascript" src="https://hsg-mysql.toplearning.vn/moodle/lib/javascript.php/1604893707/lib/cookies.js"></script>

<script type="text/javascript" src="{{ asset('modules/online/scorm/js/jquery-3.2.1.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('styles/module/online/js/scorm_12.js') }}"></script>
<script type="text/javascript">
    //<![CDATA[
    document.body.className += ' jsenabled';
    //]]>
</script>


<div id="page">
    <div id="page-content">
        <div role="main"><span id="maincontent"></span><h2>Scorm 4</h2><div id="scormpage"><div id="tocbox"><div id="scormapi-parent"><script id="external-scormapi" type="text/JavaScript"></script></div><div id="toctree"><div class="yui3-g-r" id="scorm_layout"><div class="yui3-u-1-5 loading" id="scorm_toc"><div id="scorm_toc_title"></div><div id="scorm_tree"><ul><li><a data-scoid="8" title="a=4&amp;scoid=8&amp;currentorg=Course_ID1_ORG&amp;mode=&amp;attempt=1"><i class="icon fa fa-pause fa-fw "  title="Incomplete - Suspended" aria-label="Incomplete - Suspended"></i>&nbsp;THỰC PHẨM BẢO VỆ SỨC KHỎE NUTRILITE NATURAL B COMPLEX&nbsp;</a></li></ul></div></div><div class="loading" id="scorm_toc_toggle"><button id="scorm_toc_toggle_btn"></button></div><div id="scorm_content"><div id="scorm_navpanel"></div></div></div></div></div><noscript><div id="noscript">Your browser does not support JavaScript or it has JavaScript support disabled. This SCORM package may not play or save data correctly.</div></noscript></div><noscript><div class="box generalbox boxaligncenter forcejavascriptmessage py-3">JavaScript is required to view this object, please enable JavaScript in your browser and try again.</div></noscript></div>
    </div>
</div>
<script type="text/javascript">
    //<![CDATA[
    var require = {
        baseUrl : 'https://hsg-mysql.toplearning.vn/moodle/lib/requirejs.php/1604893707/',
        // We only support AMD modules with an explicit define() statement.
        enforceDefine: true,
        skipDataMain: true,
        waitSeconds : 0,

        paths: {
            jquery: 'https://hsg-mysql.toplearning.vn/moodle/lib/javascript.php/1604893707/lib/jquery/jquery-3.2.1.min',
            jqueryui: 'https://hsg-mysql.toplearning.vn/moodle/lib/javascript.php/1604893707/lib/jquery/ui-1.12.1/jquery-ui.min',
            jqueryprivate: 'https://hsg-mysql.toplearning.vn/moodle/lib/javascript.php/1604893707/lib/requirejs/jquery-private'
        },

        // Custom jquery config map.
        map: {
            // '*' means all modules will get 'jqueryprivate'
            // for their 'jquery' dependency.
            '*': { jquery: 'jqueryprivate' },
            // Stub module for 'process'. This is a workaround for a bug in MathJax (see MDL-60458).
            '*': { process: 'core/first' },

            // 'jquery-private' wants the real jQuery module
            // though. If this line was not here, there would
            // be an unresolvable cyclic dependency.
            jqueryprivate: { jquery: 'jquery' }
        }
    };

    //]]>
</script>
<script type="text/javascript" src="https://hsg-mysql.toplearning.vn/moodle/lib/javascript.php/1604893707/lib/requirejs/require.min.js"></script>
<script type="text/javascript">
    //<![CDATA[
    require(['core/first'], function() {
        ;
        require(["media_videojs/loader"], function(loader) {
            loader.setUp(function(videojs) {
                videojs.options.flash.swf = "https://hsg-mysql.toplearning.vn/moodle/media/player/videojs/videojs/video-js.swf";
                videojs.addLanguage("vi",{
                    "Audio Player": "Trình phát Audio",
                    "Video Player": "Trình phát Video",
                    "Play": "Phát",
                    "Pause": "Tạm dừng",
                    "Replay": "Phát lại",
                    "Current Time": "Thời gian hiện tại",
                    "Duration Time": "Độ dài",
                    "Remaining Time": "Thời gian còn lại",
                    "Stream Type": "Kiểu Stream",
                    "LIVE": "TRỰC TIẾP",
                    "Loaded": "Đã tải",
                    "Progress": "Tiến trình",
                    "Progress Bar": "Thanh tiến trình",
                    "progress bar timing: currentTime={1} duration={2}": "{1} của {2}",
                    "Fullscreen": "Toàn màn hình",
                    "Non-Fullscreen": "Thoát toàn màn hình",
                    "Mute": "Tắt tiếng",
                    "Unmute": "Bật âm thanh",
                    "Playback Rate": "Tỉ lệ phát lại",
                    "Subtitles": "Phụ đề",
                    "subtitles off": "tắt phụ đề",
                    "Captions": "Chú thích",
                    "captions off": "tắt chú thích",
                    "Chapters": "Chương",
                    "Descriptions": "Mô tả",
                    "descriptions off": "tắt mô tả",
                    "Audio Track": "Track âm thanh",
                    "Volume Level": "Mức âm lượng",
                    "You aborted the media playback": "Bạn đã hủy việc phát lại media.",
                    "A network error caused the media download to fail part-way.": "Một lỗi mạng dẫn đến việc tải media bị lỗi.",
                    "The media could not be loaded, either because the server or network failed or because the format is not supported.": "Video không tải được, mạng hay server có lỗi hoặc định dạng không được hỗ trợ.",
                    "The media playback was aborted due to a corruption problem or because the media used features your browser did not support.": "Phát media đã bị hủy do một sai lỗi hoặc media sử dụng những tính năng trình duyệt không hỗ trợ.",
                    "No compatible source was found for this media.": "Không có nguồn tương thích cho media này.",
                    "The media is encrypted and we do not have the keys to decrypt it.": "Media đã được mã hóa và chúng tôi không có để giải mã nó.",
                    "Play Video": "Phát Video",
                    "Close": "Đóng",
                    "Close Modal Dialog": "Đóng cửa sổ",
                    "Modal Window": "Cửa sổ",
                    "This is a modal window": "Đây là một cửa sổ",
                    "This modal can be closed by pressing the Escape key or activating the close button.": "Cửa sổ này có thể thoát bằng việc nhấn phím Esc hoặc kích hoạt nút đóng.",
                    ", opens captions settings dialog": ", mở hộp thoại cài đặt chú thích",
                    ", opens subtitles settings dialog": ", mở hộp thoại cài đặt phụ đề",
                    ", opens descriptions settings dialog": ", mở hộp thoại cài đặt mô tả",
                    ", selected": ", đã chọn",
                    "captions settings": "cài đặt chú thích",
                    "subtitles settings": "cài đặt phụ đề",
                    "descriptions settings": "cài đặt mô tả",
                    "Text": "Văn bản",
                    "White": "Trắng",
                    "Black": "Đen",
                    "Red": "Đỏ",
                    "Green": "Xanh lá cây",
                    "Blue": "Xanh da trời",
                    "Yellow": "Vàng",
                    "Magenta": "Đỏ tươi",
                    "Cyan": "Lam",
                    "Background": "Nền",
                    "Window": "Cửa sổ",
                    "Transparent": "Trong suốt",
                    "Semi-Transparent": "Bán trong suốt",
                    "Opaque": "Mờ",
                    "Font Size": "Kích cỡ phông chữ",
                    "Text Edge Style": "Dạng viền văn bản",
                    "None": "None",
                    "Raised": "Raised",
                    "Depressed": "Depressed",
                    "Uniform": "Uniform",
                    "Dropshadow": "Dropshadow",
                    "Font Family": "Phông chữ",
                    "Proportional Sans-Serif": "Proportional Sans-Serif",
                    "Monospace Sans-Serif": "Monospace Sans-Serif",
                    "Proportional Serif": "Proportional Serif",
                    "Monospace Serif": "Monospace Serif",
                    "Casual": "Casual",
                    "Script": "Script",
                    "Small Caps": "Small Caps",
                    "Reset": "Đặt lại",
                    "restore all settings to the default values": "khôi phục lại tất cả các cài đặt về giá trị mặc định",
                    "Done": "Xong",
                    "Caption Settings Dialog": "Hộp thoại cài đặt chú thích",
                    "Beginning of dialog window. Escape will cancel and close the window.": "Bắt đầu cửa sổ hộp thoại. Esc sẽ thoát và đóng cửa sổ.",
                    "End of dialog window.": "Kết thúc cửa sổ hộp thoại."
                });

            });
        });;

        require(['theme_boost/loader']);
        ;
        require(["core/notification"], function(amd) { amd.init(30, []); });;
        require(["core/log"], function(amd) { amd.setConfig({"level":"warn"}); });;
        require(["core/page_global"], function(amd) { amd.init(); });
    });
    //]]>
</script>
<script type="text/javascript">
    //<![CDATA[
    M.yui.add_module({
        "mod_scorm":{
            "name":"mod_scorm",
            "fullpath":"{{ asset('styles/module/online/js/scorm-module.js') }}",
            "requires":["json"]}
    });
    //]]>
</script>

<script type="text/javascript">
    //<![CDATA[
    M.str = {"moodle":{"lastmodified":"S\u1eeda l\u1ea7n cu\u1ed1i","name":"T\u00ean","error":"L\u1ed7i","info":"Th\u00f4ng tin","yes":"C\u00f3","no":"Kh\u00f4ng","cancel":"Hu\u1ef7 b\u1ecf","hide":"\u0110\u00f3ng","show":"M\u1edf","confirm":"X\u00e1c nh\u1eadn","areyousure":"B\u1ea1n c\u00f3 ch\u1eafc kh\u00f4ng?","closebuttontitle":"\u0110\u00f3ng","unknownerror":"L\u1ed7i kh\u00f4ng r\u00f5"},"repository":{"type":"Lo\u1ea1i","size":"K\u00edch th\u01b0\u1edbc","invalidjson":"Chu\u1ed7i JSON kh\u00f4ng h\u1ee3p l\u1ec7","nofilesattached":"Kh\u00f4ng c\u00f3 t\u1ec7p \u0111\u00ednh k\u00e8m","filepicker":"B\u1ed9 ch\u1ecdn t\u1ec7p","logout":"Tho\u00e1t","nofilesavailable":"Kh\u00f4ng c\u00f3 t\u1ec7p","norepositoriesavailable":"Xin l\u1ed7i, kh\u00f4ng c\u00f3 kho n\u00e0o hi\u1ec7n t\u1ea1i c\u1ee7a b\u1ea1n c\u00f3 th\u1ec3 tr\u1ea3 v\u1ec1 c\u00e1c t\u1ec7p theo \u0111\u1ecbnh d\u1ea1ng y\u00eau c\u1ea7u.","fileexistsdialogheader":"T\u1ec7p t\u1ed3n t\u1ea1i","fileexistsdialog_editor":"M\u1ed9t t\u1ec7p c\u00f3 t\u00ean \u0111\u00f3 \u0111\u00e3 \u0111\u01b0\u1ee3c \u0111\u00ednh k\u00e8m trong v\u0103n b\u1ea3n b\u1ea1n \u0111ang s\u1eeda.","fileexistsdialog_filemanager":"M\u1ed9t t\u1ec7p c\u00f3 t\u00ean \u0111\u00e3 \u0111\u01b0\u1ee3c \u0111\u00ednh k\u00e8m","renameto":"\u0110\u1eb7t t\u00ean th\u00e0nh \"{$a}\"","referencesexist":"C\u00f3 {$a} t\u1ec7p b\u00ed danh\/l\u1ed1i t\u1eaft s\u1eed d\u1ee5ng t\u1ec7p n\u00e0y l\u00e0m ngu\u1ed3n","select":"Ch\u1ecdn"},"admin":{"confirmdeletecomments":"B\u1ea1n \u0111\u1ecbnh x\u00f3a c\u00e1c b\u00ecnh lu\u1eadn, b\u1ea1n c\u00f3 ch\u1eafc kh\u00f4ng?","confirmation":"X\u00e1c nh\u1eadn"},"scorm":{"navigation":"\u0110i\u1ec1u h\u01b0\u1edbng","toc":"TOC","popupsblocked":"It appears that popup windows are blocked, stopping this SCORM package from playing. Please check your browser settings before trying again."},"mod_scorm":{"networkdropped":"The SCORM player has determined that your Internet connection is unreliable or has been interrupted. If you continue in this SCORM activity, your progress may not be saved.<br \/>\nYou should exit the activity now, and return when you have a dependable Internet connection."}};
    //]]>
</script>
<script type="text/javascript">
    //<![CDATA[
    (function() {
        Y.use("moodle-filter_mathjaxloader-loader",function() {
            M.filter_mathjaxloader.configure({"mathjaxconfig":"\nMathJax.Hub.Config({\n    config: [\"Accessible.js\", \"Safe.js\"],\n    errorSettings: { message: [\"!\"] },\n    skipStartupTypeset: true,\n    messageStyle: \"none\"\n});\n","lang":"vi"});
        });

        M.util.help_popups.setup(Y);
        M.util.js_pending('random5fb239a725e2b2'); Y.use('mod_scorm', function(Y) { M.mod_scorm.init(Y, 1, "-100", "-100", "0", 767, "TH\u1ef0C PH\u1ea8M B\u1ea2O V\u1ec6 S\u1ee8C KH\u1eceE NUTRILITE NATURAL B COMPLEX", false, "8", "{\"8\":{\"identifier\":\"SCO_ID1\",\"launch\":\"index_scorm.html\",\"title\":\"TH\\u1ef0C PH\\u1ea8M B\\u1ea2O V\\u1ec6 S\\u1ee8C KH\\u1eceE NUTRILITE NATURAL B COMPLEX\",\"url\":\"a=4&scoid={{ $activity->id }}&currentorg=Course_ID1_ORG&mode=&attempt={{ $attempt_id }}\",\"parent\":\"Course_ID1_ORG\",\"isvisible\":\"true\",\"parameters\":\"\"}}");  M.util.js_complete('random5fb239a725e2b2'); });
        M.scorm_api.init(Y, {"7":{"cmi.core.student_id":"admin","cmi.core.student_name":"Admin User","cmi.core.credit":"credit","cmi.core.entry":"ab-initio","cmi.core.lesson_mode":"normal","cmi.launch_data":"","cmi.student_data.mastery_score":"","cmi.student_data.max_time_allowed":"","cmi.student_data.time_limit_action":"","cmi.core.total_time":"00:00:00","cmi.core.lesson_location":"","cmi.core.lesson_status":"","cmi.core.score.raw":"","cmi.core.score.max":"","cmi.core.score.min":"","cmi.core.exit":"","cmi.suspend_data":"","cmi.comments":"","cmi.student_preference.language":"","cmi.student_preference.audio":"0","cmi.student_preference.speed":"0","cmi.student_preference.text":"0"},"8":{"cmi.core.student_id":"admin","cmi.core.student_name":"Admin User","cmi.core.credit":"credit","cmi.core.entry":"resume","cmi.core.lesson_mode":"normal","cmi.launch_data":"","cmi.student_data.mastery_score":"","cmi.student_data.max_time_allowed":"","cmi.student_data.time_limit_action":"","cmi.core.total_time":"00:00:00","cmi.core.lesson_location":"B_01","cmi.core.lesson_status":"incomplete","cmi.core.score.raw":"0","cmi.core.score.max":"10","cmi.core.score.min":"0","cmi.core.exit":"suspend","cmi.suspend_data":"A1A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP0FPool1O0FPool1K0FPool1M0FPool1C0FPool1N0FPool1G0FPool1F0FPool1B0FPool1H0FPool1L0A%24nP0A%24nP0A%24nP0A%24nP0A%24nP000AA0000C-1A0STcpQuizInfoStudentIDBAVcpQuizInfoStudentNameAKAdmin%20UserCp1BACp2BAEp2_1BAEp2_2BAEp2_3BAEp2_4BACp3BAEp3_1BAEp3_2BAEp3_3BAEp3_4BAMvarGioiThieuBAMvarPrebioticBAMvarProbioticBAKvarProcessBAQcpQuizHandledAllBA$_#-#_$","cmi.comments":"","cmi.student_preference.language":"","cmi.student_preference.audio":"0","cmi.student_preference.speed":"0","cmi.student_preference.text":"0"}}, {"7":"","8":""}, {"7":"","8":""}, "^[\\u0000-\\uFFFF]{0,64000}$", "^[\\u0000-\\uFFFF]{0,64000}$", false, "0", "4", "https:\/\/hsg-mysql.toplearning.vn\/moodle", "AtKAMfiooM", "8", "1", "normal", "", "Course_ID1_ORG", false, true, "0");
        Y.use("moodle-core-checknet",function() {M.core.checknet.init({"message":["networkdropped","mod_scorm"],"frequency":30000,"timeout":10000,"maxalerts":1});
        });
        M.util.js_pending('random5fb239a725e2b4'); Y.on('domready', function() { M.util.js_complete("init");  M.util.js_complete('random5fb239a725e2b4'); });
    })();
    //]]>
</script>

</body>
</html>
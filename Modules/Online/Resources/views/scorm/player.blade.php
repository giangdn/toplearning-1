<html>
<head>
    <title>{{ $title }}</title>
    <link rel="shortcut icon" href="{{ image_file(\App\Config::getFavicon()) }}" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="{{ asset('modules/online/scorm/js/jquery-3.2.1.min.js') }}"></script>
    <script src="{{ asset('modules/online/scorm/js/load-ajax.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('modules/online/scorm/css/scorm.css') }}">
</head>
<body>
<div id="scorm-display"></div>

<script type="text/javascript">
    var API = {};
    var API_1484_11 = {};

    (function ($) {
        var scormvar = {
            'cmi.core.exit': '',
            'cmi.core.lesson_location': '',
            'cmi.suspend_data': '',
            'cmi.core.lesson_status': 'incomplete',

            //2004
            'cmi.exit': '',
            'cmi.location': '',
            'cmi.completion_status': 'incomplete',
            'cmi.success_status': 'failed',
            'cmi.score.scaled': '',
        };

        $(document).ready(setupScormApi());

        function setupScormApi() {
            API.LMSInitialize = LMSInitialize;
            API.LMSGetValue = LMSGetValue;
            API.LMSSetValue = LMSSetValue;
            API.LMSCommit = LMSCommit;
            API.LMSFinish = LMSFinish;
            API.LMSGetLastError = LMSGetLastError;
            API.LMSGetDiagnostic = LMSGetDiagnostic;
            API.LMSGetErrorString = LMSGetErrorString;

            API_1484_11.Initialize = LMSInitialize;
            API_1484_11.GetValue = LMSGetValue;
            API_1484_11.SetValue = LMSSetValue;
            API_1484_11.Commit = LMSCommit;
            API_1484_11.Finish = LMSFinish;
            API_1484_11.GetLastError = LMSGetLastError;
            API_1484_11.GetDiagnostic = LMSGetDiagnostic;
            API_1484_11.GetErrorString = LMSGetErrorString;

            var obj = document.createElement('iframe');
            obj.setAttribute('id', 'scorm_object');
            obj.setAttribute('allowfullscreen', 'allowfullscreen');
            obj.setAttribute('webkitallowfullscreen', 'webkitallowfullscreen');
            obj.setAttribute('mozallowfullscreen', 'mozallowfullscreen');
            obj.setAttribute('src', '/online/scorm/redirect?scoid={{ $activity->id }}&mode=&attempt={{ $attempt->id }}');
            $('#scorm-display').html(obj);
        }

        /**
         * @param initializeInput
         * @return {boolean}
         */
        function LMSInitialize(initializeInput) {
            displayLog("LMSInitialize: " + initializeInput);
            @if($attempt->suspend_data)
            API.LMSSetValue('cmi.suspend_data', '{!! $attempt->suspend_data !!}');
            API_1484_11.SetValue('cmi.suspend_data', '{!! $attempt->suspend_data !!}');
            @endif
                return true;
        }

        /**
         * @param {string} varname
         * @return {string}
         */
        function LMSGetValue(varname) {
            displayLog("LMSGetValue: "+ varname+ " - "+ scormvar[varname]);
            /*if (varname == "cmi.core.lesson_location") {
                displayLog("Sa_03");
                return "Sa_03";
            }*/

            return scormvar[varname];
        }

        /**
         * @param {string} varname
         * @param {string} varvalue
         * */
        function LMSSetValue(varname, varvalue) {
            displayLog("LMSSetValue: " + varname + "=" + varvalue);
            scormvar[varname] = varvalue;

            $.ajax({
                type: "POST",
                url: "/online/scorm/save",
                dataType: 'json',
                data: {
                    attempt: '{{ $attempt->id }}',
                    scoid: '{{ $activity->id }}',
                    varname: varname,
                    varvalue: varvalue,
                },
                success: function (result) {
                    return "";
                    //console.log(result);
                }
            });

            return "";
        }

        /**
         * @return {boolean}
         */
        function LMSCommit(commitInput) {
            displayLog("LMSCommit: " + commitInput);
            return true;
        }

        /**
         * @return {boolean}
         */
        function LMSFinish(finishInput) {
            displayLog("LMSFinish: " + finishInput);
            return true;
        }

        /**
         * @return {number}
         */
        function LMSGetLastError() {
            displayLog("LMSGetLastError: ");
            return 0;
        }

        /**
         * @return {string}
         */
        function LMSGetDiagnostic(errorCode) {
            displayLog("LMSGetDiagnostic: " + errorCode);
            return "";
        }

        /**
         * @return {string}
         */
        function LMSGetErrorString(errorCode) {
            displayLog("LMSGetErrorString: " + errorCode);
            return "";
        }

        function displayLog(textToDisplay){
            //var loggerWindow = document.getElementById("logDisplay");
            //var item = document.createElement("div");
            //item.innerText = textToDisplay;
            //loggerWindow.innerHTML = item;
            @if(config('app.debug') == true)
            console.log(textToDisplay);
            @endif
        }

    })(jQuery);
</script>
</body>
</html>

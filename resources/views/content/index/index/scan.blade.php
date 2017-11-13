@extends('layouts.scan')

@section('content')


    @if ( $organization )

        <script type="text/javascript">

            var popupBlockerDisabled = true;
            var popupBlockerChecker = {
                check: function(popup_window){
                    var _scope = this;
                    if (popup_window) {
                        if(/chrome/.test(navigator.userAgent.toLowerCase())){
                            setTimeout(function () {
                                _scope._is_popup_blocked(_scope, popup_window);
                            },200);
                        }else{
                            popup_window.onload = function () {
                                _scope._is_popup_blocked(_scope, popup_window);
                            };
                        }
                    }else{
                        _scope._displayError();
                    }
                },
                _is_popup_blocked: function(scope, popup_window){
                    if ((popup_window.innerHeight > 0)==false){ scope._displayError(); }
                },
                _displayError: function(){
                    popupBlockerDisabled = false;
                }
            };
            var popup = window.open('http://www.google.com');
            popupBlockerChecker.check(popup);
            if ( popup ) {
                popup.close();
            }

            var resolutionPass = window.screen.width >= parseFloat({{ $scan_settings->resolution_width }}) && window.screen.height >= parseFloat({{ $scan_settings->resolution_height }});

            function areCookiesEnabled() {
                var cookieEnabled = (navigator.cookieEnabled) ? true : false;
                if (typeof navigator.cookieEnabled == "undefined" && !cookieEnabled)
                {
                    document.cookie="testcookie";
                    cookieEnabled = (document.cookie.indexOf("testcookie") != -1) ? true : false;
                }
                return (cookieEnabled);
            }
            var cookiesEnabled = areCookiesEnabled();

            var userLang = navigator.language || navigator.userLanguage;

            var timeStamp = Math.floor(Date.now() / 1000);

            var timeCheckPass = timeStamp <= {{ time() + ($scan_settings->time_check_window * 60) }} || timeStamp >= {{ time() - ($scan_settings->time_check_window * 60) }};
            var d = new Date();

            var tlsVersion = '';
            var tlsVersionSplit = '';
            window.parseTLSinfo = function(data) {
                tlsVersion = data.tls_version;
                tlsVersionSplit = tlsVersion.split(' ')[1];
            };
            
        </script>
        <script type='text/javascript' src='https://www.howsmyssl.com/a/check?callback=parseTLSinfo'></script>

        <form action="{{ url('save-scan') }}" method="post" class="" id="save_scan_form">
            {!! Html::hiddenInput(['method' => 'post']) !!}

            <input type="hidden" name="browser" value="{{ $ua_data->parse->browser_name }}">
            <input type="hidden" name="browser_version" value="{{ $ua_data->parse->browser_version_full }}">
            <input type="hidden" name="browser_up_to_date" value="{{ isset($ua_data->version_check->is_up_to_date) && $ua_data->version_check->is_up_to_date ? 1 : 0 }}">
            <input type="hidden" name="city" value="{{ $ip_data->city }}">
            <input type="hidden" name="country" value="{{ $ip_data->country }}">
            <input type="hidden" name="device_name" value="{{ $ua_data->parse->simple_browser_string }}">
            <input type="hidden" name="device_type" value="{{ $device_type }}">
            <input type="hidden" name="download" value="">
            <input type="hidden" name="fail_data" value=''>
            <input type="hidden" name="ip" value="{{ $ip_data->query }}">
            <input type="hidden" name="isp" value="{{ $ip_data->isp }}">
            <input type="hidden" name="jitter" value="">
            <input type="hidden" name="latitude" value="{{ $ip_data->lat }}">
            <input type="hidden" name="longitude" value="{{ $ip_data->lon }}">
            <input type="hidden" name="operating_system" value="{{ $ua_data->parse->operating_system }}">
            <input type="hidden" name="org" value="{{ $ip_data->org }}">
            <input type="hidden" name="organization_id" value="{{ $organization->id }}">
            <input type="hidden" name="pass" value="1">
            <input type="hidden" name="ping" value="">
            <input type="hidden" name="referrer" value="{{ \Request::server('HTTP_REFERER') }}">
            <input type="hidden" name="session_id" value="{{ \Session::getId() }}">
            <input type="hidden" name="state" value="{{ $ip_data->regionName }}">
            <input type="hidden" name="timezone" value="{{ $ip_data->timezone }}">
            <input type="hidden" name="unique_id" value="{{ $scan_id }}">
            <input type="hidden" name="upload" value="">
            <input type="hidden" name="user_agent_string" value="{{ $ua_data->parse->user_agent }}">
            <input type="hidden" name="zip" value="{{ $ip_data->zip }}">
            <input type="hidden" name="operating_system_up_to_date" value="{{ $os_up_to_date ? '1' : '0' }}">
            <input type="hidden" class="scan-settings" value='{{ json_encode($scan_settings) }}'>

            <script type="text/javascript">
            document.write('<input type="hidden" name="flash_installed" value="' + (FlashDetect.installed ? '1' : '0') + '">');
            document.write('<input type="hidden" name="flash_version" value="' + (FlashDetect.installed ? FlashDetect.major : '') + '">');
            document.write('<input type="hidden" name="flash_up_to_date" value="' + (FlashDetect.installed && FlashDetect.majorAtLeast({{ $flash_version }}) === true ? '1' : '0') + '">');
            document.write('<input type="hidden" name="popups_disabled" value="' + (popupBlockerDisabled ? '1' : '0') + '">');
            document.write('<input type="hidden" name="tls_version" value="' + tlsVersion + '">');
            document.write('<input type="hidden" name="java_installed" value="' + (WIMB.detect.java.enabled() ? '1' : '0') + '">');
            document.write('<input type="hidden" name="cookies_enabled" value="' + (cookiesEnabled ? '1' : '0') + '">');
            document.write('<input type="hidden" name="insecure_cookies" value="1">');
            document.write('<input type="hidden" name="time_check" value="' + (timeCheckPass ? '1' : '0') + '">');
            document.write('<input type="hidden" name="default_language" value="' + (userLang) + '">');
            document.write('<input type="hidden" name="screen_resolution" value="' + (window.screen.width + 'x' + window.screen.height) + '">');
            </script>

        </form>

        <div class="wrapper">


            <img src="{{ url('uploads/organization_logos/' . $organization->logo) }}" style="height: 40px; margin-top: -15px;">
            <h2 class="mb-4 display-inline-block ml-4">
                {{ $organization->long_name }}
            </h2>


            <div class="action-wrapper text-center">

                <div class="row button-wrapper">
                    <div class="col-md-4"></div>
                    <div class="col-md-4">
                        <a href="#" class="begin-scan" style="background-color: {{ $organization->primary_color }}">Begin Scan <i class="fa fa-angle-right"></i></a>
                    </div>
                    <div class="col-md-4"></div>
                </div>

                <div class="progress display-none">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="scan-again display-none mt-1">
                    <small><a href="#" class="scan-again text-muted text-underline">Scan Again</a></small>
                </div>

            </div>

            <hr>

            <div class="row gauge-wrapper mt-5">

                <div class="col-md-3 {{ $scan_settings->download_enabled ? '' : 'display-none' }}">
                    <div class="card">
                        <!-- Start .panel -->
                        <div class="card-header">
                            <span>Download</span> <i class="fa fa-download text-muted"></i>
                            <div class="float-right">
                                <i class="fa fa-question-circle-o text-muted" data-toggle="tooltip" data-placement="top" title="The speed you receive information from the Internet, measured in megabits per second (Mb/s). Improve download performance by pausing streaming services and connecting to your router using an ethernet cable rather than Wi-Fi."></i>
                            </div>
                        </div>
                        <div class="card-block text-xs-center">
                            <div class="panel-middle margin-b-0 meter" class="hidden" id="ggdl" data-color="{{ $organization->primary_color }}"></div>
                        </div>
                    </div><!-- End .panel -->
                </div>

                <div class="col-md-3 {{ $scan_settings->upload_enabled ? '' : 'display-none' }}">
                    <div class="card">
                        <!-- Start .panel -->
                        <div class="card-header">
                            <span>Upload</span> <i class="fa fa-upload text-muted"></i>
                            <div class="float-right">
                                <i class="fa fa-question-circle-o text-muted" data-toggle="tooltip" data-placement="top" title="" data-original-title="The speed you send information to the internet, measured in megabits per second (Mb/s). Improve upload performance by connecting to your router using an ethernet cable rather than Wi-Fi."></i>
                            </div>
                        </div>
                        <div class="card-block text-xs-center">
                            <div class="right panel-middle margin-b-0 meter" class="hidden" id="ggul" data-color="{{ $organization->secondary_color }}"></div>
                        </div>
                    </div><!-- End .panel -->
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <!-- Start .panel -->
                        <div class="card-header">
                            <span>Ping</span> <i class="fa fa-refresh text-muted"></i>
                            <div class="float-right">
                                <i class="fa fa-question-circle-o text-muted" data-toggle="tooltip" data-placement="top" title="" data-original-title="Determines whether your device can communicate with another device on the internet. Latency is the time it takes to complete this test, measured in milliseconds (ms). A good ping latency is 200ms or lower. Improve ping score by closing background programs and connecting to your router using an ethernet cable rather than Wi-Fi."></i>
                            </div>
                        </div>
                        <div class="card-block text-xs-center">
                            <div class="right panel-middle margin-b-0 meter" class="hidden" id="ggping" data-color="{{ $organization->primary_color }}"></div>
                        </div>
                    </div><!-- End .panel -->
                </div>

                <div class="col-md-3">
                    <div class="card">
                        <!-- Start .panel -->
                        <div class="card-header">
                            <span>Jitter</span> <i class="fa fa-line-chart text-muted"></i>
                            <div class="float-right">
                                <i class="fa fa-question-circle-o text-muted" data-toggle="tooltip" data-placement="top" title="" data-original-title="Variation or hesitation in latency testing (ping). High jitter may cause music or video to stutter when streaming. Improve jitter score by connecting to your router using an ethernet cable rather than Wi-Fi."></i>
                            </div>
                        </div>
                        <div class="card-block text-xs-center">
                            <div class="right panel-middle margin-b-0 meter" class="hidden" id="ggjitter" data-color="{{ $organization->secondary_color }}"></div>
                        </div>
                    </div><!-- End .panel -->
                </div>

            </div>

            <div class="after-scan display-none">

                <div class="pass-fail-wrapper mt-5">

                    <div class="row">
                        <div class="{{ $scan_settings->location_enabled ? 'col-md-6' : 'col-md-12' }}">

                            <div class="card device-information">
                                <div class="card-header">
                                    <h5 class="text-center mb-0">
                                        <i class="fa fa-desktop fa-lg" style="color: {{ $organization->secondary_color }};"></i> <span style="color: {{ $organization->primary_color }};">Device</span>
                                    </h5>
                                </div>
                                <div class="card-body pb-0">

                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 50px;"></th>
                                                <th style="min-width: 150px;">Test</th>
                                                <th>Result</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    @if ( $valid_device_type )
                                                    <span class="badge badge-success">PASS</span>
                                                    @else
                                                    <span class="badge badge-danger">FAIL</span>
                                                    @endif
                                                </td>
                                                <td>Device Type</td>
                                                <td>{{ $device_type }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ( $valid_os )
                                                        <span class="badge badge-success">PASS</span>
                                                    @else
                                                        <span class="badge badge-danger">FAIL</span>
                                                    @endif
                                                </td>
                                                <td>Operating System</td>
                                                <td>{{ $ua_data->parse->operating_system }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ( $valid_browser )
                                                        <span class="badge badge-success">PASS</span>
                                                    @else
                                                        <span class="badge badge-danger">FAIL</span>
                                                    @endif
                                                </td>
                                                <td>Browser</td>
                                                <td>{{ $ua_data->parse->browser_name }}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if ( $valid_browser_version )
                                                        <span class="badge badge-success">PASS</span>
                                                    @else
                                                        <span class="badge badge-danger">FAIL</span>
                                                    @endif
                                                </td>
                                                <td>Browser - Version</td>
                                                <td>{{ $ua_data->parse->browser_version_full }} {!! $valid_browser_version ? '' : ' - <a href="https://www.computerhope.com/issues/ch001388.htm" target="_blank" class="track-click" data-type="solution" data-solution="browser_version">Update Your Browser</a>' !!}</td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <script type="text/javascript">
                                                        if ( resolutionPass ) {
                                                            document.write('<span class="badge badge-success">PASS</span>');
                                                        } else {
                                                            document.write('<span class="badge badge-danger">FAIL</span>');
                                                        }
                                                    </script>
                                                </td>
                                                <td>Screen Resolution</td>
                                                <td>
                                                    <script type="text/javascript">
                                                        document.write(window.screen.width + 'x' + window.screen.height + (resolutionPass ? '' : ' - Min. resolution is {{ $scan_settings->resolution_width . 'x' . $scan_settings->resolution_height }}'));
                                                    </script>
                                                </td>
                                            </tr>
                                            <tr class="{{ $scan_settings->flash_enabled ? '' : 'display-none' }}">
                                                <td>
                                                    <script type="text/javascript">
                                                        if ( FlashDetect.installed ) {
                                                            document.write('<span class="badge badge-success">PASS</span>');
                                                        } else {
                                                            document.write('<span class="badge badge-danger">FAIL</span>');
                                                        }
                                                    </script>
                                                </td>
                                                <td>Flash - Installed</td>
                                                <td>
                                                    <script type="text/javascript">
                                                        if ( FlashDetect.installed ) {
                                                            document.write('Yes, version ' + FlashDetect.major);
                                                        } else {
                                                            document.write('Flash Not Installed - <a href="https://get.adobe.com/flashplayer/" target="_blank" class="track-click" data-type="solution" data-solution="flash_installed">Install Flash Player</a>');
                                                        }
                                                    </script>
                                                </td>
                                            </tr>
                                            <tr class="{{ $scan_settings->cookies_enabled ? '' : 'display-none' }}">
                                                <td>
                                                    <script type="text/javascript">
                                                        if ( cookiesEnabled ) {
                                                            document.write('<span class="badge badge-success">PASS</span>');
                                                        } else {
                                                            document.write('<span class="badge badge-danger">FAIL</span>');
                                                        }
                                                    </script>
                                                </td>
                                                <td>Cookies Enabled</td>
                                                <td>
                                                    <script type="text/javascript">
                                                        if ( cookiesEnabled ) {
                                                            document.write('Yes');
                                                        } else {
                                                            document.write('Cookies not enabled');
                                                        }
                                                    </script>
                                                </td>
                                            </tr>
                                            <tr class="{{ $scan_settings->java_enabled ? '' : 'display-none' }}">
                                                <td>
                                                    <script type="text/javascript">
                                                        if ( WIMB.detect.java.enabled() ) {
                                                            document.write('<span class="badge badge-success">PASS</span>');
                                                        } else {
                                                            document.write('<span class="badge badge-danger">FAIL</span>');
                                                        }
                                                    </script>
                                                </td>
                                                <td>Java</td>
                                                <td>
                                                    <script type="text/javascript">
                                                        if ( WIMB.detect.java.enabled() ) {
                                                            document.write('Java is installed');
                                                        } else {
                                                            document.write('Java is not installed - <a href="http://www.java.com/getjava/" target="_blank" class="track-click" data-type="solution" data-solution="java">Install Java</a>');
                                                        }
                                                    </script>
                                                </td>
                                            </tr>
                                            <tr class="{{ $scan_settings->popup_blocker_enabled ? '' : 'display-none' }}">
                                                <td>
                                                    <script type="text/javascript">
                                                        if ( popupBlockerDisabled ) {
                                                            document.write('<span class="badge badge-success">PASS</span>');
                                                        } else {
                                                            document.write('<span class="badge badge-danger">FAIL</span>');
                                                        }
                                                    </script>
                                                </td>
                                                <td>Popup Blocker</td>
                                                <td>
                                                    <script type="text/javascript">
                                                        if ( popupBlockerDisabled ) {
                                                            document.write('Popup blocker has been disabled');
                                                        }else{
                                                            document.write('Popup blocker needs to be disabled - <a href="https://www.google.com/amp/s/m.wikihow.com/Disable-Popup-Blockers%3Famp=1" target="_blank" class="track-click" data-type="solution" data-solution="popup">Disable Popup Blocker</a>');
                                                        }
                                                    </script>
                                                </td>
                                            </tr>
                                            <tr class="{{ $scan_settings->default_language_enabled ? '' : 'display-none' }}">
                                                <td>
                                                    <script type="text/javascript">
                                                        if ( userLang ) {
                                                            document.write('<span class="badge badge-success">PASS</span>');
                                                        } else {
                                                            document.write('<span class="badge badge-danger">FAIL</span>');
                                                        }
                                                    </script>
                                                </td>
                                                <td>Default Language</td>
                                                <td>
                                                    <script type="text/javascript">
                                                        document.write(userLang);
                                                    </script>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                        </div>
                        <div class="col-md-6 {{ $scan_settings->location_enabled ? '' : 'display-none' }}">

                            <div class="card device-information">
                                <div class="card-header">
                                    <h5 class="text-center mb-0">
                                        <i class="fa fa-globe fa-lg" style="color: {{ $organization->secondary_color }};"></i> <span style="color: {{ $organization->primary_color }};">Network/ISP</span>
                                    </h5>
                                </div>
                                <div class="card-body pb-0">

                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th style="min-width: 50px;"></th>
                                                <th style="min-width: 150px;">Test</th>
                                                <th>Result</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td><span class="badge badge-success">PASS</span></td>
                                                <td>ISP Name</td>
                                                <td>{{ $ip_data->isp }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-success">PASS</span></td>
                                                <td>Organization</td>
                                                <td>{{ $ip_data->org }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-success">PASS</span></td>
                                                <td>Country</td>
                                                <td>{{ $ip_data->country }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-success">PASS</span></td>
                                                <td>State</td>
                                                <td>{{ $ip_data->regionName }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-success">PASS</span></td>
                                                <td>City</td>
                                                <td>{{ $ip_data->city }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-success">PASS</span></td>
                                                <td>Zip Code</td>
                                                <td>{{ $ip_data->zip }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-success">PASS</span></td>
                                                <td>Time Zone</td>
                                                <td>{{ $ip_data->timezone }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-success">PASS</span></td>
                                                <td>Latitude</td>
                                                <td>{{ $ip_data->lat }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-success">PASS</span></td>
                                                <td>Longitude</td>
                                                <td>{{ $ip_data->lon }}</td>
                                            </tr>
                                            <tr>
                                                <td><span class="badge badge-success">PASS</span></td>
                                                <td>IP Address</td>
                                                <td>{{ $ip_data->query }}</td>
                                            </tr>
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="{{ $scan_settings->location_enabled ? 'col-md-6' : 'col-md-12' }}">

                            <div class="card device-information">
                                <div class="card-header">
                                    <h5 class="text-center mb-0">
                                        <i class="fa fa-shield fa-lg" style="color: {{ $organization->secondary_color }};"></i> <span style="color: {{ $organization->primary_color }};">Security</span>
                                    </h5>
                                </div>
                                <div class="card-body pb-0">

                                    <table class="table table-sm">
                                        <thead>
                                        <tr>
                                            <th style="min-width: 50px;"></th>
                                            <th style="min-width: 150px;">Test</th>
                                            <th>Result</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <tr class="{{ $scan_settings->tls_enabled ? '' : 'display-none' }}">
                                            <td>
                                                <script type="text/javascript">
                                                    document.write(tlsVersionSplit >= 1.2 ? '<span class="badge badge-success">PASS</span>' : '<span class="badge badge-danger">FAIL</span>');
                                                </script>
                                            </td>
                                            <td>TLS 1.2</td>
                                            <td>
                                                <script type="text/javascript">
                                                    document.write(
                                                        tlsVersionSplit >= 1.2
                                                            ? 'Your browser supports ' + tlsVersion + '.'
                                                            : 'Your browser only supports ' + tlsVersion + ' - Please upgrade to a browser with TLS 1.2 support.'
                                                    );
                                                </script>
                                            </td>
                                        </tr>
                                        <script type="text/javascript">
                                            /*var insecureUrl = config.url.replace('https', 'http');
                                            $.ajax({
                                                url: insecureUrl + '/cookie-test',
                                                method: 'POST',
                                                beforeSubmit: function() {
                                                    $('.insecure-pass, .insecure-fail').hide();
                                                }
                                            }).done(function(data, textStatus, jqXHR) {
                                                $('.insecure-fail').show();
                                                $('input[name="insecure_cookies"]').val(0);
                                            }).fail(function (jqXHR, textStatus, errorThrown) {
                                                $('.insecure-pass').show();
                                                $('input[name="insecure_cookies"]').val(1);
                                            });*/
                                        </script>
                                        <tr class="{{ $scan_settings->insecure_cookies_enabled ? '' : 'display-none' }}">
                                            <td>
                                                <span class="badge badge-success insecure-pass">PASS</span>
                                                <span class="badge badge-success insecure-fail display-none">FAIL</span>
                                            </td>
                                            <td>Insecure Cookies</td>
                                            <td>
                                                <span class="insecure-pass">Your browser blocks insecure cookies</span>
                                                <span class="insecure-fail display-none">Your browser allows insecure cookies</span>
                                            </td>
                                        </tr>
                                        <tr class="{{ $scan_settings->operating_system_updates_enabled ? '' : 'display-none' }}">
                                            <td>
                                                @if ( $os_up_to_date )
                                                <span class="badge badge-success">PASS</span></td>
                                                @else
                                                <span class="badge badge-danger">FAIL</span></td>
                                                @endif
                                            <td>OS - Up To Date</td>
                                            <td>{{ $os_up_to_date ? 'Yes' : 'No' }}</td>
                                        </tr>
                                        <tr class="{{ $scan_settings->browser_current_version_enabled ? '' : 'display-none' }}">
                                            <td>
                                            @if ( isset($ua_data->version_check->is_up_to_date) && $ua_data->version_check->is_up_to_date )
                                                    <span class="badge badge-success">PASS</span></td>
                                            @else
                                                <span class="badge badge-danger">FAIL</span></td>
                                            @endif
                                            <td>Browser - Up To Date</td>
                                            <td>{!! isset($ua_data->version_check->is_up_to_date) && $ua_data->version_check->is_up_to_date ? 'Yes' : 'No - <a href="https://www.computerhope.com/issues/ch001388.htm" target="_blank" class="track-click" data-type="solution" data-solution="browser_up_to_date">Update Your Browser</a>' !!}</td>
                                        </tr>
                                        <tr class="{{ $scan_settings->flash_current_version_enabled ? '' : 'display-none' }}">
                                            <td>
                                                <script type="text/javascript">
                                                    if ( FlashDetect.majorAtLeast({{ $flash_version }}) === true ) {
                                                        document.write('<span class="badge badge-success">PASS</span>');
                                                    } else {
                                                        document.write('<span class="badge badge-danger">FAIL</span>');
                                                    }
                                                </script>
                                            </td>
                                            <td>Flash - Up To Date</td>
                                            <td>
                                                <script type="text/javascript">
                                                    if ( FlashDetect.installed && FlashDetect.majorAtLeast({{ $flash_version }}) === true ) {
                                                        document.write('Yes');
                                                    } else if ( !FlashDetect.installed ) {
                                                        document.write('Flash not installed');
                                                    } else {
                                                        document.write('Out of Date - <a href="https://get.adobe.com/flashplayer/" target="_blank" class="track-click" data-type="solution" data-solution="flash_version">Update Flash Player</a>');
                                                    }
                                                </script>
                                            </td>
                                        </tr>
                                        <tr class="{{ $scan_settings->time_check_enabled ? '' : 'display-none' }}">
                                            <td>
                                                <script type="text/javascript">
                                                    if ( timeCheckPass ) {
                                                        document.write('<span class="badge badge-success">PASS</span>');
                                                    } else {
                                                        document.write('<span class="badge badge-danger">FAIL</span>');
                                                    }
                                                </script>
                                            </td>
                                            <td>Time Check</td>
                                            <td>
                                                <script type="text/javascript">
                                                    document.write('Local: ' + d.getHours() + ':' + d.getMinutes() + ', Server: {{ date('H:i') }}');
                                                </script>
                                            </td>
                                        </tr>

                                        </tbody>
                                    </table>

                                </div>
                            </div>

                        </div>
                        <div class="col-md-6 {{ $scan_settings->location_enabled ? '' : 'display-none' }}">

                            <div class="card device-information">
                                <div class="card-header">
                                    <h5 class="text-center mb-0">
                                        <i class="fa fa-map-marker fa-lg" style="color: {{ $organization->secondary_color }};"></i> <span style="color: {{ $organization->primary_color }};">Location</span>
                                    </h5>
                                </div>
                                <div class="card-body">

                                    <script type="text/javascript">
                                        function initMap() {
                                            var uluru = {lat: {{ $ip_data->lat }}, lng: {{ $ip_data->lon }}};
                                            var map = new google.maps.Map(document.getElementById('google_map'), {
                                                zoom: 8,
                                                center: uluru
                                            });
                                            var marker = new google.maps.Marker({
                                                position: uluru,
                                                map: map
                                            });
                                        }
                                    </script>
                                    <div id="google_map">
                                        <div class="text-center text-muted mt-5"><i class="fa fa-circle-o-notch fa-spin"></i> Loading Map...</div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>

                </div>

                <hr class="mt-5">


                <div class="info-wrapper mt-5">

                    <div class="row">
                        <div class="col-md-6">

                            <a href="mailto:{{ $organization->support_email }}?subject={{ 'Scan ID: ' . $scan_id }}" class="info support track-click" target="_blank" data-type="support" style="background: {{ $organization->primary_color }};">
                                <h3 href="#" id="trackingCode" >Scan ID: <strong>
                                        <?php echo $scan_id; ?>
                                    </strong></h3>
                                <span data-toggle="false" data-placement="top" title="" data-original-title="Contact your institution’s IT department to enable this feature.">Need further help?<br>Click here to email Tech Support.</span>
                            </a>

                        </div>
                        <div class="col-md-6">

                            <div class="info content" style="background: {{ $organization->secondary_color }};">
                                <div class="summernote-content">{!! $organization->scan_page_content; !!}</div>
                            </div>

                        </div>
                    </div>

                </div>

            </div>


        </div>


    @else

        <div class="alert alert-danger">
            <i class="fa fa-exclamation-triangle"></i> Invalid Organization Request
        </div>

    @endif

@endsection
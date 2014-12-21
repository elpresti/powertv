<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="format-detection" content="telephone=no" />
  <title>VoiceMessages</title>

  <link rel="stylesheet" type="text/css" href="style.css" />

  <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.2.min.js"></script>

</head>
<body>
  
<div id="recorder-app">
    <div id="native-objects">
        <div id="flashobj-container">
            <!--[if IE]>
            <object id="_speakpipe_flash_recorder" width="1" height="1"
                    classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
                <param name="movie" value="/static/flash/speakpipe.swf?369">
                <param name="allowScriptAccess" value="always" />
            </object>
            <![endif]-->
            <!--[if !IE]>-->
            <object id="_speakpipe_flash_recorder" width="1" height="1" data="/static/flash/speakpipe.swf?369" type="application/x-shockwave-flash">
                <param name="allowScriptAccess" value="always"/>
            </object>
            <!--<![endif]-->
        </div>
    </div>

    <div id="flashobj-page" class="app-page">
        <div class="nativeobj-container" style="margin-left: 568px;">
        </div>
        <div class="widget-aux-action">
            <a href="#" class="show-flash-workaround-btn">Help</a>
            -
            <a id="open-mic-settings" href="#">Mic settings</a>
            -
            <a target="_blank" href="http://www.speakpipe.com/">SpeakPipe</a>
        </div>
    </div>

    <div id="-speakpipe-msg-repository">
        <span id="_speakpipe_msg_mic_denied">
            Access to your microphone is disabled.
            <p>
                <a href="#" onclick="_speakpipe.showMicAllowHelp(); return false;">
                    How to allow access to the microphone</a>
            </p>
        </span>
        <span id="_speakpipe_msg_mic_absent">
            SpeakPipe cannot find a microphone in your system. In order to record a voice message, you need a microphone.
        </span>
        <span id="_speakpipe_msg_old_flash">
            You have an old version of Flash Player. <br/> Please update it, and try again. <p> <a href="http://get.adobe.com/flashplayer/" target="_blank">Download the latest Flash Player</a> </p>
        </span>
    </div>

    <div id="start-recording-page" class="app-page">
        <div class="page-title">
            Send a voice message<br/> to <span class="user-name-box">Radio Power Pinamar</span>
        </div>
        <div class="mic-ready-question">Is your microphone ready?</div>
        <div class="main-btn start-rec-btn">Start recording</div>
        <div class="workflow-hint">
            <span class="bullet">1</span> Record -
            <span class="bullet">2</span> Listen -
            <span class="bullet">3</span> Send
        </div>
    </div>

    <div id="recording-page" class="app-page">
        <div class="page-title">Speak now</div>
        <div style="font-size: 18px; font-weight: bold; margin: 3px 0 0px;">
            Recording: <span class="recording-duration">00:00</span>
        </div>
        <div class="max-duration-info">
            Max recording duration:
            <b class="max-duration-value">90</b>
            <span class="max-duration-units">seconds</span>
        </div>
        <div id="mic-activity-level">
            <div class="mic-al-left">
                <div></div>
            </div>
            <div class="mic-al-right">
                <div></div>
            </div>
        </div>
        <div class="main-btn stop-rec-btn">Stop</div>
        <div class="secondary-btn rc-show-start-page">Reset</div>
    </div>

    <div id="progress-page" class="app-page">
        <div class="page-title status-msg">
            Processing the audio...
        </div>
        <div class="fake-indicator">
            <img src="/static/img/widget/dialog-loader.gif"/>
        </div>
        <div class="progressbar">
            <div></div>
            <span>0%</span>
        </div>
        Please wait while it's being processed.
    </div>

    <div id="recording-warn-page" class="app-page">
        <div class="page-title warn-title"></div>
        <div class="dlg-msg">
        </div>
        <div class="dlg-action">
            <a class="secondary-btn rc-play-rec" href="#"><b>Replay</b></a>
            <a class="secondary-btn rc-show-send-page" href="#">Skip</a>
            <a href="#" class="secondary-btn bt-reset-record rc-reload-widget">Start over</a>
        </div>
    </div>

    <div id="play-recording-page" class="app-page">
        <div class="page-title">
            Playing: <span id="-speakpipe-sound-time">00:00</span>
        </div>
        <div class="progressbar">
            <div id="-speakpipe-sound-progress"></div>
        </div>
        <div class="main-btn rc-show-send-page" href="#">Proceed</div>
        <div id="-speakpipe-button-play-pause" class="secondary-btn">Play</div>
        <div class="secondary-btn rc-reset-rec">Reset</div>
    </div>

    <div id="reset-recording-page" class="app-page">
        <div class="page-title">
            Reset recording
        </div>
        <div class="dlg-msg">
            Are you sure you want<br/> to start a new recording?
            <br/> Your current recording will be deleted.
        </div>
        <div class="dlg-action">
            <a class="secondary-btn rc-show-start-page" href="#">Yes</a>
            <a class="secondary-btn rc-show-send-page" href="#">No</a>
        </div>
    </div>

    <div id="send-recording-page" class="app-page">
        <div class="page-title">
            Send to <span class="user-name-box">Radio Power Pinamar</span>
        </div>
        <div class="contact-info-box">
            <b>Your contact info:</b>
            <div class="input-field">
                <input id="-speakpipe-record-author-name" type="text" name="name" maxlength="60"/>
            </div>
            <div class="input-field">
                <input id="-speakpipe-record-author-email" type="text" name="email" maxlength="60"/>
            </div>
            <div id="subscribe-email-field" style="display: none;">
                <input id="subscribe-email-checkbox" type="checkbox"/>
                <label id="subscribe-email-label" class="for_checkbox" for="subscribe-email-checkbox"></label>
            </div>
        </div>
        <div class="main-btn send-rec-btn">Send</div>
        <div class="secondary-btn rc-play-rec"><b>Replay</b></div>
        <div class="secondary-btn rc-reset-rec">Reset</div>
    </div>

    <div id="thankyou-page" class="app-page">
        <div class="dlg-msg">
            Your message has been sent.<br/>
            Thank you!
        </div>
        <div class="secondary-btn rc-show-start-page">Record another message</div>
    </div>

    <div id="recorder-error-page" class="app-page">
        <div class="status-msg page-title">
          Recorder error
        </div>
        <div class="dlg-msg">
        </div>
        
<div class="widget-aux-action">
    <a class="rc-reload-widget" href="#">Try again</a>
</div>

    </div>

    <div id="mic-allow-help-page" class="app-page">
        
<div class="widget-info-message">
    <h1>How to enable access to a microphone</h1>
    <ol>
        <li>
            Open <a target="_blank" href="http://www.macromedia.com/support/documentation/en/flashplayer/help/settings_manager06.html">settings panel</a>.
            Click <a target="_blank" href="http://www.macromedia.com/support/documentation/en/flashplayer/help/settings_manager06.html">here</a> to open it.
        </li>
        <li>Select <b>www.speakpipe.com</b> in the list of visited websites.</li>
        <li>Click "Always allow" (make sure that <b>www.speakpipe.com</b> is selected).</li>
        <li>Restart your browser, return to this page and try to record a voice message.</li>
    </ol>
    
<div class="widget-aux-action">
    <a class="rc-reload-widget" href="#">Try again</a>
</div>

</div>

    </div>

    <div id="allow-button-workaround-page" class="app-page">
        
<div class="widget-info-message">
    <h1>How to enable access to a microphone</h1>
    <ol>
        <li>
            Open <a target="_blank" href="http://www.macromedia.com/support/documentation/en/flashplayer/help/settings_manager06.html">settings panel</a>.
            Click <a target="_blank" href="http://www.macromedia.com/support/documentation/en/flashplayer/help/settings_manager06.html">here</a> to open it.
        </li>
        <li>Select <b>www.speakpipe.com</b> in the list of visited websites.</li>
        <li>Click "Always allow" (make sure that <b>www.speakpipe.com</b> is selected).</li>
        <li>Restart your browser, return to this page and try to record a voice message.</li>
    </ol>
    
<div class="widget-aux-action">
    <a class="rc-reload-widget" href="#">Try again</a>
</div>

</div>

    </div>

    <div id="mic-extra-privacy-page" class="app-page">
        <div class="page-title">
            Click <b>Allow</b> on the panel above
        </div>
        Here is an example:
        <div>
            <img src="/static/img/widget/mic_privacy_chrome.png?v=2"/>
        </div>
    </div>

    <div id="mobile-app-page" class="app-page">
        <div class="page-title">
            Send a voice message<br/> to <span class="user-name-box">Radio Power Pinamar</span>
        </div>
        <div style="max-width: 300px; min-width: 270px; margin: 3px auto 0; font-size: 14px;">
            <div style="margin: 0 5px;">
                To send a voice message from a mobile device you need the SpeakPipe app.
            </div>
            <div class="dlg-action">
                <div class="main-btn launch-ios-app-btn">
                    I have the app
                </div>
                <div class="secondary-btn download-ios-app-btn">
                    I don't have it
                </div>
            </div>
        </div>
    </div>

    <div id="launch-ios-app-page" class="app-page">
        <div class="page-title">
            Record via the app
        </div>
        <div style="max-width: 300px; min-width: 270px; margin: 5px auto 0; font-size: 14px;">
            <a href="#" class="launch-speakpipe-app-icon" id="rc-start-mobile-app">
                <div>Launch the<br/>SpeakPipe<br/>app</div>
            </a>
            <div class="secondary-btn ios-cancel-btn">Cancel</div>
            <div style="margin: 8px 5px 0;">
                If you don't have the SpeakPipe app,
                you need to download it from the AppStore.
            </div>
        </div>
    </div>

    <div id="download-ios-app-page" class="app-page">
        <div class="page-title">
            Download the app
        </div>
        <div style="max-width: 300px; min-width: 270px; margin: 7px auto 0; font-size: 14px;">
            <a href="https://itunes.apple.com/us/app/speakpipe/id602749650?mt=8&amp;uo=4" target="itunes_store" class="appstore-badge"></a>
            <div class="secondary-btn ios-cancel-btn">Cancel</div>
            <div style="margin: 8px 5px 0;">
                <b>Note:</b> After you have installed the app,
                return to this page and tap on the launch button.
            </div>
        </div>
    </div>

</div>


  
</body>
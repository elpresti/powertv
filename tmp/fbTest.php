<html>
<head>
    <title></title>
</head>
<body>
    <div id="fb-root"></div>

    <script>
    window.fbAsyncInit = function() {
            FB.init({
            appId: '479795868782758',
            status: true,
            cookie: true,
            xfbml: true
        });
    };

    // Load the SDK asynchronously
    (function(d){
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if (d.getElementById(id)) {return;}
    js = d.createElement('script'); js.id = id; js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
    }(document));

    function login() {
        FB.login(function(response) {

        // handle the response
        console.log("Response goes here!");

        }, {scope: 'read_stream,publish_stream,publish_actions,read_friendlists'});            
    }

    function logout() {
        FB.logout(function(response) {
          // user is now logged out
        });
    }

    var status = FB.getLoginStatus();

    console.log(status);

    </script>

    <button onclick="javascript:login();">Login Facebook</button>

    <br>

    <div class="fb-send" data-href="https://scontent.xx.fbcdn.net/hphotos-xfp1/t31.0-8/p960x960/11011945_598124316956392_2336392518776829819_o.jpg" data-colorscheme="light"></div>

    <br>

    <button onclick="javascript:logout();">Logout from Facebook</button>
</body>
</html>

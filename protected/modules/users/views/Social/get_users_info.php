<?php
$sClientId = '626676873784.apps.googleusercontent.com';
$sClientSecret = 'cZdXlwgyMLdOhT10OF1Knyis';
$contactURL = 'http://userbase.greennet.com/users/social/managegoogleplus'; // callback url, don't forget to change it to your!

$iMaxResults = 20; // max results
$sStep = 'auth'; // current step
$argarray   = '';
$scope_info = "https://www.googleapis.com/auth/userinfo.profile ". // optional
                               "https://www.googleapis.com/auth/userinfo.email"   ;
$infoURL = 'http://userbase.greennet.com/users/social/managegoogleplus';
$access_type = "offline"; // online or offline

?>
<h2><a href='https://accounts.google.com/o/oauth2/auth?response_type=code&client_id=<?php echo $sClientId?>&redirect_uri=<?php echo urlencode($infoURL)?>&scope=<?php echo urlencode($scope_info)?>&state=email'>Get user info</a></h2>

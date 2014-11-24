<?php
return CMap::mergeArray(array(
	'debug'	=> array(
		'class'	=> 'greennet.controllers.DebugController',
	),
	// core
	'login'	=> array(
		'class'	=> 'greennet.modules.users.controllers.GNLoginController',
	),
	'logout'	=> array(
		'class'	=> 'greennet.modules.users.controllers.GNLogoutController',
	),
	'profile'	=> array(
		'class'	=> 'greennet.modules.users.controllers.GNProfileController',
	),
	'recover'	=> array(
		'class'	=> 'greennet.modules.users.controllers.GNRecoveryController'
	),
	
	// Social Login
	'facebook'	=> array(
		'class'	=> 'greennet.modules.social.controllers.GNFacebookController'
	),
	'google'	=> array(
		'class'	=> 'greennet.modules.social.controllers.GNGoogleController'
	)),
	GNBase::$mappedControllers
);

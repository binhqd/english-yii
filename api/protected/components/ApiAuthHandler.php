<?php

class ApiAuthHandler extends CComponent
{
    public static function checkAuth()
    {
        if (!currentUser()->isGuest) return true;
        ApiAccess::$access_token = ApiAccess::requestToken();
        if (!empty(ApiAccess::$access_token)) {
            ApiAccess::check();
        }
        return true;

        // 		$cookies = Yii::app()->request->cookies;
        
        // 		if (isset($cookies[self::rememberMe]) && Yii::app()->user->isGuest) {

        
        // 			$hash = $cookies[self::rememberMe]->value;

        
        // 			$url = Yii::app()->request->url;

        
        // 			$model = new GNLoginForm;

        
        // 			// decode hash

        
        // 			$key = GNUserLogin::$key;

        
        // 			$decode = GNUserLogin::decode($hash, $key);

        
        // 			// if hash is valid

        
        // 			if (GNUserLogin::model()->isAuthenticate($decode['hexID'], $decode['token'])) {

        
        // 				// create user identity

        
        // 				$identity = GNUserIdentity::processAuth($decode['hexID']);

        
        // 				// set user identity

        
        // 				$duration = 3600*24*30;

        
        // 				Yii::app()->user->login($identity, $duration);

        
        // 				// get user info

        
        // 				$user = GNUser::model()->getUserInfo(IDHelper::uuidToBinary($decode['hexID']));

        
        // 				// create new code for hashing

        
        // 				$code = GNUserLogin::encode($user->id, $key);

        
        // 				GNUserLogin::model()->setCode($user->id, $code['token']);

        
        // 				// reset remember hash

        
        // 				$cookieName = GNAuthHandler::rememberMe;

        
        // 				if (isset(Yii::app()->request->cookies[$cookieName]))

        
        // 					unset(Yii::app()->request->cookies[$cookieName]);

        
        // 				$cookie = new CHttpCookie($cookieName, $code['hash']);

        
        // 				$cookie->expire = time() + $duration;

        
        // 				$cookie->domain = Yii::app()->session->cookieParams['domain'];

        
        // 				Yii::app()->request->cookies[$cookieName] = $cookie;

        
        // 				//

        
        // 				$user->lastvisit = time();

        
        // 				$user->save();

        
        // 				$user->isAuthenticated();

        
        // // 				debug(Yii::app()->session);

        
        // 				$user->saltkey = null;

        
        // 				$user->password = null;

        
        // 				Yii::app()->user->setState('model', $user);

        
        // 				// ----------------------------------------

        
        // // 				Yii::app()->request->redirect($url);

        
        // 				//return true;

        
        // 			}

        
        // 			//

        
        // 		}

        
    }
}

<?php


namespace \API\Component;


/**
 * This component is used to support api access token
 *
 * @author binhqd <binhqd@gmail.com>
 * @namespace \API\Component
 * @version 1.0
 */
class ApiAccess extends CApplicationComponent
{

    /**
     * @var string duration of token
     */
    public static $duration = '+30 days';
    public static $version = API_VERSION;
    public static $headers = array();
    public static $session = array();
    public static $method = "GET";

    /**
     * @var string path for store token
     */
    public static $prefix = 't_';
    public static $access_token = "";
    private static $_parts = array();

    /**
     * Token key
     */
    const TOKEN_KEY = 'API_ACCESS_TOKEN';

    /**
     * This method is used to get expire time of token
     *
     * @return integer expire time
     */
    public static function expiresAt()
    {
        $duration = static ::$duration;
        if (!is_numeric($duration)) {
            $duration = strtotime($duration) - time();
        }
        return intval(time() + $duration);
    }
    public static function setParts($parts)
    {
        $arrParts = explode(",", $parts);
        self::$_parts = array_map("trim", $arrParts);
    }
    public static function getParts()
    {
        return self::$_parts;
    }

    /**
     * This method is used to request token
     */
    public static function requestToken()
    {
        if (!empty($_GET['cookie-less']) || !isset($_SERVER['HTTP_ACCESS_TOKEN'])) {
            return null;
        }
        return $_SERVER['HTTP_ACCESS_TOKEN'];
    }

    /**
     * This method is used to clear current token
     */
    public static function clearCurrentToken()
    {
        $token = Yii::app()->session[self::TOKEN_KEY];
        if (!$token) {
            $token = static ::requestToken();
        }
        if ($token) {
            Yii::app()->cache->delete(static ::$prefix . $token);
        }
    }

    /**
     * This method is used to validate code
     */
    public static function validateCode($User, $token)
    {
        return md5($User->password . '.' . $User->saltkey . '.' . $token);
    }

    /**
     * This method is used to generate token
     */
    public static function generate()
    {
        if (currentUser()->isGuest) {
            $message = Yii::t("Youlook", "You need to login");
            throw new Exception($message, 401);
        }
        $currentUser = currentUser();
        $User = ZoneUser::model()->findByPk($currentUser->id);
        $data = array(
            'ip' => $_SERVER['REMOTE_ADDR'],

            //'session_id'	=> Yii::app()->session->sessionID,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'user_id' => $currentUser->hexID,
            'expires_at' => self::expiresAt() ,

            //'code'			=> self::validateCode($User, )
            
        );
        static ::clearCurrentToken();
        $token = preg_replace('/[^a-z0-9]/i', '', base64_encode(md5(serialize($data))));
        $data['code'] = self::validateCode($User, $token);
        static ::_setTokenData($token, $data);
        return $token;
    }

    /**
     * This method is used to set token data
     */
    protected static function _setTokenData($token, $data)
    {
        $data['session_id'] = Yii::app()->session->sessionID;
        Yii::app()->cache->set(static ::$prefix . $token, $data);
    }

    /**
     * This method is used to parse header information
     * @param unknown_type $headers
     */
    public static function parse($headers)
    {
        if (isset($headers[self::TOKEN_KEY])) {
            self::$access_token = $headers[self::TOKEN_KEY];
        }
    }
    public static function check()
    {
        $headers = getallheaders();
        self::parse($headers);
        self::$headers = $headers;

        // Set version information
        if (isset($headers['API-Version'])) {
            self::$version = $headers['API-Version'];
        }
        self::$method = strtoupper($_SERVER['REQUEST_METHOD']);

        // if access token is given, check if access token is a valid one
        if (!empty(self::$access_token)) {
            $data = Yii::app()->cache->get(static ::$prefix . self::$access_token);
            if (!empty($data) && time() < $data['expires_at'] /*&& $_SERVER['REMOTE_ADDR'] == $data['ip']*/
) {
                self::$session = $data;

                // 				@session_id(ApiAccess::$session['session_id']);
                Yii::app()->session->sessionID = ApiAccess::$session['session_id'];
                Yii::app()->session->open();
                if (currentUser()->isGuest) {
                    $User = ZoneUser::model()->findByPk(IDHelper::uuidToBinary($data['user_id']));
                    if (static ::validateCode($User, self::$access_token) == @$data['code']) {
                        $User->forceLogin();
                        static ::_setTokenData(self::$access_token, $data);
                    }
                }
            }
        }

        // parse part query
        $parts = Yii::app()->request->getParam('part');
        self::setParts($parts);
    }

    /**
     * This method is used to prevent all disallowed methods
     * @param string $methods
     */
    public static function allow($strMethods)
    {
        $methods = explode(",", strtoupper($strMethods));
        array_map("trim", $methods);
        if (!in_array(self::$method, $methods)) {
            $message = Yii::t("Youlook", "Invalid request method. This action only allow these methods: {methods}", array(
                '{methods}' => $strMethods
            ));
            Yii::app()->response->send(405, array() , $message);
        }
    }
}

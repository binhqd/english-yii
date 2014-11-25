<?php


/**
 * Component GNApiController
 *
 * @author LocND <locnd@greenglobal.vn>
 * @author huytbt <huytbt@gmail.com> (updated)
 * @version 1.0
 */
class GNApiController extends GNController
{

    /**
     * define constant http methods
     */
    const HTTP_METHOD_GET = 'GET';
    const HTTP_METHOD_POST = 'POST';
    const HTTP_METHOD_PUT = 'PUT';
    const HTTP_METHOD_DELETE = 'DELETE';

    /**
     * @var string HTTP Method of current request
     */
    private $_currentRequestHttpMethod = 'GET';

    /**
     * @var array HTTP Status Codes (referer http://www.restapitutorial.com/httpstatuscodes.html)
     */
    private $_httpStatusCodes = array(
        '100' => 'Continue',
        '101' => 'Switching Protocols',
        '102' => 'Processing (WebDAV)',
        '200' => 'OK',
        '201' => 'Created',
        '202' => 'Accepted',
        '203' => 'Non-Authoritative Information',
        '204' => 'No Content',
        '205' => 'Reset Content',
        '206' => 'Partial Content',
        '207' => 'Multi-Status (WebDAV)',
        '208' => 'Already Reported (WebDAV)',
        '226' => 'IM Used',
        '300' => 'Multiple Choices',
        '400' => 'Bad Request',
        '401' => 'Unauthorized',
        '402' => 'Payment Required',
        '403' => 'Forbidden',
        '404' => 'Not Found',
        '405' => 'Method Not Allowed',
        '406' => 'Not Acceptable',
        '407' => 'Proxy Authentication Required',
        '408' => 'Request Timeout',
        '409' => 'Conflict',
        '500' => 'Internal Server Error',
        '501' => 'Not Implemented',
        '502' => 'Bad Gateway',
        '503' => 'Service Unavailable',
        '504' => 'Gateway Timeout',
    );

    /**
     * This method is used to construct controller
     *
     * @author huytbt <huytbt@gmail.com>
     * @version 1.0
     */
    public function __construct($id = null, $module = null)
    {
        parent::__construct($id, $module);
        ApiAccess::check();
    }

    /**
     * Support dummy api
     */
    protected function createActionFromMap($actionMap, $actionID, $requestActionID, $config = array())
    {
        // Support dummy data
        if (!empty($_GET['dummy']) && isset($actionMap[$actionID])) {
            $jsonFile = $actionID;
            if (isset($actionMap[$actionID]['class'])) {
                $jsonFile = $actionMap[$actionID]['class'];
                $jsonFile = str_replace(array('api_app.modules.', '.php'), '' , $jsonFile);
            }
            $jsonFile .= '.json';
            $jsonFile = Yii::getPathOfAlias('webroot') . '/dummy/' . $jsonFile;
            if (file_exists($jsonFile)) {
                $_GET['dummyFile'] = $jsonFile;
                $actionMap[$actionID] = 'api_app.components.ZoneDummyAction';
            }
        }
        return parent::createActionFromMap($actionMap, $actionID, $requestActionID, $config);
    }

    /**
     * This method is define api method type
     *
     * @author huytbt <huytbt@gmail.com>
     * @version 1.0
     */
    public function httpMethodActions()
    {
        return array();
    }

    /**
     * This method is used to allow guest access action
     *
     * @author huytbt <huytbt@gmail.com>
     * @version 1.0
     * @return string actions allow access from guest
     */
    public function allowGuestAccessActions()
    {
        return '';
    }

    /**
     * This method is used to filter action
     *
     * @author huytbt <huytbt@gmail.com>
     * @version 1.0
     */
    public function filters()
    {
        return CMap::mergeArray(parent::filters() , array(

            //'checkHttpMethodsActions',
            
            //'checkGuestAccessActions',

            
        ));
    }

    /**
     * This method is used to check HTTP Methods actions
     *
     * @author huytbt <huytbt@gmail.com>
     * @version 1.0
     */
    public function filterCheckHttpMethodsActions($filterChain)
    {
        $actionName = Yii::app()->controller->action->id;
        $methodRequest = $this->_getMethodOfAction($actionName);
        $currentMethod = $_SERVER['REQUEST_METHOD'];
        if ($currentMethod !== $methodRequest) {
            $this->sendResponse(405);
        } else {
            $this->_currentRequestHttpMethod = $currentMethod;
            $filterChain->run();
        }
    }

    /**
     * This method is used to check HTTP Methods actions
     *
     * @author huytbt <huytbt@gmail.com>
     * @version 1.0
     */
    public function filterCheckGuestAccessActions($filterChain)
    {
        $actionName = Yii::app()->controller->action->id;
        $allowActions = $this->allowGuestAccessActions();
        if ($allowActions == '*') {
            $filterChain->run();
            return;
        } else {
            $actions = explode(',', $allowActions);
            foreach ($actions as $action) {
                $action = trim($action);
                if ($action == $actionName) {
                    $filterChain->run();
                    return;
                }
            }
        }
        $currentUser = currentUser();
        if ($currentUser->isGuest) {
            $this->sendResponse(401, array(
                'message' => 'This request requires login',
                'loginUrl' => GNRouter::createUrl('/api/user/login') ,
            ));
        }
        $filterChain->run();
    }

    /**
     * This method is used to get param
     *
     * @author huytbt <huytbt@gmail.com> (updated)
     * @version 1.0
     * @param string $paramName name of param
     * @param string $method method
     */
    public function getParam($paramName, $method = null, $defaultValue = null)
    {
        if (empty($method)) {
            $method = $this->_currentRequestHttpMethod;
        }
        $params = $this->getParams($method);
        if (is_null($defaultValue)) {
            if (!isset($params[$paramName]) || empty($params[$paramName])) {
                $this->sendResponse(400, array() , "Missing param $paramName.");
            }
        } else {
            if (!isset($params[$paramName]) || empty($params[$paramName])) {
                return $defaultValue;
            }
        }
        return $params[$paramName];
    }

    /**
     * This method is used to get all params
     *
     * @author huytbt <huytbt@gmail.com> (updated)
     * @version 1.0
     * @param string $method method
     */
    public function getParams($method = null)
    {
        if (empty($method)) {
            $method = $this->_currentRequestHttpMethod;
        }
        if ($method == self::HTTP_METHOD_PUT || $method == self::HTTP_METHOD_DELETE) {
            $params = Yii::app()->request->getRestParams();
        } elseif ($method == self::HTTP_METHOD_POST) {
            $params = $_POST;
        } elseif ($method == self::HTTP_METHOD_GET) {
            $params = $_GET;
        }
        return $params;
    }

    /**
     * This method is used to get param from GET
     *
     * @author huytbt <huytbt@gmail.com> (updated)
     * @version 1.0
     * @param string $paramName name of param
     */
    public function getGetParam($paramName, $defaultValue = null)
    {
        return $this->getParam($paramName, self::HTTP_METHOD_GET, $defaultValue);
    }

    /**
     * This method is used to get param from POST
     *
     * @author huytbt <huytbt@gmail.com> (updated)
     * @version 1.0
     * @param string $paramName name of param
     */
    public function getPostParam($paramName, $defaultValue = null)
    {
        return $this->getParam($paramName, self::HTTP_METHOD_POST, $defaultValue);
    }

    /**
     * This method is used to get param from PUT
     *
     * @author huytbt <huytbt@gmail.com> (updated)
     * @version 1.0
     * @param string $paramName name of param
     */
    public function getPutParam($paramName, $defaultValue = null)
    {
        return $this->getParam($paramName, self::HTTP_METHOD_PUT, $defaultValue);
    }

    /**
     * This method is used to get param from DELETE
     *
     * @author huytbt <huytbt@gmail.com> (updated)
     * @version 1.0
     * @param string $paramName name of param
     */
    public function getDeleteParam($paramName, $defaultValue = null)
    {
        return $this->getParam($paramName, self::HTTP_METHOD_DELETE, $defaultValue);
    }

    /**
     * This method is used to response
     *
     * @author LocND <locnd@greenglobal.vn>
     * @author huytbt <huytbt@gmail.com> (updated)
     * @version 1.0
     * @param integer $httpStatusCode HTTP Status Code for response
     * @param array $data Data for response (optional)
     * @param string $message message response with error code (optional)
     * @param boolean $exit exit after reponse or not (optional)
     */
    public function sendResponse($httpStatusCode, $data = array() , $message = '', $exit = false)
    {
        $status_header = 'HTTP/1.1 ' . $httpStatusCode . ' ' . $this->_httpStatusCodes[$httpStatusCode];
        header($status_header);
        if ($message == '') {
            $message = $this->_httpStatusCodes[$httpStatusCode];
        }
        $response = array(
            'meta' => array(
                'code' => $httpStatusCode,
                'message' => $message,
                'version' => "2.0",
                'encoding' => 'UTF-8',
            ) ,
        );
        if (!empty($data)) {
            $response['data'] = $data;
        }
        $response['time'] = time();
        jsonOut($response, $exit);
    }

    /**
     * This methid is used to get method of action
     *
     * @author huytbt <huytbt@gmail.com>
     * @version 1.0
     * @param string $actionName name of action for get method
     */
    private function _getMethodOfAction($actionName)
    {
        $rows = $this->httpMethodActions();
        foreach ($rows as $strActions => $method) {
            if (strpos($strActions, $actionName) === false) continue;
            $actions = explode(',', $strActions);
            foreach ($actions as $action) {
                $action = trim($action);
                if ($action == $actionName) return $method;
            }
        }

        // return default method
        return self::HTTP_METHOD_GET;
    }
}

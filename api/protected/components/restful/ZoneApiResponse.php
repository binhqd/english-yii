<?php


/**
 *
 * @author BinhQD
 *
 */
class ZoneApiResponse extends CApplicationComponent
{
    private static $_httpStatusCodes = array(
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
        '304' => 'Not Modified',
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
     * This method is used to response api result to client
     * @author LocND <locnd@greenglobal.vn>
     * @param int $httpStatusCode
     * @param array $data
     * @param string $message
     * @param array $headers
     * @param boolean $exit
     */
    public function send($httpStatusCode, $data = array() , $message = '', $customHeaders = array() , $exit = false)
    {
        $headers = array(
            'Cache-Control' => "private, post-check=0, pre-check=0, must-revalidate",

            // 			'Expires'		=> gmdate('D, d M Y H:i:s \G\M\T', time() + 3600),
            
            // 			'Last-Modified'	=> gmdate('D, d M Y H:i:s \G\M\T', strtotime("2014-05-30 16:32:12"))

            
        );
        if (isset($data['Etag'])) {
            $headers["ETag"] = $data['Etag'];
        }
        header_remove("Pragma");
        header_remove("X-Powered-By");
        header_remove("Set-Cookie");
        header_remove("Keep-Alive");
        header_remove("Server");
        header('Vary: Accept');
        if (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
            header('Content-type: application/json');
        } else {
            header('Content-type: application/json');
        }

        // Add headers
        foreach ($headers as $key => $value) {
            header("{$key}: {$value}");
        }
        if (strtoupper(ApiAccess::$method) == "OPTIONS") {
            $responseCode = 200;
            $status_header = 'HTTP/1.1 ' . $responseCode . ' ' . self::$_httpStatusCodes[$responseCode];
            header($status_header);
            die;
        } else if ( // if isset ETag and Etag equal with If-None-Match

        isset(ApiAccess::$headers['If-None-Match']) && isset($data['Etag']) && $data['Etag'] == ApiAccess::$headers['If-None-Match']) {
            $responseCode = 304;
            $status_header = 'HTTP/1.1 ' . $responseCode . ' ' . self::$_httpStatusCodes[$responseCode];
            header($status_header);
            die;
        } else {
            if ($message == '' && isset(self::$_httpStatusCodes[$httpStatusCode])) {
                $message = self::$_httpStatusCodes[$httpStatusCode];
            }
            $response = array(
                'meta' => array(
                    'code' => $httpStatusCode,
                    'message' => $message,
                    'version' => API_VERSION,
                    'encoding' => 'UTF-8',
                ) ,
            );
            if (!empty($data)) {
                $response['data'] = $data;
            }
            $response['time'] = date(DATE_ISO8601, time());
        }
        $obj = @CJSON::encode($response);
        if (!empty($customHeaders)) {
            foreach ($customHeaders as $key => $value) {
                header("{$key}: {$value}");
            }
        }
        $isAcceptGzipEncoding = stripos($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip");
        if ($isAcceptGzipEncoding >= 0) {

            //
            $gzContent = gzencode($obj, 5);
            if ($gzContent) {
                header('Content-Encoding: gzip');
                header('Vary: Accept-Encoding');
                header("Content-Length: " . strlen($gzContent));
                echo $gzContent;
                @ob_end_flush();
            } else {
                if ($isAcceptGzipEncoding !== false) {
                    header('Content-Encoding: gzip');
                    header('Vary: Accept-Encoding');
                    ob_start("ob_gzhandler");
                } else {
                    ob_start();
                }
                echo $obj;
                $size = ob_get_length();

                //ob_end_flush();
                header("Content-Length: {$size}");
                @ob_end_flush();
                @ob_flush();
            }
        } else {
            header("Content-Length: " . strlen($obj));
            echo $obj;
        }
        @flush();
        if (YII_DEBUG) exit();
        else Yii::app()->end();
    }
}

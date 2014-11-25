<?php


/**
 * Resource Format
 * @author huytbt <huytbt@gmail.com>
 */
class ZoneApiResourceFormat
{
    public static $resourceFormats = array(
        'user' => array(
            'id' => array('string','id') ,
            'username'      => array('string','username') ,
            'firstname'     => array('string','firstname') ,
            'lastname'      => array('string','lastname') ,
            'displayname'   => array('string','displayname') ,
            'email'         => array('string','email') ,
            'location'      => array('string',array('profile','location')) ,
            'latitude'      => array('int',array('profile','lat')) ,
            'longitude'     => array('int',array('profile','lon')) ,
            'avatar'        => array('string',array('profile','image')) ,
            'birthday'      => array('string',array('profile','birth')) ,
        ) ,
    );

    /**
     * Format data
     */
    public static function formatData($resurceName, $data)
    {
        if (!isset(self::$resourceFormats[$resurceName])) {
            throw new Exception('Error Development: Resource name is invalid', 500);
        }
        $formatFields = self::$resourceFormats[$resurceName];
        $newData = array();
        foreach ($formatFields as $field => $fromField) {
            $type = $fromField[0];
            $from = $fromField[1];
            if (is_array($from)) {
                if (isset($data[$from[0]][$from[1]])) {
                    if ($type == 'int') {
                        $newData[$field] = (int) $data[$from[0]][$from[1]];
                    } else {
                        $newData[$field] = $data[$from[0]][$from[1]];
                    }
                } else {
                    if ($type == 'string') {
                        $newData[$field] = '';
                    } elseif ($type == 'int') {
                        $newData[$field] = 0;
                    } elseif ($type == 'array') {
                        $newData[$field] = array();
                    }
                }
            } else {
                if (isset($data[$from])) {
                    if ($type == 'int') {
                        $newData[$field] = (int) $data[$from];
                    } else {
                        $newData[$field] = $data[$from];
                    }
                } else {
                    if ($type == 'string') {
                        $newData[$field] = '';
                    } elseif ($type == 'int') {
                        $newData[$field] = 0;
                    } elseif ($type == 'array') {
                        $newData[$field] = array();
                    }
                }
            }
        }
        return $newData;
    }
}

<?php

/**
 * ZoneDummyAction
 *
 * @author huytbt <huytbt@gmail.com>
 */
class ZoneDummyAction extends CAction
{
    /**
     * Run action
     *
     * @return void
     */
    public function run()
    {
        if (!isset($_GET['dummyFile']))
            return;
        $content = file_get_contents($_GET['dummyFile']);

        try {
            $arrContent = json_decode($content, true);
            if (is_array($arrContent)) {
                if (isset($arrContent['meta']) && isset($arrContent['data'])) {
                    $metaCode = $arrContent['meta']['code'];
                    $metaMssage = $arrContent['meta']['message'];
                    $data = $arrContent['data'];
                    Yii::app()->response->send($metaCode, $data, $metaMssage);
                    exit;
                }
            }
        } catch (Exception $ex) {
        }

        ob_clean();
        header('content-type:application/json');
        echo $content;
        exit;
    }
}

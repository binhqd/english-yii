<?php

/**
 * General Validator
 */
class ApiGeneralValidator extends CComponent
{
    /**
     * Validate pagination
     *
     * @param [type] $limit limit
     * @param [type] $total total
     *
     * @return [type]
     *
     * @author HuyTBT <huytbt@gmail.com>
     */
    public static function validatePages($limit, $total)
    {
        $pages = new ApiPagination($total);
        $currentPage = isset($_GET[$pages->pageVar]) ? $_GET[$pages->pageVar] : 1;

        if (($limit != (string)(int)$limit) || ($currentPage != (string)(int)$currentPage) || ($limit < 0)) {
            throw new Exception(null, 400);
        }
        if ($limit == 0) {
            $pages->pageSize = $pages->itemCount;
        } else {
            $pages->pageSize = $limit;
        }
        $pages->climit = $limit;
        $pages->currentPage = $currentPage;
        if ($currentPage <= 0 || $currentPage > $pages->pageCount) {
            Yii::app()->response->send(200, array('items' => array(), 'pages' => $pages->toArray()));
        }
        return $pages;
    }
}

/**
 * ApiPagination
 */
class ApiPagination extends CPagination
{
    public $climit;
    public $currentPage;

    /**
     * to array
     *
     * @return [type]
     *
     * @author HuyTBT <huytbt@gmail.com>
     */
    public function toArray()
    {
        return array(
            'total' => (int)$this->itemCount,
            'limit' => (int)$this->climit,
            'pages' => (int)$this->currentPage,
        );
    }
}

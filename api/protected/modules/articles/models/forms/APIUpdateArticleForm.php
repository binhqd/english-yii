<?php
/**
 * Update Article Form
 * @author truonghn
 * @version 1.0
 */
class APIUpdateArticleForm extends CFormModel
{
	/**
	 * @var string title
	 */
	public $title;
	/**
	 * @var string article id
	 */
	public $article_id;
	/**
	 * @var string content
	 */
	public $content;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('article_id', 'required'),
			array('title, content', 'required'),
		);
	}
}
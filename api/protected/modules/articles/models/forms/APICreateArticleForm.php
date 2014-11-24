<?php
/**
 * Create Article Form
 * @author truonghn
 * @version 1.0
 */
class APICreateArticleForm extends CFormModel
{
	/**
	 * @title string title
	 */
	public $title;
	/**
	 * @content string content
	 */
	public $content;
	/**
	 * @description string description
	 */
	public $description;

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('title', 'required','message'=>Yii::t('Youlook', 'Title is required')),
			array('content', 'required', 'message'=>Yii::t('Youlook', 'Content is required')),
		);
	}
}
<?php
/**
 * This controller is used to manage destinations module
 *
 * @author thanhngt
 * @version 1.0
 * @created 2013-03-20 07:59:55
 */
Yii::import('application.modules.articles.models.ArticleImage');
class ImageController extends GNController
{
	public function allowedActions()
	{
		return '*';
	}
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/admin', meaning
	 * using two-column layout. See 'protected/views/layouts/admin.php'.
	 */
	public $layout = '//layouts/admin';
	
	public function actions()
	{
		return array(
			'upload'	=> array(
				'class'			=> 'greennet.extensions.GNUploader.actions.GNUploadAction',
				'model'			=> 'ArticleImage',
				'fieldName'		=> 'filename',
				'uploadPath'	=> '/upload/article-photos/'
			),
			'delete'	=> array(
				'class'			=> 'greennet.modules.gallery.actions.GNDeleteGalleryItemAction',
				'model'			=> 'ArticleImage',
				'uploadPath'	=> '/upload/article-photos/'
			)
		);
	
	}

}

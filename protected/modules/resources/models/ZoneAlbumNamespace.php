<?php
Yii::import('application.modules.articles.models.*');
class ZoneAlbumNamespace extends GNActiveRecord {
	
	const	DATA_STATUS_NORMAL = 1;
	const	DATA_STATUS_DELETED = 0;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param $className
	 * @return GNUser the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'zone_albums_namespaces';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
					
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'album' => array(self::BELONGS_TO, 'ZoneResourceAlbum', 'album_id'),
			//'node' => array(self::BELONGS_TO, 'ZoneUser', 'holder_id'),
		);
	}
	
// 	public function behaviors()
// 	{
// 		return array(
			
// 		);
// 	}
	/**
	 * This method is used to hide album
	 */
	public function hideAlbumNamespace($binAlbumID=null){
		$criteria = new CDbCriteria();
		$criteria->condition = 'album_id=:album_id';
		$criteria->params = array(
			':album_id'=>$binAlbumID
		);
		$modelAlbumNamespaces = self::model()->findAll($criteria);
		
		if(!empty($modelAlbumNamespaces)){
			
			$transaction = Yii::app()->db->beginTransaction();
			try {
				foreach($modelAlbumNamespaces as $modelAlbumNamespace){
					$modelAlbumNamespace->data_status = self::DATA_STATUS_DELETED;
					$modelAlbumNamespace->save();
				}
				$transaction->commit();
				return true;
				
			} catch (Exception $e) {
				$transaction->rollBack();
				return false;
			}
		}return false;
	}
}
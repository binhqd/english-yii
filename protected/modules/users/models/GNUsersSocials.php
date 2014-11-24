<?php
/**
 * GNUsersSocials - This model is used to process the data on the table userbase_users_socials
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 26-Jan-2013 10:32:21 AM
 * @modified 29-Jan-2013 11:09:18 AM
 */
class GNUsersSocials extends JLActiveRecord
{
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User_Socials the static model class
	 */
	public static function model($className=__CLASS__) {
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'userbase_users_socials';
	}
	
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
				array('id, user_id, social_id', 'required'),
				array('id, user_id, social_id', 'length', 'max'=>16),
				array('social_alias', 'length', 'max'=>48),
				array('social_account_id', 'length', 'max'=>32),
				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('id, user_id, social_id, social_alias, social_account_id', 'safe', 'on'=>'search'),
		);
	}
	
	/**
	 * @return array relational rules.
	 */
	public function relations() {
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'user' => array(self::BELONGS_TO, 'UserbaseUsers', 'user_id'),
				'social' => array(self::BELONGS_TO, 'UserbaseSocials', 'social_id'),
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels() {
		return array(
				'id' => 'ID',
				'user_id' => 'User',
				'social_id' => 'Social',
				'social_alias' => 'Social Alias',
				'social_account_id' => 'Social Account',
		);
	}
	
	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search() {
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.
	
		$criteria=new CDbCriteria;
	
		$criteria->compare('id',$this->id,true);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('social_id',$this->social_id,true);
		$criteria->compare('social_alias',$this->social_alias,true);
		$criteria->compare('social_account_id',$this->social_account_id,true);
	
		return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
		));
	}
	
	/**
	 * This method is used to get user from social account id
	 *
	 * @param strSocialAccountId    String ID of social account
	 */
	public function findUserBySocialAccountId($strSocialAccountId) {
		if ($strSocialAccountId) {
			$criteria = new CDbCriteria();
			$criteria->condition = 'social_account_id = :binSocialAccountId';
			$criteria->params = array(
					':binSocialAccountId' => $strSocialAccountId
			);
		
			$modelSocialAccount = self::model()->findAll($criteria);
		
			if (!empty($modelSocialAccount)) {
				return  $modelSocialAccount;
			}
		}
		
		return array();
	}

	/**
	 * This method is used to get socials from user id
	 *
	 * @param binUserId    Binary of user id
	 */
	public function findSocialsByUserId($binUserId) {
		if ($binUserId) {
			$criteria = new CDbCriteria();
			$criteria->condition = 'user_id = :binUserId';
			$criteria->params = array(
					':binUserId' =>	$binUserId
			);
				
			$modelSocial = self::model()->findAll($criteria);
				
			if (!empty($modelSocial)) {
				return  $modelSocial;
			}
		}
		
		return array();
	}

	/**
	 * This method is used to map user with social account
	 *
	 * @param binUserId    Binary of User ID
	 * @param strSocialId    String of social account
	 * @param strSocialAlias    String of social alias
	 */
	public function mapUserWithSocial($binUserId, $strSocialId, $strSocialAlias) {
		if (isset($binUserId) && isset($strSocialId) && isset($strSocialAlias)) {
			$modelUserSocial = new GNUsersSocials();
			$modelUserSocial->user_id = $binUserId;
			$modelUserSocial->social_id = $strSocialId;
			$modelUserSocial->social_alias = $strSocialAlias;
				
			$saveUserSocial = $modelUserSocial->save();
				
			if ($saveUserSocial) {
				return true;
			} else {
				$_error	= $saveUserSocial->errors;
				if (empty($_error)) {
					throw new Exception("Error : Can't save user social. Please try again !");
				} else {
					list($field, $_err) = each($_error);
					throw new Exception("Can't created new user social, because : ".$_err[0]);
				}
			}
		}
		
		return false;
	}

	/**
	 * This method is used to disconnect mapping
	 *
	 * @param binUserId    Binary of User ID
	 * @param strSocialAccountId    String of social account ID
	 * @param strSocialAlias    String of social alias
	 */
	public function disconnectMapping($binUserId, $strSocialAlias) {
		if (isset($binUserId) && isset($strSocialAlias)) {
			$cdbCriteria = new CDbCriteria();
			$cdbCriteria->condition = 'user_id = :binUserId and social_alias = :strSocialAlias';
			$cdbCriteria->params = array(
				':binUserId'		=> $binUserId,
				':strSocialAlias'	=> $strSocialAlias
			);
			
			$modelSocial = self::model()->find($cdbCriteria);
			$disconnectMapping = $modelSocial->delete();
			
			if ($disconnectMapping) {
				return true;
			} else {
				$_error	= $disconnectMapping->errors;
				if (empty($_error)) {
					throw new Exception("Error : Can't disconnect user social. Please try again !");
				} else {
					list($field, $_err) = each($_error);
					throw new Exception("Can't disconnect user social, because : ".$_err[0]);
				}
			}
		}
		
		return false;
	}

}
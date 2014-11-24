<?php
/**
 * Widget is used to register library JLBD
 *
 * @author Thanh Huy
 * @version 1.0
 * @created 2013-02-01 4:15 PM
 */
class JLBDLibrary extends CApplicationComponent
{
	public $plugins = array();
	private $_plugins = array();

	/**
	 * This method is used to initial component
	 */
	public function init()
	{
		$this->installPlugins($this->plugins);
		parent::init();
	}

	/**
	 * This method is used to call plugin
	 */
	public function __get($attribute)
	{
		if (array_key_exists($attribute, $this->_plugins))
			return $this->_plugins[$attribute];
		throw new CException('Plugin "'.$attribute.'" is not install.');
	}

	/**
	 * This method is used to register script, css
	 */
	public function register()
	{
		GNAssetHelper::init(array(
			'image'		=> 'img',
			'css'		=> 'css',
			'script'	=> 'js',
		));

		GNAssetHelper::setBase('application.components.jlbd.assets');
		GNAssetHelper::scriptFile('jlbd', CClientScript::POS_HEAD);
		GNAssetHelper::scriptFile('jlbd.user', CClientScript::POS_HEAD);

		// jlbd.user
		if (!currentUser()->isGuest) {
			$arrUser = currentUser()->toArray(true);
		} else {
			$arrUser = ZoneUser::model()->toArray(true);
		}
		
		$script = "
			if ((typeof jlbd != 'undefined' && jlbd !== null) && (typeof jlbd.user != 'undefined' && jlbd.user !== null)) {
				var user = ".CJSON::encode($arrUser).";
				var currentUser = new jlbd.user.Libs.GNUser(user.id, user);
				jlbd.user.collection.current.user = currentUser;
			}
		";
		GNAssetHelper::registerScript('createUserObject', $script, CClientScript::POS_BEGIN);

		foreach ($this->_plugins as $plugin => $object) {
			if (method_exists($object, 'register')) $object->register();
		}

		if (isset(Yii::app()->session['jlbd_command'])) {
			GNAssetHelper::registerScript(__CLASS__, Yii::app()->session['jlbd_command'], CClientScript::POS_READY);
			unset(Yii::app()->session['jlbd_command']);
		}
	}

	/**
	 * This method is used to call script
	 */
	public function callScript($strCommand)
	{
		Yii::app()->session['jlbd_command'] = $strCommand;
	}

	/**
	 * This method is used to install plugins
	 */
	public function installPlugins($arrPlugins)
	{
		foreach ($arrPlugins as $plugin => $class) {
			if (array_key_exists($plugin, $this->_plugins)) continue;
			Yii::import($class);
			$arrPaths = explode('.', $class);
			$className = $arrPaths[count($arrPaths) - 1];
			$this->_plugins[$plugin] = new $className;
		}
	}
}
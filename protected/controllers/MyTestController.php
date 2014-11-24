<?php
use Everyman\Neo4j\Client,
Everyman\Neo4j\Index\NodeIndex,
Everyman\Neo4j\Relationship,
Everyman\Neo4j\Node,
Everyman\Neo4j\Cypher;

class MyTestController extends JLController
{
	//public $layout = '//layouts/backend';
	public $layout = '//layouts/dashboard';

	public function allowedActions() {
		return '*';
	}
	
	public function actionTestLayout() {
		$this->layout = "ajax";
	}
	
	public function actionIndex() {
		Yii::import('greennet.helpers.Sluggable');
		
		$email = 'bì-hồnhqd@gmail.com';
		$email = Sluggable::slug($email);
		$username = preg_replace("/@/", '.', $email);
		$username = preg_replace("/(\.[a-z0-9]+)$/", '', $username);
		echo $username;exit;
	}
	public function actionTestNeo4j() {
		
		
		$client = new Client('192.168.1.110', 7478);
		$actors = new NodeIndex($client, 'actors');
		
		$keanu = $client->makeNode()->setProperty('name', 'Keanu Reeves')->save();
		$laurence = $client->makeNode()->setProperty('name', 'Laurence Fishburne')->save();
		$jennifer = $client->makeNode()->setProperty('name', 'Jennifer Connelly')->save();
		$kevin = $client->makeNode()->setProperty('name', 'Kevin Bacon')->save();
		
		$actors->add($keanu, 'name', $keanu->getProperty('name'));
		$actors->add($laurence, 'name', $laurence->getProperty('name'));
		$actors->add($jennifer, 'name', $jennifer->getProperty('name'));
		$actors->add($kevin, 'name', $kevin->getProperty('name'));
		
		$matrix = $client->makeNode()->setProperty('title', 'The Matrix')->save();
		$higherLearning = $client->makeNode()->setProperty('title', 'Higher Learning')->save();
		$mysticRiver = $client->makeNode()->setProperty('title', 'Mystic River')->save();
		
		$keanu->relateTo($matrix, 'IN')->save();
		$laurence->relateTo($matrix, 'IN')->save();
		
		$laurence->relateTo($higherLearning, 'IN')->save();
		$jennifer->relateTo($higherLearning, 'IN')->save();
		
		$laurence->relateTo($mysticRiver, 'IN')->save();
		$kevin->relateTo($mysticRiver, 'IN')->save();
	}
	
	public function actionVu() {
		$this->layout = '//layouts/master/myzone';
		
		$this->render('vu');
	}
	
	public function actionAddArticle() {
		$this->layout = '//layouts/myzone/default';
		$this->render('add-article');
	}
	
	public function actionTestCache() {
		$photoID = "91785a8a0b3e72996243c4d27f99e1d4";
		$albumID = "51780475f324454f82781840c0a801be";
		
		$photo = ZoneResourceImage::model()->get($photoID, $albumID);
		debug($photo);
	}
	
	public function actionTestCrawl() {
		debug(MigrationManager::crawlImage('51d55587c1e04438a0dc0436ac111364'));
	}
	
	public function actionCountArticles() {
		$count = ZoneArticle::model()->countArticlesByUserID(currentUser()->id);
		debug($count);
	}
	
	public function actionTestUser() {
// 		debug(currentUser());
// 		$user = GNUserProfile::model()->find('user_id=:user_id', array(':user_id' => currentUser()->id));
// 		$user->location = "Da Nang";
// 		$save = $user->save();
		
		
		$userInfo = ZoneUser::model()->get(currentUser()->hexID);
		dump($userInfo);
	}
	
	public function actionTestAvatar() {
		$users = ZoneUser::model()->findAll();
		$cnt = 0;
		//watch($users);
		foreach ($users as $user) {
			$cnt++;
			$user = ZoneUser::model()->getUserInfo($user->id);
			$user->makeDefaultAvatar();
		}
		
		exit("{$cnt} users has changed their avatar");
	}
	
	public function actionCleanUpAvatar() {
		$webroot = Yii::getPathOfAlias("jlwebroot");
		$avatars = ZoneUserAvatar::model()->findAll();
		
		foreach($avatars as $item) {
			$user_id = substr($item->object_id, 7);
			$avatar = "{$webroot}/upload/user-photos/{$user_id}/{$item->image}";
			if (!is_file($avatar)) {
				
				$item->delete();
				watch ("{$item->image} doesn't existed in the server. Removed");
				//return;
			}
		}
	}
	
	public function actionTestPush() {
		$data = array(
			'namespace'	=> 'zonetype-activities-event-all',
			'a'	=> 10,
			'b'	=> 5
		);
		
		Yii::import('application.components.notification.JLNotificationWriter');
		$noti = new JLNotificationWriter();
		$noti->push($data);
	}
	
	public function actionTypeActivities() {
		$related = ZoneNodeRender::getCategories('5260bbdd5ad8421faa7378c2ac1f091f');
		dump($related);
		
	}
	
	public function actionHex() {
		$encode = IDHelper::uuidToBinary('people');
		$decode = IDHelper::uuidFromBinary('people');
		
	}
	
	public function actionErrorLogging() {
		echo 'abc';
		throw new Exception('test log');
	}
}
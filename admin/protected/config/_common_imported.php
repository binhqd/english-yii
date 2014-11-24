<?php
return array(
	'application.models.*',
	'application.components.*',
	'rules.*',
	
	'application.modules.rights.RightsModule',
	'application.modules.rights.models.*',
	'application.modules.rights.components.*',
	
	'application.modules.comments.models.*',
	'application.modules.like.models.*',
	'application.modules.articles.models.*',
	'application.modules.resources.models.*',
	'application.modules.resources.components.*',
	'application.modules.zone.models.*',
	'application.modules.zone.models.mysql.*',
	'application.modules.zone.components.*',
	
	'application.modules.crawler.models.*',
	'application.modules.crawler.components.engine.*',
	'application.modules.crawler.components.*',
	
	'application.modules.users.UsersModule',
	'application.modules.users.models.*',
	'application.modules.users.components.*',
	
	'application.modules.landingpage.models.*',
	'application.modules.landingpage.components.*',
	'application.modules.search.models.*',
	'application.modules.search.components.*',
	'application.modules.activities.models.*',
	'application.modules.activities.components.*',
	
	'application.modules.interest.models.*',
	'application.modules.interest.components.*',
	
	'application.modules.status.models.*',
	'application.modules.status.components.*',
	
	'application.modules.categories.models.*',
	'application.modules.categories.components.*',
	
	
	'application.components.utils.*',
	
	// Neo4Yii
	'application.extensions.EActiveResource.*',
	'application.extensions.Neo4Yii.*',

	// widgets
	'application.components.jl_bd.*',	
	'application.components.jl_bd.helpers.*',	
	'application.components.helpers.*',	

	// error code
	'application.config.error.*',
	
	'greennet.extensions.YiiMongoDbSuite.*',
	'greennet.helpers.*',
		
	'application.extensions.debugtoolbar.*',
	'ext.wunit.*',
	'ext.worker.*',
	'ext.Neo4Yii.*',

	'application.modules.followings.components.behaviors.GNUserFollowingBehavior',
	'application.modules.friends.components.behaviors.GNUserFriendBehavior',

	// apiAuth Extention
	'ext.apiAuth.components.*',
	'ext.EActiveResource.*',
	'application.modules.zonetype.models.*',

	'ext.iwi.Iwi',
	
	'application.modules.api.models.*',
	'application.modules.users.models.Language',
	'application.modules.users.models.ZoneUserProfile',
);
;(function($, scope){
	scope['user'] = {
		collection : {
			items : {},
			add : function(user) {
				this.items[user.id] = user;
			},
			get : function(userID) {
				return this.items[userID] ? this.items[userID] : null; 
			},
			current : {
				user : null
			}
		},
		Libs : {
			GNUser : function(userID, attr) {
				this.id = userID;
				this.attr = attr;

				//this.bizRates = {};
				// this.rate = function(bizID, value) {
				// 	return jlbd.biz.collection.get(bizID).setRate(value);
				// }
				
				// end
				jlbd.user.collection.add(this);
			}
		}
	}
})(jQuery, jlbd);

$(document).ready(function() {
	
});

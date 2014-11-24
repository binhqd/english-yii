var homeURL = '';
;if(window.jQuery) (function($){
	window['jlbd'] = {
		rateURL : homeURL + '/rating/rateBusiness',
		discardDraftReviewURL : homeURL + '/review/discardDraft',
		dashboardReviewURL : homeURL + '/dashboard/review',
		redirect: function(strUrl) {
			window.location = strUrl;
		}
	};
	window['Libs'] = {
		makeid : function(strLength) {
			if (typeof strLength == "undefined") strLength = 5;
			var text = "";
			var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
			for( var i=0; i < strLength; i++ )
				text += possible.charAt(Math.floor(Math.random() * possible.length));
			return text;
		}
	}
})(jQuery);

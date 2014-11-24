$(document).ready(function() {
	var compareData = $.cookie('compareBizInfo');
	compareData = JSON.parse(compareData);

	if(compareData != null) {
		var form = $('#found-info');
		form.find('#name-found').val($.trim(compareData.name)).trigger('change');
		form.find('#phone-found').val($.trim(compareData.phone).replace('(', '').replace(')', '').split(' ').join('')).trigger('change');
		form.find('#address-found').val($.trim(compareData.address)).trigger('change');
		
		$.cookie('compareBizInfo', null , {path: '/'});
	}

});
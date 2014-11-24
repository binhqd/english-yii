// function count minute and replace minute
$(function(){
	var oldDate = new Date();
	setInterval(function(){
		$('.cgn-time-count').each(function(){
			var obj = $(this);
			setDate(obj, oldDate);
		});
		oldDate = new Date();
	},5000);
});

// function set date
function setDate(obj, oldDate){

	var timeNumber = parseInt(obj.find('.cgn-time-number').html());
	var timeVarchar = obj.find('.cgn-time-varchar').html();
	var timeVar = timeVarchar.substring(0,3);
	var newDate = new Date();
	year = newDate.getYear()-oldDate.getYear();
	month = newDate.getMonth()-oldDate.getMonth();
	day = newDate.getDate()-oldDate.getDate();
	hour = newDate.getHours()-oldDate.getHours();
	minute = newDate.getMinutes()-oldDate.getMinutes();
	if (year==1){
		if(timeVar=='yea'){
			replaceStr(obj, timeNumber, 'years');
		} else if(timeVar=='mon') {
			replaceStr(obj, 0, 'year');
		}
		return false;
	} else if (month==1) {
		if(timeVar=='mon'){
			replaceStr(obj, timeNumber, 'months');
		} else if(timeVar=='day') {
			replaceStr(obj, 0, 'month');
		}
		return false;
	} else if (day==1) {
		if(timeVar=='day'){
			replaceStr(obj, timeNumber, 'days');
		} else if(timeVar=='hou'){
			replaceStr(obj, 0, 'day');
		}
		return false;
	} else if (hour==1) {
		if(timeVar=='hou'){
			replaceStr(obj, timeNumber, 'hours');
		} else if(timeVar=='min'){
			replaceStr(obj, 0, 'hour');
		}
		return false;
	} else if (minute==1) {
		if(timeVar=='min'){
			replaceStr(obj, timeNumber, 'minutes');
		} else if(timeVar=='sec'){
			replaceStr(obj, 0, 'minute');
		}
		return false;
	} else return false;
}

// replace
function replaceStr(obj, timeNumber, strVar){
	obj.find('.cgn-time-number').html(timeNumber+1);
	obj.find('.cgn-time-varchar').html(strVar);
}
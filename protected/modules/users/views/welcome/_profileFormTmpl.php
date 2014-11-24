<div class="wd-input">
	<label for="txtskype">SkypeID, YMID, GtalkID</label>
	<input type="text" value="{{if (typeof value['/people/s_id'] != "undefined")}}${value['/people/s_id'][0]['value']}{{/if}}" name="UserNodeInfo[/people/s_id]" id="txtskype" placeholder="Your Messenger ID:"/>
</div>

<div class="wd-input">
	<label for="txtfullname">Full name</label>
	<input type="text" value="{{if (typeof value['/people/fullname'] != "undefined")}}${value['/people/fullname'][0]['value']}{{/if}}" name="UserNodeInfo[/people/fullname]" id="txtfullname" placeholder="Enter Your Full Name:"/>
</div>

<div class="wd-input">
	<label for="txtweb">Web Blog</label>
	<input type="text" value="{{if (typeof value['/people/web_blog'] != "undefined")}}${value['/people/web_blog'][0]['value']}{{/if}}" name="UserNodeInfo[/people/web_blog]" id="txtweb" placeholder="http://"/>
</div>


<div class="wd-input">
	<label for="txtmobile">Mobile</label>
	<input type="text" value="{{if (typeof value['/people/mobile_number'] != "undefined")}}${value['/people/mobile_number'][0]['value']}{{/if}}" name="UserNodeInfo[/people/mobile_number]" id="txtmobile" placeholder="Mobile:"/>
</div>

<div class="wd-input">
	<label for="txtmobile">Birthday:</label>
	<input type="text" value="{{if (typeof value['/people/person/date_of_birth'] != "undefined")}}${value['/people/person/date_of_birth'][0]['value']}{{/if}}" name="UserNodeInfo[/people/person/date_of_birth]" id="txtmobile" placeholder="Enter Your Birthday:"/>
</div>

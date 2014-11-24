<?php 
	GNAssetHelper::init(array(
		'image'		=> 'img',
		'css'		=> 'css',
		'script'	=> 'js',
	));

	GNAssetHelper::setBase('justlook');
	GNAssetHelper::cssFile('user-gallery-photo');
	GNAssetHelper::cssFile('user-gallery-photo-more');
	GNAssetHelper::cssFile('bt-small');
	GNAssetHelper::cssFile('popup-upload-photo');
	GNAssetHelper::cssFile('common-extend');
	GNAssetHelper::cssFile('jquery.fancybox-1.3.4');
	
	GNAssetHelper::scriptFile('jlbd', CClientScript::POS_END);
	GNAssetHelper::scriptFile('jquery.fancybox-1.3.4.pack', CClientScript::POS_END);
	
	GNAssetHelper::setBase('application.modules.admin_manage.assets.manage_user','Manage_User');
	GNAssetHelper::cssFile('admin_manage_user');
	GNAssetHelper::scriptFile('jlbd.manage-photo-clone-admin', CClientScript::POS_END);
	GNAssetHelper::scriptFile('manage.user.edit.profile', CClientScript::POS_END);
	GNAssetHelper::scriptFile('manage.user.changed.password', CClientScript::POS_END);
	GNAssetHelper::scriptFile('manage_user_resendmail', CClientScript::POS_END);
?>
<div style=" float:left; width:95%;margin:0px 20px;" id="manage_monitor_check_action">

	<!-- Gọi Boostrap alert  -->
	<?php $this->widget('bootstrap.widgets.TbAlert', array(
			'block'=>true, // display a larger alert block?
			'fade'=>true, // use transitions?
			'closeText'=>'&times;', // close link text - if set to false, no close link is displayed
			'alerts'=>array( // configurations per alert type
				'success'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				'info'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				'warning'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
				'error'=>array('block'=>true, 'fade'=>true, 'closeText'=>'&times;'), // success, info, warning, error or danger
			)
		)); 
	?>
	
	<!-- Form để thực hiện multi action từ check box -->
	<form action="<?php echo JLRouter::createUrl('/admin_manage/manageUser/monitorAction')?>" method="POST" id="action_monitor_manage_user" style="display: none;">
		<div class="data_check"></div>
		<div class="type_append">
			<input type="text" name="Action_Monitor[type_action]" value="delete"/>
		</div>
		<input type="text" name="Action_Monitor[type_core]" value="<?php echo isset($_GET['type']) ? $_GET['type'] : 'GNUser';?>"/>
	</form>
	<!-- end form -->
	
	<div class="grid-view">
	<div class="manager_monitor admin_manager_search_advanced">
	
		<!-- Tiêu đề và link search -->
		<h1 style="font-size: 18px;">User Monitor</h1>
		<ul class="help_monitor" style="position: absolute;right: 0px;">
			<li><a href="#" class="search_hide" defaultClass="more_categories"><i class="icon-search"></i><span>Advanced Search</span></a></li>
			<li><a href="#" class="show-help-monitor-user"><i class="icon-question-sign"></i><span>Help</span></a></li>
		</ul>
		<!-- end -->
		
		<!-- Mô tả trọe giúp tùy chọn -->
		<div class="manage_user_monitor_help" style="background-color: #dededf;padding:10px;width:480px;position: absolute;right: 103px;border-radius:5px;boder-color:#000;display: none;top: -13px;">
			<h4>Mô tả các kiểu để filter trong Monitor User :  <span style="font-size:12px;"><a href="#" class="close_comment">X - Close</a></span></h4>
			<ol>
				<?php 
					if (!empty($type)) {
						foreach ($type as $key=>$item) {
							echo "<li>";
								echo "<ul style='margin-bottom: 5px;'>";
									echo "<li>Name : {$item['name']}</li>";
									echo "<li>Value : {$item['key']}</li>";
									echo "<li>Description : {$item['description']}</li>";
								echo "</ul>";
							echo "</li>";
						}
					}
				?>
			</ol>
		</div>
		<!-- end -->
		
	<?php $type_show_view_mode = '';?>
	<!-- Form tùy chọn filter dữ liệu -->
	<form name="form_filter_user_monitor" <?php if(!isset($_GET['type'])) {?>style="display: none;"<?php } else {?>style="display: block;"<?php }?> class="well form-search" id="searchForm" action="<?php echo JLRouter::createUrl('admin_manage/manageUser/monitor');?>" method="get"  style="width:98%">
		<fieldset>
		<div class="manager_monitor_left">
			Type
			<!-- Select chọn kiểu dữ liệu hiển thị -->
			<?php if (!empty($type)) :?>
			<select name="type" onChange="document.form_filter_user_monitor.submit()">
				<?php 
				$defaultType = '';
					if (isset($_GET['type']) && $_GET['type']!="") {
						$defaultType = $_GET['type'];
						foreach ($type as $key=>$item) {
							if ($item['key']===$_GET['type']) $defaultName = $item['name'];
						}
				?>
						<option value="<?php echo $_GET['type']?>"><?php echo $defaultName?></option>
				<?php 
					}
					foreach($type as $key=>$value) :
						if ($value['key']!==$defaultType) :
				?>
						<option value="<?php echo $value['key']?>"><?php echo $value['name']?></option>
				<?php
						endif;
					endforeach;
				?>
			</select>
			<?php endif;?>
			<!-- end -->
			
			<!-- Select tùy chọn view mode -->
			<label style="width:70px">View mode</label>
			<select name="filter_give" onChange="document.form_filter_user_monitor.submit()">
				<?php 
					foreach ($viewMode as $type_viewMode) {
						if (isset($_GET['filter_give']) && $_GET['filter_give']===$type_viewMode['value']) {
							echo "<option selected value={$type_viewMode['value']}>{$type_viewMode['name']}</option>";
							$type_show_view_mode = $type_viewMode['name'];
						} else {
							echo "<option value={$type_viewMode['value']}>{$type_viewMode['name']}</option>";
						}
					}
				?>
			</select>
			<!-- end -->
			
			<!-- Form chọn ngày để filter : Ngày bắt đầu -> Ngày kết thúc -->
			<br><br>
			Date
			<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'=>'dateFrom',
				'value'=>$dateFrom,
				// additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat'=>'dd-mm-yy',
				),
				'htmlOptions'=>array(
					'style'=>'height:20px;'
				),
			));
			?>
			<label style="width:70px">To</label>
			<?php
			$this->widget('zii.widgets.jui.CJuiDatePicker', array(
				'name'=>'dateTo',
				'value'=>$dateTo,
				// additional javascript options for the date picker plugin
				'options'=>array(
					'showAnim'=>'fold',
					'dateFormat'=>'dd-mm-yy',
				),
				'htmlOptions'=>array(
					'style'=>'height:20px;'
				),
			));
			?>
			<!--  end -->
		</div>
		
		<!-- Form nhập user name hoặc password để lọc -->
		<div class = "manager_monitor_right">
			<div class = "admin_manage_search">
				<label>Username :</label>
				<div class = "input"><input type="text"  id="name_username" value="<?php echo !empty($_GET['name_username']) ? $_GET['name_username'] : ''?>" name="name_username" class="input-medium"/></div>
			</div>
			<div class = "admin_manage_search">
				<label>Email :</label>
				<div class = "input"><input type="text"  id="name_email" value="<?php echo !empty($_GET['name_email']) ? $_GET['name_email'] : ''?>" name="name_email" class="input-medium"/></div>
			</div>
		</div>
		<!-- end -->
		
		<div style="clear:both;"></div>
		<br>
		<button class="btn" type="submit" ><i class="icon-search"></i> Search</button>
		<button class="btn" type="reset" ><i class="icon-repeat"></i> Reset</button>
		<fieldset>
	</form>
	<!-- end -->
	
</div>
		<!-- Hiển thị tiêu đề của danh sách user đang chọn -->
			<?php 
				if (!empty($type)) {
					foreach ($type as $key=>$item) {
						if (isset($_GET['type']) && $item['key']===$_GET['type']) {
							echo "<div>{$item['description']}</div>";
						}
					}
				}
			?>
		<!--  -->
		<div style="margin: 10px 0px;font-size: 14px;font-weight: bold;"><?php echo 'This list including '.$count.' user account ( '.$type_show_view_mode.' )';?></div>
		<?php if ($pages->pageCount>1) { ?>
		<div class="pagination">
			
			<?php
				$to		=	$pages->offset+1;
				$from	=	$pages->offset+$pages->limit;
				if($from >=$item_count)	$from = $item_count;
				$this->widget('CLinkPager', array(
					'cssFile'=> '',
					'pages' => $pages,
					'header' => '',
					'footer' => '',
					'firstPageLabel' => 'First',
					'prevPageLabel' => 'Prev',
					'nextPageLabel' => 'Next',
					'lastPageLabel' => 'Last',
					'cssFile'=>''
				));
			?>
		</div>
		<?php
		}
		?>
		
		<!-- Form để select các action thực hiện bằng checkbox -->
		<div style="padding:5px 0px;position: relative;overflow: hidden;height: 30px;">
			<span style="position: absolute;top: 5px;left: 0px;">Select all on page </span>
			<input type="checkbox" class="check-all" value="all" name="Action_Monitor[check][all]" style="position: absolute;top: 5px;left: 117px;">
			<span style="position: absolute;top: 5px;left: 151px;">Action </span>
			<select name="type_action_monitor" class="type_action_monitor" style="position: absolute;top: 0px;left: 194px;">
				<option value="delete">Delete</option>
				<?php if ($typeName_core==="GNUser" || $typeName_core==="face_user"){?>
				<option value="resend-forgot">Re-Send email forgot password</option>
				<?php } else {?>
				<option value="resend-activation">Re-Send email activation account</option>
				<?php }?>
			</select>
			<input type="submit" value="Submit" id="manage_monitor_check_action_submit" style="position: absolute;top: 1px;left: 431px;"/>
		</div>
		<!-- end -->
		
		<table class="items table table-striped table-bordered table-condensed">
			<thead>
				<tr style="font-size: 12px;">
					<th style="width:20px;">Choose</th>
					<th>#</th>
					<th>UserName</th>
					<th>Full Name</th>
					<th>Email</th>
					<th>Created</th>
					<?php 
						if (isset($_GET['type']) && $_GET['type']==="tmp_user_expiry_date") {
							echo "<th>Expiry date</ht>";
						}
					?>
					<th>Activation</th>
					<th>Source</th>
					<th>
						<?php if ($typeName_core==="GNUser" || $typeName_core==="face_user"){?>
							Changed password
						<?php } else {?>
							Re-Send email
						<?php }?>
					</th>
					<th>
						<?php if ($typeName_core==="GNUser" || $typeName_core==="face_user"){?>
							Send mail
						<?php }?>
					</th>
					<th>Action</th>
					<th>
						<?php if ($typeName_core==="GNUser" || $typeName_core==="face_user"){?>
							Upload photo
						<?php }?>
					</th>
				</tr>
			</thead>
			<tbody>
			<?php
				if(!empty($model)){
					$count = 0;
					foreach($model as $key=>$value){
						$count++;
						$css = "old";
						if($key%2==0)	$css = "even";
			?>
				<tr class="<?php echo $css;?>">
					<td style="width:20px;text-align: center;">
						<input type="checkbox" class="check-item" value="<?php echo IDHelper::uuidFromBinary($value['id']);?>" name="Action_Monitor[check][]">
					</td>
					<td style="width:60px;text-align:center;"><?php echo $count;?></td>
					<td><a rel="tooltip" href="<?php echo JLRouter::createAbsoluteUrl('admin_manage/manageUser/photoUser/binIDUser/'.IDHelper::uuidFromBinary($value['id']));?>" data-original-title="View infor of user"><?php echo $value['username'];?></a></td>
					<td><?php echo $value['firstname'].' '.$value['lastname'];?></td>
					<td><?php echo $value['email'];?></td>
					<td><?php echo date("d-m-Y H:i:s",$value['createtime']);?></td>
					<?php
						if (isset($_GET['type']) && $_GET['type']==="tmp_user_expiry_date") {
							echo "<td>";
							$day = date("d",$value['createtime']) + 1;
							echo date($day."-m-Y H:i:s",$value['createtime']);
							echo "</td>";
						}
					?>
					<td>
						<?php
							if ($value['is_activation']==='true') {
								echo '<span style="color:green;">'.$value['is_activation'].'</span>';
							} else {
								echo '<span style="color:red;">'.$value['is_activation'].'</span>';
							}
						?>
					</td>
					<td>
						<?php
							if ($value['source']==='facebook') {
								echo '<span style="color:blue;">'.$value['source'].'</span>';
							} else {
								echo '<span>'.$value['source'].'</span>';
							}
						?>
					</td>
					<td class="button-column">
						<?php if ($typeName_core==="GNUser" || $typeName_core==="face_user"){?>
							<?php echo '<a rel="tooltip" href='.JLRouter::createAbsoluteUrl('/admin_manage/manageUser/edit',array('binUserID'=>IDHelper::uuidFromBinary($value['id']))).' data-original-title="Changed password for user : '.$value['username'].'" class="changed-password" altUserID="'.IDHelper::uuidFromBinary($value['id']).'" altUsername="'.$value['username'].'"><i class="icon-repeat"></i></a>';?>
						<?php } else {?>
							<?php echo '<a rel="tooltip" href='.JLRouter::createAbsoluteUrl('/admin_manage/manageUser/reSendEmail',array('binUserID'=>IDHelper::uuidFromBinary($value['id']))).' data-original-title="Re Send email with link to activation account for user : '.$value['username'].'" class = "Manage_ResendEmail"><i class="icon-repeat"></i></a>';?>
						<?php }?>
					</td>
					
					<td class="button-column">
						<?php if ($typeName_core==="GNUser" || $typeName_core==="face_user"):?>
							<?php echo '<a rel="tooltip" href='.JLRouter::createAbsoluteUrl('/admin_manage/manageUser/resetPassword',array('binUser'=>IDHelper::uuidFromBinary($value['id']))).' data-original-title="Send link to user recovery password for : '.$value['username'].'" class = "reset_password"><i class="icon-lock"></i></a>';?>
						<?php endif;?>
					</td>
					<td class="button-column">
						<?php if ($typeName_core==="GNUser" || $typeName_core==="face_user"):?>
							<a class="update form-edit-user" rel="tooltip" href="<?php echo Yii::app()->createUrl('admin_manage/manageUser/edit/binUserID/'.IDHelper::uuidFromBinary($value['id']));?>" altUserID="<?php echo IDHelper::uuidFromBinary($value['id']);?>" data-original-title="Update info for user : <?php echo $value['username'];?>"><i class="icon-pencil"></i></a>
						<?php endif;?>
						<a class="delete_user" rel="tooltip" href="<?php echo Yii::app()->createUrl('admin_manage/manageUser/monitorAction',array('binUserID'=>IDHelper::uuidFromBinary($value['id']), 'models' => $typeName_core));?>" data-original-title="Delete user : <?php echo $value['username'];?>"><i class="icon-trash"></i></a>
					</td>
					<td class="button-column">
						<?php if ($typeName_core==="GNUser" || $typeName_core==="face_user"):?>
							<a rel="tooltip" href="<?php echo JLRouter::createAbsoluteUrl('admin_manage/manageUser/photoUser/binIDUser/'.IDHelper::uuidFromBinary($value['id']));?>" data-original-title="Upload photo for user" class = "btnAddPhoto"><i class="icon-upload"></i></a>
						<?php endif;?>
					</td>
				</tr>
			<?php	
					}
				}else{
			?>
				<tr>
					<td colspan="9">
						List user account is empty.
					</td>
				</tr>
			<?php	
				}
			?>
			</tbody>
		</table>
		
		<?php if ($pages->pageCount>1) { ?>
		<div class="pagination">
			
			<?php
				$to		=	$pages->offset+1;
				$from	=	$pages->offset+$pages->limit;
				if($from >=$item_count)	$from = $item_count;
				$this->widget('CLinkPager', array(
					'cssFile'=> '',
					'pages' => $pages,
					'header' => '',
					'footer' => '',
					'firstPageLabel' => 'First',
					'prevPageLabel' => 'Prev',
					'nextPageLabel' => 'Next',
					'lastPageLabel' => 'Last',
					'cssFile'=>''
				));
			?>
		</div>
		<?php
		}
		?>
	</div>
</div>

<script type='text/javascript'>
	$(document).ready(function() {
		$('.delete_user').click(function() {
			var baseUrl = $(this).attr('href');
			jlbd.dialog.confirm('Justlook Manage','You are delete current user !',function(r) {
				if(r==true) {
					window.location.href = baseUrl;
				} else {
					return false;
				}
			});
			return false;
		});
		$('.reset_password').click(function() {
			var baseUrl = $(this).attr('href');
			jlbd.dialog.confirm('Justlook Manage','Do you want to send email for account of current user ?',function(r) {
				if(r==true) {
					window.location.href = baseUrl;
				} else {
					return false;
				}
			});
			return false;
		});
		$('body').on('click', '.search_hide', function(e){
			var _this = $(this);
			$('#searchForm').slideToggle(200);
		});
		$('a.show-help-monitor-user').click(function() {
			$('div.manage_user_monitor_help').slideToggle(200);
		});
		$('a.close_comment').click(function() {
			$('div.manage_user_monitor_help').fadeOut(200);
		});
		//Proccess checkbox

		$("#manage_monitor_check_action").find('input.check-all').click(function(){
			var _this = $(this)
			if(this.checked==true){
				$("#manage_monitor_check_action").find('.check-item').each(function(){
					$(this).attr('checked',true);
				});
			}
			else if(this.checked==false)	{
				$("#manage_monitor_check_action").find('.check-item').each(function(){
					$(this).attr('checked',false);
				});
			}
		});
		
		$('#manage_monitor_check_action').on('change', 'input.check-item', function(e){
			$(	'.check-item'	).length==$(	'.check-item:checked'	).length	?	$('.check-all').attr('checked',true).next()	:	$('.check-all').attr('checked',false).next();
		});
		//
		$("input.check-item").click(function() {
			$("#action_monitor_manage_user").find('.data_check').html('');
			var checkboxes = $('input.check-item:checked').clone(true);
			checkboxes.each(function() {
				$(this).attr('checked',true);
			});
			$("#action_monitor_manage_user").find('.data_check').append(checkboxes);
		});
		$("input.check-all").click(function() {
			$("#action_monitor_manage_user").find('.data_check').html('');
			var checkboxes = $('input.check-item:checked').clone(true);
			checkboxes.each(function() {
				$(this).attr('checked',true);
			});
			$("#action_monitor_manage_user").find('.data_check').append(checkboxes);
		});
		$('.type_action_monitor').change(function() {
			$("#action_monitor_manage_user").find(".type_append").html('')
				.append('<input type="text" name="Action_Monitor[type_action]" value="'+$('.type_action_monitor').val()+'"/>');
		});
		$("#manage_monitor_check_action_submit").click(function() {
			jlbd.dialog.confirm('JustLook Message !', 'Do you want delete all user in selected ?', function(r) {
				if (r) {
					$("#action_monitor_manage_user").submit();
				} else {
					return false;}
			})
		});
	});
</script>
<?php $this->renderPartial('edit');?>
<?php $this->renderPartial('changed_password');?>
<style type="text/css">
	.manager_monitor_left{float: left;width:55%;}
	.manager_monitor_right{float: left;width:30%;}
	ul.help_monitor{margin: 0px;padding: 0px;width: 148px;}
	ul.help_monitor li{width: 100%;display: block;list-style-type: none;}
</style>
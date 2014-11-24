<?php

GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js',
));
?>
<?php
	GNAssetHelper::setBase('justlook', "layout-dashboard");
	GNAssetHelper::setPriority(100);
	
	GNAssetHelper::cssFile('user-gallery-photo');
	GNAssetHelper::cssFile('user-gallery-photo-more');
	GNAssetHelper::cssFile('bt-small');
	GNAssetHelper::cssFile('popup-upload-photo');
	GNAssetHelper::cssFile('common-extend');
	GNAssetHelper::cssFile('jquery.fancybox-1.3.4');
	
	GNAssetHelper::scriptFile('jquery.fancybox-1.3.4.pack', CClientScript::POS_END);
	//GNAssetHelper::scriptFile('xii.thumbnailer', CClientScript::POS_END);
//	GNAssetHelper::setBase('application.modules.user.assets');
//	GNAssetHelper::cssFile('update_css_user_manageuser');
	GNAssetHelper::setBase('application.modules.admin_manage.assets.manage_user');
	GNAssetHelper::scriptFile('jlbd.manage-photo', CClientScript::POS_END);
	
	GNAssetHelper::setBase('application.modules.admin_manage.assets.manage_user');
	//GNAssetHelper::scriptFile('jlbd.manage-photo', CClientScript::POS_END);
	
?>

<div style=" float:left; width:900px;margin-left: 100px;">	
	<div class="grid-view">	
		<table class="items table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th>#</th>
					<th>UserName</th>
					<th>Full Name</th>
					<th>Email</th>
					<th>Reset password</th>
					<th>Action</th>
					<th>Upload photo</th>
				</tr>
			</thead>
			<tbody>
			<tbody>
			<?php
			$key = 2;
				if(!empty($model)){
					$count = 0;
						$count++;
						$css = "old";
						if($key%2==0)	$css = "even";
			?>
				<tr class="<?php echo $css;?>">
					<td style="width:60px;text-align:center;"><?php echo $count;?></td>
					<td><?php echo $model->username;?></td>
					<td><?php echo $model->firstname.' '.$model->lastname;?></td>
					<td><?php echo $model->email;?></td>
					<td class="button-column"><?php echo '<a rel="tooltip" href='.JLRouter::createAbsoluteUrl('/admin_manage/manageUser/resetPassword',array('binUser'=>IDHelper::uuidFromBinary($model->id))).' data-original-title="Send link to user recovery password" class = "reset_password"><i class="icon-lock"></i></a>';?></td>
					<td class="button-column">
						<a class="update" rel="tooltip" href="<?php echo Yii::app()->createUrl('admin_manage/categories/edit',array('id'=>IDHelper::uuidFromBinary($model->id)));?>" data-original-title="Update infor of user"><i class="icon-pencil"></i></a>
						<a class="delete_user" rel="tooltip" href="<?php echo Yii::app()->createUrl('admin_manage/manageUser/delete',array('binIDUser'=>IDHelper::uuidFromBinary($model->id)));?>" data-original-title="Delete user"><i class="icon-trash"></i></a>
					</td>
					<td class="button-column">
						<a relClass="img-user" rel="tooltip" href="#wd-upload-photo" urlhref="<?php echo JLRouter::createAbsoluteUrl('admin_manage/manageUser/photoUser/binIDUser/'.IDHelper::uuidFromBinary($model->id));?>" data-original-title="Upload photo for user" class="btnAddPhoto_admin" id="upload_photo_user_admin"><i class="icon-upload"></i></a>
					</td>
					
				</tr>
				<tr class="<?php echo $css;?>">
					<td colspan="7">
						<?php if (empty($userPhotos)):?>
					<div class='no-photo'>
						There currently no photo available
					</div>
					<?php else:?>
						<ul class="wd-gallery-user-photo" id='user-album-<?php echo $this->id;?>'>
							<?php 
							$cnt = 0;
							foreach ($userPhotos as $item): 
							$size = array(0,0);
							if ($item instanceof JLImageFS) {
								$size = $item->imageSize;
								$_item = array(
									'id'		=> $item->metadata['info']['filename'],
									'filename'	=> $item->metadata['info']['basename'],
								);
							
								$item = $_item;
							} else {
								if (!isset($item['width'])) $item['width'] = 0;
								if (!isset($item['height'])) $item['height'] = 0;
								$size = array($item['width'], $item['height']);
							}
							?>
							<li photo_id="<?php echo $item['id']?>" <?php echo ($cnt == 0) ? "class='primary'" : ""?>>
								<a href="<?php echo JLRouter::createUrl("/user/photos/showGallery?uid=".$UserInfo['uuid']."&imgID=" . $item['id'])?>" class='view make-as-primary img viewPhotoDetail' preferWidth='<?php echo $size[0]?>' preferHeight='<?php echo $size[1]?>'>
									<img src="<?php echo JLRouter::createUrl('/upload/user-photos/'.$UserInfo['uuid']) . "/" . $item['filename'];?>" alt="<?php echo $item['filename']?>" />
								</a>
								
								<a class="wd-delete-image" href="<?php echo JLRouter::createUrl('/user/managePhoto/deletePhoto', array('photoID' => $item['id']))?>">remove</a>
								<div class="section-thumb extras">
									<?php if ($cnt == 0):?>
									<p class='make-primary'>
										Is primary
									</p>
									<?php else:?>
									<p class='make-primary'>
										<a href="#">
											Make it primary
										</a>
									</p>
									<?php endif;?>
								</div>
							</li>
							
							<?php $cnt++;endforeach;?>
						</ul>	
						<?php endif;?>
					</td>
				</tr>
			
			<?php	
				} else {
			?>
				<tr>
					<td colspan="6">
						List user account is empty.
					</td>
				</tr>
			<?php	
				}
			?>
			</tbody>
		</table>
	</div>
</div>
<div style=" float:left; width:250px; margin-left:10px; margin-top:20px;">

<?php 
	$this->widget('ext.bootstrap.widgets.BootMenu', array(
		'type'=>'list', // '', 'tabs', 'pills' (or 'list')
		'stacked'=>false, // whether this is a stacked menu
		'items'=>array(
			array('label'=>'Created new account', 'url'=>Yii::app()->createUrl('admin_manage/manageUser/created'), 'active'=>true),
			array('label'=>'List user account', 'url'=>Yii::app()->createUrl('admin_manage/manageUser/listUser'), 'active'=>true),
		),
	)); 
?>
</div>
<div class="clear"></div>
<?php $this->renderPartial("/manageUser/uploadPhoto",array(
	'UserInfo'=>$UserInfo
));?>
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
		/*$('.btnAddPhoto_admin').click(function() {
			alert('a');
			return false;
		});*/
	});
</script>

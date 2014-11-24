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
?>
<div style=" float:left; width:95%;margin:0px 20px;">
	
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
	
	<div class="grid-view">
		
		<div class = "admin_manager_search_advanced">
			<?php /** @var BootActiveForm $form */
				$form = $this->beginWidget('ext.bootstrap.widgets.BootActiveForm', array(
					'id'=>'searchForm',
					'type'=>'search',
					'method'=>'GET',
					'action'=>Yii::app()->createUrl('admin_manage/manageUser/searchAdvanced'),
					'htmlOptions'=>array('class'=>'well'),
				));
			?>
			<a href="#" class="search_hide"><i class="icon-search"></i> Advanced Search</a>
			<div class = "admin_manage_search_tool" style="display: none;">
				<div class = "admin_manage_search">
					<label>Username :</label>
					<div class = "input"><input type="text"  id="name_username" value="" name="name_username" class="input-medium"/></div>
				</div>
				<div class = "admin_manage_search">
					<label>Fullname :</label>
					<div class = "input"><input type="text"  id="name_fullname" value="" name="name_fullname" class="input-medium"/></div>
				</div>
				<div class = "admin_manage_search">
					<label>Email :</label>
					<div class = "input"><input type="text"  id="name_email" value="" name="name_email" class="input-medium"/></div>
				</div>
				<div class = "admin_manage_search button">
					<?php $this->widget('ext.bootstrap.widgets.BootButton', array('buttonType'=>'button', 'icon'=>'search', 'label'=>'Search','id'=>'submit')); ?>
				</div>
				
				<script type="text/javascript">
					$("#searchForm").keypress(function(e) {
						if(e.keyCode==13){
							$("#searchForm").submit();
					
						}
					});
					$(".btn").click(function(e) {
						$("#searchForm").submit();
						//window.location.href = "<?php echo Yii::app()->createUrl('admin_manage/business/awaiting');?>?name="+encodeURI($("#name").val());
					});
				</script>
	 		</div>
			<?php $this->endWidget(); ?>
		</div>
		
		<div style="margin: 10px 0px;font-size: 14px;font-weight: bold;"><?php echo 'This list including '.$count.' user account';?></div>
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
		<table class="items table table-striped table-bordered table-condensed">
			<thead>
				<tr>
					<th>#</th>
					<th>UserName</th>
					<th>Full Name</th>
					<th>Email</th>
					<th>Created</th>
					<th>Changed password</th>
					<th>Send mail</th>
					<th>Action</th>
					<th>Upload photo</th>
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
					<td style="width:60px;text-align:center;"><?php echo $count;?></td>
					<td><a rel="tooltip" href="<?php echo JLRouter::createAbsoluteUrl('admin_manage/manageUser/photoUser/binIDUser/'.IDHelper::uuidFromBinary($value->id));?>" data-original-title="View infor of user"><?php echo $value->username;?></a></td>
					<td><?php echo $value->firstname.' '.$value->lastname;?></td>
					<td><?php echo $value->email;?></td>
					<td><?php echo date("d-m-Y",$value->createtime);?></td>
					<td class="button-column">
						<?php echo '<a rel="tooltip" href='.JLRouter::createAbsoluteUrl('/admin_manage/manageUser/edit',array('binUserID'=>IDHelper::uuidFromBinary($value->id))).' data-original-title="Changed password for user : '.$value->username.'" class="changed-password" altUserID="'.IDHelper::uuidFromBinary($value->id).'" altUsername="'.$value->username.'"><i class="icon-repeat"></i></a>';?>
					</td>
					<td class="button-column"><?php echo '<a rel="tooltip" href='.JLRouter::createAbsoluteUrl('/admin_manage/manageUser/resetPassword',array('binUser'=>IDHelper::uuidFromBinary($value->id))).' data-original-title="Send link to user recovery password for : '.$value->username.'" class = "reset_password"><i class="icon-lock"></i></a>';?></td>
					<td class="button-column">
						<a class="update form-edit-user" rel="tooltip" href="<?php echo Yii::app()->createUrl('admin_manage/manageUser/edit/binUserID/'.IDHelper::uuidFromBinary($value->id));?>" altUserID="<?php echo IDHelper::uuidFromBinary($value->id);?>" data-original-title="Update info for user : <?php echo $value->username?>"><i class="icon-pencil"></i></a>
						<a class="delete_user" rel="tooltip" href="<?php echo Yii::app()->createUrl('admin_manage/manageUser/monitorAction',array('binUserID'=>IDHelper::uuidFromBinary($value->id), 'models' => "GNUser"));?>" data-original-title="Delete user : <?php echo $value->username?>"><i class="icon-trash"></i></a>
					</td>
					<td class="button-column">
						<a rel="tooltip" href="<?php echo JLRouter::createAbsoluteUrl('admin_manage/manageUser/photoUser/binIDUser/'.IDHelper::uuidFromBinary($value->id));?>" data-original-title="Upload photo for user" class = "btnAddPhoto"><i class="icon-upload"></i></a>
					</td>
				</tr>
			<?php	
					}
				}else{
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
		$('.search_hide').click(function() {
			$('.admin_manage_search_tool').toggle(500);
		});
	});
</script>
<?php $this->renderPartial('edit');?>
<?php $this->renderPartial('changed_password');?>
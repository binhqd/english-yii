<ul class="breadcrumb">
	<li><a href="/">Data List</a> <span class="divider">/</span></li>
	<li><a href="<?php echo GNRouter::createUrl('/vocabulary/list?id=' . IDHelper::uuidFromBinary($data->id, true))?>"><?php echo $data->title?></a> <span class="divider">/</span></li>
	<li class='active'><?php echo $model->word?></li>
</ul>
<h3>Editing: <?php echo $model->word?></h3>

<?php if ($isOwner):?>
<hr/>
<?php 
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'editProfile',
		'enableClientValidation'=>true,
		'action'	=> GNRouter::createUrl('/vocabulary/edit/word'),
		'htmlOptions'=>array(
			'class'		=> 'well span8 form-horizontal',
			'enctype'	=> 'multipart/form-data'
		),
));
?>
	<input name='GNWord[id]' value='<?php echo $id;?>' type="hidden" placeholder="Enter word" class='span5'>
	<fieldset>
		<div class='control-group'>
			<label class='control-label'>Word</label>
			<div class='controls'>
				<?php echo $form->textField($model, 'word', array('class'=>'span5', 'placeholder' => 'Enter word')); ?>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>Type</label>
			<div class='controls'>
				<?php echo $form->dropDownList($model, 'type', array('noun' => 'Noun', 'adjective' => 'Adjective', 'verb' => 'Verb', 'adverb' => 'Adverb')); ?>
				
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>Phonetic</label>
			<div class='controls'>
				<?php echo $form->textField($model, 'phonetic', array('class'=>'span5', 'placeholder' => '/Phonetic/')); ?>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>Vietnamese</label>
			<div class='controls'>
				<?php echo $form->textField($model, 'vietnamese', array('class'=>'span5', 'placeholder' => 'Vietnamese')); ?>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>English Meaning</label>
			<div class='controls'>
				<?php echo $form->textArea($model, 'meaning', array('class'=>'span5', 'placeholder' => 'English Meaning', 'rows' => 4)); ?>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>Example</label>
			<div class='controls'>
				<?php echo $form->textArea($model, 'example', array('class'=>'span5', 'placeholder' => 'Example')); ?>
			</div>
		</div>
		<div class='control-group'>
			<label class='control-label'>Image</label>
			<?php if (!empty($model->image)):?>
			<img src='<?php echo GNRouter::createUrl('/upload/english/thumbs/300-300/' . $model->image)?>'><br/>
			[ <a href='<?php echo GNRouter::createUrl('/vocabulary/edit/removeImage?id=' . $id)?>'>Remove</a> ]
			<?php endif;?>
			<div class='controls'>
				<?php echo $form->fileField($model, 'image', array('class'=>'span5', 'placeholder' => 'Image')); ?>
			</div>
		</div>
		
		<div class='control-group'>
			<label class='control-label'>Learned</label>
			<div class='controls'>
				<?php echo $form->checkbox($model, 'learned', array()); ?>
			</div>
		</div>
		
		<button type="submit" class="btn">Submit</button>
	</fieldset>
<?php $this->endWidget()?>
<?php endif;?>
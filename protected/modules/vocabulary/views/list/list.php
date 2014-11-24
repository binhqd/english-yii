<style>
.learned {color:green}
.jfk-button-img {
	background: url(/assets/buttons11.png) -84px 0 no-repeat;
	opacity: 0.55;
	-moz-opacity: 0.55;
	margin-top: -3px;
	vertical-align: middle;
	width: 21px;
	height: 21px;
	display: inline-block;
	cursor:pointer;
}
</style>
<h3><?php echo $data->title?></h3>
<ul class="breadcrumb">
	<li><a href="/">Data List</a> <span class="divider">/</span></li>
	<li class='active'><?php echo $data->title?></li>
</ul>
( Test: <a href='<?php echo GNRouter::createUrl('/vocabulary/test/?id=' . $id . '&lang=vi')?>'>Vietnamese</a> | <a href='<?php echo GNRouter::createUrl('/vocabulary/test/?id=' . $id . '&lang=en')?>'>English</a> )
<table class="table table-striped">
<tr>
	<th class='span1'>#</th>
	<th class='span2'>Word</th>
	<th class='span4'>Vietnamese</th>
	<th class='span4'>Explain</th>
	<th class='span1'>Actions</th>
</tr>
<tbody>
<?php $cnt=0;foreach($words as $word):$cnt++;?>
<tr>
	<td><?php echo $cnt;?></td>
	<td>
		<b><span <?php if ($word->learned):?>class='learned'<?php endif;?>><?php echo $word->word?></span></b> <?php if (!empty($word->image)):?><i class="icon-picture"></i><?php endif;?>
		<span class="jfk-button-img audio" ref='<?php echo $word->word?>'></span>
		<br/>
		<i><?php echo $word->phonetic?></i>
	</td>
	<td><?php echo $word->vietnamese?></td>
	<td>
		<?php echo $word->meaning?>
		<?php echo !(empty($word->meaning)) ? "<br/>" : "";?>
		<i><?php echo $word->example?></i>
	</td>
	<td>
		<i class="icon-pencil"></i> <a href='<?php echo GNRouter::createUrl('/vocabulary/edit/word?id=' . IDHelper::uuidFromBinary($word->id, true))?>'>Edit</a>
	</td>
</tr>
<?php endforeach;?>
</tbody>
</table>

<?php if ($isOwner):?>
<hr/>
<?php 
$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id'=>'editProfile',
		'enableClientValidation'=>true,
		'action'	=> GNRouter::createUrl('/vocabulary/create/word'),
		'htmlOptions'=>array(
			'class'		=> 'well span8 form-horizontal',
			
		),
));
?>
	<input name='GNWord[user_data_id]' value='<?php echo $id;?>' type="hidden" placeholder="Enter word" class='span5'>
	<fieldset>
		<legend>Create new word</legend>
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
			<div class='controls'>
				<?php echo $form->fileField($model, 'image', array('class'=>'span5', 'placeholder' => 'Image')); ?>
			</div>
		</div>
		<button type="submit" class="btn">Submit</button>
	</fieldset>
<?php $this->endWidget()?>
<?php endif;?>
<script>
var words = {};
$(document).ready(function() {
	$('.audio').click(function() {
		var word = $(this).attr('ref');
		if (typeof words[word] == "undefined") {
			var url = homeURL + '/getWord/getAudio?word=' + encodeURIComponent(word);
			var source = new Audio(url);
			words[word] = source;
		}

		words[word].play();
	});
});

</script>
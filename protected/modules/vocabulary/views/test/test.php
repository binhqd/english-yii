<?php
GNAssetHelper::init(array(
	'image'		=> 'img',
	'css'		=> 'css',
	'script'	=> 'js',
));
GNAssetHelper::setBase('assets/english');
GNAssetHelper::scriptFile('jquery.tmpl.min');
//GNAssetHelper::scriptFile('common', CClientScript::POS_HEAD);

Yii::app()->jlbd->register(); // Register JLBD Library

Yii::app()->bootstrap->register(); // Register bootstrap
?>
<style>
#out {font-weight: bold;position: absolute;}
#btnNext {font-weight: bold;font-size:14px;position:absolute; right:30px; bottom: 100px; width: 100px; height: 100px;display:none;}
#btnHelp {font-weight: bold;font-size:14px;position:absolute; right:140px; bottom: 100px; width: 100px; height: 100px;display:none;}
.fl {float:left;}
.hint {display:none;font-size:15px;margin-top:10px;}
.sentence, .mean {line-height: 1.4em;display: block;margin-top:15px;font-weight: normal;}
.sentence {font-style:italic;}
.red {color: #ff0000 !important;}
.word {
	font-size: 32px;
	font-weight: bold;
	color: #0096C8;
}
.inputs {margin-top:10px;} 
.jfk-button-img {
	background: url(/assets/buttons11.png) -84px 0 no-repeat;
	opacity: 0.55;
	-moz-opacity: 0.55;
	margin-top: -15px;
	margin-left: 10px;
	vertical-align: middle;
	width: 21px;
	height: 21px;
	display: inline-block;
	cursor:pointer;
}
.test-options {background:#eee;padding:10px;margin-bottom:15px;}

.alert {display:none};
br {font-size:12px !important;}
.text {}
.item {font-size:16px;}
.character-item {width: 20px; text-transform:uppercase; font-size: 15px;font-weight: bold;text-align: center;}
</style>
<ul class="breadcrumb">
	<li><a href="/">Data List</a> <span class="divider">/</span></li>
	<li><a href="<?php echo GNRouter::createUrl('/vocabulary/list?id=' . $id)?>"><?php echo $data->title?></a> <span class="divider">/</span></li>
	<li class='active'>Test</li>
</ul>
<script id="textRender" type="text/x-jquery-tmpl">
{{if (image != '' && image != null)}}
<div class='span2'>
<img width='100' src='/upload/english/thumbs/300-300/${image}'>
</div>
{{/if}}
<div class='span12 row-fluid'>
	<div class='span5'>
		
		<div class='item span12 quiztext'{{if soundonly == true}} style='display:none'{{/if}}>${i} . ${vi}</div>
		<div class='soundonly quizsound'{{if soundonly == false}} style='display:none'{{/if}}>
			<span class='sound-icon'>
				<img src='/assets/default/img/sound.png' width='128' height='128' />
			</span>
			[ <a href='javascript:void(0)' class='listen'>Chưa nghe rõ, nghe lại</a> ]
		</div>
		<div class='inputs'>
		{{each en.split('')}}
			<input type='text' class='character-item'/>
		{{/each}}
		</div>
		<div class="alert alert-success"></div>
	</div>
	
	<div class='span5'>
		<div class='word-info hint'>
			<div class='word-pane'>
				<span class='word'>${en}</span> <span class="jfk-button-img audio" ref='${en}'></span><br/>
				<span class=''>${read}</span>
				<span class='mean'>${vi}</span>
			</div>
			<span class='sentence'>{{html sentence}}</span>
		</div>
	</div>
</div>
</script>

<script id="textRenderEn" type="text/x-jquery-tmpl">
<div class='span12'>
	{{if (image != '' && image != null)}}
	<img width='100' src='/upload/english/thumbs/300-300/${image}'>
	{{/if}}
	<div class='span9'>
		<div class='item'>${i} . ${en}</div>
		<span class='hint'>${vi} (${read})</span><br/>
		<span class='hint sentence'>{{html sentence}}</span>
	</div>
</div>
</script>

<script language='javascript'>
var lang = '<?php echo isset($_GET['lang']) ? $_GET['lang'] : "en";?>';
var source = <?php echo json_encode($arr);?>;
var soundonly = false;
</script>


<div class='container-fluid'>
	<div class='test-options'>
		<div class='radio'>
			<label class='radio-inline'><input class='test-option' type='radio' name='test-option' checked value='text-only'/> Chỉ hiện text</label>
			<label class='radio-inline'><input class='test-option' type='radio' name='test-option' /> Nghe</label>
		</div>
		
	</div>
	<div id='out' class='row-fluid'>
	
	</div>
	<input type='button' value='Help' id='btnHelp'/> <input type='button' value='next' id='btnNext'/>
</div>
<script language='javascript'>

var i = 0;
var words = {};

function playSound() {
	$('.audio').trigger('click');
}
function bindInputs() {
	$('.listen').click(function() {
		playSound();
	});
	
	$('.character-item').on('keydown', function(e) {
		var code = e.keyCode;

		if (code == 17) return;
		
		if (code == 8) {
			$(this).val('');
			$(this).prev().focus();
		} else if (code == 37){
			$(this).prev().focus();
		} else if (code == 13) {
			var text = '';
			$('.character-item').each(function(i, item) {
				text += $(item).val();
			});

			text = text.trim();

			// Nếu trả lời đúng
			if (text == source[i].en) {
				$('.hint').css('display', 'block');

				$('.alert').html('Chúc mừng, bạn đã trả lời đúng.').addClass('alert-success').removeClass('alert-danger').show();
				//$('#btnNext').trigger('click');
			} else {
				$('.hint').css('display', 'block');
				$('.alert').html('Bạn đã trả lời sai.').addClass('alert-danger').removeClass('alert-success').show();
				$('.word').addClass('red');
			}
			
		} else {
			$(this).val('');
		}
		
	});
	$('.character-item').on('keypress', function(e) {
		var code = e.keyCode;

		if (code == 17) return;
		
		if (code != 32 && code != 13 && code != 37 && code != 8) {
			$(this).next().focus();
		}
	});

	// audio
	$('.audio').click(function() {
		var word = $(this).attr('ref');
		if (typeof words[word] == "undefined") {
			var url = homeURL + '/getWord/getAudio?word=' + word;
			var source = new Audio(url);
			words[word] = source;
		}

		words[word].play();
	});
}
$(document).ready(function() {
	var data = {
		image : source[i].image,
		i : i + 1,
		vi : source[i].vi,
		en : source[i].en,
		read : source[i].read,
		sentence : source[i].sentence
	};

	
	if (lang == 'vi') {
		var txt = $.tmpl($('#textRender'), data);
		$('#out').html(txt);
		
		$('#btnNext').click(function() {
			i++;
			if (typeof source[i] != "undefined") {
				data = {
					image : source[i].image,
					i : i + 1,
					vi : source[i].vi,
					en : source[i].en,
					read : source[i].read,
					sentence : source[i].sentence
				};
				txt = $.tmpl($('#textRender'), data);
				$('#out').html(txt);
				bindInputs();

				
			}
			else
				window.location = window.location;
			
			$('.character-item').first().focus();
			$('.alert').hide();

			if (soundonly) {
				playSound();
			}
		});
	} else {
		var txt = $.tmpl($('#textRenderEn'), data);
		$('#out').html(txt);
		
		$('#btnNext').click(function() {
			i++;
			if (typeof source[i] != "undefined") {
				data = {
					image : source[i].image,
					i : i + 1,
					vi : source[i].vi,
					en : source[i].en,
					read : source[i].read,
					sentence : source[i].sentence
				};
				txt = $.tmpl($('#textRenderEn'), data);
				$('#out').html(txt);

				
			}
			else
				window.location = window.location;
			
			$('.alert').hide();
		});
	}

	$('.character-item').first().focus();
	
	$('#btnHelp').click(function() {
		$('.hint').css('display', 'block');
	});

	$(document).keydown(function(event) {
		if (event.keyCode == 39 || event.keyCode == 32){
			$('#btnNext').trigger('click');
		} else

		if (event.keyCode == 27) {
			$('#btnHelp').trigger('click');
		} else

		if (event.keyCode == 17) {
			$('.audio').trigger('click');
		}
	});

	bindInputs();
	
	if (soundonly) {
		playSound();
	}

	$('.test-option').click(function() {
		if ($(this).val() == 'text-only') {
			soundonly = false;
			$('.quizsound').hide();
			$('.quiztext').show();
		} else {
			soundonly = true;
			$('.quizsound').show();
			$('.quiztext').hide();
		}
	});
});
</script>
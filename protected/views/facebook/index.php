<?php
$this->breadcrumbs=array(
	'Facebook',
);?>
<h1><?php echo $this->id . '/' . $this->action->id; ?></h1>

<p>
	You may change the content of this page by modifying
	the file <tt><?php echo __FILE__; ?></tt>.
</p>
<p><a class='customAlert' href="#">publish customAlert</a></p>
<p><a class='customAlert2' href="#">publish customAlert2</a></p>
<?php // Initialize the extension
$this->widget('ext.comet.JComet', array(

    'url'=> $this->createUrl('/site/test'),
)); ?>
<script type="text/javascript">

NovComet.subscribe('customAlert', function(data){
    console.log('customAlert');
    console.log(data);
}).subscribe('customAlert2', function(data){
    console.log('customAlert2');
    console.log(data);
});

$(document).ready(function() {
	$("a.customAlert").click(function(event) {
        NovComet.publish('customAlert');
    });
    
    $("a.customAlert2").click(function(event) {
        NovComet.publish('customAlert2');
    });    
});
</script>
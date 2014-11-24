<h1>List business by : <strong>same name,address,location,landline  </strong></h1>
<div style="display: none;;">
<form id="frmAjax" method="post">
    <label>SQL Query :</label><br />
    <textarea id="sqlQuery" name="sql" class="span12" rows="6">
        <?php echo $sql; ?>
    </textarea>
    <br />
    <input type="submit" name="query" value="Query"/>
</form>
</div>
<div id="outData">
    <?php
     if(count($result))
     {
        $this->widget('bootstrap.widgets.BootGridView',array(
		'id'=>'business-grid',
		'dataProvider'=>$dataProvider,		
        'afterAjaxUpdate' => 'function(){$("#business-grid a[title=\'process\']").fancybox()}',
		'columns'=>array(            
			'name',
            'address',
            'location',
            'landline',
            'url',
            'fax',
            'email',
            'count',
			array(
				'class'=>'bootstrap.widgets.BootButtonColumn',
							'template' => '{process}',
							'buttons' => array(								
								'process' => array(
									'url' => 'array("process","name"=>"$data[name]","address"=>"$data[address]","location"=>"$data[location]","landline"=>"$data[landline]")',
								),
							),
			),		
		),
	));
    }
     ?>  
</div>

<script type="text/javascript">
$(document).ready(function(){
    
   $("#business-grid a[title='process']").fancybox();
   
   $('#business-duplicate-grid .button-column a').on(
        {'click' : function(){
            
           if($(this).text() == 'edit')
           {
                var url = $(this).attr('href');
                $(this).text('update');
                $(this).parent().siblings().each(function(el){
                   if(el > 2)
                   {
                        var txt = $(this).text();
                        $(this).html('<input type="input" style="width:130px;" name="input' + el + '" value="' + txt +'"/>'); 
                   } 
                });
                $('#fancybox-content').width($('#business-duplicate-grid table').width());
                return false; 
           }
           
           if($(this).text() == 'update')
           {
                var url = $(this).attr('href');
                $(this).text('edit');
                var data = new Array();
                var counter = 0;
                $(this).parent().siblings().each(function(el){
                   if(el > 2)
                   {
                       var txt = $(this).find('input').val();
                       data[counter++] = txt;
                       $(this).html(txt);
                   }
                });
                
                $('#fancybox-content').width($('#business-duplicate-grid table').width());
                $.post(url,{data : data},function(resp){
                   if(resp.success)
                   {
                        jlbd.dialog.notify({
							message : 'Remove duplication business success!',
                            type : 'success',
							autoHide : true
						});
                        window.location.href = '';
                   } 
                });
                return false; 
           }
           
           
           if($(this).text() == 'remove')
           {
             $.get($(this).attr('href'),function(resp){
                if(resp.success)
                {
                    console.log('ok');
                }
                else
                {
                    console.log('fail');
                }
                window.location.href = '';
             });
             return false; 
           }
        } 
        }
    );
});
</script>

<?php
     if(count($result))
     {
        $this->widget('bootstrap.widgets.BootGridView',array(
		'id'=>'business-duplicate-grid',
		'dataProvider'=>$dataProvider,		
		'columns'=>array(           
			'name',
            'address',
            'location',
            'landline',
            'url',
            'fax',
            'email',
			array(
				'class'=>'bootstrap.widgets.BootButtonColumn',
							'template' => '{detail} {edit}',
							'buttons' => array(								
								'edit' => array(
									'url' => 'array("edit","uuid"=> IDHelper::uuidFromBinary($data["id"]))',
								),                               
                                 'detail' => array(
									'url' => 'array("/business/$data[alias]")',
								),
							),
			),			
		),
	));
    }
     ?>  
     
<script type="text/javascript">
$(document).ready(function(){
    $('.button-column a').each(function(){
       if($(this).text() == 'detail')
            $(this).attr('target','_blank'); 
    });
    
});
</script>
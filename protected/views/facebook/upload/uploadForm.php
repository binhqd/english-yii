<?php
 
return array(
    'title' => 'Upload your image',
	'title'=>'Upload a photo to facebook',
    'attributes' => array(
        'enctype' => 'multipart/form-data',
    ),
 
    'elements' => array(
    	'name' => array(
            'type'=>'text',
            'maxlength'=>45,
        ), 
        'description' => array(
            'type'=>'textarea',
        ), 
        'caption' => array(
            'type'=>'text',
            'maxlength'=> 100,
        ), 
        'image' => array(
            'type' => 'file',
        ),
    ),
 
    'buttons' => array(
        'reset' => array(
            'type' => 'reset',
            'label' => 'Reset',
        ),
        'submit' => array(
            'type' => 'submit',
            'label' => 'Upload',
        ),
    ),
);
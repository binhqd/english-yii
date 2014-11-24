<?php
class CustomCLinkPager extends CLinkPager
{
	public $customURL = NULL;
	public function init(){
		//
	}
    protected function createPageButton($label,$page,$class,$hidden,$selected)
    {
        if($hidden || $selected)
            $class.=' '.($hidden ? self::CSS_HIDDEN_PAGE : self::CSS_SELECTED_PAGE);
		
        if (!$hidden){
			if($this->customURL==NULL)
				return '<li class="'.$class.'">'.CHtml::link($label,$this->createPageUrl($page)).'</li>';
			else
				return '<li class="'.$class.'">'.CHtml::link($label,JLRouter::createUrl($this->customURL."/page/".$page)).'</li>';
		}

        return '<li class="'.$class.'">'.'</li>';
    }

}
?>
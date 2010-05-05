<?php
class BLinkPager extends CLinkPager
{    
	public function run()
    {
    	$this->nextPageLabel = '下一页';
    	$this->prevPageLabel = '上一页';
    	$this->firstPageLabel = '第一页';
    	$this->lastPageLabel = '最末页';
    	$this->header = '';
		$this->htmlOptions['class'] = 'pagination';
		
        parent::run();
    }
    
	public function registerClientScript()
	{
	}
    
}

?>
<?php

class TagCloud extends CWidget
{
	public $title='Tags';

	public function run()
	{
		$tags = TicketTag::model()->getTags(Yii::app()->session['currentProject']->id); 
		$this->render('tagCloud', array('tags'=>$tags));
	}
}
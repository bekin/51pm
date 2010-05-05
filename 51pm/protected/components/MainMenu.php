<?php
class MainMenu extends CWidget
{
	public $menutype;
	public $activemenu;
	public $currentProject;

	public function run()
	{
		$this->render('mainMenu');
	}
}
<?php

namespace App\Presenters;

use Nette;


class HomepagePresenter extends Nette\Application\UI\Presenter
{
	private Nette\Database\Explorer $database;

	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}

	public function renderDefault(): void
{
	$this->template->posts = $posts = $this->database->table('zamestnanci')
	
	    ->order('id ASC');
		bdump($posts);
	$this->template->teams = $this->database->table('tymy')
	    ->order('id ASC');
		$this->template->propoj = $this->database->table('propojeni')
	    ->order('id ASC');
}

	// ...
}

<?php

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;


class PostPresenter extends Nette\Application\UI\Presenter
{
	private Nette\Database\Explorer $database;

	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}

	public function renderShow(int $postId): void
	{
		$this->template->post = $this->database->table("zamestnanci")->get($postId);        
	}
	public function renderemploadd(int $postId): void
	{
		$this->template->post = $this->database->table("tymy")->get($postId);        
	}
	public function renderteamslist(): void
	{
		$this->template->posts = $posts = $this->database->table('zamestnanci')
		
			->order('id ASC');
			
		$this->template->teams = $this->database->table('tymy')
			->order('id ASC');
			$this->template->propoj = $this->database->table('propojeni')
			->order('id ASC');


	
		    
			
	}
	public function renderemploye(): void
	{
		$this->template->posts = $posts = $this->database->table('zamestnanci')
		
			->order('id ASC');
			
		$this->template->teams = $this->database->table('tymy')
			->order('id ASC');
			$this->template->propoj = $this->database->table('propojeni')
			->order('id ASC');


	
		    
			
	}
	public function renderteams(): void
	{
		$this->template->posts = $posts = $this->database->table('zamestnanci')
		
			->order('id ASC');
			
		$this->template->teams = $this->database->table('tymy')
			->order('id ASC');
			$this->template->propoj = $this->database->table('propojeni')
			->order('id ASC');


	
		    
			
	}
	


    protected function createComponentCommentForm(): Form
{

    $pozice = [
        'závodník' => 'závodník',
        'technik' => 'technik',
        'manažer' => 'manažer',
        'spolujezdec' => 'spolujezdec',
        'fotograf' => 'fotograf',
    ];
	$form = new Form; // means Nette\Application\UI\Form

	$form->addText('jmeno', 'Jméno:')
		->setRequired();

	$form->addText('prijmeni', 'Příjmení:')
    ->setRequired();
    $form->addSelect('vlastnost', 'Pozice:', $pozice)
	->setPrompt('Zvolte pozici')
    ->setRequired();
	

	$form->addSubmit('send', 'Přidat');
    $form->onSuccess[] = [$this, 'commentFormSucceeded'];



	return $form;
}
protected function createComponentTeamaddForm(): Form
{

   
	$form = new Form; 
	$form->addText('name', 'Název týmu:')
		->setRequired();

	

	$form->addSubmit('send', 'Přidat');
    $form->onSuccess[] = [$this, 'teamaddSucceeded'];



	return $form;
}
protected function createComponentDeletePerson(): Form
{

   
	$form = new Form; 
	$form->addSubmit('send', 'Smazat zaměstnance');
    $form->onSuccess[] = [$this, 'delete'];



	return $form;
}

public function createComponentTeamsForm(): Form
{
	$spolupracee = array();
	$pozice = array();
	$pozice1 = array();
	$pozice2 = array();
	$pozice3 = array();
	$pozice4 = array();

	$this->template->posts = $propoj = $this->database->table('propojeni') ->order('id ASC');
	$this->template->posts = $tymy = $this->database->table('tymy') ->order('id ASC');
	$this->template->posts = $posts = $this->database->table('zamestnanci') ->order('id ASC');
    foreach ($posts as $zavodnik)
	{
	if($zavodnik->vlastnost == "závodník") $pozice[$zavodnik->id] = " $zavodnik->jmeno  $zavodnik->prijmeni";
	if($zavodnik->vlastnost == "spolujezdec") $pozice1[$zavodnik->id] = "$zavodnik->jmeno  $zavodnik->prijmeni";
	if($zavodnik->vlastnost == "technik") $pozice2[$zavodnik->id] = "$zavodnik->jmeno  $zavodnik->prijmeni";
	if($zavodnik->vlastnost == "manažer") $pozice3[$zavodnik->id] = "$zavodnik->jmeno  $zavodnik->prijmeni";
	if($zavodnik->vlastnost == "fotograf") $pozice4[$zavodnik->id] = "$zavodnik->jmeno  $zavodnik->prijmeni";

    }

	$postId = $this->getParameter('postId');
	foreach ($propoj as $spoluprace)
	{
		if ($spoluprace->idTeams == $postId) 
		{
			$spolupracee[$spoluprace->idemplo] = $spoluprace->idemplo;
		}					
    }
	$pozicePZ = array();
	$pozicePZ1 = array();
	$pozicePZ2 = array();
	$pozicePZ3= array();
	$pozicePZ4 = array();
	foreach ($posts as $pracujici)
	{
		if($pracujici->vlastnost == "závodník" && in_array($pracujici->id, $spolupracee)) $pozicePZ[$pracujici->id] = $pracujici->id;
		if($pracujici->vlastnost == "spolujezdec" && in_array($pracujici->id, $spolupracee)) $pozicePZ1[$pracujici->id] = $pracujici->id;
		if($pracujici->vlastnost == "technik" && in_array($pracujici->id, $spolupracee)) $pozicePZ2[$pracujici->id] = $pracujici->id;
		if($pracujici->vlastnost == "manažer" && in_array($pracujici->id, $spolupracee)) $pozicePZ3[$pracujici->id] = $pracujici->id;
		if($pracujici->vlastnost == "fotograf" && in_array($pracujici->id, $spolupracee)) $pozicePZ4[$pracujici->id] = $pracujici->id;
    }


       
	$form = new Form; // means Nette\Application\UI\Form


    $form->addMultiSelect('Zavodnici', 'Závodníci:', $pozice)
	->setDefaultValue($pozicePZ)
	->addRule($form::MAX_LENGTH,"Maximálně tři závodníci!", 3)
	->setRequired();
	$form->addMultiSelect('Spolujezdci', 'Spolujezdci:', $pozice1)
	->setDefaultValue($pozicePZ1)
	->addRule($form::MAX_LENGTH,"Maximálně tři spolujezdci!", 3)
    ->setRequired();
	$form->addMultiSelect('Technici', 'Technici:', $pozice2)
	->setDefaultValue($pozicePZ2)
	->addRule($form::MAX_LENGTH,"Maximálně dva technici!", 2)
    ->setRequired();
	$form->addMultiSelect('Manazeri', 'Manažeři:', $pozice3)
	->setDefaultValue($pozicePZ3)
	->addRule($form::MAX_LENGTH,"Maximálně JEDEN manažér!", 1)
    ->setRequired();
	$form->addMultiSelect('Fotografove', 'Fotografové:', $pozice4)
	->setDefaultValue($pozicePZ4)
	->addRule($form::MAX_LENGTH,"Maximálně JEDEN fotograf!", 1);

	

	$form->addSubmit('send', 'Přidat');
    $form->onSuccess[] = [$this, 'Kdekdomaka'];



	return $form;
}



public function commentFormSucceeded(\stdClass $values): void
{
	$postId = $this->getParameter('id');

	$this->database->table('zamestnanci')->insert([
		
		'jmeno' => $values->jmeno,
		'prijmeni' => $values->prijmeni,
		'vlastnost' => $values->vlastnost,
	]);

	$this->flashMessage('Přidáno!', 'success');
	$this->redirect('this');
}
public function delete(\stdClass $values): void
{
	$postId = $this->getParameter('postId');
	$this->database->query('DELETE FROM zamestnanci WHERE id  = ?', $postId);
	$this->database->query('DELETE FROM propojeni WHERE idEmplo  = ?', $postId);

	$this->flashMessage('Přidáno!', 'success');
	$this->redirect('Homepage:default');
}
public function teamaddSucceeded(\stdClass $values): void
{
	
	$this->database->table('tymy')->insert([
		
		'nazev' => $values->name,
		
	]);

	$this->flashMessage('Přidáno!', 'success');
	$this->redirect('this');
}
public function Kdekdomaka(\stdClass $values): void
{
	
	$postId = $this->getParameter('postId');
	$this->database->query('DELETE FROM propojeni WHERE idTeams  = ?', $postId);
   foreach ($values->Zavodnici as $lidi) 
   {	     
	$this->database->table('propojeni')->insert([
		
		 'idTeams' => $postId,
		 'idEmplo' => $lidi,
	
	]);
   }
   foreach ($values->Spolujezdci as $lidi) 
   {	     
	$this->database->table('propojeni')->insert([
		
		 'idTeams' => $postId,
		 'idEmplo' => $lidi,
	
	]);
   }
   foreach ($values->Technici as $lidi) 
   {	     
	$this->database->table('propojeni')->insert([
		
		 'idTeams' => $postId,
		 'idEmplo' => $lidi,
	
	]);
   }
   foreach ($values->Manazeri as $lidi) 
   {	     
	$this->database->table('propojeni')->insert([
		
		 'idTeams' => $postId,
		 'idEmplo' => $lidi,
	
	]);
   }
   foreach ($values->Fotografove as $lidi) 
   {	     
	$this->database->table('propojeni')->insert([
		
		 'idTeams' => $postId,
		 'idEmplo' => $lidi,
	
	]);
   }
	$this->flashMessage('Přidáno!!!!', 'success');
	$this->redirect('this');
}

}

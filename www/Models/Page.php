<?php

namespace App\Models;

use App\Core\Database;

class Page extends Database
{

	private $id = null;
	protected $title;
	protected $slug;

	public function __construct(){
		parent::__construct();
	}
	
	public function getId(){
		return $this->id;
	}


	public function setId($id){
		$this->id=$id;
	}


	public function setTitle($title){
		$this->title=$title;
	}


	public function setSlug($slug){
		$this->slug=$slug;
	}
}
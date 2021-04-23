<?php

namespace App\Models;

use App\Core\Database;

class Page extends Database
{

	private $id = null;
	protected $title;
    protected $slug;
    protected $position;
    protected $state;
    protected $titleSeo;
    protected $descriptionSeo;
    protected $publicationDate;
    protected $createdAt;
    protected $updateAt;
    protected $deletedAt;

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

	public function getAll()
    {
        $page = new Page();
        $page->findAll();
    }
}
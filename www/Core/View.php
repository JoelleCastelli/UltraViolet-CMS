<?php

namespace App\Core;

class View
{

	private $template;
	private $view;
	private $data = [];

	public function __construct($view, $template = "back"){
		$this->setTemplate($template);
		$this->setView($view, $template);
	}

	public function setTemplate($template) {
		if(file_exists("Views/templates/".$template.".tpl.php")){
			$this->template = "Views/templates/".$template.".tpl.php";
		} else {
			die("Le template n'existe pas");
		}
	}

	public function setView($view, $template) {
		if(file_exists("Views/$template/$view.view.php")) {
			$this->view = "Views/$template/$view.view.php";
		} else {
			die("La vue n'existe pas");
		}
	}

	public function assign($key, $value){
		$this->data[$key]=$value;
	}

	public function __destruct(){
		extract($this->data);
		include $this->template;
	}

}
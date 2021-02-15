<?php

namespace App\Core;


class View
{

	private $template;
	private $view;
	private $data = [];


	public function __construct($view, $template = "front"){
		$this->setTemplate($template);
		$this->setView($view);
	}

	public function setTemplate($template) {
		if(file_exists("Views/templates/".$template.".tpl.php")){
			$this->template = "Views/templates/".$template.".tpl.php";
		} else {
			die("Le template n'existe pas");
		}
	}

	public function setView($view) {
		if(file_exists("Views/".$view.".view.php")) {
			print_r($view);
			$this->view = "Views/".$view.".view.php";
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









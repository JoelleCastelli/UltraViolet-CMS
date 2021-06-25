<?php

namespace App\Core;

class View
{

	private string $template;
	private string $view;
	private array $data = [];

	public function __construct($view, $template = "back"){
	    if ($template != null)
            $this->setTemplate($template);
        $this->setView($view, $template);
        $this->assignFlash();
	}

	public function setTemplate($template) {
		if(file_exists("Views/templates/".$template.".tpl.php")){
			$this->template = "Views/templates/".$template.".tpl.php";
		} else {
			die("Le template Views/templates/".$template.".tpl.php n'existe pas");
		}
	}

	public function setView($view, $template) {
	    $template = $template ?? "noTemplate";
		if(file_exists("Views/$template/$view.view.php")) {
			$this->view = "Views/$template/$view.view.php";
		} else {
			die("La vue Views/$template/$view.view.php n'existe pas");
		}
	}

	public function assign($key, $value){
		$this->data[$key] = $value;
	}

	public function assignFlash() {
        if (isset($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $flash => $value) {
                $this->data['flash'][$flash] = $value;
            }
            unset($_SESSION['flash']);
        }
    }

    public function displayFlash($flash) {
        foreach ($flash as $flashType => $flashContent) {
            if(gettype($flashContent) == "array") {
                echo "<div class='flash flash-$flashType'>";
                foreach ($flashContent as $message) {
                    echo "<li>$message</li>";
                }
                echo "</div>";
            } else {
                echo "<div class='flash flash-$flashType'>$flashContent</div>";
            }
        }
    }

	public function __destruct(){
		extract($this->data);
		if(isset($this->template))
            include $this->template;
        else
            include $this->view;
	}

}
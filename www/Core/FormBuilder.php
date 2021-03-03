<?php

namespace App\Core;

class FormBuilder
{

	public function __construct(){
	}

	public static function render($config, $show=true){

		$html = "<form 
				method='".($config["config"]["method"]??"GET")."' 
				action='".($config["config"]["action"]??"")."'
				class='".($config["config"]["class"]??"")."'
				id='".($config["config"]["id"]??"")."'
				>";

		foreach ($config["inputs"] as $name => $configInput) {
			$html .="<label for='".($configInput["id"]??$name)."'>".($configInput["label"]??"")." </label>";

			// Check previous post
            // Using just one ternaire for easier code review
            $value = "";
            if (!empty($_POST)) {
                $value = ($configInput["type"] === "password") ? "" : $_POST[$name];
            }

			$html .="<input 
						type='".($configInput["type"]??"text")."'
						name='".$name."'
						value='".$value."'
						placeholder='".($configInput["placeholder"]??"")."'
						class='".($configInput["class"]??"")."'
						id='".($configInput["id"]??$name)."'
						".(!empty($configInput["required"])?"required='required'":"")."
						 ><br>";
		}
		
		if (isset($config["selects"])) {
            foreach ($config["selects"] as $name => $configSelect)
            {
                $html .="<label for='".($name)."'>".($configSelect["label"]??"")." </label>";
                $html .= "<select name='".$name."' id='".$name."'>";

                foreach($configSelect["options"] as $option)
                {
                    $html .= "<option 
                                value='".$option["value"]."'".
                                ($option["disabled"]??"") ." ".
                                ($option["selected"]??"") .
                            ">".
                            $option["label"].
                        "</option>";
                }

                $html .= "</select>";
            }
        }

        if (isset($config["radios"])) {
            foreach ($config["radios"] as $name => $configRadio)
            {
                foreach($configRadio["options"] as $option)
                {
                    $html .= "<input 
                                type='radio' 
                                id='" . $option['id'] . "' 
                                name='" . $name . "' 
                                value='".$option["value"] . "'
                                class='".($option["class"]??"")."' " .
                                ($option["checked"]??"") .
                            ">";

                    $html .= "<label for='" . $option['id'] . "'>" . $option['label'] . "</label>";
                }
            }
        }

        if (isset($config["checkboxes"])) {
            foreach ($config["checkboxes"] as $name => $configCheckbox)
            {
                foreach($configCheckbox["options"] as $option)
                {
                    $html .= "<input 
                                type='checkbox' 
                                id='" . $option['id'] . "' 
                                name='" . $option['name'] . "' 
                                value='".$option["value"] . "'
                                class='".($option["class"]??"")."' " .
                                (!empty($option["required"])?"required='required'":"") . " " .
                                ($option["checked"]??"") .
                        ">";
                    $html .= "<label for='" . $option['id'] . "'>" . $option['label'] . "</label>";
                }
            }
        }

        $html .= "<input type='submit' value=\"".($config["config"]["submit"]??"Valider")."\">";
		$html .= "</form>";

		if($show){
			echo $html;
		}else{
			return $html;
		}
	}

}
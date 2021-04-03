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
                $html .="<label class='" . ($configSelect['class_label']??"") . "' for='".($name)."'>".($configSelect["label"]??"")." </label>";
                $html .= "<select class='" . ($configSelect['class_select']??"")  . "' name='".$name."' id='".$name."'>";

                foreach($configSelect["options"] as $option)
                {

                    // Check previous post
                    // Using just one ternaire for easier code review
                    $value = false;
                    if (!empty($_POST[$name])) {
                        $value = $_POST[$name] === $option["value"];
                    }

                    if($value)
                    {
                        $html .= "<option 
                                value='" . $option["value"] . "'" .
                            ($option["disabled"]??"") ." " .
                            "selected " .
                            "class ='" . ($option['class']??"") . "' " .
                            ">".
                            $option["label"].
                            "</option>";
                    } else {
                        $html .= "<option 
                                value='" . $option["value"] . "'" .
                            ($option["disabled"]??"") ." " .
                            ($option["selected"]??"") . " " .
                            "class ='" . ($option['class']??"") . "' " .
                            ">".
                            $option["label"].
                            "</option>";
                    }
                }

                $html .= "</select><br>";
            }
        }

        if (isset($config["radios"])) {
            foreach ($config["radios"] as $name => $configRadio)
            {
                $html .= "<fieldset class='". ($configRadio['class_fieldset']??"") . "' id='" . ($configRadio['id']??"") . "'>";
                $html .= "<legend class='" . ($configRadio['class_legend']??"") . "'>" . $configRadio['label'] . "</legend>";

                foreach($configRadio["options"] as $option)
                {

                    // Check previous post
                    // Using just one ternaire for easier code review
                    $value = false;
                    if (!empty($_POST[$name])) {
                        $value = $_POST[$name] === $option["value"];
                    }

                    if($value)
                    {

                        $html .= "<input 
                                type='radio' 
                                id='" . $option['id'] . "' 
                                name='" . $name . "' 
                                value='".$option["value"] . "'
                                class='".($option["class_input"]??"")."' checked" .
                            ">";
                    }else {
                        $html .= "<input 
                                type='radio' 
                                id='" . $option['id'] . "' 
                                name='" . $name . "' 
                                value='".$option["value"] . "'
                                class='".($option["class_input"]??"")."' " .
                            ($option["checked"]??"") .
                            ">";
                    }

                    $html .= "<label for='" . $option['id'] . "' class='" . ( $option['class_label']??"") . "'>" . $option['label'] . "</label><br>";
                }

                $html .= "</fieldset>";

            }
        }

        if (isset($config["checkboxes"])) {

            foreach ($config["checkboxes"] as $name => $configCheckbox)
            {
                $html .= "<fieldset class='". ($configCheckbox['class_fieldset']??"") . "' id='" . ($configCheckbox['id']??"") . "'>";
                $html .= "<legend class='" . ($configCheckbox['class_legend']??"") . "'>" . $configCheckbox['label'] . "</legend>";

                foreach($configCheckbox["options"] as $option)
                {

                    // Check previous post
                    // Using just one ternaire for easier code review
                    $value = false;
                    if (!empty($_POST[$name])) {

                        foreach ($_POST[$name] as $key => $val)
                        {
                            $value = $_POST[$name][$key] === $option['value'];

                            if($value)
                                break;
                        }
                    }

                    if($value)
                    {
                        $html .= "<input 
                                type='checkbox' 
                                id='" . $option['id'] . "' 
                                name='" . $name . "[]' 
                                value='".$option["value"] . "'
                                class='".($option["class_input"]??"")."' " .
                            (!empty($option["required"])?"required='required'":"") . " checked" .
                            ">";
                    } else {
                        $html .= "<input 
                                type='checkbox' 
                                id='" . $option['id'] . "' 
                                name='" . $name . "[]' 
                                value='".$option["value"] . "'
                                class='".($option["class_input"]??"")."' " .
                            (!empty($option["required"])?"required='required'":"") . " " .
                            ($option["checked"]??"") .
                            ">";
                    }
                    $html .= "<label for='" . $option['id'] . "' class='" . ($option['class_label']??"") . "'>" . $option['label'] . "</label><br>";
                }

                $html .= "</fieldset>";
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
<?php

namespace App\Core;

class FormBuilder
{

	public function __construct(){
	}

	public static function render($config, $show = true){

		$html = "<form method = '".($config["config"]["method"] ?? "GET")."' 
                       action = '".($config["config"]["action"] ?? "")."'
                       class = '".($config["config"]["class"] ?? "")."'
                       id = '".($config["config"]["id"] ?? "")."'
				>";

		foreach ($config["fields"] as $fieldName => $field) {

            $required = isset($field["required"]) && $field["required"] == true ? "required" : '';
            $disabled = isset($field["disabled"]) && $field["disabled"] == true ? "disabled" : '';

            $html .= "<label for = '".($field["id"] ?? $fieldName)."'>".($field["label"] ?? "")." </label>";

            // SELECT
		    if ($field["type"] == "select") {

                $html .= "<select name='$fieldName' id='".($field["id"] ?? $fieldName)."'>";
                foreach($field["options"] as $option) {
                    $selected = isset($option["selected"]) &&  $option["selected"] == true ? "selected" : '';
                    $html .= "<option value='".$option["value"]."' $selected $disabled>".$option["text"]."</option>";
                }
                $html .= "</select>";

            // RADIO
            } elseif($field['type'] == 'radio') {
                $html .= "<fieldset id='".($field["id"] ?? $fieldName)."'>";
                foreach($field["options"] as $option) {
                    $disabledOption = isset($option["disabled"]) && $option["disabled"] == true ? "disabled" : '';
                    $checked = isset($option["checked"]) && $option["checked"] == true ? "checked" : '';
                    if (!empty($_POST[$fieldName]) && $_POST[$fieldName] == $option["value"]) {
                        $checked = "checked";
                    }
                    $html .= "<input type='radio' name='".$fieldName."' value='".$option["value"]."' $checked $disabledOption>";
                    $html .= "<label for='".$option['value']."' $disabledOption>".$option['text']."</label>";
                }
                $html .= "</fieldset>";

            // CHECKBOX
            } elseif ($field['type'] == 'checkbox') {

                $html .= "<fieldset id='".($field["id"] ?? $fieldName)."'>";
                foreach($field["options"] as $option) {
                    $disabledOption = isset($option["disabled"]) &&  $option["disabled"] == true ? "disabled" : '';
                    $selected = isset($option["selected"]) &&  $option["selected"] == true ? "selected" : '';
                    if (!empty($_POST[$fieldName]) && in_array($option['value'], $_POST[$fieldName])) {
                        $selected = "checked";
                    }
                    $html .= "<input type='checkbox' name='".$fieldName."[]' value='".$option["value"]."' $selected $disabledOption>";
                    $html .= "<label for='".$option['value']."' $disabledOption>".$option['text']."</label>";
                }
                $html .= "</fieldset>";

            // OTHER INPUTS
            } else {

                $value = $field['value'] ?? '';
                if (!empty($_POST[$fieldName])) {
                    $value = ($field['type'] === 'password') ? '' : htmlspecialchars($_POST[$fieldName], ENT_QUOTES);
                }

                $html .="<input
                    type='".($field["type"] ?? "text")."'
                    name='".$fieldName."'
                    value='".$value."'
                    placeholder='".($field["placeholder"] ?? "")."'
                    class='".($field["class"] ?? "")."'
                    min='".($field["min"] ?? "")."'
                    max='".($field["max"] ?? "")."'
                    id='".($field["id"] ?? $fieldName)."'
                    $required $disabled
                >";
            }
		}

        $html .= "<input type='submit' class='btn' value=\"".($config["config"]["submit"]??"Valider")."\">";
		$html .= "</form>";

		if($show) {
			echo $html;
		} else {
			return $html;
		}
	}

}
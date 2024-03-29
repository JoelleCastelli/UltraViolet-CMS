<?php

namespace App\Core;

class FormBuilder
{

	public function __construct(){
	}

	public static function render($config, $data = [], $show = true){

		$html = "<form method = '".($config["config"]["method"] ?? "GET")."' 
                       action = '".($config["config"]["action"] ?? "")."'
                       class = '".($config["config"]["class"] ?? "")."'
                       id = '".($config["config"]["id"] ?? "")."'
                       enctype = '".($config["config"]["enctype"] ?? "")."'
				>";

		foreach ($config["fields"] as $fieldName => $field) {

            $required = isset($field["required"]) && $field["required"] == true ? "required" : '';
            $disabled = isset($field["disabled"]) && $field["disabled"] == true ? "disabled" : '';
            $readonly = isset($field["readonly"]) && $field["readonly"] == true ? "readonly" : '';
            $multiple = isset($field["multiple"]) && $field["multiple"] == true ? "multiple" : '';

            // Add * next to label if required
            $html .= "<label class='".($field["classLabel"] ?? "")."' for = '".($field["id"] ?? $fieldName)."'>";
            $html .= ($field["label"] ?? "");
            if(!in_array($config["config"]["id"], ['form_register', 'form_add_media']))
                $html .= $required ? ' <span class="requiredField">*</span>' : '';
            $html .= "</label>";

            // SELECT
		    if ($field["type"] == "select") {
                $html .= "<select $multiple name='$fieldName' id='".($field["id"] ?? $fieldName)."'>";
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
                    } else if (!empty($data[$fieldName]) && $data[$fieldName] == $option["value"]){
                        $checked = "checked";
                    } else if (isset($option["checked"]) && $option["checked"] == true){
                        $checked = "checked";
                    }
                                        
                    $html .= "<input id='" . ($option["id"] ?? ""). "' type='radio' class='".($option["class"] ?? "")."' name='".$fieldName."' value='".$option["value"]."' $checked $disabledOption>";
                    $html .= "<label for='".$option['value']."' $disabledOption>".$option['text']."</label>";
                }
                $html .= "</fieldset>";

            // CHECKBOX
            } elseif ($field['type'] == 'checkbox') {
                $html .= "<fieldset id='".($field["id"] ?? $fieldName)."'>";
                foreach($field["options"] as $option) {
                    $disabledOption = isset($option["disabled"]) &&  $option["disabled"] == true ? "disabled" : '';
                    $selected = isset($option["checked"]) &&  $option["checked"] == true ? "checked" : '';

                    if (!empty($_POST[$fieldName]) && in_array($option['value'], $_POST[$fieldName])) {
                        $selected = "checked";
                    }else if (!empty($data[$fieldName]) && in_array($option['value'], $data[$fieldName])){
                        $checked = "checked";
                    }

                    $html .= "<input type='checkbox' class='".($option["class"] ?? "")."' name='".$fieldName."[]' value='".$option["value"]."' $selected $disabledOption>";
                    $html .= "<label for='".$option['value']."' $disabledOption>".$option['text']."</label>";
                }
                $html .= "</fieldset>";

            } elseif ($field['type'] == 'textarea') {

                $value = $field['value'] ?? '';
                if (!empty($_POST[$fieldName])) {
                    $value = htmlspecialchars($_POST[$fieldName], ENT_QUOTES);
                }else if (!empty($data[$fieldName])){
                    $value = htmlspecialchars($data[$fieldName], ENT_QUOTES);
                }

                $html .="<textarea wrap='off' name='".$fieldName."'
                                   placeholder='".($field["placeholder"] ?? "")."'
                                   class='".($field["class"] ?? "")."'
                                   id='".($field["id"] ?? $fieldName)."'
                                   $required $disabled $readonly>$value</textarea>";

            // OTHER INPUTS
            } else {

                $value = $field['value'] ?? '';
                
                if (!empty($_POST[$fieldName])) {
                    $value = ($field['type'] === 'password') ? '' : htmlspecialchars($_POST[$fieldName], ENT_QUOTES);
                }else if(!empty($data[$fieldName])) {
                    $value = ($field['type'] === 'password') ? '' : htmlspecialchars($data[$fieldName], ENT_QUOTES);
                }

                // Add preview for app logo and favicon in Settings page
                if($fieldName == "logo")
                    $html .="<img id='appLogo' src='".PATH_TO_IMG."logo/$fieldName.png'>";
                if($fieldName == "favicon")
                    $html .="<img id='appFavicon' src='".PATH_TO_IMG."logo/$fieldName.ico'>";

                $html .="<input
                    type='".($field["type"] ?? "text")."'
                    name='".$fieldName."'
                    value=\"".$value."\"
                    placeholder=\"".($field["placeholder"] ?? "")."\"
                    class='".($field["class"] ?? "")."'
                    min='".($field["min"] ?? "")."'
                    max='".($field["max"] ?? "")."'
                    step='".($field["step"] ?? "")."'
                    id='".($field["id"] ?? $fieldName)."'
                    accept='".($field["accept"] ?? "")."'
                    $required $disabled $readonly $multiple
                >";
            }
		}
        $html .= "<input type='submit' class='btn tagsBackground tagsColor' value=\"".($config["config"]["submit"]??"Valider")."\">";
		$html .= "</form>";

		if($show) {
			echo $html;
		} else {
			return $html;
		}
	}

    public static function generateCSRFToken() {
        $token = bin2hex(random_bytes(32)).time();
        $_SESSION['csrfTokens'][] = $token;
        return $token;
    }

}
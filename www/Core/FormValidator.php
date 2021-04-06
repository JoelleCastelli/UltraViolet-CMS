<?php
namespace App\Core;

class FormValidator
{

	public static function check($config, $data)
	{

		$errors = [];
		$pattern = '/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';

        $checkboxes = count($config["checkboxes"])??0;
        $radios = count($config["radios"])??0;
        $selects = count($config["selects"])??0;
        $total_inputs =  count($config["inputs"]) + $checkboxes + $radios + $selects;

		if (!(count($data) != $total_inputs)) {
			$errors[] = "Tentative de HACK - Faille XSS";

		}
		else {

			foreach ($config["inputs"] as $name => $configInputs) {

			    if(!isset($data[$name]))
                {
                    echo "Tentative de hack formulaire";
                    exit;
                    //$errors = ["Tentative de Hack du formulaire"];
                    //return $errors;
                }

			    // if required or input not empty
			    if( !empty($configInputs["required"]) || !empty($data[$name]))
                {
                    // check string min length
                    if (!empty($configInputs["minLength"]) && strlen($data[$name]) < $configInputs["minLength"])
                        $errors[] = $configInputs["error"];

                    // check string max length
                    if (!empty($configInputs["maxLength"]) && strlen($data[$name]) > $configInputs["maxLength"]) {
                        $errors[] = $configInputs["error"];
                    }

                    // check number min length
                    if (!empty($configInputs["min"]) && is_numeric($configInputs["min"]) && $data[$name] < $configInputs["min"] )
                        $errors[] = $configInputs["error"];

                    // check number max length
                    if (!empty($configInputs["max"]) && is_numeric($configInputs["max"]) && $data[$name] > $configInputs["max"]) {
                        $errors[] = $configInputs["error"];
                    }

                    //check password match
                    if ($name == "pwd" && preg_match($pattern, $data["pwd"])) {
                        $errors[] = $configInputs["error"];
                    }

                    //check password confirm
                    if ( $name == "pwdConfirm" && preg_match($pattern, $data["pwdConfirm"])) {
                        $errors[] = $configInputs["error"];
                    }

                    // check email
                    if ($name == "email") {

                        $emailvalidator = self::emailValidator($data["email"]);

                        if ($emailvalidator == false) {

                            $configInputs["error"] = "Votre email n'est pas valide";
                            $errors[] = $configInputs["error"];
                        }
                    }

                    // check date min
                    if (!empty($configInputs["min"]) && self::checkValidDate($configInputs["min"]) && self::checkValidDate($data[$name]) && ($data[$name]) < $configInputs["min"]) {
                        $errors[] = $configInputs["error"];
                    }

                    // check date max
                    if (!empty($configInputs["max"]) && self::checkValidDate($configInputs["max"]) && self::checkValidDate($data[$name]) && ($data[$name]) > $configInputs["max"]) {
                        $errors[] = $configInputs["error"];
                    }

                }

            }

            if (isset($config["selects"] )) {
                foreach ($config["selects"] as $name => $configSelects) {

                    if(!empty($configSelects["required"]) &&
                        (isset($data[$name]) || empty($data[$name]))
                    ) {
                        $errors[] = $configSelects["error"];
                    }
                }
            }

            if (isset($config["radios"] )) {
                foreach ($config["radios"] as $name => $configRadios) {

                    if(!empty($configRadios["required"]) &&
                        (isset($data[$name]) || empty($data[$name]))
                    )
                    {
                        $errors[] = $configRadios["error"];
                    }
                }
            }

            if (isset($config["checkboxes"] )) {
                foreach ($config["checkboxes"] as $name => $configCheckboxes) {

                    if(!empty($configCheckboxes["required"]) &&
                        (isset($data[$name]) || empty($data[$name]))
                    )
                    {
                        $errors[] = $configCheckboxes["error"];
                    }

                }
            }

        }

		return $errors; //[] vide si ok
	}

	public function emailValidator($email)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
			return true;
		}

		return false;
	}

	public function checkValidDate()
    {
        
        return true;
    }

}
<?php
namespace App\Core;

class FormValidator
{

	public static function check($config, $data)
	{
	    print_r($data);

		$errors = [];
		$pattern = '/^(?=.*[!@#$%^&*-])(?=.*[0-9])(?=.*[A-Z]).{8,20}$/';

		if (count($data) != count($config["inputs"])) {
			$errors[] = "Tentative de HACK - Faille XSS";

		} else {

			foreach ($config["inputs"] as $name => $configInputs) {

				if(	!empty($configInputs["minLength"])
					&& is_numeric($configInputs["minLength"])
					&& strlen($data[$name]) < $configInputs["minLength"]
					&& !empty($configInputs["required"])
					) {

					$errors[] = $configInputs["error"];

				}

				$emailvalidator = self::emailValidator($data["email"]);

				if($emailvalidator == false && $name == "email"){
				
					$configInputs["error"] = "Votre email n'est pas valide";
					$errors[] = $configInputs["error"];
				}

				if(preg_match($pattern, $data["pwd"] && $name == "pwd")){
					$errors[] = $configInputs["error"];
				}

				if(preg_match($pattern, $data["pwdConfirm"] && $name == "pwdConfirm")){
					$errors[] = $configInputs["error"];
				}

				// check min length
                if (!empty($configInputs["minLength"]) && is_numeric($configInputs["minLength"]) && strlen($data[$name]) < $configInputs["minLength"]) {
                    $errors[] = $configInputs["error"];
                }

                // check max length
                if (!empty($configInputs["maxLength"]) && is_numeric($configInputs["maxLength"]) && strlen($data[$name]) < $configInputs["maxLength"]) {
                    $errors[] = $configInputs["error"];
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

}
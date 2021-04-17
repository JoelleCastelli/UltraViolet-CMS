<?php
namespace App\Core;

class FormValidator
{

	public static function check($config, $data)
	{

		$errors = [];

        $checkboxes = count($config["checkboxes"])??0;
        $radios = count($config["radios"])??0;
        $selects = count($config["selects"])??0;
        $total_inputs =  count($config["inputs"]) + $checkboxes + $radios + $selects;

        // check if numbers of inputs is correct
		//if (!(count($data) != $total_inputs))
        if (false)
        {
			$errors[] = "Tentative de HACK - Faille XSS";

		}
		else {

			foreach ($config["inputs"] as $name => $configInputs) {

                $data[$name] = htmlspecialchars($data[$name]);

			    // if form has not been changed
			    if(!isset($data[$name]))
                {
                    echo "Tentative de hack formulaire";
                    exit;
                }

			    // if input is required or not empty
			    if( !empty($configInputs["required"]) || !empty($data[$name]))
                {

                    if($configInputs["type"] == "text")
                    {

                        // check string min length
                        if (!empty($configInputs["minLength"]) && strlen($data[$name]) < $configInputs["minLength"]) {
                            $errors[] = $configInputs["error"];
                            continue;
                        }

                        // check string max length
                        if (!empty($configInputs["maxLength"]) && strlen($data[$name]) > $configInputs["maxLength"]) {
                            $errors[] = $configInputs["error"];
                            continue;
                        }

                        // check string max length
                        if (!empty($configInputs["regex"]) && !preg_match($configInputs["regex"], $data[$name])) {
                            $errors[] = $configInputs["error"];
                            continue;
                        }

                    }

                    if($configInputs["type"] == "number")
                    {
                        // check number min length
                        if (!empty($configInputs["min"]) && is_numeric($configInputs["min"]) && $data[$name] < $configInputs["min"] ) {
                            $errors[] = $configInputs["error"];
                            continue;
                        }

                        // check number max length
                        if (!empty($configInputs["max"]) && is_numeric($configInputs["max"]) && $data[$name] > $configInputs["max"]) {
                            $errors[] = $configInputs["error"];
                            continue;

                        }
                    }

                    //check password match
                    if ($name == "pwd" && !preg_match($configInputs["regex"], $data["pwd"])  ) {
                        $errors[] = $configInputs["error"];
                        continue;

                    }

                    //check password confirm
                    if ($name == "pwdConfirm" && ($data["pwdConfirm"] !== $data[$configInputs["confirm"]])) {
                        $errors[] = $configInputs["error"];
                        continue;

                    }

                    // check email
                    if ($configInputs["type"] == "email") {

                        $emailvalidator = self::emailValidator($data["email"]);

                        if ($emailvalidator == false) {

                            $configInputs["error"] = "Votre email n'est pas valide";
                            $errors[] = $configInputs["error"];
                            continue;

                        }
                    }

                    // check date min
                    if($configInputs["type"] == "date")
                    {
                        if (!empty($configInputs["min"]) && self::dateValidator($data[$name], "Y-m-d") && new \DateTime($data[$name]) >= new \DateTime($configInputs["min"])) {
                            $errors[] = $configInputs["error"];
                            continue;

                        }

                        // check date max
                        if (!empty($configInputs["max"]) && self::dateValidator($data[$name], "Y-m-d") &&  new \DateTime($data[$name]) <= new \DateTime($configInputs["max"])) {
                            $errors[] = $configInputs["error"];
                            continue;

                        }

                    }

                }

            }

            if (isset($config["selects"] )) {
                foreach ($config["selects"] as $name => $configSelects) {

                    if(!empty($configSelects["required"]) && (isset($data[$name]) || empty($data[$name])))
                    {
                        $errors[] = $configSelects["error"];
                    }
                }
            }

            if (isset($config["radios"] )) {
                foreach ($config["radios"] as $name => $configRadios) {

                    if(!empty($configRadios["required"]) && (isset($data[$name]) || empty($data[$name]))
                    )
                    {
                        $errors[] = $configRadios["error"];
                    }
                }
            }

            if (isset($config["checkboxes"] )) {
                foreach ($config["checkboxes"] as $name => $configCheckboxes) {

                    if(!empty($configCheckboxes["required"]) && (isset($data[$name]) || empty($data[$name])))
                    {
                        $errors[] = $configCheckboxes["error"];
                    }

                }
            }

        }

		return $errors; //[] empty if it's all okay
	}

	public function emailValidator($email)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL)){
			return true;
		}

		return false;
	}

	public function dateValidator($date, $format)
    {
        $d = \DateTime::createFromFormat($format, $date); // create object date from format a date in string
        return $d && $d->format($format) == $date;
    }

}

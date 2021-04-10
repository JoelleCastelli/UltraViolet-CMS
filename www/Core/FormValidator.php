<?php
namespace App\Core;

class FormValidator
{

	public static function check($config, $data)
	{

		$errors = [];

        $checkboxes = isset($config["checkboxes"]) ? count($config["checkboxes"]) : 0;
        $radios = isset($config["radios"]) ? count($config["radios"]) : 0;
        $selects = isset($config["selects"]) ? count($config["selects"]) : 0;
        $total_inputs =  count($config["inputs"]) + $checkboxes + $radios + $selects;

        // check if numbers of inputs is correct
		//if (!(count($data) != $total_inputs))
        if (false)
        {
			$errors[] = "Tentative de HACK - Faille XSS";

		}
		else {

			foreach ($config["inputs"] as $name => $configInputs) {

                $data[$name] = htmlspecialchars($data[$name], ENT_QUOTES);

			    // if form has not been changed
			    if(!isset($data[$name]))
                {
                    echo "Tentative de hack formulaire";
                    exit;
                }

			    // if input is required or not empty
			    if( !empty($configInputs["required"]) || !empty($data[$name]))
                {

                    echo "BFEORE " . $name . "<br>";
                    if($configInputs["type"] == "text")
                        self::textValidator($data[$name], $configInputs, $errors);

                    if($configInputs["type"] == "number")
                        self::numberValidator($data[$name], $configInputs, $errors);

                    //check password match
                    if ($name == "pwd")
                        self::passwordValidator($data[$name], $configInputs, $errors);

                    //check password confirm
                    if ($name == "pwdConfirm")
                        self::passwordConfirmationValidator($data[$name], $data[$configInputs["confirm"]], $configInputs, $errors);

                    // check email
                    if ($configInputs["type"] == "email")
                        self::emailValidator($data["email"], $configInputs, $errors);

                    // check date min
                    if($configInputs["type"] == "date")
                        self::dateValidator($data[$name], "Y-m-d", $configInputs, $errors);
                    echo "AFTER" . "<br>";

                }

            }

            if (isset($config["selects"] )) {
                foreach ($config["selects"] as $name => $configSelects) {

                    if(!empty($configSelects["required"]) && (!isset($data[$name]) || empty($data[$name])))
                    {
                        $errors[] = $configSelects["error"];
                    }
                }
            }

            if (isset($config["radios"] )) {
                foreach ($config["radios"] as $name => $configRadios) {

                    if(!empty($configRadios["required"]) && (!isset($data[$name]) || empty($data[$name]))
                    )
                    {
                        $errors[] = $configRadios["error"];
                    }
                }
            }

            if (isset($config["checkboxes"] )) {
                foreach ($config["checkboxes"] as $name => $configCheckboxes) {

                    if(!empty($configCheckboxes["required"]) && (!isset($data[$name]) || empty($data[$name])))
                    {
                        $errors[] = $configCheckboxes["error"];
                    }

                }
            }

        }

		return $errors; //[] empty if it's all okay
	}

    public function textValidator($text, $configInputs, &$errors)
    {
        // check string min length
        if (!empty($configInputs["minLength"]) && strlen($text) < $configInputs["minLength"]) {
            $errors[] = $configInputs["error"];
            return;
        }

        // check string max length
        if (!empty($configInputs["maxLength"]) && strlen($text) > $configInputs["maxLength"]) {
            $errors[] = $configInputs["error"];
            return;
        }

        // check string max length
        if (!empty($configInputs["regex"]) && !preg_match($configInputs["regex"], $text)) {
            $errors[] = $configInputs["error"];
            return;
        }
    }

    public function numberValidator($number, $configInputs, &$errors)
    {
        // check number min length
        if (!empty($configInputs["min"]) && is_numeric($configInputs["min"]) && $number < $configInputs["min"] ) {
            $errors[] = $configInputs["error"];
            return;
        }

        // check number max length
        if (!empty($configInputs["max"]) && is_numeric($configInputs["max"]) && $number > $configInputs["max"]) {
            $errors[] = $configInputs["error"];
           return;
        }
    }

    public function passwordValidator($password, $configInputs, &$errors) {
        if (!preg_match($configInputs["regex"], $password)  ) {
            $errors[] = $configInputs["error"];
            return;
        }
    }

    public function passwordConfirmationValidator($passwordConfirm, $password, $configInputs, &$errors) {
        if ($passwordConfirm !== $password) {
            $errors[] = $configInputs["error"];
            return;
        }
    }

	public function emailValidator($email, $configInputs, &$errors)
	{
		if(filter_var($email, FILTER_VALIDATE_EMAIL) == false){
            $configInputs["error"] = "Votre email n'est pas valide";
            $errors[] = $configInputs["error"];
            return;
		}
	}

	public function dateValidator($date, $format, $configInputs, &$errors)
    {
        $d = \DateTime::createFromFormat($format, $date); // create object date from format a date in string

        if( $d && $d->format($format) == $date)
        {
            // check date min
            if (!empty($configInputs["min"]) && (new \DateTime($date) <= new \DateTime($configInputs["min"]))) {
                $errors[] = $configInputs["error"];
                return;
            }

            // check date max
            if (!empty($configInputs["max"]) && (new \DateTime($date) >= new \DateTime($configInputs["max"]))) {
                $errors[] = $configInputs["error"];
                return;

            }
        }
    }
}

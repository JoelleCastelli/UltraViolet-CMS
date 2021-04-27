<?php

namespace App\Core;

class FormValidator
{

    public static function check($config, $data) {

        self::validateCSFRToken($config['config'], $data);
        $errors = [];

        if(count($data) != count($config["fields"])) {
            $errors[] = "Tentative de HACK - Faille XSS";
        } else {
            foreach ($config["fields"] as $fieldName => $fieldConfig) {

                // check if config field has a matching $_POST field
                if(!isset($data[$fieldName])) {
                    echo "Tentative de hack !";
                    exit;
                }

                // check if required field is not empty
                if (isset($fieldConfig['required']) && empty($data[$fieldName])) {
                    echo "Tentative de hack : le champ est obligatoire !";
                    exit;
                }

                if(!empty($data[$fieldName])) {
                    self::textInputValidator($data[$fieldName], $fieldConfig, $errors);
                    self::numberInputValidator($data[$fieldName], $fieldConfig, $errors);
                    self::passwordValidator($fieldName, $data[$fieldName], $fieldConfig, $errors);
                    self::passwordConfirmationValidator($fieldName, $data, $fieldConfig, $errors);
                    self::emailInputValidator($data[$fieldName], $fieldConfig, $errors);
                    self::dateInputValidator($data[$fieldName], "Y-m-d", $fieldConfig, $errors);
                    self::optionsValidator($data[$fieldName], $fieldConfig);
                }
            }
        }
        return $errors;
    }

    public static function textInputValidator($textInput, $fieldConfig, &$errors) {
        if($fieldConfig["type"] == "text") {
            if (!empty($fieldConfig["minLength"]) && strlen($textInput) < $fieldConfig["minLength"]) {
                $errors[] = $fieldConfig["error"];
            }
            if (!empty($fieldConfig["maxLength"]) && strlen($textInput) > $fieldConfig["maxLength"]) {
                $errors[] = $fieldConfig["error"];
            }
            if (!empty($fieldConfig["regex"]) && !preg_match($fieldConfig["regex"], $textInput)) {
                $errors[] = $fieldConfig["error"];
            }
        }
    }

    public static function numberInputValidator($numberInput, $fieldConfig, &$errors){
        if($fieldConfig["type"] == "number") {
            if (!empty($field["min"]) && is_numeric($fieldConfig["min"]) && $numberInput < $fieldConfig["min"] ) {
                $errors[] = $fieldConfig["error"];
            }
            if (!empty($field["max"]) && is_numeric($fieldConfig["max"]) && $numberInput > $field["max"]) {
                $errors[] = $fieldConfig["error"];
            }
        }
    }

    public static function passwordValidator($fieldName, $password, $fieldConfig, &$errors) {
        if ($fieldName == "pwd") {
            if (!preg_match($fieldConfig["regex"], $password)  ) {
                $errors[] = $fieldConfig["error"];
            }
        }
    }

    public static function passwordConfirmationValidator($fieldName, $data, $fieldConfig, &$errors) {
        if ($fieldName == "pwdConfirm") {
            if ($data['pwdConfirm'] !== $data[$fieldConfig["confirm"]]) {
                $errors[] = $fieldConfig["error"];
            }
        }
    }

    public static function emailInputValidator($field, $fieldConfig, &$errors) {
        if ($fieldConfig["type"] == "email") {
            if(!filter_var($field, FILTER_VALIDATE_EMAIL)){
                $errors[] = $fieldConfig["error"];
            }
        }
    }

    public static function dateInputValidator($date, $format, $fieldConfig, &$errors) {
        if($fieldConfig["type"] == "date") {
            $d = \DateTime::createFromFormat($format, $date);
            if($d && $d->format($format) == $date) {
                if (!empty($fieldConfig["min"]) && (new \DateTime($date) <= new \DateTime($fieldConfig["min"]))) {
                    $errors[] = $fieldConfig["error"];
                }
                if (!empty($fieldConfig["max"]) && (new \DateTime($date) >= new \DateTime($fieldConfig["max"]))) {
                    $errors[] = $fieldConfig["error"];
                }
            }
        }
    }

    public static function optionsValidator($fieldContent, $fieldConfig) {
        $correctOptions = [];
        if(in_array($fieldConfig["type"], ['select', 'radio'])) {
            $correctOptions = [false];
            foreach ($fieldConfig['options'] as $option) {
                if ($fieldContent == $option['value']) {
                    $correctOptions = [true];
                }
            }
        } elseif($fieldConfig["type"] == 'checkbox') {
            foreach ($fieldContent as $key => $value) {
                $correctOptions[$key] = false;
                foreach ($fieldConfig['options'] as $option) {
                    if ($value == $option['value']) {
                        $correctOptions[$key] = true;
                    }
                }
            }
        }
        if(in_array(false, $correctOptions)) {
            echo "Tentative de hack : l'option n'existe pas !";
            exit;
        }
    }

    public static function validateCSFRToken($config, $data) {
        if(isset($_SESSION['csrf_token']) && isset($data['csrf_token'])) {
            if($_SESSION['csrf_token'] == $data['csrf_token']) {
                if($_SERVER['REQUEST_URI'] !== $config['referer']) {
                    echo "Attaque CSRF : la page source est incorrecte"; exit;
                }
            } else {
                echo "Attaque CSRF : les valeurs sont différentes"; exit;
            }
        } else {
            echo "Attaque CSRF: une des valeurs n'est pas présente"; exit;
        }
        unset($_SESSION['csrf_token']);
    }

}
<?php

namespace App\Core;

class FormValidator
{

    public static function check($config, $data) {

        self::validateCSFRToken($config);
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
                    self::emailInputValidator($data["email"], $fieldConfig, $errors);
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

    public static function emailInputValidator($email, $fieldConfig, &$errors) {
        if ($fieldConfig["type"] == "email") {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
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

    public static function getCSRFToken() {
        $message = '-encyclopedie Anticonstitutionnellement Ceci est une phrase random-';
        $hash = 'sha256';

        if(empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            $_SESSION['csrf_token_time'] = time();
        }

        $key = $_SESSION['csrf_token'] ;
        $token = hash_hmac($hash, $message,  $key); // Generate a keyed hash value using the HMAC method

        return $token;
    }

    public static function validateCSFRToken($config) {

        $message = '-encyclopedie Anticonstitutionnellement Ceci est une phrase random-';
        $hash = 'sha256';

        if(isset($_SESSION['csrf_token']) && isset($_SESSION['csrf_token_time']) && isset($config['config']['csrf'])) {

            $token = hash_hmac($hash, $message,  $_SESSION['csrf_token']);

            // check if the session's token equals form's token
            if(hash_equals($token, $config['config']['csrf'])) {
                // timestamp of 15 minutes
                $timestamp_ancien = time() - (15*60);

                // check if the timestamp is expired
                if($_SESSION['csrf_token_time'] >= $timestamp_ancien) {
                    unset($_SESSION['csrf_token_time']);
                    unset($_SESSION['csrf_token']);
                } else {
                    echo "CSRF ATTACK time not correct";
                    die();
                }
            } else {
                echo "CSRF ATTACK csrf not equal";
                die();
            }
        } else {
            echo "CSRF ATTACK csrf not set";
            die();
        }
    }

}

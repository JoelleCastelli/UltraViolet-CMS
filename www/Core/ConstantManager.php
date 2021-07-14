<?php

namespace App\Core;

class ConstantManager {

	private string $envFile = ".env";
	private array $data = [];

	public function __construct() {
	    // Check if .env file exists
		if(!file_exists($this->envFile))
			die("File ".$this->envFile." doesn't exist");

		// Parse file to fill $this->data
		$this->parseEnvFile($this->envFile);

		// If ENV=dev, parse .env.dev file
		if($this->data["ENV"] == "dev"){
			$devEnvFile = $this->envFile.".".$this->data["ENV"];
			if(!file_exists($devEnvFile))
				die("File ".$devEnvFile." doesn't exist");
			$this->parseEnvFile($devEnvFile);
		}

		$this->defineConstants();
	}

	private function defineConstants(){
		foreach ($this->data as $key => $value) {
			self::defineConstant($key, $value);
		}
	}

	public static function defineConstant($key, $value){
		if(!defined($key)) {
			define($key, $value);
		} else {
			die("Caution: you have used a constant reserved for this framework: ".$key);
		}
	}

	public function parseEnvFile($file) {
		$handle = fopen($file, "r");
		$regex = "/([^=]*)=([^#]*)/";
		if(!empty($handle)){
			while (!feof($handle)) {
				$line = fgets($handle);
				preg_match($regex, $line, $results);
				if(!empty($results[1]) && !empty($results[2]))
					$this->data[mb_strtoupper($results[1])] = trim($results[2]);
			}
		}
	}

}
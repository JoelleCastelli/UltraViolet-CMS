<?php

namespace App\Core;

class ConstantManager {

	private string $envFile = ".env";
	private array $data = [];

	public function __construct() {
		if(!file_exists($this->envFile))
			die("File ".$this->envFile." doesn't exist");

		$this->parsingEnv($this->envFile);

		if(!empty($this->data["ENV"])){
			$newFile = $this->envFile.".".$this->data["ENV"];

			if(!file_exists($newFile))
				die("File ".$newFile." doesn't exist");

			$this->parsingEnv($newFile);
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

	public function parsingEnv($file) {
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
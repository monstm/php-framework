<?php
	namespace Phenobytes\Framework;

	class Wizard{
		protected static function PathSeparator($Path){
			$ret = $Path;

			foreach(array(
				"\\" => "/",
				"/" => DIRECTORY_SEPARATOR
			) as $delimiter => $glue){
				$ret = implode($glue, explode($delimiter, $ret));
			}

			$temp = "";
			while($ret != $temp){
				$temp = $ret;
				$ret = implode(DIRECTORY_SEPARATOR, explode(DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR, $temp));
			}

			return $ret;
		}

		protected static function AbsolutePath($Prefix, $Suffix){
			if(is_dir($Prefix)){
				$path = self::PathSeparator($Prefix . DIRECTORY_SEPARATOR . $Suffix);
				if(is_dir($path)){
					$ret = realpath($path);
				}else{
					$ret = realpath($Prefix);
					error_log(__CLASS__ . "::" . __FUNCTION__ . "(" . $path . "): is not a directory");
				}
			}else{
				$ret = (isset($_SERVER["DOCUMENT_ROOT"]) ? $_SERVER["DOCUMENT_ROOT"] : __DIR__);
				die(__CLASS__ . "::" . __FUNCTION__ . "(" . $Prefix . "): is not a directory");
			}

			return $ret;
		}

		protected static function Structure(&$Result, $Default){
			if(gettype($Result) != gettype($Default)){
				$Result = $Default;
			}

			if(is_array($Default)){
				foreach($Default as $key => $value){
					self::Structure($Result[$key], $value);
				}
			}
		}

		protected static function AutoInclude($Path, $Recursive = false){
			if(is_dir($Path)){
				foreach(scandir($Path) as $scandir){
					if(!in_array($scandir, array(".", "..", "~"))){
						$realpath = realpath($Path . DIRECTORY_SEPARATOR . $scandir);

						if(is_dir($realpath) && $Recursive){
							self::AutoInclude($realpath, $Recursive);
						}

						if(is_file($realpath) && (strtolower(pathinfo($realpath, PATHINFO_EXTENSION)) == "php")){
							include_once($realpath);
						}
					}
				}
			}
		}


		private static function Normalize(&$Data){
			if(!is_array($Data)) $Data = array();
			self::Structure($Data, array("base" => ""));

			$Data["root"] = self::AbsolutePath(
				(isset($_SERVER["DOCUMENT_ROOT"]) ? $_SERVER["DOCUMENT_ROOT"] : __DIR__),
				$Data["base"]
			);
		}


		public static function Setup($Data = array()){
			self::Normalize($Data);

			$Data = \Phenobytes\Framework\WizardLibrary::Setup($Data);
			$Data = \Phenobytes\Framework\WizardConfig::Setup($Data);
			$Data = \Phenobytes\Framework\WizardModel::Setup($Data);
			$Data = \Phenobytes\Framework\WizardView::Setup($Data);
			$Data = \Phenobytes\Framework\WizardControl::Setup($Data, $control);
			\Phenobytes\Framework\Property::SetSetup($Data);

			$control->run();
		}
	}
?>
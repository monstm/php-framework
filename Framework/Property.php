<?php
	namespace Phenobytes\Framework;

	class Property{
		private static $Setup = array();
		private static $Config = array();

		private static $ViewLoader = array();
		private static $ViewEnvironment = array();


		public static function SetSetup($Data){
			if(is_array($Data)){
				self::$Setup = $Data;
			}else{
				self::trace("invalid setup data type: " . gettype($Data));
			}
		}

		public static function GetSetup($Key){
			if(isset(self::$Setup[$Key])){
				$ret = self::$Setup[$Key];
			}else{
				$ret = null;
				self::trace("setup key " . $Key . " is not exists");
			}

			return $ret;
		}

		//~ public static function DumpSetup(){
			//~ return self::$Setup;
		//~ }


		public static function SetConfig($Key, $Value){
			if(is_string($Key)){
				self::$Config[$Key] = $Value;
			}else{
				self::trace("invalid config key type: " . gettype($Data));
			}
		}

		public static function GetConfig($Key){
			if(isset(self::$Config[$Key])){
				$ret = self::$Config[$Key];
			}else{
				$ret = null;
				self::trace("config key " . $Key . " is not exists");
			}

			return $ret;
		}

		//~ public static function DumpConfig(){
			//~ return self::$Config;
		//~ }


		public static function SetViewLoader($Key, $Value){
			if(is_string($Key) && is_string($Value)){
				self::$ViewLoader[$Key] = $Value;
			}else{
				self::trace("invalid view loader key/value type: " . gettype($Key) . "/" . gettype($Value));
			}
		}

		public static function GetViewLoader(){
			return self::$ViewLoader;
		}

		public static function SetViewEnvironment($Data){
			if(is_array($Data)){
				self::$ViewEnvironment = $Data;
			}else{
				self::trace("invalid view loader data type: " . gettype($Data));
			}
		}

		public static function GetViewEnvironment(){
			return self::$ViewEnvironment;
		}

		protected static function log($Message){
			error_log($Message);
		}

		protected static function trace($Message){
			try{
				$backtrace = array_slice(debug_backtrace(), 1);
				self::log($Message . (count($backtrace) > 0 ? " " . json_encode($backtrace) : ""));
			}catch(Exception $error){
				self::log($error->getMessage() . " " . json_encode($error));
				self::log($Message);
			}
		}
	}
?>
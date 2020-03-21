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
			}
		}

		public static function GetSetup($Key){
			return (isset(self::$Setup[$Key]) ? self::$Setup[$Key] : null);
		}

		//~ public static function DumpSetup(){
			//~ return self::$Setup;
		//~ }


		public static function SetConfig($Key, $Value){
			self::$Config[$Key] = $Value;
		}

		public static function GetConfig($Key){
			return (isset(self::$Config[$Key]) ? self::$Config[$Key] : null);
		}

		public static function DumpConfig(){
			return self::$Config;
		}


		public static function SetViewLoader($Key, $Value){
			self::$ViewLoader[$Key] = $Value;
		}

		public static function GetViewLoader(){
			return self::$ViewLoader;
		}

		public static function SetViewEnvironment($Data){
			if(is_array($Data)){
				self::$ViewEnvironment = $Data;
			}
		}

		public static function GetViewEnvironment(){
			return self::$ViewEnvironment;
		}
	}
?>
<?php
	namespace Phenobytes\Framework;

	class WizardConfig extends Wizard{
		private static function Normalize(&$Data){
			self::Structure($Data, array(
				"config" => array(
					"path" => "/config",
					"recursive" => true
				)
			));

			$Data["config"]["path"] = self::AbsolutePath($Data["root"], $Data["config"]["path"]);
		}


		private static function SetProperty(){
			foreach(get_declared_classes() as $class){
				if(is_subclass_of($class, "\Phenobytes\Framework\Config")){
					$instance = new $class();
					foreach(get_object_vars($instance) as $key => $value){
						\Phenobytes\Framework\Property::SetConfig($class . ":" . $key, $value);
					}
				}
			}
		}


		public static function Setup($Data = array()){
			self::Normalize($Data);

			if($Data["config"]["path"] != $Data["root"]){
				self::AutoInclude($Data["config"]["path"], $Data["config"]["recursive"]);
			}

			self::SetProperty();

			return $Data;
		}
	}
?>
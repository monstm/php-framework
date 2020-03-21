<?php
	namespace Phenobytes\Framework;

	class WizardModel extends Wizard{
		private static function Normalize(&$Data){
			self::Structure($Data, array(
				"model" => array(
					"path" => "/model",
					"recursive" => true
				)
			));

			$Data["model"]["path"] = self::AbsolutePath($Data["root"], $Data["model"]["path"]);
		}

		public static function Setup($Data = array()){
			self::Normalize($Data);

			if($Data["model"]["path"] != $Data["root"]){
				self::AutoInclude($Data["model"]["path"], $Data["model"]["recursive"]);
			}

			return $Data;
		}
	}
?>
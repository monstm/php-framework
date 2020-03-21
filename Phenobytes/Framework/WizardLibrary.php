<?php
	namespace Phenobytes\Framework;

	class WizardLibrary extends Wizard{
		private static function Normalize(&$Data){
			self::Structure($Data, array(
				"library" => array(
					"path" => "/library",
					"recursive" => true
				)
			));

			$Data["library"]["path"] = self::AbsolutePath($Data["root"], $Data["library"]["path"]);
		}

		public static function Setup($Data = array()){
			self::Normalize($Data);

			if($Data["library"]["path"] != $Data["root"]){
				self::AutoInclude($Data["library"]["path"], $Data["library"]["recursive"]);
			}

			return $Data;
		}
	}
?>
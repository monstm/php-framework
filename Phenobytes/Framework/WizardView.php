<?php
	namespace Phenobytes\Framework;

	class WizardView extends Wizard{
		private static function Normalize(&$Data){
			self::Structure($Data, array(
				"view" => array(
					"path" => "/view", // View Path
					"recursive" => true, // Recursive Include
					"extension" => "twig", // View File Extension
					"environment" => array( // Twig Environment Options
						"debug" => false,
						"defaults" => "utf-8",
						"cache" => false,
						"auto_reload" => false,
						"strict_variables" => false,
						//"autoescape" => false,
						"optimizations" => -1
					)
				)
			));

			$Data["view"]["path"] = self::AbsolutePath($Data["root"], $Data["view"]["path"]);
			$Data["view"]["extension"] = strtolower(trim($Data["view"]["extension"]));
		}


		private static function SetLoader($AbsolutePath, $RelativePath, $Recursive, $Extension){
			if(is_dir($AbsolutePath)){
				foreach(scandir($AbsolutePath) as $scandir){
					if(!in_array($scandir, array(".", "..", "~"))){
						$absolute = realpath($AbsolutePath . DIRECTORY_SEPARATOR . $scandir);
						$relative = $RelativePath . $scandir;

						if(is_dir($absolute) && $Recursive){
							self::SetLoader($absolute, ($relative . DIRECTORY_SEPARATOR), $Recursive, $Extension);
						}

						if(is_file($absolute) && (strtolower(pathinfo($absolute, PATHINFO_EXTENSION)) == $Extension)){
							$content = @file_get_contents($absolute);
							if(trim($content) != ""){
								$pathinfo = pathinfo($relative);
								$name = implode(
									"/",
									explode(
										DIRECTORY_SEPARATOR,
										(
											(
												!in_array($pathinfo["dirname"], array(".", "..", "~")) ?
												$pathinfo["dirname"] . DIRECTORY_SEPARATOR : ""
											) .
											$pathinfo["filename"]
										)
									)
								);

								\Phenobytes\Framework\Property::SetViewLoader($name, $content);
							}
						}
					}
				}
			}
		}

		private static function SetEnvironment($Data){
			$result = array();
			foreach(array(
				"debug", "defaults", "cache",
				"auto_reload", "strict_variables",
				"autoescape", "optimizations"
			) as $key){
				if(isset($Data[$key])){
					$result[$key] = $Data[$key];
				}
			}

			\Phenobytes\Framework\Property::SetViewEnvironment($result);
		}


		public static function Setup($Data = array()){
			self::Normalize($Data);

			if($Data["view"]["path"] != $Data["root"]){
				self::SetLoader($Data["view"]["path"], "", $Data["view"]["recursive"], $Data["view"]["extension"]);
			}

			self::SetEnvironment($Data["view"]["environment"]);

			return $Data;
		}
	}
?>
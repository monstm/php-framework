<?php
	namespace Phenobytes\Framework;

	class WizardControl extends Wizard{
		private static function Normalize(&$Data){
			self::Structure($Data, array(
				"control" => array(
					"path" => "/control",
					"recursive" => true,
					"middleware" => array(),
					"error" => array(
						"display" => false,
						"callback" => function(
							\Psr\Http\Message\ServerRequestInterface $Request,
							\Psr\Http\Message\ResponseInterface $Response,
							\Throwable $Exception,
							bool $Display
						){
							return $Response->withStatus(404);
						}
					)
				)
			));

			$Data["control"]["path"] = self::AbsolutePath($Data["root"], $Data["control"]["path"]);
		}


		private static function AddMiddleware(&$Wizard, $Data){
			foreach($Data as $middleware){
				if(is_object($middleware)){
					$Wizard->add($middleware);
				}
			}
		}

		private static function ErrorMiddleware(&$Wizard, $Data){
			$error = $Wizard->addErrorMiddleware($Data["display"], true, true);
			//~ $error->setDefaultErrorHandler(function(
				//~ \Psr\Http\Message\ServerRequestInterface $Request,
				//~ \Throwable $Exception,
				//~ bool $DisplayErrorDetails,
				//~ bool $LogErrors,
				//~ bool $LogErrorDetails
			//~ ) use($Wizard, $Data){
				//~ return $Data["callback"](
					//~ $Request,
					//~ $Wizard->getResponseFactory()->createResponse(),
					//~ $Exception,
					//~ $DisplayErrorDetails
				//~ );
			//~ });
		}

		private static function AddControl(&$Wizard){
			$cache = array();

			foreach(get_declared_classes() as $class){
				if(is_subclass_of($class, "\Phenobytes\Framework\Control")){
					$instance = new $class();

					foreach($instance->__getroute() as $route => $map){
						if(is_string($route) && is_array($map)){
							if(!isset($cache[$route])){ $cache[$route] = array(); }

							foreach($map as $method => $callback){
								if(is_string($method) && is_string($callback)){
									$method = strtolower(trim($method));

									if(in_array($method, array("get", "post", "put", "delete", "options", "patch", "any"))){
										if(method_exists($instance, $callback)){
											if(!isset($cache[$route][$method])){
												$cache[$route][$method] = $callback;
												$Wizard->$method($route, $class . ":" . $callback);
											}else{
												error_log("Duplicate route control " . $class . ":" . $method . "@" . $route);
											}
										}else{
											error_log("Route method is not exists " . $class . ":" . $callback);

										}
									}
								}
							}
						}
					}
				}
			}

			foreach($cache as $route => $map){
				if(!isset($map["options"])){
					$Wizard->options($route, function(
						\Psr\Http\Message\ServerRequestInterface $Request,
						\Psr\Http\Message\ResponseInterface $Response,
						array $Arguments
					){
						return $Response->withStatus(200);
					});
				}
			}
		}


		public static function Setup($Data = array(), &$Wizard = null){
			self::Normalize($Data);

			if($Data["control"]["path"] != $Data["root"]){
				self::AutoInclude($Data["control"]["path"], $Data["control"]["recursive"]);
			}

			$Wizard = \Slim\Factory\AppFactory::create();
			$Wizard->setBasePath($Data["base"]);
			$Wizard->addBodyParsingMiddleware(); // Parse json, form data and xml
			$Wizard->addRoutingMiddleware();

			self::AddMiddleware($Wizard, $Data["control"]["middleware"]);
			self::ErrorMiddleware($Wizard, $Data["control"]["error"]);
			self::AddControl($Wizard);

			return $Data;
		}
	}
?>
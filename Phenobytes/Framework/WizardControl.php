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
			foreach(get_declared_classes() as $class){
				if(is_subclass_of($class, "\Phenobytes\Framework\Control")){
					$instance = new $class();

					foreach($instance->__getroute() as $route => $map){
						$options = false;
						foreach(array("get", "post", "put", "delete") as $method){ // "get", "post", "put", "delete", "options", "patch", "any"
							if(isset($map[$method]) && is_string($map[$method]) && is_string($map[$method])){
								$Wizard->$method($route, $class . ":" . $map[$method]);
								$options = true;
							//~ }else{
								//~ $app->$method($route, function(
									//~ Psr\Http\Message\ServerRequestInterface $Request,
									//~ Psr\Http\Message\ResponseInterface $Response,
									//~ array $Arguments
								//~ ){
									//~ return $Response->withStatus(405);
								//~ });
							}
						}

						if($options){
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
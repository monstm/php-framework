<?php
	namespace Phenobytes\Framework;

	class Seed{
		public function __construct(){}

		public function __destruct(){}

		public function DataKey($Data, $Key, $Default = null){
			return (is_array($Data) && isset($Data[$Key]) ? $Data[$Key] : $Default);
		}

		protected function config($ConfigName){
			return \Phenobytes\Framework\Property::GetConfig($ConfigName);
		}

		private function fork($ClassName, $Inheritance, $ErrorMessage){
			if(is_subclass_of($ClassName, $Inheritance)){
				$class = $ClassName;
			}else{
				$class = $Inheritance;
				$this->trace($ErrorMessage);
			}

			return (new $class());
		}

		protected function control($ClassName){
			return $this->fork(
				$ClassName, "\Phenobytes\Framework\Control",
				"invalid inheritance for control '" . $ClassName . "'"
			);
		}

		protected function model($ClassName){
			return $this->fork(
				$ClassName, "\Phenobytes\Framework\Model",
				"invalid inheritance for model '" . $ClassName . "'"
			);
		}

		protected function view($ViewName, $Data = array()){
			$property_loader = \Phenobytes\Framework\Property::GetViewLoader();
			if(isset($property_loader[$ViewName])){
				$twig_loader = new \Twig\Loader\ArrayLoader($property_loader);
				$property_environment = \Phenobytes\Framework\Property::GetViewEnvironment();
				$twig_environment = new \Twig\Environment($twig_loader, $property_environment);
				$ret = $twig_environment->render($ViewName, (is_array($Data) ? $Data : array()));
			}else{
				$ret = "";
			}

			return $ret;
		}

		protected function log($Message){
			error_log($Message);
		}

		protected function trace($Message){
			try{
				$backtrace = array_slice(debug_backtrace(), 1);
				$this->log($Message . (count($backtrace) > 0 ? " " . json_encode($backtrace) : ""));
			}catch(Exception $error){
				$this->log($error->getMessage() . " " . json_encode($error));
				$this->log($Message);
			}
		}
	}
?>
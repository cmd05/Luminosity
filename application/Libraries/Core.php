<?php 

declare(strict_types = 1); 

/**
 * 
 * File contains Core Class to route URL calls to controller and method.
 * 
 */

/**
 * Core Class
 * 
 * Contains logic to dynamically load controller from ajax or view folder and invoke method with parameters based on REQUEST URL.
 * 
 */ 
class Core {
	/**
	 * Default values for controller properties.
	 * 
	 * Must be specified to route to home page when request url does not specify request type, controller and method
	 * Eg: site.com
	 * 
	 */
	private $currentController = 'Info';
	private $currentMethod = 'index';
	private $params = [];
	private $requestType = "view";

	/**
	 * Complete logic for instantiating controller and invoking method
	 * 
	 * All Controllers are in:
	 * /Controllers/AjaxControllers/* 
	 * and /Controllers/ViewControllers/*
	 * 
	 * - Get Request URL 
	 * - Check whether controller is view type or ajax type
	 * - Set Controller in Current Controller if specified
	 *  - Parse from lowercase to conventional PascalCase
	 *  - Save in property if controller file exists (else die())
	 * - Instantiate Controller
	 * - Save Method in property if specified in URL
	 * - Parse method to conventional camelCase
	 * - Check if method called is valid (else die())
	 * - Check URL Parameters
	 *      - Cast to correct datatypes
	 *      - Invoke Controller method if valid params
	 *      - else die
	 * 
	 */
	public function __construct() {
		$url = $this->getUrl();

		// Look in controllers folder for controller (when specified)
		if(isset($url[0])) {
			// Use to prevent direct controller calls such as site/UserActions instead of site/user-actions
			$url[0] = strtolower($url[0]);
			$controller = $this->parseObjectNames($url[0]); // Change to Pascal Case

			if($controller === "ajax" && isset($url[1])) {
				$controller = ucwords($url[1]);

				// Die if file does not exist
				if(!file_exists(APPROOT.'/Controllers/AjaxControllers/'.$controller.'.php')) Server::die_404();

				$this->currentController = $controller;
				
				unset($url[1], $url[0]); // unset "ajax" and controller
				$url = array_values($url); // reset indexes
				array_unshift($url, ""); 
				unset($url[0]); // reset method to index 1
				
				$this->requestType = "ajax";
			} else {
				$controller = ucwords($controller);
				
				if(!file_exists(APPROOT.'/Controllers/ViewControllers/'.$controller.'.php')) Server::die_404();

				$this->currentController = $controller;
				unset($url[0]);
			}
		}

		// Require and instantiate the current controller
		$controllerDir = $this->requestType==="view" ? "ViewControllers" : "AjaxControllers";
		require_once(APPROOT."/Controllers/$controllerDir/".$this->currentController.'.php');

		$this->currentController = new $this->currentController;

		// Save Method if specified
		if(isset($url[1])) {
			$url[1] = strtolower($url[1]);
			$this->currentMethod = $url[1];
			unset($url[1]);
		}

		// Parse to camelCase
		$this->currentMethod = $this->parseObjectNames($this->currentMethod);
		
		if($this->checkMethod($this->currentController, $this->currentMethod)) {
			// Any values left over in url are parameters
			if($url) $this->params = $this->castUrlParams(array_values($url));

			// Check if params are valid
			if($this->checkParams($this->currentController, $this->currentMethod, $this->params)) {
				if($this->requestType === "ajax") Server::jsonHeader();

				call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
			}   else {
				Server::die_404();
			}    
		}   else {
			Server::die_404();
		}
	}

	/**
	 * Method to get REQUEST_URI, sanitize it and return as array
	 * 
	 * Each URL request must be parsed in the following manner:
	 * example.com/controller/method/param1/param2/param3
	 * 
	 * Extra slashes must be trimmed before splitting string to array to prevent other extra elements
	 * 
	 * - Remove sitename and preceeding forward slash from request URL.
	 * - Trim preceeding and trailing slashes
	 * - Remove GET parameters from URL 
	 * - Sanitize URL before splitting
	 * 
	 * - Split sanitized query to url in controller, method and parameter form
	 * - Remove Empty strings from array
	 * 
	 * @return array $arr Return URL query split into array containing controller, method, params
	 */
	private function getUrl(): array {
		$query = Server::getRequestUrl();
		
		$arr = explode('/', $query);  // Explode into parts
		$arr = Utils::unsetNullArray($arr); // Remove Empty Strings
		
		return $arr;
	}

	/**
	 * Validate Ajax Request 
	 * 
	 * Method docblocks may specify:
	 * @route (true)
	 * @requestMethods (Array)
	 * @postParams (Array)
	 * @csrfToken (true|false)
	 * 
	 * Route = false by default
	 * requestMethods = POST by default
	 * csrfToken = true by default
	 */
	private function validateAjaxMethod(object $controller, string $method): bool {
		if(!method_exists($controller, $method)) return false;
		$reflectedMethod = new ReflectionMethod($controller, $method);

		$docTags = DocBlock::getTags($controller, $method);

		// Check Valid request method
		if(array_key_exists("requestMethods", $docTags)) {
			$methods = DocBlock::tagValueToArray($controller, $method, "requestMethods");
			foreach ($methods as $key => $value) $methods[$key] = Str::trimWhiteSpaces($value);

			if(!in_array($_SERVER['REQUEST_METHOD'], $methods)) return false;

		} else if($_SERVER['REQUEST_METHOD'] !== "POST") { // default post request
			return false;
		}

		// check if required params exist
		if(array_key_exists("postParams", $docTags)) {
			$array = DocBlock::tagValueToArray($controller, $method, "postParams");
			$array = Utils::unsetNullArray($array);

			foreach($array as $index) {
				if(!isset($_POST[$index])) return false;
			}
		}

		// check csrf token
		if((!(array_key_exists("csrfToken", $docTags) && trim($docTags['csrfToken']) === "false")) && 
			!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? "")) {
			return false;
		}
		
		if(!$reflectedMethod->isPublic()) return false;
		if(trim($docTags["route"] ?? "") !== "true") return false;

		return true;
	}
	/**
	 * Method to Cast URL parameters to correct types
	 * 
	 * Allowed Types include: string, float, int, bool
	 * is_numeric
	 *  - int
	 *  - float
	 * else 
	 *  - bool
	 *  - string
	 * 
	 * @param array $arr Array containing default passedd parameters (as strings)
	 * @return array $arr Array containing elements with correctly casted types
	 */
	private function castUrlParams(array $arr): array {
		foreach ($arr as $key => $value) {
			if(is_numeric($value)) {
				if(ctype_digit($value)) {
				   $arr[$key] = (int) $value;
				} else if(is_float($value+0)) {
					$arr[$key] = (float) $value;
				}
			} else {
				if($value === 'true') {
					$arr[$key] = true;
				}   else if($value === 'false') {
					$arr[$key] = false;
				}   else {
					$arr[$key] = (string) $value;
				}
			}
		}
		return $arr;
	}

	/**
	 * Check **required** number of parameters for controller method
	 * using Reflection API.
	 * 
	 * @param object $controller Controller containing method
	 * @param string $method Name of method
	 * @return int $required Number of required Parameters
	 */
	private function requiredArgCount(object $controller, string $method): int {
		$method = new ReflectionMethod($controller, $method);
		$required = 0;

		foreach ($method->getParameters() as $val) 
			if(!$val->isDefaultValueAvailable()) $required++;
		
		return $required;
	}

	/**
	 * Parsing object names from kebab-case to camelCase
	 * for currently identifying objects
	 * 
	 * @param string $str Name of object passed (kebab-case)
	 * @return string Parsed string to camelCase
	 */
	private function parseObjectNames(string $str): string {
		return trim(str_replace(' ', '', lcfirst(ucwords(implode(" ", explode("-", $str))))));
	}

	/**
	 * Check if specified method is valid
	 * 
	 * - Check if method exists in controller
	 * - Validate for both ajax and view request
	 * - Check if method is public 
	 * - Check if called method is a route method or not
	 * 
	 * @param object $controller Controller Object
	 * @param string $method Name of method to check
	 * @return bool If method is valid or not
	 */
	private function checkMethod(object $controller, string $method): bool {
		if($this->requestType === "ajax") {
			return $this->validateAjaxMethod($controller, $method);   
		} else {
			if(method_exists($controller, $method)) {
				$reflectedMethod = new ReflectionMethod($controller, $method);
	
				if($reflectedMethod->isPublic() && $this->isRoute($controller, $method)) {
					return true;
				}
			}
	
			return false;
		}
	}
	
	/**
	 * Check if method specified is a route method
	 * 
	 * Route methods are tagged with @route
	 * Use Reflection API to get Doc Comments
	 * Parse Doc Comments with Regex to get @tags
	 * Trim Whitespaces of tags
	 * 
	 * @param object $controller Controller Class containing method
	 * @param string $method Name of method to check
	 * @return bool Check if route tag is in array of @tags
	 * 
	 */
	private function isRoute(object $controller, string $method): bool {
		$class = new ReflectionClass($controller);
		$reflectedMethod = $class->getMethod($method);
		
		// Retrieving documentation comments
		$docComments = $reflectedMethod->getDocComment(); 

		if(!$docComments) return false;

		preg_match_all('# @(.*?)\n#s', $docComments, $annotations);
		$arr0 = $annotations[0] ?? []; // Check All @tags

		foreach($arr0 as $key => $val)  $arr0[$key] = Str::trimWhiteSpaces($val); // Trim whitespaces from comments
		
		return in_array('@route', $arr0);
	}
	
	/**
	 * Check if URL parameters are valid
	 * 
	 * Use Reflection API to verify if param count is correct and types match
	 * Check Whether the given number of parameters is <= total no. of params
	 * AND >= required (minimum) arguments
	 * 
	 * Parameters are looped index based;
	 * Methods must not contain optional params on left side of function due to
	 * constant parameter order for function arguments in PHP
	 * 
	 * Check Whether expected parameter type and given parameter type match
	 * (when expected type is hinted in method).
	 * 
	 * Cast Parameter values to strings when expecting. For eg:
	 * /profile/true (pretty dull name) will cast true to bool however profile method expects type=string  
	 * 
	 * @param object $controller Controller Object
	 * @param string $method Method to check params of 
	 * @param array $params Parameters specified
	 * 
	 * @return bool Return true when parameters for called method are valid
	 */
	private function checkParams(object $controller, string $method, array $params): bool {
		$reflection = new ReflectionMethod($controller, $method);
		$requiredArgs = $this->requiredArgCount($controller, $method); // Only Required params not optional
		$totalArgs = $reflection->getNumberOfParameters(); // Total no. of params in method
		$givenArgs = count($params);

		if($givenArgs >= $requiredArgs && $givenArgs <= $totalArgs) {
			// get params of index 1, 2, 3 ... $givenArgs
			for($i = 0; $i < $givenArgs; $i++) { 
				$param = new ReflectionParameter(array($this->currentController, $this->currentMethod), $i);
				$paramType = $param->getType();

				if($paramType == 'string') $this->params[$i] = (string) $this->params[$i]; // cast int/bool/float if expecting string

				$givenType = Utils::typeOf($this->params[$i]);

				if(!is_null($paramType) && $paramType != $givenType) { // if type specified and dont match
					return false;
				}
			}

			return true;
		}

		return false;
	}
}
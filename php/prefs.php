<?php 

/**
 * http://wiki.eclipse.org/Orion/Server_API/Preference_API
 * 
 * @author jacek
 *
 */

include 'init.php';
include 'impl/data/KeyValueStore.php';

$key = $_GET["key"];
$p = new KeyValueStore($mysqli, "prefs");

switch ($requestMethod) {
	case "GET":
		$response = $p->getPath ( $path, $key);
		if (is_array($response)) {
			header("Content-Type: application/json");
			echo json_encode($response);
		} else {
			header($_SERVER["SERVER_PROTOCOL"] . " 404 Preference Not Found");
		}
		break;
	case "PUT":
		if (! empty($requestJson)) {
			$prefsToPut = $requestJson;
		} else if (! empty($_GET['value'])) {
			$prefsToPut = array($_GET['key'] => $_GET['value']);
		}
		$success = $p->put ( $path, $key, $prefsToPut);
		if (!$success) {
			header($_SERVER["SERVER_PROTOCOL"] . " 404 Failed to set preferences");
		}
		break;
	case "DELETE" :
		$p->delete ( $path, $key );
		break;
	default :
		// error
}

?>
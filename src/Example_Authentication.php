<?php
header('Content-Type: text/plain');
error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once '../vendor/autoload.php'; // HINT: path has to be adapted to your needs
require_once './tools/Tools.inc.php';
require_once './Credentials.inc.php';

// =============================================================================
// Create session and authenticate
// see https://github.com/addvideo/addvideo-api-php-client/blob/master/docs/Api/AuthApi.md#authenticate
// =============================================================================

$auth_api_instance = new \de\addvideo\client\api\AuthApi();
$credentials = new \de\addvideo\client\model\CredentialsDTO();
$credentials->setAccount(ACCOUNT); // As defined in Credentials.inc.php
$credentials->setSecret(SECRET); // As defined in Credentials.inc.php

echo "Try to authenticate...";
try {
    $result = $auth_api_instance->authenticate($credentials);
    // print_r($result);
    $status = $result->getStatus();
    if (isset($status) && $status == "success") {
        /* token will be valid for 12 hours from now on. You could save the 
         * valid_to info and re-authenticate when the time is come. This reduces 
         * the number of  API calls!
         */
        echo "success!\n";
        echo "Token [", $result->getToken(), "].\n";
        echo "Authentication valid until [", $result->getValidTo()->format('Y-m-d H:i:s'), "].\n";

        // Configure API key authorization: token
        \de\addvideo\client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', $result->getToken());
    } else {
        // should never happen, since if status is 'failed' a http status value 
        // unequal 200 will be returned. In that case the client sdk will 
        // transform the result into an ApiException (see catch clause below).
    }
} catch (\de\addvideo\client\ApiException $e) {
    echo 'EXCEPTION in 1st Step: ', \de\addvideo\examples\tools\Tools::getExceptionString($e), PHP_EOL;
}
echo "\n\n";

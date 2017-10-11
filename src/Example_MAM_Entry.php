<?php

\header('Content-Type: text/plain');
// \error_reporting(\E_ERROR | \E_WARNING | \E_PARSE);

require_once '../vendor/autoload.php'; // HINT: path has to be adapted to your needs
require_once './tools/AuthenticationHelper.inc.php';
require_once './tools/Tools.inc.php';
require_once './Credentials.inc.php';

if (!\de\addvideo\examples\tools\AuthenticationHelper::authenticate(\ACCOUNT, \SECRET)) {
    echo 'Unable to authenticate!', \PHP_EOL;
    exit();
}
echo 'Authenticated successfully!', \PHP_EOL;

$entry_id = ""; // PUT a valid entry_id of an entry here!
// =============================================================================
// Request entry
// =============================================================================
echo "Requesting entry for entry_id [", $entry_id, "]...";
try {
    /*
     * request information for an existing entry
     */
    $service_mam_api_instance = new \de\addvideo\client\api\AddvideoservicemamApi();
    $entry = $service_mam_api_instance->getEntry($entry_id);
    if (isset($entry)) {
        echo "found!", \PHP_EOL;
        echo 'ENTRY [id: ' . $entry->getId() . ', creation-date: ' . $entry->getCreationDate()->format('Y-m-d H:i:s') . ', modification-date: ' . $entry->getModificationDate()->format('Y-m-d H:i:s') . ', metadata: ' . print_r($entry->getMetadata(), true) . ', flavors: ' . print_r($entry->getFlavorsIds(), true) . ']', \PHP_EOL;
    }
} catch (\de\addvideo\client\ApiException $e) {
    echo \PHP_EOL;
    echo \PHP_EOL;
    echo 'EXCEPTION: ', \de\addvideo\examples\tools\Tools::getExceptionString($e), \PHP_EOL;
}
echo "\n\n";


<?php

header('Content-Type: text/plain');
// error_reporting(E_ERROR | E_WARNING | E_PARSE);

require_once '../vendor/autoload.php'; // HINT: path has to be adapted to your needs
require_once './tools/AuthenticationHelper.inc.php';
require_once './tools/Tools.inc.php';
require_once './Credentials.inc.php';

if (!\de\addvideo\examples\tools\AuthenticationHelper::authenticate(ACCOUNT, SECRET)) {
    echo 'Unable to authenticate!', PHP_EOL;
    exit();
}
echo 'Authenticated successfully!', PHP_EOL;

$entry_id = ""; // PUT a valid entry_id for a READY!!! entry here!
// =============================================================================
// Request playout urls
// =============================================================================
echo "Requesting playout URLs for entry_id [", $entry_id, "]...";
try {
    /*
     * request playout URLs for a READY!!!!! entry (review ingest status first)
     */
    $workflow_api_instance = new \de\addvideo\client\api\AddvideoworkflowApi();
    $playout_URLs = $workflow_api_instance->getPlayoutURLs($entry_id);
    if (isset($playout_URLs) && null !== $playout_URLs->getEntry()) {
        echo "found!", PHP_EOL;
        /*
         * Reference to parent entry
         */
        // $playout_URLs->getEntry();
        $playout_URLs_Set = $playout_URLs->getPlayoutUrlsSet();
        foreach ($playout_URLs_Set as $playout_URL) {
            $url = $playout_URL->getURL();
            echo 'URL [URL: ' . $url . ', label: ' . $playout_URL->getLabel() . ', bitrate: ' . $playout_URL->getBitrate() . ', isProtected: ' . $playout_URL->getProtected() . '].', PHP_EOL;
            /*
             * Hint whether URL is protected against unauthorized access or not!
             */
            // $playout_URL->getProtected();
        }
    }
} catch (\de\addvideo\client\ApiException $e) {
    echo 'EXCEPTION: ', \de\addvideo\examples\tools\Tools::getExceptionString($e), PHP_EOL;
}
echo "\n\n";


<?php

\header('Content-Type: text/plain');
\error_reporting(\E_ERROR | \E_WARNING | \E_PARSE);

require_once '../vendor/autoload.php'; // HINT: path has to be adapted to your needs
require_once './tools/AuthenticationHelper.inc.php';
require_once './tools/Tools.inc.php';
require_once './Credentials.inc.php';
// =============================================================================
// 1st Step: Create session and authenticate
// see https://github.com/addvideo/addvideo-api-php-client/blob/master/docs/Api/AuthApi.md#authenticate
// =============================================================================
echo "1st STEP", \PHP_EOL;
echo "========", \PHP_EOL;
echo "Try to authenticate...";
/*
 * simplified authentication process. If you'd like to take a deeper dive, please take look at Example_Authentication.php
 */
if (!\de\addvideo\examples\tools\AuthenticationHelper::authenticate(\ACCOUNT, \SECRET)) {
    echo "failed!", \PHP_EOL;
    exit;
}
echo "success!", \PHP_EOL;
echo \PHP_EOL, \PHP_EOL;


// =============================================================================
// 2nd Step: Ingest file
// see https://github.com/addvideo/addvideo-api-php-client/blob/master/docs/Api/AddvideoworkflowApi.md#ingest
// =============================================================================
echo "2nd STEP\n";
echo "========\n";

$workflow_api_instance = new \de\addvideo\client\api\AddvideoworkflowApi();

$ingest_data = new \de\addvideo\client\model\IngestDataDTO(); // \de\addvideo\client\model\IngestDataDTO | IngestDataDTO object containing all ingest data
$entry = new \de\addvideo\client\model\IngestDataEntryDTO();

/*
 * Set your external reference id. This id will be used to lookup allready 
 * ingested files and update/overwrite them (This is only important if MAM 
 * services are available and active)! If left empty, each time a new entry in 
 * your booked MAM Service will be created.
 */
$entry->setExternalReferenceId("YOUR_ID");

/*
 * This download link has to be provided, where the source files can be retrieved from.
 */
$entry->setSourceUrl("http://download.blender.org/peach/bigbuckbunny_movies/big_buck_bunny_720p_h264.mov");

/*
 * You should provide a callback service. This means if the callback url param 
 * is set, we will try to reach your callback service to tell you the current 
 * status on your ingest. Please note the special param value @STATUS@. This 
 * placeholder will be replaced by the staus update info.
 */
$entry->setWebhook("http://your-domain.com/your-path?your-query-params=&status=@STATUS@");

$entries = array();
$entries[] = $entry;

$ingest_data->setEntries($entries);

echo "Try to ingest [", $ingest_data, "]...";
try {
    $result = $workflow_api_instance->ingest($ingest_data);
    // print_r($result);
    /*
     * This id can be used to retrieve job status information on your 
     * scheduled job.
     */
    $ingest_job_id = $result->getIngestJobId();
    echo "success! [ingestJobId: ", $ingest_job_id, "].\n";
} catch (\de\addvideo\client\ApiException $e) {
    echo 'EXCEPTION in 2nd Step: ', \de\addvideo\examples\tools\Tools::getExceptionString($e), \PHP_EOL;
}
echo "\n\n";


// =============================================================================
// 3rd Step: request status for ingest
// =============================================================================
echo "3rd STEP\n";
echo "========\n";

$count = 0;
while (true) {

    echo "sleeping for 5 sec...";
    sleep(5);

    echo "Requesting status for ingestJobId [", $ingest_job_id, "]...";
    $stored_entry_id = 0;
    try {
        $status_result = $workflow_api_instance->status($ingest_job_id);
        print_r($status_result);
        /*
         * You can access the status info as needed
         */
        $status_entries = $status_result->getEntries();
        foreach ($status_entries as $status_entry) {
            $external_reference_id = $status_entry->getExternalReferenceId();
            $status = $status_entry->getStatus();
            /*
             * Retrieve entry information for this ingest object. There will be an 
             * entry for READY ingest jobs, only!
             */
            $mam_entry = $status_entry->getEntry();
            if (isset($mam_entry)) {
                /*
                 * $mam_entry_id is needed to retrieve playout URLs later on!
                 */
                $mam_entry_id = $mam_entry->getId();
                /*
                 * Store status update in your external systems or view them in a web 
                 * ui, and so on....
                 */
                $stored_entry_id = $mam_entry_id; // don't do this in your prduction environment, this is just a very stupid hack to show the functionality!
            }
        }
    } catch (\de\addvideo\client\ApiException $e) {
        echo 'EXCEPTION in 3rd Step: ', \de\addvideo\examples\tools\Tools::getExceptionString($e), \PHP_EOL;
    }
    $count++;
    if ($count > 4) {
        break;
    }
}
echo "\n\n";

// =============================================================================
// 4th Step: request playout urls
// =============================================================================
echo "4th STEP\n";
echo "========\n";

echo "Requesting playout URLs for storedEntryId [", $stored_entry_id, "]...";
try {
    /*
     * request playout URLs for a READY!!!!! entry (review ingest status first)
     */
    $playout_URLs = $workflow_api_instance->getPlayoutURLs($stored_entry_id);

    echo "found!", \PHP_EOL;
    /*
     * Reference to parent entry
     */
    // $playout_URLs->getEntry();
    $playout_URLs_Set = $playout_URLs->getPlayoutUrlsSet();
    foreach ($playout_URLs_Set as $playout_URL) {
        $url = $playout_URL->getURL();
        echo 'URL [' . $url . '].', \PHP_EOL;
        /*
         * Hint whether URL is protected against unauthorized access or not!
         */
        // $playout_URL->isProtected();
    }
} catch (\de\addvideo\client\ApiException $e) {
    echo 'EXCEPTION in 4th Step: ', \de\addvideo\examples\tools\Tools::getExceptionString($e), \PHP_EOL;
}
echo "\n\n";

<?php
\header('Content-Type: text/plain');
// \error_reporting(\E_ERROR | \E_WARNING | \E_PARSE);

require_once '../vendor/autoload.php'; // HINT: path has to be adapted to your needs
require_once './tools/AuthenticationHelper.inc.php';
require_once './tools/Tools.inc.php';
require_once './Credentials.inc.php';

if(!\de\addvideo\examples\tools\AuthenticationHelper::authenticate(\ACCOUNT, \SECRET)){
    echo 'Unable to authenticate!', \PHP_EOL;
    exit();
}
echo 'Authenticated successfully!', \PHP_EOL;

// =============================================================================
// Ingest file
// see https://github.com/addvideo/addvideo-api-php-client/blob/master/docs/Api/AddvideoworkflowApi.md#ingest
// =============================================================================
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


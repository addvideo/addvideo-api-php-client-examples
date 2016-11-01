<?php

require_once('../vendor/autoload.php');

// =============================================================================
// 1st: Create session and authenticate
// see https://github.com/addvideo/addvideo-api-php-client/blob/master/docs/Api/AuthApi.md#authenticate
// =============================================================================

$auth_api_instance = new de\addvideo\client\api\AuthApi();
$credentials = new \de\addvideo\client\model\CredentialsDTO();
$credentials->setAccount(""); // PUT YOUR ACCOUNT ID HERE!
$credentials->setSecret(""); // PUT YOUR SECRET HERE!

try {
    $result = $auth_api_instance->authenticate($credentials);
    $status = $result->getStatus();
    if (isset($status) && $status == "success") {
        /* token will be valid for 12 hours from now on. You could save the 
         * valid_to info and re-authenticate when the time is come. This reduces 
         * the number of  API calls!
         */
        // $valid_to = $result->getValidTo();
        // Configure API key authorization: token
        de\addvideo\client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', $result->getToken());
    }
} catch (\de\addvideo\client\ApiException $e) {
    echo '1st Step: ', $e->getResponseObject(), PHP_EOL;
}


// =============================================================================
// 2nd: Ingest file
// see https://github.com/addvideo/addvideo-api-php-client/blob/master/docs/Api/AddvideoworkflowApi.md#ingest
// =============================================================================

$workflow_api_instance = new de\addvideo\client\api\AddvideoworkflowApi();
$ingest_data = new \de\addvideo\client\model\IngestDataDTO(); // \de\addvideo\client\model\IngestDataDTO | IngestDataDTO object containing all ingest data

$entry = new de\addvideo\client\model\IngestDataEntryDTO();

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
$entry->setDownloadLink("http://download.blender.org/peach/bigbuckbunny_movies/big_buck_bunny_720p_h264.mov");

/*
 * You should provide a callback service. This means if the callback url param 
 * is set, we will try to reach your callback service to tell you the current 
 * status on your ingest. Please note the special param value @STATUS@. This 
 * placeholder will be replaced by the staus update info.
 */
$entry->setCallbackUrl("http://your-domain.com/your-path?your-query-params=&status=@STATUS@");

$entries = array();
$entries[] = $entry;

$ingest_data->setEntries($entries);
try {
    $result = $workflow_api_instance->ingest($ingest_data);
    print_r($result);
    $status = $result->getStatus();
    if (isset($status) && $status === "success") {
        /*
         * This id can be used to retrieve job status information on your 
         * scheduled job.
         */
        $ingest_job_id = $result->getIngestJobId();
    }
} catch (\de\addvideo\client\ApiException $e) {
    echo '2nd Step: ', $e->getResponseObject(), PHP_EOL;
}

// =============================================================================
// 3rd: request status for ingest
// =============================================================================


// =============================================================================
// 4th: request playout urls
// =============================================================================



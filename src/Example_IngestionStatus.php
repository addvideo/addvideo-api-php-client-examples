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

$ingestion_job_id = "";

requestStatusForIngestionJob($ingestion_job_id);

/**
 * Request status for ingest.
 * 
 * @param string $ingestion_job_id
 */
function requestStatusForIngestionJob($ingestion_job_id) {
    echo "Requesting status for ingestJobId [", $ingestion_job_id, "]...";
    try {
        $workflow_api_instance = new \de\addvideo\client\api\AddvideoworkflowApi();
        $status_result = $workflow_api_instance->status($ingestion_job_id);
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
                //$stored_entry_id = $mam_entry_id; // don't do this in your prduction environment, this is just a very stupid hack to show the functionality!
            }
        }
    } catch (\de\addvideo\client\ApiException $e) {
        echo 'EXCEPTION: ', \de\addvideo\examples\tools\Tools::getExceptionString($e), PHP_EOL;
    }
}

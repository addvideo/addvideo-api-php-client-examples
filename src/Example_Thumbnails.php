<?php

\header('Content-Type: text/plain');
\error_reporting(\E_ERROR | \E_WARNING | \E_PARSE);

require_once '../vendor/autoload.php'; // HINT: path has to be adapted to your needs
require_once './tools/AuthenticationHelper.inc.php';
require_once './tools/Tools.inc.php';
require_once './Credentials.inc.php';

if (!\de\addvideo\examples\tools\AuthenticationHelper::authenticate(\ACCOUNT, \SECRET)) {
    echo 'Unable to authenticate!', \PHP_EOL;
    exit();
}
echo 'Authenticated successfully!', \PHP_EOL;

$entry_id = ""; // PUT a valid entry_id for a READY!!! entry here!
// =============================================================================
// Request playout urls
// =============================================================================
echo "Requesting video preview thumbnails URLs for entry_id [", $entry_id, "]...";
try {
    /*
     * request video preview thumbnails URLs for a READY!!!!! entry (review ingest status first)
     */
    $workflow_api_instance = new \de\addvideo\client\api\AddvideoworkflowApi();
    $video_preview_thumbnails_URLs = $workflow_api_instance->getVideoPreviewThumbnailsURLs($entry_id);
    if (isset($video_preview_thumbnails_URLs) && null !== $video_preview_thumbnails_URLs->getEntry()) {
        echo "found!", \PHP_EOL;
        \print_r( $video_preview_thumbnails_URLs );
        
        /*
         * Reference to parent entry
         */
        //$video_preview_thumbnails_URLs->getEntry();
        
        $thumbnails_URLs_Set = $video_preview_thumbnails_URLs->getVideoPreviewThumbnailsUrlsSet();
        foreach ($thumbnails_URLs_Set as $thumbnail_URL) {
            $url = $thumbnail_URL->getURL();
            echo 'URL [URL: ' . $url . ', dimensions: ' . $thumbnail_URL->getDimensions() . ', imageFileFormat: ' . $thumbnail_URL->getImageFileFormat() . ', fileSize: ' . $thumbnail_URL->getFileSize() . '].', \PHP_EOL;
        }
    }
} catch (\de\addvideo\client\ApiException $e) {
    echo 'EXCEPTION: ', \de\addvideo\examples\tools\Tools::getExceptionString($e), \PHP_EOL;
}
echo "\n\n";


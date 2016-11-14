<?php

/**
 * Tools
 */
class Tools {

    /**
     * getExceptionString
     * @param \de\addvideo\client\ApiException $e to create string for.
     * @return type string
     */
    public static function getExceptionString(\de\addvideo\client\ApiException $e) {
        $header_string = "n/a";
        if (null !== $e->getResponseHeaders() && \is_array($e->getResponseHeaders())) {
            $header_string = \implode(";", $e->getResponseHeaders());
        }
        $response_object_string = "n/a";
        if (null !== $e->getResponseObject() && \is_array($e->getResponseObject())) {
            $response_object_string = \implode(";", $e->getResponseObject());
        }
        return "[code: " . $e->getCode() . ", message: " . $e->getMessage() . ", responseHeaders: "
                . $header_string . ", responseBody: " . $e->getResponseBody() . ", responseObject: "
                . $response_object_string . "]";
    }

}

<?php
/**
 * Tools
 *
 * PHP version 5
 *
 * @category Class
 * @package  de\addvideo\examples\tools
 * @author   info@addvideo.de
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache Licene v2
 * @link     https://github.com/addvideo/addvideo-api-php-client-examples
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
namespace de\addvideo\examples\tools;

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

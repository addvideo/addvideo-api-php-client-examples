<?php
/**
 * AuthenticationHelper
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

/**
 * AuthenticationHelper
 */
class AuthenticationHelper {

    /**
     * function authenticate. Simplified authentcation method.
     * 
     * @param $account name of account.
     * @param $secret value of common secret.
     * @return boolean false if unable to authenticate.
     */
    public static function authenticate($account, $secret) {
        $auth_api_instance = new \de\addvideo\client\api\AuthApi();
        $credentials = new \de\addvideo\client\model\CredentialsDTO();
        $credentials->setAccount($account);
        $credentials->setSecret($secret);
        try {
            $result = $auth_api_instance->authenticate($credentials);
            $status = $result->getStatus();
            if (isset($status) && $status == "success") {
                // Configure API key authorization: token
                \de\addvideo\client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', $result->getToken());
                return true;
            }
        } catch (\de\addvideo\client\ApiException $e) {
        }
        return false;
    }

}

<?php

require_once '../vendor/autoload.php';

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
        $auth_api_instance = new de\addvideo\client\api\AuthApi();
        $credentials = new \de\addvideo\client\model\CredentialsDTO();
        $credentials->setAccount($account);
        $credentials->setSecret($secret);
        try {
            $result = $auth_api_instance->authenticate($credentials);
            $status = $result->getStatus();
            if (isset($status) && $status == "success") {
                // Configure API key authorization: token
                de\addvideo\client\Configuration::getDefaultConfiguration()->setApiKey('Authorization', $result->getToken());
                return true;
            }
        } catch (\de\addvideo\client\ApiException $e) {
        }
        return false;
    }

}

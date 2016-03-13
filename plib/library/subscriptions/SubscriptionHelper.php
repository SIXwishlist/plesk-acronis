<?php
/**
 * This File is part of the plesk-acronis extension
 * (https://github.com/StratoAG/plesk-acronis)
 *
 * Created by Eitan Schuler <schuler@strato-rz.net>
 *
 * Date: 3/13/16
 * Time: 11:25 AM
 *
 * Short Info
 *
 * @licence http://www.apache.org/licenses/LICENSE-2.0 Apache Licence v. 2.0
 */

class Modules_AcronisBackup_subscriptions_SubscriptionHelper
{

    private static $pleskVersion;

    /**
     * getPleskVersion
     *
     * Description
     *
     *
     * @return string
     */
    public static function getPleskVersion()
    {
        if (self::$pleskVersion === null) {
            self::$pleskVersion = pm_ProductInfo::getVersion();
        }
        return self::$pleskVersion;
    }

    /**
     * _getSubscriptions
     *
     * Description
     *
     *
     * @return array\
     */
    public static function getSubscriptions($client = null)
    {
        if ($client === null) {
            $login = pm_Session::getClient()->getProperty('login');
            if ('admin' != $login) {
                return [pm_Session::getCurrentDomain()->getName()];
            }
        }
        $request = "<webspace>
            <get>
                <filter/>
                <dataset>
                    <gen_info/>
                </dataset>
            </get>
        </webspace>";
        $response = pm_ApiRpc::getService()->call($request);
        $responseSubscriptions = reset($response->webspace->get);
        if ($responseSubscriptions instanceof SimpleXMLElement) {
            $responseSubscriptions = [$responseSubscriptions];
        }
        $subscriptions = [];
        foreach ($responseSubscriptions as $subscription) {
            $subscriptions[] = (string)$subscription->data->gen_info->name;
        }
        return $subscriptions;
    }

    public static function getEnabledSubscriptions()
    {
        $enabledSubscriptions = pm_Settings::get('enabledSubscriptions');
        if ($enabledSubscriptions == null) {
            $enabledSubscriptions = [];
        } else {
            $enabledSubscriptions = json_decode($enabledSubscriptions, true);
        }

        return $enabledSubscriptions;
    }

    public static function setEnabledSubscriptions($enabledSubscriptions)
    {
        $enabledSubscriptions = json_encode($enabledSubscriptions);
        pm_Settings::set('enabledSubscriptions', $enabledSubscriptions);
    }

    public static function getAuthorizationMode()
    {
        return pm_Settings::get('authorizationMode', 'extended');
    }

    public static function setAuthorizationMode($mode)
    {
        $authorizationMode = ($mode == 'extended' | $mode == 'simple') ? $mode : 'extended';
        pm_Settings::set('authorizationMode', $authorizationMode);
    }
}
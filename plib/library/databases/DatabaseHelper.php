<?php
/**
 * This File is part of the plesk-acronis extension
 * (https://github.com/StratoAG/plesk-acronis)
 *
 * Created by Eitan Schuler <schuler@strato-rz.de>
 *
 * Date: 3/13/16
 * Time: 5:33 PM
 *
 * Short Info
 *
 * @licence http://www.apache.org/licenses/LICENSE-2.0 Apache Licence v. 2.0
 */

require_once(__DIR__ . '/../subscriptions/SubscriptionHelper.php');

/**
 * Class Modules_AcronisBackup_databases_DatabaseHelper
 *
 * Helper to get informations about databases defined in Plesk
 *
 * @category Helper
 * @author   Vincent Fahrenholz <fahrenholz@strato.de>
 * @version  Release: 1.0.0
 */
class Modules_AcronisBackup_databases_DatabaseHelper
{
    /**
     * getDatabases
     *
     * Gets all Databases which the current client can see via the plesk API
     *
     * @param null|pm_Client $client
     *
     * @return array
     */
    public static function getDatabases($client = null)
    {
        if ($client === null) {
            $login = pm_Session::getClient()->getProperty('login');
            if ('admin' != $login) {
                return [pm_Session::getCurrentDomain()->getName()];
            }
        }
        $request = "<database>
            <get-db>
                <filter>";
        $subscriptions = Modules_AcronisBackup_subscriptions_SubscriptionHelper::getSubscriptions($client);
        foreach ($subscriptions as $subscription) {
            $request .= "<webspace-name>" . $subscription . "</webspace-name>";
        }
        $request .= "</filter>
            </get-db>
        </database>";

        $response = pm_ApiRpc::getService()->call($request);
        $json = json_encode($response);
        $array = json_decode($json, true);

        $responseDatabases = [];

        foreach ($array["database"]["get-db"]["result"] as $instance) {
            if (! isset($responseDatabases[$instance["filter-id"]])) {
                $responseDatabases[$instance["filter-id"]] = [];
            }
            $responseDatabases[$instance["filter-id"]][] = $instance["name"];
        }

        return $responseDatabases;
    }

}

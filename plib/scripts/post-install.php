<?php

pm_Context::init('acronisbackup');
if (false !== ($upgrade = array_search('upgrade', $argv))) {
    $upgradeVersion = $argv[$upgrade + 1];
    echo "upgrading from version $upgradeVersion\n";
    if (version_compare($upgradeVersion, '1.2') < 0) {
        pm_Bootstrap::init();
        $id = pm_Bootstrap::getDbAdapter()->fetchOne("select val from misc where param = 'moduleAcronisBackupCustomButton'");
        pm_Bootstrap::getDbAdapter()->delete('misc', array("param = 'moduleAcronisBackupCustomButton'"));
        pm_Settings::set('customButtonId', $id);
    }
    echo "done\n";
    exit(0);
}
$iconPath = rtrim(pm_Context::getHtdocsDir(), '/') . '/images/icon_16.gif';
$baseUrl = pm_Context::getBaseUrl();
$request = <<<APICALL
<ui>
   <create-custombutton>
         <owner>
            <admin/>
         </owner>
      <properties>
         <file>$iconPath</file>
         <internal>true</internal>
         <noframe>true</noframe>
         <place>client</place>
         <url>$baseUrl</url>
         <text>Acronis Backup</text>
      </properties>
   </create-custombutton>
</ui>
APICALL;
try {
    $response = pm_ApiRpc::getService()->call($request);
    $result = $response->ui->{"create-custombutton"}->result;
    if ('ok' == $result->status) {
        pm_Settings::set('customButtonId', $result->id);
        echo "done\n";
        exit(0);
    } else {
        echo "error $result->errcode: $result->errtext\n";
        exit(1);
    }
} catch(PleskAPIParseException $e) {
    echo $e->getMessage() . "\n";
    exit(1);
}

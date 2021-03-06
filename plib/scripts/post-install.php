<?php
/**
 * This File is a part of the plesk-acronis extension (https://github.com/StratoAG/plesk-acronis)
 *
 * Created by Vincent Fahrenholz <fahrenholz@strato-rz.de>
 *
 * Date: 11.03.16
 * Time: 16:25
 *
 * Script executed directly after installing the Extension
 *
 * @licence http://www.apache.org/licenses/LICENSE-2.0 Apache Licence v. 2.0
 */

if (!file_exists('/usr/local/psa/var/modules/acronis-backup/logs')) {
    mkdir('/usr/local/psa/var/modules/acronis-backup/logs', 0777, true);
}
if (!file_exists('/usr/local/psa/var/modules/acronis-backup/tmp')) {
    mkdir('/usr/local/psa/var/modules/acronis-backup/tmp', 0777, true);
}
if (!file_exists('/usr/local/psa/var/modules/acronis-backup/databases')) {
    mkdir('/usr/local/psa/var/modules/acronis-backup/databases', 0644, true);
}

chown('/usr/local/psa/var/modules/acronis-backup', 'psaadm');
chgrp('/usr/local/psa/var/modules/acronis-backup', 'psaadm');
chown('/usr/local/psa/var/modules/acronis-backup/tmp', 'psaadm');
chgrp('/usr/local/psa/var/modules/acronis-backup/tmp', 'psaadm');

if (!file_exists('/usr/local/psa/var/modules/acronis-backup/logs/acronis-backup-extension.log')) {
    $logFile = fopen('/usr/local/psa/var/modules/acronis-backup/logs/acronis-backup-extension.log', 'w');
    fclose($logFile);
}

chown('/usr/local/psa/var/modules/acronis-backup/logs/acronis-backup-extension.log', 'psaadm');
chgrp('/usr/local/psa/var/modules/acronis-backup/logs/acronis-backup-extension.log', 'psaadm');

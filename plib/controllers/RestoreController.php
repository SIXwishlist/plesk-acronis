<?php
/**
 * This File is a part of the plesk-acronis extension (https://github.com/StratoAG/plesk-acronis)
 *
 * Created by Vincent Fahrenholz <fahrenholz@strato-rz.de>
 *
 * Date: 11.03.16
 * Time: 16:25
 *
 * Contains the RestoreController class
 *
 * @licence http://www.apache.org/licenses/LICENSE-2.0 Apache Licence v. 2.0
 */

/**
 * Class RestoreController
 *
 * Controller handling all actions relevant to backup-recovery-functionality
 *
 * @category Controller
 * @author   Vincent Fahrenholz <fahrenholz@strato.de>
 * @version  Release: 1.0.0
 */
class RestoreController extends pm_Controller_Action
{
    /**
     * webspaceAction
     *
     * Action used to restore one entire webspace
     */
    public function webspaceAction()
    {
        $domain = pm_Session::getCurrentDomain()->getName();
        $this->view->pageTitle = pm_Locale::lmsg('webspaceRecovering').$domain;

        $itemSliceId = $this->_request->getParam('id');
        $itemSliceFile = $this->_request->getParam('resource');

        if ($itemSliceFile == null || $itemSliceId == null) {
            $this->_status->addMessage('error', pm_Locale::lmsg('wrongUrlAlert'));
            $this->_helper->json(array('redirect' => pm_Context::getActionUrl('customer', 'index')));
        } else {
            $itemSliceFile = base64_decode($itemSliceFile);
        }

        try {
            $filename = Modules_AcronisBackup_backups_BackupHelper::getWebspaceBackup($itemSliceFile, pm_Session::getCurrentDomain()->getName());
            exec('/usr/local/psa/admin/bin/modules/acronis-backup/restore.sh '.$filename);

        } catch (Exception $e) {
            $this->_status->addMessage('error', pm_Locale::lmsg('fileErrorAlert'));
            $this->_helper->json(array('redirect' => pm_Context::getActionUrl('customer', 'index')));
        }

        $this->_status->addMessage('info', pm_Locale::lmsg('webspaceRestoreRetrieving'));
    }
}
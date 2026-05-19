<?php
namespace Exbil\Mailcow\SyncJobs;

use Exbil\MailCowAPI;

class SyncJobs {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    /**
     * `createSyncJob()` - Creates a new sync job
     * @param string $username The username of the local mailbox to sync to
     * @param string $host1 The remote host to sync from, e.g. mail.acme.inc
     * @param string $port1 The remote port to sync from, e.g. 993/143
     * @param string $user1 The remote user to sync from, e.g. "john.doe@acme.inc"
     * @param string $pass1 The remote password needed for authentication
     * @param bool $active Whether the sync job should be active right after creation
     * @param bool $dryRun Whether the sync job should be executed as a dry run, meaning that no changes will be made to the local or remote mailbox. This is useful for testing the connection and settings of the sync job without actually syncing any emails.
     * @param string $enc The encryption method to use for the connection, e.g. "TLS", "SSL" or "STARTTLS"
     * @param int $mins_interval The interval in minutes at which the sync job should run
     * @param int $maxage The maximum age of emails to sync in days, e.g. 30. Emails older than this will not be synced. Set to 0 to sync all emails regardless of age.
     * @param int $timeouts The timeout in seconds for the connection to the remote mail server, e.g. 600
     * @param string $exclude A regular expression pattern to exclude certain emails from being synced based on their subject or sender, e.g. "(?i)spam|(?i)junk" to exclude emails with "spam" or "junk" in the subject or sender. Set to an empty string to not exclude any emails.
     * @param bool $deleteDuplicatesOnMailcow Whether to delete duplicate emails on Mailcow that are also present on the remote mailbox. This can help save storage space on Mailcow, but may result in data loss if not used carefully. Set to true to enable this option, or false to keep duplicate emails on Mailcow.
     * @param bool $deleteFromRemote Whether to delete emails from the remote mailbox after they have been synced to Mailcow. This can help keep the remote mailbox clean, but may result in data loss if not used carefully. Set to true to enable this option, or false to keep emails on the remote mailbox after syncing.
     * @param bool $automap Whether to automatically map the remote mailbox' contents into the local mailbox' folders. If set to true, the sync job will try to match the remote mailbox' folders with the local mailbox' folders and sync emails accordingly. If set to false, all synced emails will be placed in the local mailbox' inbox, regardless of their original folder on the remote mailbox.
     * @return array|string
    */
    public function createSyncJob(string $username, string $host1, string $port1, string $user1, string $pass1, bool $active = false, bool $dryRun = true, string $enc = "TLS", int $mins_interval = 20, int $maxage = 0, int $timeouts = 600, string $exclude = "(?i)spam|(?i)junk", bool $deleteDuplicatesOnMailcow = true, bool $deleteFromRemote = false, bool $automap = true){
        return $this->MailCowAPI->post('add/syncjob', [
            "active" => $active ? 1 : 0,
            "username" => $username,
            "host1" => $host1,
            "port1" => $port1,
            "user1" => $user1,
            "pass1" => $pass1,
            "enc" => $enc,
            "mins_interval" => $mins_interval,
            "maxage" => $maxage,
            "timeouts" => $timeouts,
            "exclude" => $exclude,
            "deleteDuplicatesOnMailcow" => $deleteDuplicatesOnMailcow ? 1 : 0,
            "deleteFromRemote" => $deleteFromRemote ? 1 : 0,
            "automap" => $automap ? 1 : 0,
            "dryRun" => $dryRun ? 1 : 0
        ]);
    }

    /**
     * `deleteSyncJob()` - Deletes one or multiple Sync Job
     * @param array $ids An array of IDs of the sync jobs to delete, e.g. [1, 2, 3]
     * @return array|string
     */
    public function deleteSyncJob(array $ids){
        return $this->MailCowAPI->post('delete/syncjob', $ids);
    }

    public function getAllSyncJobs(){
        return $this->MailCowAPI->get('get/syncjobs/all/no_log');
    }
}
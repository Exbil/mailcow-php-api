<?php
namespace Exbil\Mailcow\Status;

use Exbil\MailCowAPI;

class Status {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    /**
     * `getContainerStatus()` - Returns the status of all containers
     * @return array
     */
    public function getContainerStatus(){
        return $this->MailCowAPI->get('get/status/containers');
    }

    /**
     * `getSolr()` - Returns the status of Solr
     * @return array
     */
    public function getSolr(){
        return $this->MailCowAPI->get('get/status/solr');
    }

    /**
     * `getVmail()` - Returns the status of vMail along with the storage in use
     * @return array
     */
    public function getVmail(){
        return $this->MailCowAPI->get('get/status/vmail');
    }

    /**
     * `getVersion()` - Returns the mailcow version
     * @return array
     */
    public function getVersion(){
        return $this->MailCowAPI->get("get/status/version");
    }
}
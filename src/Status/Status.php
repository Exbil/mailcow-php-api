<?php
namespace Exbil\Mailcow\Status;

use Exbil\MailCowAPI;

class Status {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    public function getContainerStatus(){
        return $this->MailCowAPI->get('get/status/containers');
    }

    public function getSolr(){
        return $this->MailCowAPI->get('get/status/solr');
    }

    public function getVmail(){
        return $this->MailCowAPI->get('get/status/vmail');
    }

    public function getVersion(){
        return $this->MailCowAPI->get("get/status/version");
    }
}
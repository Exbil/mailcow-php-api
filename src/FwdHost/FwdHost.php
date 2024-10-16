<?php
namespace Exbil\Mailcow\FwdHost;

use Exbil\MailCowAPI;

class FwdHost {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    public function addHost(string $hostname, bool $filter_spam = false){
        return $this->MailCowAPI->post('add/fwdhost', [
            "filter_spam" => (int)$filter_spam,
            "hostname" => $hostname
        ]);
    }

    public function deleteHost(string $hostname){
        return $this->MailCowAPI->post('delete/fwdhost', [$hostname]);
    }

    public function getAllHosts(){
        return $this->MailCowAPI->get('get/fwdhost/all');
    }
}
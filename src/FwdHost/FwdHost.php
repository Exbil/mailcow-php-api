<?php
namespace Exbil\Mailcow\FwdHost;

use Exbil\MailCowAPI;

class FwdHost {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    /**
     * `addHost()` - Add a new forward host
     * @param string $hostname The hostname to allow forwards from
     * @param bool $filter_spam Enable or disable spam filtering from this host
     * @return array
     */
    public function addHost(string $hostname, bool $filter_spam = false){
        return $this->MailCowAPI->post('add/fwdhost', [
            "filter_spam" => (int)$filter_spam,
            "hostname" => $hostname
        ]);
    }

    /**
     * `deleteHost()` - Delete a forward host
     * @param string $hostname The hostname
     * @return array
     */
    public function deleteHost(string $hostname){
        return $this->MailCowAPI->post('delete/fwdhost', [$hostname]);
    }

    /**
     * `getAllHosts()` - Get all forward hosts
     * @return array
     */
    public function getAllHosts(){
        return $this->MailCowAPI->get('get/fwdhost/all');
    }
}
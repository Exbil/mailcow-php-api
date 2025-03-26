<?php
namespace Exbil\Mailcow\Routing;

use Exbil\MailCowAPI;

class Routing {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    public function addRelayHost(string $hostname, string $password, string $username){
        return $this->MailCowAPI->post('add/relayhost', [
            "hostname" => $hostname,
            "password" => $password,
            "username" => $username
        ]);
    }

    /**
     * addTransportMap
     * @param string $destination Some Domain name, e.g. "domain.tld
     * @param string $nexthop Mailserver with port, e.g. "mail.domain.tld:25"
     * @param string $password
     * @param string $username
     * @return array
     */
    public function addTransportMap(string $destination, string $nexthop, string $password, string $username){
        return $this->MailCowAPI->post('add/transport', [
            "active" => 1,
            "destination" => $destination,
            "nexthop" => $nexthop,
            "password" => $password,
            "username" => $username
        ]);
    }

    public function deleteRelayHost(int $id){
        return $this->MailCowAPI->post('delete/relayhost', [$id]);
    }

    public function deleteTransportMap(int $id){
        return $this->MailCowAPI->post('delete/transport', [$id]);
    }

    public function getRelayHost(int $id){
        return $this->MailCowAPI->get('get/relayhost/' . $id);
    }

    public function getTransportMap(int $id){
        return $this->MailCowAPI->get('get/transport/' . $id);
    }
}
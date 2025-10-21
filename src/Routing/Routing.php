<?php
namespace Exbil\Mailcow\Routing;

use Exbil\MailCowAPI;

class Routing {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    /**
     * `addRelayHost()` - Add a new relay host to relay emails to
     * @param string $hostname The hostname
     * @param string $password The password for authentication
     * @param string $username The username for authentication
     */
    public function addRelayHost(string $hostname, string $password, string $username){
        return $this->MailCowAPI->post('add/relayhost', [
            "hostname" => $hostname,
            "password" => $password,
            "username" => $username
        ]);
    }

    /**
     * `addTransportMap()` - Adds a new transport map to send received emails from $destination to $nexthop
     * @param string $destination Some Domain name, e.g. "domain.tld
     * @param string $nexthop Mailserver with port, e.g. "mail.domain.tld:25"
     * @param string $password The username to authenticate
     * @param string $username The password to authenticate
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

    /**
     * `deleteRelayHost()` - Deletes a relay host
     * @param int $id The ID of the host
     * @return array
     */
    public function deleteRelayHost(int $id){
        return $this->MailCowAPI->post('delete/relayhost', [$id]);
    }

    /**
     * `deleteTransportMap()` - Deletes a transport map
     * @param int $id The ID of the mapping
     * @return array
     */
    public function deleteTransportMap(int $id){
        return $this->MailCowAPI->post('delete/transport', [$id]);
    }

    /**
     * `getRelayHost()` - Returns given relay's host configuration
     * @param int $id ID of the relay host
     * @return array
     */
    public function getRelayHost(int $id){
        return $this->MailCowAPI->get('get/relayhost/' . $id);
    }

    /**
     * `getTransportMap()` - Returns given transport map's configuration
     * @param int $id ID of the transport map
     * @return array
     */
    public function getTransportMap(int $id){
        return $this->MailCowAPI->get('get/transport/' . $id);
    }
}
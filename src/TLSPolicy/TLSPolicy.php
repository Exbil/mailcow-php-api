<?php
namespace Exbil\Mailcow\TLSPolicy;

use Exbil\MailCowAPI;

class TLSPolicy {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    /**
     * `createPolicyMap()` - Creates a new outgoing policy map for given domain or mail server
     * @param string $parameters None or e.g. "protocols=!SSLv2 ciphers=medium exclude=3DES"
     * @param string $destination The destination the policy is valid for, e.g. "mailcow.tld" or "mail.mailcow.tld" 
     * @param string $policy The policy itself, e.g. "may", "encrypt", "dane", "dane-only", "fingerprint", "verify" or "secure"
     */
    public function createPolicyMap(string $parameters, string $destination, string $policy = "encrypt"){
        return $this->MailCowAPI->post('add/tls-policy-map', [
            "active" => 1,
            "parameters" => $parameters,
            "dest" => $destination,
            "policy" => $policy
        ]);
    }

    /**
     * `deletePolicyMap()` - Deletes given Policy map
     * @param int $id ID of the mapping
     * @return array
     */
    public function deletePolicyMap(int $id){
        return $this->MailCowAPI->post('delete/tls-policy-map', [$id]);
    }

    /**
     * `getPolicyMap()` - Returns the given Policy map
     * @param int $id ID of the mapping
     * @return array
     */
    public function getPolicyMap(int $id)
    {
        return $this->MailCowAPI->get('get/tls-policy-map/' . $id);
    }

    /**
     * `getAllPolicyMaps()` - Returns all TLS policy maps
     * @return array
     */
    public function getAllPolicyMaps()
    {
        return $this->MailCowAPI->get('get/tls-policy-map/all');
    }
}
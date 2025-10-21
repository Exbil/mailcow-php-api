<?php

namespace Exbil\Mailcow\AntiSpam;

use Exbil\MailCowAPI;

class AntiSpam
{

    /**
     * @var MailCowAPI
     */
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI)
    {
        $this->MailCowAPI = $MailCowAPI;
    }


    /**
     * `getWhitelistPolicy()` - Returns the current whitelist policy for given domain
     * @param string $domain The domain name
     * @return array|string
     */
    public function getWhitelistPolicy(string $domain)
    {
        return $this->MailCowAPI->get('get/policy_wl_domain/' . $domain);
    }

    /**
     * `getBlacklistPolicy()` - Returns the current blacklist policy for given domain
     * @param string $domain The domain name
     * @return array|string
     */
    public function getBlacklistPolicy(string $domain)
    {
        return $this->MailCowAPI->get('get/policy_bl_domain/' . $domain);
    }

    /**
     * `addPolicy()` - Add a new domain policy
     * @param string $object_list Either whitelist (wl) or blacklist (bl)
     * @param string $object_from The name to either black- or whitelist, e.g. '*@somedomain.com'
     * @return array|string
     */
    public function addPolicy(string $domain, string $object_list, string $object_from)
    {
        return $this->MailCowAPI->post('add/domain-policy', [
            "domain" => $domain,
            "object_list" => $object_list,
            "object_from" => $object_from
        ]);
    }

    /**
     * `deletePolicy()` - Deletes a domain policy
     * @param string $PolicyID The policy ID to delete
     * @return array|string
     */
    public function deletePolicy(array $PolicyID)
    {
        return $this->MailCowAPI->post('delete/domain-policy', $PolicyID);
    }
}
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
     * @return array|string
     */
    public function getWhitelistPolicy(string $domain)
    {
        return $this->MailCowAPI->get('get/policy_wl_domain/' . $domain);
    }

    /**
     * @return array|string
     */
    public function getBlacklistPolicy(string $domain)
    {
        return $this->MailCowAPI->get('get/policy_bl_domain/' . $domain);
    }

    /**
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
     * @return array|string
     */
    public function deletePolicy(array $PolicyID)
    {
        return $this->MailCowAPI->post('delete/domain-policy', $PolicyID);
    }
}
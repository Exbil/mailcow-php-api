<?php


namespace Exbil\Mailcow\Domains;

use Exbil\MailCowAPI;

class Domains
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
    public function getDomains()
    {
        return $this->MailCowAPI->get('get/domain/all');
    }

    /**
     * @return array|string
     */
    public function getDomain(string $domain)
    {
        return $this->MailCowAPI->get('get/domain/' . $domain);
    }

    /**
     * @return array|string
     */
    public function addDomain(string $domain, string $description, int $aliases, int $mailboxes)
    {
        return $this->MailCowAPI->post('add/domain', [
            "domain" => $domain,
            "description" => $description,
            "aliases" => $aliases,
            "mailboxes" => $mailboxes,
            "defquota" => "3072",
            "maxquota" => "10240",
            "quota" => "10240",
            "active" => "1",
            "rl_value" => "10",
            "rl_frame" => "s",
            "backupmx" => "0",
            "relay_all_recipients" => "0",
            "restart_sogo" => "1"
        ]);
    }

    /**
     * @return array|string
     */
    public function updateDomain(string $domain, string $description, int $aliases, int $mailboxes)
    {
        return $this->MailCowAPI->post('edit/domain', [
            "items" => [
                "domain" => $domain
            ],
            "attr" => [
                "description" => $description,
                "aliases" => $aliases,
                "mailboxes" => $mailboxes,
                "defquota" => "3072",
                "maxquota" => "10240",
                "quota" => "10240",
                "active" => "1",
                "rl_value" => "10",
                "rl_frame" => "s",
                "backupmx" => "0",
                "relay_all_recipients" => "0",
                "restart_sogo" => "1"
            ]
        ]);
    }

    /**
     * @return array|string
     */
    public function deleteDomains(string $domain)
    {
        return $this->MailCowAPI->post('delete/domain', [$domain]);
    }
}
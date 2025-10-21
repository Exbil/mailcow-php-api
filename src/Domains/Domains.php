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
     * `getDomains()` - Returns all domains configuration
     * @return array|string
     */
    public function getDomains()
    {
        return $this->MailCowAPI->get('get/domain/all');
    }

    /**
     * `getDomain()` - Returns the configuration of given domain
     * @return array|string
     */
    public function getDomain(string $domain)
    {
        return $this->MailCowAPI->get('get/domain/' . $domain);
    }

    /**
     * `addDomain()` - Creates a new domain
     * @param string $domain The domain name, e.g. 'mailcow.tld'
     * @param string $description A description for the domain
     * @param int $aliases Max. amount of aliases for this domain
     * @param int $mailboxes Max. amount of mailboxes for this domain
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
     * `updateDomain()` - Updates given domain
     * @param string $domain The domain name to update
     * @param string $description A description for the domain
     * @param string $aliases Max. amount of aliases for this domain
     * @param string $mailboxes Max. amount of mailboxes for this domain
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
     * `deleteDomain()` - Deletes given domain
     * @param string $domain The domain name to delete
     * @return array|string
     */
    public function deleteDomains(string $domain)
    {
        return $this->MailCowAPI->post('delete/domain', [$domain]);
    }

    /**
     * `updateFooter()` - Updates the domain-wide footer
     * @param string $html Footer in HTML format
     * @param string $plain Footer in plain text
     * @param array $mbox_exclude Optional mailboxes to exclude to have this footer
     * @return array|string
     */
    public function updateFooter(string $domain, string $html, string $plain, ?array $mbox_exclude){
        return $this->MailCowAPI->post('edit/domain/footer', [
            "items" => $domain,
            "attr" => [
                "html" => $html,
                "plain" => $plain,
                "mbox_exclude" => $mbox_exclude
            ]
            ]);
    }
}
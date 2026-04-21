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
     * @param string $domain The domain name to retrieve the configuration for
     * @param ?array $tags Optional array of tags to filter the domains by
     * @return array|string
     */
    public function getDomain(string $domain, ?array $tags = null)
    {
        $url = 'get/domain/' . $domain;
        if ($tags !== null) {
            $url .= '?' . http_build_query(['tags' => $tags]);
        }
        return $this->MailCowAPI->get($url);
    }

    /**
     * `addDomain()` - Creates a new domain
     * @param string $domain Domain name, e.g.. 'mailcow.tld'
     * @param string $description Description for the domain
     * @param int $aliases Maximum amount of aliases allowed for this domain
     * @param int $mailboxes Maximum amount of mailboxes allowed for this domain
     * @param int $defquota Standard-Quota in MB
     * @param int $maxquota Maximum-Quota in MB
     * @param int $active 1 = aktiv, 0 = disabled
     * @param int $rl_value Rate-Limit Value
     * @param string $rl_frame Rate-Limit Time frame, e.g. 's', 'm'
     * @param int $backupmx Enable (1) or disable (0) Backup-MX
     * @param int $relay_all_recipients 1 = relay all recipients, 0 = do not relay all recipients
     * @param int $restart_sogo 1 = Restart SoGO, 0 = do not restart SOGO,
     * @param ?array $tags Optional array of tags to assign to the domain
     * @return array|string
     */
    public function addDomain(
        string $domain,
        string $description,
        int $aliases,
        int $mailboxes,
        int $defquota = 3072,
        int $maxquota = 10240,
        int $active = 1,
        int $rl_value = 10,
        string $rl_frame = "s",
        int $backupmx = 0,
        int $relay_all_recipients = 0,
        int $restart_sogo = 1,
        ?array $tags = null
    ) {
        $payload = [
            "domain" => $domain,
            "description" => $description,
            "aliases" => $aliases,
            "mailboxes" => $mailboxes,
            "defquota" => $defquota,
            "maxquota" => $maxquota,
            "quota" => $maxquota,
            "active" => $active,
            "rl_value" => $rl_value,
            "rl_frame" => $rl_frame,
            "backupmx" => $backupmx,
            "relay_all_recipients" => $relay_all_recipients,
            "restart_sogo" => $restart_sogo,
            "tags" => $tags
        ];

        return $this->MailCowAPI->post('add/domain', $payload);
    }

    /**
     * `updateDomain()` - Updates an existing domain
     * @param string $domain Domain name, z.B. 'mailcow.tld'
     * @param string $description Beschreibung der Domain
     * @param int $aliases Maximalanzahl an Aliases
     * @param int $mailboxes Maximalanzahl an Mailboxen
     * @param int $defquota Standard-Quota in MB
     * @param int $maxquota Maximal-Quota in MB
     * @param int $active 1 = aktiv, 0 = deaktiviert
     * @param int $rl_value Rate-Limit Wert
     * @param string $rl_frame Rate-Limit Zeitrahmen
     * @param int $backupmx Backup-MX aktiv (1) oder nicht (0)
     * @param int $relay_all_recipients 1 = alle Empfänger durchreichen, 0 = nicht
     * @param int $restart_sogo 1 = SOGo neu starten, 0 = nicht
     * @return array|string
     */
    public function updateDomain(
        string $domain,
        string $description,
        int $aliases,
        int $mailboxes,
        int $defquota = 3072,
        int $maxquota = 10240,
        int $active = 1,
        int $rl_value = 10,
        string $rl_frame = "s",
        int $backupmx = 0,
        int $relay_all_recipients = 0,
        int $restart_sogo = 1
    ) {
        $payload = [
            "items" => [$domain],
            "attr" => [
                "description" => $description,
                "aliases" => $aliases,
                "mailboxes" => $mailboxes,
                "defquota" => $defquota,
                "maxquota" => $maxquota,
                "quota" => $maxquota,
                "active" => $active,
                "rl_value" => $rl_value,
                "rl_frame" => $rl_frame,
                "backupmx" => $backupmx,
                "relay_all_recipients" => $relay_all_recipients,
                "restart_sogo" => $restart_sogo
            ]
        ];

        return $this->MailCowAPI->post('edit/domain', $payload);
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

    /**
     * `deleteDomainTag()` - Deletes given domain tag
     * @param string $domain The domain name to delete the tag from
     * @param array $tags The tags to delete
     * @return array|string
     * 
     */
    public function deleteDomainTag(string $domain, array $tags){
        return $this->MailCowAPI->post('delete/domain/tag/' . urlencode($domain), $tags);
    }
}
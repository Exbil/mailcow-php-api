<?php

namespace Exbil\MailBoxes;

use Exbil\MailCowAPI;

class MailBoxes
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
    public function getMailBoxes()
    {
        return $this->MailCowAPI->get('get/mailbox/all');
    }

    /**
     * @return array|string
     */
    public function getMailBox(string $domain)
    {
        return $this->MailCowAPI->get('get/mailbox/' . $domain);
    }

    /**
     * @param string $mailname ONLY name | Example "mail" for "mail@domain.de"
     * @return array|string
     */
    public function addMailBox(string $mailname, string $domain, string $full_name, string $password, string $active = "1", string $force_pw_update = "1", string $quota = "1024")
    {
        return $this->MailCowAPI->post('add/mailbox', [
            "local_part" => $mailname,
            "domain" => $domain,
            "name" => $full_name,
            "quota" => $quota,
            "password" => $password,
            "password2" => $password,
            "active" => $active,
            "force_pw_update" => $force_pw_update,
            "tls_enforce_in" => "1",
            "tls_enforce_out" => "1",
        ]);
    }

    /**
     * @return array|string
     */
    public function updateMailBox(string $mail_address, string $full_name, string $password, string $active = "1", string $force_pw_update = "0", string $quota = "1024")
    {
        return $this->MailCowAPI->post('edit/mailbox', [
            "items" => [
                $mail_address
            ],
            "attr" => [
                "name" => $full_name,
                "quota" => $quota,
                "password" => $password,
                "password2" => $password,
                "active" => $active,
                "sender_acl" => [
                    "default",
                ],
                "force_pw_update" => $force_pw_update,
                "sogo_access" => "1"
            ]
        ]);
    }

    /**
     * @return array|string
     */
    public function updateMailboxSpamScore(string $email, string $score)
    {
        return $this->MailCowAPI->post('edit/spam-score', [
            "items" => [
                $email
            ],
            "attr" => [
                "spam_score" => $score
            ]
        ]);
    }

    /**
     * @return array|string
     */
    public function deleteMailBox(array $mails)
    {
        return $this->MailCowAPI->post('delete/mailbox', $mails);
    }
}
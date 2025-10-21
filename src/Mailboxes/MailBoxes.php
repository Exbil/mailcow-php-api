<?php

namespace Exbil\Mailcow\MailBoxes;

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
     * `getMailBoxes()` - Returns all mailboxes
     * @return array|string
     */
    public function getMailBoxes()
    {
        return $this->MailCowAPI->get('get/mailbox/all');
    }

    /**
     * `getMailBox` - Returns all mailboxes for given domain
     * @param string $domain The domain name
     * @return array|string
     */
    public function getMailBox(string $domain)
    {
        return $this->MailCowAPI->get('get/mailbox/all/' . $domain);
    }

    /**
     * `addMailBox()` - Add a new mailbox
     * @param string $mailname ONLY name | Example "mail" for "mail@domain.de"
     * @param string $domain The domain name
     * @param string $full_name User's full name
     * @param string $password The mailbox' password
     * @param int $active Enable (1) or disable (0) mailbox
     * @param int $force_pw_update Force PW update on next login if set to 1
     * @param string $quota Set the quota in MB for mailbox
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
     * `editMailBox()` - Edit a mailbox
     * @param string $mail_address The mailbox to edit
     * @param string $full_name The user's full name
     * @param string $password The user's new password
     * @param string $active Enable (1) or disable (0) mailbox
     * @param string $force_pw_update Force PW update on next login if set to 1
     * @param string $quota Set the quota in MB for mailbox
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
     * `updateMailboxSpamScore()` - Update the mailbox' spam score
     * @param string $email The mailbox to update
     * @param string $score The score to set it to, e.g. '8,5'
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
     * `deleteMailBox()` - Delete given mailbox
     * @param string $mails The mailbox to delete
     * @return array|string
     */
    public function deleteMailBox(array $mails)
    {
        return $this->MailCowAPI->post('delete/mailbox', $mails);
    }

    /**
     * `editPushoverSettings` - Edit the pushover settings for given mailbox
     * @param string $username The mailbox 
     * @param bool $active Enable (true) or disable (false) the pushover config
     * @param int $evaluate_x_prio The evaluation prio, e.g. '0'
     * @param string $key Your Pushover key
     * @param int $only_x_prio Only sent Notification if X prio
     * @param string $senders
     * @param string $senders_regex
     * @param string $text Custom text for your pushover notification
     * @param string $title Custom title for your pushover notification
     * @param string $token Your token from Pushover
     */
    public function editPushoverSettings(string $username, bool $active, int $evaluate_x_prio, string $key, int $only_x_prio, string $senders, string $senders_regex, string $text, string $title, string $token){
        return $this->MailCowAPI->post('edit/pushover', [
            "attr" => [
                "active" => (int)$active,
                "evaluate_x_prio" => $evaluate_x_prio,
                "key" => $key,
                "only_x_prio" => $only_x_prio,
                "senders" => $senders,
                "senders_regex" => $senders_regex,
                "text" => $text,
                "title" => $title,
                "token" => $token
            ],
            "items" => $username
        ]);
    }
}
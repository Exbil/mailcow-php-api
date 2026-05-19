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
     * `getMailBoxByAddress()` - Returns a specific mailbox by full email address
     * @param string $address The full mailbox address (e.g. "user@domain.com")
     * @return array|object|null
     */
    public function getMailBoxByAddress(string $address)
    {
        return $this->MailCowAPI->get('get/mailbox/' . urlencode($address));
    }

    /**
     * `addMailBox()` - Add a new mailbox
     * @param string $mailname ONLY name | Example "mail" for "mail@domain.de"
     * @param string $domain The domain name
     * @param string $full_name User's full name
     * @param string $password The mailbox' password
     * @param string $active Enable (1) or disable (0) mailbox
     * @param string $force_pw_update Force PW update on next login if set to 1
     * @param string $quota Set the quota in MB for mailbox
     * @param bool $tls_enforce_in Enforce TLS for incoming connections
     * @param bool $tls_enforce_out Enforce TLS for outgoing connections
     * @param bool $sogo_access Enable SOGo access
     * @return array|string
     */
    public function addMailBox(
        string $mailname,
        string $domain,
        string $full_name,
        string $password,
        string $active = "1",
        string $force_pw_update = "1",
        string $quota = "1024",
        bool $tls_enforce_in = true,
        bool $tls_enforce_out = true,
        bool $sogo_access = true,
        ?string $authsource = null,
        ?string $tags = null
    ) {
        return $this->MailCowAPI->post('add/mailbox', [
            'local_part' => $mailname,
            'domain' => $domain,
            'name' => $full_name,
            'quota' => $quota,
            'password' => $password,
            'password2' => $password,
            'active' => $active,
            'force_pw_update' => $force_pw_update,
            'tls_enforce_in' => $tls_enforce_in ? '1' : '0',
            'tls_enforce_out' => $tls_enforce_out ? '1' : '0',
            'sogo_access' => $sogo_access ? '1' : '0',
            'authsource' => $authsource ?? 'mailcow',
            'tags' => $tags ?? '',
        ]);
    }

    /**
     * `updateMailBox()` - Edit a mailbox with all fields
     * @param string $mail_address The mailbox to edit
     * @param string $full_name The user's full name
     * @param string $password The user's new password
     * @param string $active Enable (1) or disable (0) mailbox
     * @param string $force_pw_update Force PW update on next login if set to 1
     * @param string $quota Set the quota in MB for mailbox
     * @return array|string
     */
    public function updateMailBox(
        string $mail_address,
        string $full_name,
        string $password,
        string $active = "1",
        string $force_pw_update = "0",
        string $quota = "1024"
    ) {
        return $this->MailCowAPI->post('edit/mailbox', [
            'items' => [$mail_address],
            'attr' => [
                'name' => $full_name,
                'quota' => $quota,
                'password' => $password,
                'password2' => $password,
                'active' => $active,
                'sender_acl' => ['default'],
                'force_pw_update' => $force_pw_update,
                'sogo_access' => '1',
            ],
        ]);
    }

    /**
     * `editMailBox()` - Edit a mailbox with flexible attributes (no password required)
     * @param string $mail_address The mailbox to edit
     * @param array $attributes Attributes to update (name, quota, active, password, force_pw_update, sogo_access, etc.)
     * @return array|string
     */
    public function editMailBox(string $mail_address, array $attributes)
    {
        $attr = [];

        // Map common attribute names
        $fieldMappings = [
            'full_name' => 'name',
            'quota_mb' => 'quota',
        ];

        foreach ($attributes as $key => $value) {
            // Apply field mappings
            $attrKey = $fieldMappings[$key] ?? $key;

            // Handle special conversions
            if ($attrKey === 'active') {
                $attr['active'] = is_bool($value) ? ($value ? '1' : '0') : (string) $value;
            } elseif ($attrKey === 'password' && $value !== null && $value !== '') {
                $attr['password'] = $value;
                $attr['password2'] = $value;
            } elseif ($attrKey === 'quota') {
                // Quota should be in MB as string
                $attr['quota'] = (string) $value;
            } elseif ($attrKey === 'sogo_access' || $attrKey === 'force_pw_update') {
                $attr[$attrKey] = is_bool($value) ? ($value ? '1' : '0') : (string) $value;
            } elseif ($value !== null) {
                $attr[$attrKey] = $value;
            }
        }

        // Don't send empty attr
        if (empty($attr)) {
            return ['type' => 'success', 'msg' => 'no_changes'];
        }

        return $this->MailCowAPI->post('edit/mailbox', [
            'items' => [$mail_address],
            'attr' => $attr,
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
     * `getMailboxSpamScore()` - Return the mailbox' spam score
     * @param string $email The mailbox to query
     * @return array|string
     */

    public function getMailboxSpamScore(string $email){
        return $this->MailCowAPI->get('get/spam-score/' . urlencode($email));
    }

    /**
     * `deleteMailBox()` - Delete given mailbox
     * @param array $mails The mailbox(es) to delete
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

    /**
     * `editMailboxACL` - Edits the given mailbox' ACL
     * @param string $mailbox The mailbox to edit the ACL for
     * @param array $acl The ACL to set, e.g. ["spam_alias", "eas_reset", "quarantine", ...]
     * @return array|string
     */
    public function editMailboxACL(string $mailbox, array $acl){
        return $this->MailCowAPI->post('edit/mailbox-acl', [
            "items" => $mailbox,
            "attr" => [
                "user_acl" => $acl
            ]
        ]);
    }

    /**
     * `updateMailboxQuarantineNotification` - Update the quarantine notification settings for given mailbox
     * @param string $mailbox The mailbox to edit the quarantine notification settings for
     * @param array $anyOf Include these items in the notifications, e.g. acme@inc.com
     * @param string $notifyTime The time frame for the notifications, e.g. "hourly"
     * @return array|string
     */
    public function updateMailboxQuarantineNotification(string $mailbox, array $anyOf, string $notifyTime = "hourly"){
        return $this->MailCowAPI->post('edit/quarantine-notification', [
            "items" => $mailbox,
            "attr" => [
                "any_of" => $anyOf
            ],
            "quarantine_notification" => $notifyTime
        ]);
    }

    /**
     * `updateMailboxCustomAttributes()` - Update custom attributes for given mailbox
     * @param string $mailbox The mailbox to edit the custom attributes for
     * @param array $attributes The attributes to update, e.g. ["custom1", "custom2", ...]
     * @param array $value The values to set for the attributes, e.g. ["value1", "value2", ...]
     * @return array|string
     */
    public function updateMailboxCustomAttributes(string $mailbox, array $attributes, array $value){
        return $this->MailCowAPI->post('edit/mailbox', [
            "items" => $mailbox,
            "attribute" => $attributes,
            "value" => $value
        ]);
    }

    /**
     * `deleteMailboxTags()` - Delete tags from given mailbox
     * @param string $mailbox The mailbox to delete the tags from
     * @param array $tags The tags to delete, e.g. ["tag1", "tag2", ...]
     * @return array|string
     */
    public function deleteMailboxTags(string $mailbox, array $tags){
        return $this->MailCowAPI->post('delete/mailbox/tags/' . $mailbox, $tags);
    }
}
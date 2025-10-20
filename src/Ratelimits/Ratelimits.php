<?php
namespace Exbil\Mailcow\Ratelimits;

use Exbil\MailCowAPI;

class Ratelimits {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    /**
     * `getMailboxRatelimits()` - Returns the given mailbox' rate limits
     * @param string $mailbox
     * @return array
     */
    public function getMailboxRatelimits(string $mailbox){
        return $this->MailCowAPI->get('get/rl-mbox/' . $mailbox);
    }

    /**
     * `getDomainRatelimits()` - Returns the given domain's rate limits
     * @param string $domain
     */
    public function getDomainRatelimits(string $domain){
        return $this->MailCowAPI->get('get/rl-domain/' . $domain);
    }

    /**
     * `editMailboxRatelimits()` - Edit mailbox rate limits
     * @param string $mailbox The mailbox name
     * @param int $rl_value Set the rate limit (mails per $rl_frame)
     * @param string $rl_frame Hours, Days, Minutes in short, e.g. "h", "d", ...
     * @return array
     */
    public function editMailboxRatelimits(string $mailbox, int $rl_value = 10, string $rl_frame = "h"){
        return $this->MailCowAPI->post('edit/rl-mbox', [
            "attr" => [
                "rl_value" => $rl_value,
                "rl_frame" => $rl_frame
            ],
            "items" => [
                $mailbox
            ]
        ]);
    }

    /**
     * `editDomainRatelimits()` - Edit given domain's rate limits
     * @param string $domain The domain name
     * @param int $rl_value Set the rate limit (mails per $rl_frame)
     * @param string $rl_frame Hours, Days, Minutes in short, e.g. "h", "d", ...
     * @return array
     */
    public function editDomainRatelimits(string $domain, int $rl_value = 10, string $rl_frame = "h"){
        return $this->MailCowAPI->post('edit/rl-domain', [
            "attr" => [
                "rl_value" => $rl_value,
                "rl_frame" => $rl_frame
            ],
            "items" => [
                $domain
            ]
        ]);
    }
}
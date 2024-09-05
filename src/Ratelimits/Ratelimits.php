<?php
namespace Exbil\Mailcow\Ratelimits;

use Exbil\MailCowAPI;

class Ratelimits {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    public function getMailboxRatelimits(string $mailbox){
        return $this->MailCowAPI->get('get/rl-mbox/' . $mailbox);
    }

    public function getDomainRatelimits(string $domain){
        return $this->MailCowAPI->get('get/rl-domain/' . $domain);
    }

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
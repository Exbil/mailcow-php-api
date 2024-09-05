<?php
namespace Exbil\Mailcow\Fail2Ban;

use Exbil\MailCowAPI;

class Fail2Ban {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    public function editConfig(int $bantimeInMs, string $blacklist, int $max_attempts = 5, int $netban_ipv4 = 24, int $netban_ipv6 = 64, int $retry_window = 600, string $whitelist = null){
        return $this->MailCowAPI->post('edit/fail2ban', [
            "attr"=> [
                "ban_time" => $bantimeInMs,
                "blacklist" => $blacklist,
                "max_attempts" => $max_attempts,
                "netban_ipv4" => $netban_ipv4,
                "netban_ipv6" => $netban_ipv6,
                "retry_window" => $retry_window,
                "whitelist" => $whitelist
            ],
            "items" => "none"
        ]);
    }

    public function getConfig(){
        return $this->MailCowAPI->get('get/fail2ban');
    }
}
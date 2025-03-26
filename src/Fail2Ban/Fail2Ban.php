<?php
namespace Exbil\Mailcow\Fail2Ban;

use Exbil\MailCowAPI;

class Fail2Ban {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    /**
     * editConfig - Edit Fail2Ban config
     * @param int $bantimeInMs Time in ms the IP gets banned
     * @param string $blacklist Blacklist of IPs, e.g. "10.10.10.0/24, 10.100.8.4/32"
     * @param string $whitelist A whitelist, e.g. "mydomain.com, anotherdomain.org"
     * @return array
     */
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
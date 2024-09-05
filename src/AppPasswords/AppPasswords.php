<?php
namespace Exbil\Mailcow\AppPasswords;

use Exbil\MailCowAPI;

class AppPasswords {
    private $MailCowAPI;

    private $protocols = [
        "imap_access",
        "dav_access",
        "smtp_access",
        "eas_access",
        "pop3_access",
        "sieve_access"
    ];

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
        define("PROTOCOL_IMAP", "imap_access");
        define("PROTOCOL_DAV", "dav_access");
        define("PROTOCOL_SMTP", "smtp_access");
        define("PROTOCOL_EAS", "eas_access");
        define("PROTOCOL_POP3", "pop3_access");
        define("PROTOCOL_SIEVE", "sieve_access");
    }

    public function deletePassword(string $id){
        return $this->MailCowAPI->post('delete/app-passwd', [$id]);
    }

    public function getPasswordsFromMailbox(string $mail){
        return $this->MailCowAPI->get('get/app-passwd/all/' . $mail);
    }

    public function createPassword(string $username, string $appName, string $appPasswd, string $appPasswd2, array $protocols = null){
        return $this->MailCowAPI->post('add/app-passwd', [
            "username" => $username,
            "app_name" => $appName,
            "active" => "1",
            "app_passwd" => $appPasswd,
            "app_passwd2" => $appPasswd2,
            "protocols" => $this->computeProtocolsArray($protocols)
        ]);
    }

    private function computeProtocolsArray(array $protocols){
        $computed = [];
        foreach($protocols as $protocol){
            if(in_array($protocol, $this->protocols)){
                array_push($computed, $protocol);
            }
        }
        return $computed;
    }
}
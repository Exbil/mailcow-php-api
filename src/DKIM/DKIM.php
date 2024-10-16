<?php
namespace Exbil\Mailcow\DKIM;

use Exbil\MailCowAPI;

class DKIM {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    public function deleteKey(string $domain){
        return $this->MailCowAPI->post('delete/dkim', [$domain]);
    }

    public function getKey(string $domain){
        return $this->MailCowAPI->get('get/dkim/' . $domain);
    }

    public function generateKey(string $domains, string $selector = "dkim", int $keySize = 2048){
        return $this->MailCowAPI->post('add/dkim', [
            "dkim_selector" => $selector,
            "domains" => $domains,
            "key_size" => $keySize
        ]);
    }
    public function duplicateKey(string $fromDomain, string $toDomain){
        return $this->MailCowAPI->post('add/dkim_duplicate', [
            "from_domain" => $fromDomain,
            "to_domain" => $toDomain
        ]);
    }
}
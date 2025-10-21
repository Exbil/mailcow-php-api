<?php
namespace Exbil\Mailcow\DKIM;

use Exbil\MailCowAPI;

class DKIM {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    /**
     * `deleteKey()` - Deletes the DKIM key for given domain
     * @param string $domain The domain name
     * @return array
     */
    public function deleteKey(string $domain){
        return $this->MailCowAPI->post('delete/dkim', [$domain]);
    }

    /**
     * `getKey()` - Get the DKIM key for given domain
     * @param string $domain The domain name
     * @return array
     */
    public function getKey(string $domain){
        return $this->MailCowAPI->get('get/dkim/' . $domain);
    }

    /**
     * `generateKey()` - Generate a new DKIM key for given domain
     * @param string $domains The domain to create the key for
     * @param string $selector The selector (default: 'dkim')
     * @param int $keySize The keysize (default: 2048)
     * @return array
     */
    public function generateKey(string $domains, string $selector = "dkim", int $keySize = 2048){
        return $this->MailCowAPI->post('add/dkim', [
            "dkim_selector" => $selector,
            "domains" => $domains,
            "key_size" => $keySize
        ]);
    }

    /**
     * `duplicateKey()` - Duplicate the DKIM key to another domain
     * @param string $fromDomain The key you want to copy from
     * @param string $toDomain Where to copy the key to
     * @return array
     */
    public function duplicateKey(string $fromDomain, string $toDomain){
        return $this->MailCowAPI->post('add/dkim_duplicate', [
            "from_domain" => $fromDomain,
            "to_domain" => $toDomain
        ]);
    }
}
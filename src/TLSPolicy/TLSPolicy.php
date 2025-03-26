<?php
namespace Exbil\Mailcow\TLSPolicy;

use Exbil\MailCowAPI;

class TLSPolicy {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    public function createPolicyMap(string $parameters, string $destination, string $policy = "encrypt"){
        return $this->MailCowAPI->post('add/tls-policy-map', [
            "active" => 1,
            "parameters" => $parameters,
            "dest" => $destination,
            "policy" => $policy
        ]);
    }

    public function deletePolicyMap(int $id){
        return $this->MailCowAPI->post('delete/tls-policy-map', [$id]);
    }

    public function getPolicyMap(int $id){
        return $this->MailCowAPI->post('delete/tls-policy-map/' . $id);
    }
}
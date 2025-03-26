<?php
namespace Exbil\Mailcow\Resources;

use Exbil\MailCowAPI;

class Resources {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    public function deleteResource(string $resourceMail){
        return $this->MailCowAPI->post('delete/resource', [$resourceMail]);
    }

    public function getAll(){
        return $this->MailCowAPI->get('get/resource/all');
    }

    public function addResource(string $domain, string $description, string $type = "location", bool $multiple_bookings = false, bool $multiple_bookings_custom = null, bool $multiple_bookings_select = false){
        return $this->MailCowAPI->post('add/resource', [
            "active" => 1,
            "description" => $description,
            "domain" => $domain,
            "kind" => $type,
            "multiple_bookings" => (int)$multiple_bookings,
            "multiple_bookings_custom" => $multiple_bookings_custom,
            "multiple_bookings_select" => (int)$multiple_bookings_select
        ]);
    }
}
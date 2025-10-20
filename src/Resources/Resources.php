<?php
namespace Exbil\Mailcow\Resources;

use Exbil\MailCowAPI;

class Resources {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    /**
     * `deleteResource()` - Deletes given resource
     * @param string $resourceMail
     * @return array
     */
    public function deleteResource(string $resourceMail){
        return $this->MailCowAPI->post('delete/resource', [$resourceMail]);
    }

    /**
     * `getAll()` - Returns all resources
     * @return array
     */
    public function getAll(){
        return $this->MailCowAPI->get('get/resource/all');
    }

    /**
     * `addResource()` - Adds a new resource
     * @param string $domain The domain name
     * @param string $description Description for the resource
     * @param string $type Type of the resource, e.g. 'location', 'group' or 'thing'
     * @param bool $multiple_bookings Enable or disable multiple bookings
     * @param bool $multiple_bookings_custom
     * @param bool $multiple_bookings_select
     */
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
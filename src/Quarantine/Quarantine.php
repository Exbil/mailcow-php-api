<?php
namespace Exbil\Mailcow\Quarantine;

use Exbil\MailCowAPI;

class Quarantine {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    /**
     * `deleteItem()` - Delete an item form quarantine
     * @param string $id
     * @return array
     */
    public function deleteItem(string $id){
        return $this->MailCowAPI->post('delete/qitem', [$id]);
    }

    /**
     * `getAllQuarantineMails()` - Returns all quarantined emails
     * @return array
     */
    public function getAllQuarantineMails(){
        return $this->MailCowAPI->get('get/quarantine/all');
    }
}
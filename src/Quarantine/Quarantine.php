<?php
namespace Exbil\Mailcow\Quarantine;

use Exbil\MailCowAPI;

class Quarantine {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    public function deleteItem(string $id){
        return $this->MailCowAPI->post('delete/qitem', [$id]);
    }

    public function getAllQuarantineMails(){
        return $this->MailCowAPI->get('get/quarantine/all');
    }
}
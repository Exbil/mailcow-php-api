<?php
namespace Exbil\Mailcow\QueueManager;

use Exbil\MailCowAPI;

class QueueManager {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

   public function deleteQueue(){
        return $this->MailCowAPI->post('delete/mailq');
   }

   public function flushQueue(){
        return $this->MailCowAPI->post('edit/mailq');
   }

   public function getQueue(){
        return $this->MailCowAPI->get('get/mailq/all');
   }
}
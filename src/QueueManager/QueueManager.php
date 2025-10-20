<?php
namespace Exbil\Mailcow\QueueManager;

use Exbil\MailCowAPI;

class QueueManager {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

   /**
    * `deleteQueue()` - Delete server-wide mail queue
    * @return array
    */
   public function deleteQueue(){
        return $this->MailCowAPI->post('delete/mailq');
   }

   /**
    * `flushQueue()` - Flush server-wide mail queue
    * @return array
    */
   public function flushQueue(){
        return $this->MailCowAPI->post('edit/mailq');
   }

   /**
    * `getQueue()` - Returns all emails in the queue
    * @return array
    */
   public function getQueue(){
        return $this->MailCowAPI->get('get/mailq/all');
   }
}
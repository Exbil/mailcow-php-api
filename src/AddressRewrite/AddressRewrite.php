<?php
namespace Exbil\Mailcow\AddressRewrite;

use Exbil\MailCowAPI;

class AddressRewrite {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    /**
     * addBccMap - Forward all mails for a domain to a specified mailbox via bcc
     * @param string $destination Mailbox to recieve all emails in bcc
     * @param string $local_destination The domain the mails should be forwarded from
     * @param string $type e.g. "sender"
     * @return array
     */
    public function addBccMap(string $destination, string $local_destination, string $type = "sender"){
        return $this->MailCowAPI->post('add/bcc', [
            "active" => 1,
            "bcc_dest" => $destination,
            "local_dest" => $local_destination,
            "type" => $type
        ]);
    }

    /**
     * addRecipientMap - Redirect mails from one to another mailbox
     * @param string $recipient_new The mailbox to forward the mails to
     * @param string $recipient_old The mailbox to forward the mails from
     * @return array
     */
    public function addRecipientMap(string $recipient_new, string $recipient_old){
        return $this->MailCowAPI->post('add/recipient_map', [
            "active" => 1,
            "recipient_map_new" => $recipient_new,
            "recipient_map_old" => $recipient_old
        ]);
    }

    public function deleteBccMap(int $id){
        return $this->MailCowAPI->post('delete/bcc', [$id]);
    }

    public function deleteRecipientMap(int $id){
        return $this->MailCowAPI->post('delete/recipient_map', [$id]);
    }

    public function getBccMap(int $id){
        return $this->MailCowAPI->get('get/bcc/' . $id);
    }

    public function getRecipientMap(int $id){
        return $this->MailCowAPI->get('get/recipient_map/' . $id);
    }
    
}
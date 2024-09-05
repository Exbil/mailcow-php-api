<?php
namespace Exbil\Mailcow\oAuth;

use Exbil\MailCowAPI;

class oAuth {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    public function addClient(string $redirect_uri){
        return $this->MailCowAPI->post('add/oauth2-client', [
            "redirect_uri" => $redirect_uri
        ]);
    }

    public function deleteClient(int $id){
        return $this->MailCowAPI->post('delete/oauth2-client', [$id]);
    }

    public function getClient(int $id){
        return $this->MailCowAPI->get('get/oauth2-client/' . $id);
    }
}
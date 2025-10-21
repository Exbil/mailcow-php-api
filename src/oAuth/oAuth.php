<?php
namespace Exbil\Mailcow\oAuth;

use Exbil\MailCowAPI;

class oAuth {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    /**
     * addClient - Add a oAuth2 client
     * @param string $redirect_uri E.g. "https://app.domain.tld/callback-mailcow"
     * @return array
     */
    public function addClient(string $redirect_uri){
        return $this->MailCowAPI->post('add/oauth2-client', [
            "redirect_uri" => $redirect_uri
        ]);
    }

    /**
     * `deleteClient()` - Delete a oAuth2 client with given ID
     * @param int $id
     * @return array
     */
    public function deleteClient(int $id){
        return $this->MailCowAPI->post('delete/oauth2-client', [$id]);
    }

    /**
     * `getClient()` - Get oAuth2 client configuration
     * @param int $id
     * @return array
     */
    public function getClient(int $id){
        return $this->MailCowAPI->get('get/oauth2-client/' . $id);
    }
}
<?php
namespace Exbil\Mailcow\SSO;

use Exbil\MailCowAPI;

class SSO {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    /**
     * `domain_admin()` - Issues an Domain Admin SSO token
     * @param string $username The admin's username
     * @return array|string Returns the issued token or an error array
     */
    public function domain_admin(string $username){
        return $this->MailCowAPI->post('add/sso/domain-admin', [
            "username" => $username
        ]);
    }

}

?>
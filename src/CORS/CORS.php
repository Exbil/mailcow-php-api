<?php
namespace Exbil\Mailcow\CORS;

use Exbil\MailCowAPI;

class CORS {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    /**
     * `edit_cors()` - Change the CORS settings for the API thus enabling or disabling other domains to access the mailcow API
     * @param array $allowed_origins e.g. '*' or 'mail.mailcow.tld'
     * @param array $allowed_methods e.g. 'POST' or 'GET'
     * @return array
     */
    public function edit_cors(array $allowed_origins, array $allowed_methods){
        return $this->MailCowAPI->post('edit/cors', [
            "attr" => [
                "allowed_origins" => $allowed_origins,
                "allowed_methods" => $allowed_methods
            ],
        ]);
    }

}

?>
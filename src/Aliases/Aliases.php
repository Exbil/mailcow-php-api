<?php

namespace Exbil\Mailcow\Aliases;

use Exbil\MailCowAPI;

class Aliases
{

    /**
     * @var MailCowAPI
     */
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI)
    {
        $this->MailCowAPI = $MailCowAPI;
    }


    /**
     * @return array|string
     */
    public function getAliases()
    {
        return $this->MailCowAPI->get('get/alias/all');
    }

    /**
     * @return array|string
     */
    public function getAlias(string $aliasID)
    {
        return $this->MailCowAPI->get('get/alias/' . $aliasID);
    }

    /**
     * @return array|string
     */
    public function createAlias(string $alias_address, string $alias_dest)
    {
        return $this->MailCowAPI->post('add/alias', [
            "address" => $alias_address,
            "goto" => $alias_dest,
            "active" => "1"
        ]);
    }

    /**
     * @return array|string
     */
    public function updateAlias(string $alias_id, string $alias_address, string $alias_dest, string $private_comment = null, string $public_comment = null)
    {
        return $this->MailCowAPI->post('edit/alias', [
            "items" => [
                $alias_id
            ],
            "attr" => [
                "address" => $alias_address,
                "goto" => $alias_dest,
                "active" => "1",
                "private_comment" => $private_comment,
                "public_comment" => $public_comment,
            ]]);
    }

    /**
     * @return array|string
     */
    public function deleteAlias(string $AliasID)
    {
        return $this->MailCowAPI->post('delete/alias', [$AliasID]);
    }
}
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
     * `getAliases()` - Returns all Aliases
     * @return array|string
     */
    public function getAliases()
    {
        return $this->MailCowAPI->get('get/alias/all');
    }

    /**
     * `getAlias()` - Returns a specific alias
     * @param string $aliasID The alias ID
     * @return array|string
     */
    public function getAlias(string $aliasID)
    {
        return $this->MailCowAPI->get('get/alias/' . $aliasID);
    }

    /**
     * `createAlias()` - Creates a new alias
     * @param string $alias_address The alias name
     * @param string $alias_dest Where to deliver emails sent to the alias address
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
     * `updateAlias()` - Updates given alias
     * @param string $alias_id The alias ID
     * @param string $alias_address The alias name
     * @param string $alias_dest Where to deliver emails sent to the alias address
     * @param int $active Enable (1) or disable (0) the alias address
     * @param string $private_comment Define a private comment
     * @param string $public_comment Define a public comment
     * @return array|string
     */
    public function updateAlias(string $alias_id, string $alias_address, string $alias_dest, int $active = 1, string $private_comment = null, string $public_comment = null)
    {
        return $this->MailCowAPI->post('edit/alias', [
            "items" => [
                $alias_id
            ],
            "attr" => [
                "address" => $alias_address,
                "goto" => $alias_dest,
                "active" => (string) $active,
                "private_comment" => $private_comment,
                "public_comment" => $public_comment,
            ]]);
    }

    /**
     * `deleteAlias()` - Deletes given alias
     * @param string $aliasID The alias ID
     * @return array|string
     */
    public function deleteAlias(string $AliasID)
    {
        return $this->MailCowAPI->post('delete/alias', [$AliasID]);
    }
}
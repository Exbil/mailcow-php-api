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
     * `getAliasesByDomain()` - Returns all aliases for a specific domain
     * @param string $domain The domain name
     * @return array Filtered list of aliases
     */
    public function getAliasesByDomain(string $domain): array
    {
        $allAliases = $this->getAliases();

        if (!is_array($allAliases)) {
            return [];
        }

        $domain = strtolower($domain);

        return array_values(array_filter($allAliases, function ($alias) use ($domain) {
            $address = $alias->address ?? $alias['address'] ?? '';
            $aliasDomain = strtolower(substr($address, strpos($address, '@') + 1));
            return $aliasDomain === $domain;
        }));
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
     * @param string $alias_address The alias name (full email address)
     * @param string|array $alias_dest Where to deliver emails (single address or array)
     * @param bool $active Enable or disable the alias
     * @param string|null $private_comment Optional private comment
     * @param string|null $public_comment Optional public comment
     * @return array|string
     */
    public function createAlias(
        string $alias_address,
        string|array $alias_dest,
        bool $active = true,
        ?string $private_comment = null,
        ?string $public_comment = null
    ) {
        $payload = [
            'address' => $alias_address,
            'goto' => is_array($alias_dest) ? implode(',', $alias_dest) : $alias_dest,
            'active' => $active ? '1' : '0',
        ];

        if ($private_comment !== null) {
            $payload['private_comment'] = $private_comment;
        }

        if ($public_comment !== null) {
            $payload['public_comment'] = $public_comment;
        }

        return $this->MailCowAPI->post('add/alias', $payload);
    }

    /**
     * `updateAlias()` - Updates given alias by ID
     * @param string $alias_id The alias ID
     * @param array $attributes Attributes to update (address, goto, active, private_comment, public_comment)
     * @return array|string
     */
    public function updateAlias(string $alias_id, array $attributes)
    {
        $attr = [];

        if (isset($attributes['address'])) {
            $attr['address'] = $attributes['address'];
        }

        if (isset($attributes['goto'])) {
            $goto = $attributes['goto'];
            $attr['goto'] = is_array($goto) ? implode(',', $goto) : $goto;
        }

        if (array_key_exists('active', $attributes)) {
            $attr['active'] = $attributes['active'] ? '1' : '0';
        }

        if (array_key_exists('private_comment', $attributes)) {
            $attr['private_comment'] = $attributes['private_comment'];
        }

        if (array_key_exists('public_comment', $attributes)) {
            $attr['public_comment'] = $attributes['public_comment'];
        }

        return $this->MailCowAPI->post('edit/alias', [
            'items' => [$alias_id],
            'attr' => $attr,
        ]);
    }

    /**
     * `deleteAlias()` - Deletes given alias
     * @param string|array $aliasIds The alias ID(s) to delete
     * @return array|string
     */
    public function deleteAlias(string|array $aliasIds)
    {
        $ids = is_array($aliasIds) ? $aliasIds : [$aliasIds];
        return $this->MailCowAPI->post('delete/alias', $ids);
    }

    /**
     * `findAliasByAddress()` - Find an alias by its address
     * @param string $address The alias address to search for
     * @return array|null The alias data or null if not found
     */
    public function findAliasByAddress(string $address): ?array
    {
        $allAliases = $this->getAliases();

        if (!is_array($allAliases)) {
            return null;
        }

        $address = strtolower($address);

        foreach ($allAliases as $alias) {
            $aliasAddress = strtolower($alias->address ?? $alias['address'] ?? '');
            if ($aliasAddress === $address) {
                return (array) $alias;
            }
        }

        return null;
    }
}
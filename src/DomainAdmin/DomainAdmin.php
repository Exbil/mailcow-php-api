<?php
namespace Exbil\Mailcow\DomainAdmin;

use Exbil\MailCowAPI;

class DomainAdmin
{
    private $MailCowAPI;

    public $permissions = [
        "syncjobs",
        "quarantine",
        "login_as",
        "sogo_access",
        "app_passwds",
        "bcc_maps",
        "pushover",
        "filters",
        "ratelimit",
        "spam_policy",
        "extend_sender_acl",
        "unlimited_quota",
        "protocol_access",
        "smtp_ip_access",
        "alias_domains",
        "domain_desc"
    ];

    public function __construct(MailCowAPI $MailCowAPI)
    {
        $this->MailCowAPI = $MailCowAPI;
    }

    /**
     * addAdmin - Add an domain admin
     * @param array $domains Array of domains the user should be domain admin of
     * @param string $password
     * @param string $password2 Same password, just again
     * @param string $username User which gets to be the domain admin
     * @return array
     */
    public function addAdmin(array $domains, string $password, string $password2, string $username)
    {
        return $this->MailCowAPI->post('add/domain-admin', [
            "active" => 1,
            "domains" => $domains,
            "password" => $password,
            "password2" => $password2,
            "username" => $username
        ]);
    }

    /**
     * editDomainAdminACL - Edit the ACLs for Domain Admins
     * @param string $acl - The ACL in question to edit
     * @param array $permissions - An array of permissions. See $this->permissions, e.g. ["smtp_ip_access", "domain_desc", "alias_domain", ...]
     * @return array
     */
    public function editDomainAdminACL(string $acl, array $permissions)
    {
        return $this->MailCowAPI->post('edit/da-acl', [
            "items" => $acl,
            "attr" => $this->computePermissions($permissions)
        ]);
    }

    /**
     * internal Helper function
     * @param array $permissions
     * @return array
     */
    private function computePermissions(array $permissions)
    {
        $computed = [];
        foreach($permissions as $permission){
            if(in_array($permission, $this->permissions)){
                array_push($computed, $permission);
            }
        }
        return $computed;
    }

    /**
     * ``deleteDomainAdmin()` - Deletes a domain admin
     * @param string $username Username of the admin
     * @return array
     */
    public function deleteDomainAdmin(string $username){
        return $this->MailCowAPI->post('delete/domain-admin', [$username]);
    }

    /**
     * `editDomainAdmin()` - Edits the account of an domain admin
     * @param string $username The domain admin's username
     * @param array|null $domains The domains the admin can administrate (null to keep current)
     * @param int $active Activate (1) or disable (0) admin account
     * @param string|null $username_new The domain admin's new username (null to keep current)
     * @param string|null $password The domain admin's new password (null to keep current)
     * @param string|null $password2 The domain admin's new password for confirmation
     * @return array
     */
    public function editDomainAdmin(string $username, ?array $domains = null, int $active = 1, ?string $username_new = null, ?string $password = null, ?string $password2 = null)
    {
        $attr = [
            'active' => (string) $active,
        ];

        // Only add optional fields if provided
        if ($username_new !== null) {
            $attr['username_new'] = $username_new;
        }

        if ($domains !== null) {
            $attr['domains'] = $domains;
        }

        if ($password !== null) {
            $attr['password'] = $password;
            $attr['password2'] = $password2 ?? $password;
        }

        return $this->MailCowAPI->post('edit/domain-admin', [
            'items' => [$username],
            'attr' => $attr,
        ]);
    }

    /**
     * `getAllDomainAdmins()` - Returns all domain admins for all domains
     * @return array
     */
    public function getAllDomainAdmins()
    {
        return $this->MailCowAPI->get('get/domain-admin/all');
    }

    /**
     * `getDomainAdmin()` - Returns a specific domain admin by username
     * @param string $username The domain admin's username
     * @return array|object
     */
    public function getDomainAdmin(string $username)
    {
        return $this->MailCowAPI->get('get/domain-admin/' . urlencode($username));
    }

    /**
     * `getDomainAdminsByDomain()` - Returns all domain admins for a specific domain
     * @param string $domain The domain name
     * @return array Filtered list of domain admins
     */
    public function getDomainAdminsByDomain(string $domain): array
    {
        $allAdmins = $this->getAllDomainAdmins();

        if (!is_array($allAdmins)) {
            return [];
        }

        return array_values(array_filter($allAdmins, function ($admin) use ($domain) {
            $adminDomains = (array) ($admin->domains ?? $admin['domains'] ?? []);
            return in_array($domain, $adminDomains, true);
        }));
    }
}
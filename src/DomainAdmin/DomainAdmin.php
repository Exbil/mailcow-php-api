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
     * @param array $domains The domains the admin can administrate
     * @param int $active Activate (1) or disable (0) admin account
     * @param string $username_new The domain admin's new username (optionally)
     * @param string $password The domain admin's new password
     * @param string $password2 The domain admin's new password for confirmation
     * @return array
     */
    public function editDomainAdmin(string $username, array $domains, int $active = 1, string $username_new = null, string $password = null, string $password2 = null){
        return $this->MailCowAPI->post('edit/domain-admin', [
            "items" => [
                $username
            ],
            "attr" => [
                "active" => [$active]
            ],
            "username_new" => $username_new,
            "domains" => [
                $domains
            ],
            "password" => $password,
            "password2" => $password2
        ]);
    }

    /**
     * `getAllDomainAdmins()` - Returns all domain admins for all domains
     * @return array
     */
    public function getAllDomainAdmins(){
        return $this->MailCowAPI->get('get/domain-admin/all');
    }


}
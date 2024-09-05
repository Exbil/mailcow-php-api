<?php
namespace Exbil\Mailcow\DomainAdmin;

use Exbil\MailCowAPI;

class DomainAdmin
{
    private $MailCowAPI;

    private $permissions = [
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

    public function addAdmin(string $domains, string $password, string $password2, string $username)
    {
        return $this->MailCowAPI->post('add/domain-admin', [
            "active" => 1,
            "domains" => $domains,
            "password" => $password,
            "password2" => $password2,
            "username" => $username
        ]);
    }

    public function editDomainAdminACL(string $acl, array $permissions)
    {
        return $this->MailCowAPI->post('edit/da-acl', [
            "items" => $acl,
            "attr" => $this->computePermissions($permissions)
        ]);
    }

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

    public function deleteDomainAdmin(string $username){
        return $this->MailCowAPI->post('delete/domain-admin', [$username]);
    }

    public function editDomainAdmin(string $username, array $domains, int $active = 1, int $username_new = null, string $password = null, string $password2 = null){
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

    public function getAllDomainAdmins(){
        return $this->MailCowAPI->get('get/domain-admin/all');
    }

}
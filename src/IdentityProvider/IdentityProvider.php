<?php
namespace Exbil\Mailcow\IdentityProvider;

use Exbil\MailCowAPI;

class IdentityProvider {
    private $MailCowAPI;

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
    }

    /**
     * `edit_provider_keycloak()` - Edit the keycloak Provider
     * @param string $server_url URL of your keycloak server
     * @param string $realm The realm
     * @param string $client_id Your Client ID from keycloak 
     * @param string $client_secret Your Client Secret
     * @param string $redirect_url The redirect URL
     * @param string $version Keycloak version
     * @param array $redirect_url_extra Any additional URLs allowed for redirect
     * @param int $sync_interval Sync interval with keycloak (in minutes)
     * @param bool $ignore_ssl_error Ignores SSL errors if true
     * @param bool $mailpassword_flow In addition to the Authorization Code Flow (Standard Flow in Keycloak), which is used for Single-Sign On login, mailcow also supports Authentication Flow with direct Credentials. The Mailpassword Flow attempts to validate the user's credentials by using the Keycloak Admin REST API. mailcow retrieves the hashed password from the mailcow_password attribute, which is mapped in Keycloak. 
     * @param bool $periodic_sync Synches peridocially if true
     * @param bool $import_users Imports users if set to true
     * @return array
     */
    public function edit_provider_keycloak(string $server_url, string $realm, string $client_id, string $client_secret, string $redirect_url, string $version, ?array $redirect_url_extra, int $sync_interval = 30, bool $ignore_ssl_error = true, bool $mailpassword_flow = true, bool $periodic_sync = true, bool $import_users = true){
        return $this->MailCowAPI->post('edit/identity-provider', [
            "items" => ["identity-provider"],
            "attr" => [
                "authsource" => "keycloak",
                "server_url" => $server_url,
                "realm" => $realm,
                "client_id" => $client_id,
                "client_secret" => $client_secret,
                "redirect_url" => $redirect_url,
                "redirect_url_extra" => $redirect_url_extra,
                "version" => $version,
                "default_template" => "Default",
                "ignore_ssl_error" => (string) $ignore_ssl_error,
                "mailpassword_flow" => (string) $mailpassword_flow,
                "import_users" => (string) $import_users,
                "sync_interval" => $sync_interval
            ]
        ]);
    }

    /**
     * `edit_provider_ldap` - Edit the LDAP provider
     * @param string $host Your LDAP host, e.g. '127.0.0.1' or 'dc.mailcow.local'
     * @param int $port The LDAP port (default: 389 - SSL: 636)
     * @param bool $use_ssl Set to true to enable - You need to import the root certificate into the ca_certificates or the Trusted Root Certificates (Linux/Windows)
     * @param bool $use_tls Set to true to enable - You need to import the root certificate into the ca_certificates or the Trusted Root Certificates (Linux/Windows)
     * @param string $basedn The Base DN, e.g. 'DC=mailcow,DC=local' or 'OU=Users,DC=mailcow,DC=local'
     * @param string $binddn The Bind DN (full DN of your service account), e.g. 'CN=ldap-readonly,CN=Users,DC=mailcow,DC=local'
     * @param string $bindpass The service account's password
     * @param bool $periodic_sync Set to true to enable
     * @param bool $import_users Set to true to enable
     * @param int $sync_interval Sync interval in minutes
     * @param bool $ignore_ssl_error Set to true to ignore SSL errors
     * @param string $username_field The field to use as the mailcow username
     * @param string $filter Filter, e.g. '(memberOf:1.2.840.113556.1.4.1941:=DC=mailcow,DC=local)'
     * @param string $attribute_field Additional attribute field
     * @return array
     */
    public function edit_provider_ldap(string $host, int $port, bool $use_ssl, bool $use_tls, string $basedn, string $binddn, string $bindpass, bool $periodic_sync = true, bool $import_users = true, int $sync_interval = 30, bool $ignore_ssl_error = true, string $username_field = "mail", string $filter = null, string $attribute_field = "othermailbox"){
        return $this->MailCowAPI->post('edit/identity-provider', [
            "items" => ["identity-provider"],
            "attr" => [
                "authsource" => "ldap",
                "host" => $host,
                "port" => $port,
                "use_ssl" => (string) $use_ssl,
                "use_tls" => (string) $use_tls,
                "ignore_ssl_error" => (string) $ignore_ssl_error,
                "basedn" => $basedn,
                "username_field" => $username_field,
                "filter" => $filter,
                "attribute_field" => $attribute_field,
                "binddn" => $binddn,
                "bindpass" => $bindpass,
                "default_template" => "Default",
                "periodic_sync" => (string) $periodic_sync,
                "import_users" => (string) $import_users,
                "sync_interval" => $sync_interval
            ]
        ]);
    }

    /**
     * `edit_provider_generic` - Edit the generic OIDC provider
     * @param string $authorize_url The authorize URL
     * @param string $token_url The token URL
     * @param string $userinfo_url The URL for user info
     * @param string $client_id Your client ID
     * @param string $client_secret Your client secret
     * @param string $redirect_url Allowed redirect URL
     * @param string $client_scopes Client scopes, e.g. 'openid profile email'
     * @param bool $ignore_ssl_error Set to true to ignore SSL errors
     * @param mixed $redirect_url_extra Additional redirect URLs
     * @return array
     */
    public function edit_provider_generic(string $authorize_url, string $token_url, string $userinfo_url, string $client_id, string $client_secret, string $redirect_url, string $client_scopes, bool $ignore_ssl_error = true, ?array $redirect_url_extra){
        return $this->MailCowAPI->post('edit/identity-provider', [
            "items" => ["identity-provider"],
            "attr" => [
                "authsource" => "generic-oidc",
                "authorize_url" => $authorize_url,
                "token_url" => $token_url,
                "userinfo_url" => $userinfo_url,
                "client_id" => $client_id,
                "client_secret" => $client_secret,
                "redirect_url" => $redirect_url,
                "redirect_url_extra" => $redirect_url_extra,
                "client_scopes" => $client_scopes,
                "default_template" => "Default",
                "ignore_ssl_error" => (string) $ignore_ssl_error
            ]
        ]);
    }

}

?>
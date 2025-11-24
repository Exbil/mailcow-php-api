<?php

namespace Exbil;

use Exbil\Mailcow\Ratelimits\Ratelimits;
use GuzzleHttp\Client;
use Exbil\Mailcow\Aliases\Aliases;
use Exbil\Mailcow\AntiSpam\AntiSpam;
use Exbil\Mailcow\AppPasswords\AppPasswords;
use Exbil\Mailcow\DKIM\DKIM;
use Exbil\Mailcow\DomainAdmin\DomainAdmin;
use Exbil\Mailcow\Domains\Domains;
use Exbil\Mailcow\Exception\ParameterException;
use Exbil\Mailcow\FwdHost\FwdHost;
use Exbil\Mailcow\Quarantine\Quarantine;
use Exbil\Mailcow\Resources\Resources;
use Exbil\Mailcow\MailBoxes\MailBoxes;
use Exbil\Mailcow\QueueManager\QueueManager;
use Exbil\Mailcow\Fail2Ban\Fail2Ban;
use Exbil\Mailcow\Status\Status;
use Exbil\Mailcow\Logs\Logs;
use Exbil\Mailcow\oAuth\oAuth;
use Exbil\Mailcow\Routing\Routing;
use Exbil\Mailcow\AddressRewrite\AddressRewrite;
use Exbil\Mailcow\TLSPolicy\TLSPolicy;
use Exbil\Mailcow\SSO\SSO;
use Exbil\Mailcow\CORS\CORS;
use Exbil\Mailcow\IdentityProvider\IdentityProvider;
use Psr\Http\Message\ResponseInterface;

class MailCowAPI
{
    private Client $httpClient;
    private Credentials $credentials;
    private string $apiToken;
    private bool $verifySSL;
    private int $timeout;
    
    private ?Domains $domainsHandler = null;
    private ?AntiSpam $antiSpamHandler = null;
    private ?MailBoxes $mailBoxesHandler = null;
    private ?Aliases $aliasesHandler = null;
    private ?FwdHost $fwdhostsHandler = null;
    private ?Quarantine $quarantineHandler = null;
    private ?DKIM $dkimHandler = null;
    private ?AppPasswords $appPasswordsHandler = null;
    private ?Resources $resourcesHandler = null;
    private ?Ratelimits $ratelimitsHandler = null;
    private ?Fail2Ban $fail2banHandler = null;
    private ?QueueManager $queueManagerHandler = null;
    private ?Status $statusHandler = null;
    private ?Logs $logsHandler = null;
    private ?Routing $routingHandler = null;
    private ?oAuth $oAuthHandler = null;
    private ?DomainAdmin $domainAdminHandler = null;
    private ?AddressRewrite $addressRewriteHandler = null;
    private ?TLSPolicy $tlsPolicyHandler = null;
    private ?SSO $SSOHandler = null;
    private ?CORS $CORSHandler = null;
    private ?IdentityProvider $IdentityProviderHandler = null;

    /**
     * MailCowAPI constructor.
     *
     * @param string $url Base URL of the MailCow instance (e.g., 'https://mailcow.example.com')
     * @param string $token API Token for all requests
     * @param Client|null $httpClient Optional custom HTTP client
     * @param bool $verifySSL Whether to verify SSL certificates (default: true)
     * @param int $timeout Request timeout in seconds (default: 120)
     */
    public function __construct(
        string $url, 
        string $token, 
        ?Client $httpClient = null, 
        bool $verifySSL = true,
        int $timeout = 120
    ) {
        $this->apiToken = $token;
        $this->verifySSL = $verifySSL;
        $this->timeout = $timeout;
        $this->setHttpClient($httpClient);
        $this->setCredentials($token, $url);
    }

    /**
     * Set or create HTTP client
     *
     * @param Client|null $httpClient
     * @return void
     */
    public function setHttpClient(?Client $httpClient = null): void
    {
        $this->httpClient = $httpClient ?: new Client([
            'allow_redirects' => false,
            'follow_redirects' => false,
            'timeout' => $this->timeout,
            'http_errors' => false,
            'return_transfer' => true
        ]);
    }
    
    /**
     * Set credentials for API authentication
     *
     * @param string|Credentials $credentials
     * @param string $url
     * @return void
     */
    public function setCredentials($credentials, string $url): void
    {
        if (!$credentials instanceof Credentials) {
            $credentials = new Credentials($url, $credentials);
        }

        $this->credentials = $credentials;
    }

    /**
     * Get HTTP client instance
     *
     * @return Client
     */
    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    /**
     * Get API token
     *
     * @return string
     */
    public function getToken(): string
    {
        return $this->apiToken;
    }

    /**
     * Get credentials instance
     *
     * @return Credentials
     */
    private function getCredentials(): Credentials
    {
        return $this->credentials;
    }

    /**
     * Make HTTP request to MailCow API
     *
     * @param string $actionPath The resource path you want to request
     * @param array $params Array filled with request params
     * @param string $method HTTP method used in the request
     *
     * @return ResponseInterface
     *
     * @throws ParameterException If the given field in params is not an array or method is invalid
     */
    private function request(string $actionPath, array $params = [], string $method = 'GET'): ResponseInterface
    {
        $url = $this->getCredentials()->getUrl() . $actionPath;

        if (!is_array($params)) {
            throw new ParameterException('Parameters must be an array');
        }

        $options = [
            'verify' => $this->verifySSL,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'x-api-key' => $this->apiToken
            ]
        ];

        // Add body for methods that support it
        if (in_array($method, ['POST', 'PUT', 'DELETE', 'PATCH']) && !empty($params)) {
            $options['json'] = $params;
        }

        $method = strtoupper($method);
        $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'PATCH'];
        
        if (!in_array($method, $allowedMethods)) {
            throw new ParameterException("Invalid HTTP method: {$method}. Allowed methods: " . implode(', ', $allowedMethods));
        }

        return $this->getHttpClient()->request($method, $url, $options);
    }

    /**
     * Process API response
     *
     * @param ResponseInterface $response
     * @return mixed
     */
    private function processRequest(ResponseInterface $response)
    {
        $body = $response->getBody()->__toString();
        $result = json_decode($body);
        
        if (json_last_error() === JSON_ERROR_NONE) {
            return $result;
        }
        
        return $body;
    }

    /**
     * Perform GET request
     *
     * @param string $actionPath
     * @param array $params
     * @return mixed
     */
    public function get(string $actionPath, array $params = [])
    {
        $response = $this->request($actionPath, $params, 'GET');
        return $this->processRequest($response);
    }

    /**
     * Perform POST request
     *
     * @param string $actionPath
     * @param array $params
     * @return mixed
     */
    public function post(string $actionPath, array $params = [])
    {
        $response = $this->request($actionPath, $params, 'POST');
        return $this->processRequest($response);
    }

    /**
     * Perform PUT request
     *
     * @param string $actionPath
     * @param array $params
     * @return mixed
     */
    public function put(string $actionPath, array $params = [])
    {
        $response = $this->request($actionPath, $params, 'PUT');
        return $this->processRequest($response);
    }

    /**
     * Perform DELETE request
     *
     * @param string $actionPath
     * @param array $params
     * @return mixed
     */
    public function delete(string $actionPath, array $params = [])
    {
        $response = $this->request($actionPath, $params, 'DELETE');
        return $this->processRequest($response);
    }

    /**
     * Perform PATCH request
     *
     * @param string $actionPath
     * @param array $params
     * @return mixed
     */
    public function patch(string $actionPath, array $params = [])
    {
        $response = $this->request($actionPath, $params, 'PATCH');
        return $this->processRequest($response);
    }

    /**
     * Get Domains handler
     *
     * @return Domains
     */
    public function domains(): Domains
    {
        if (!$this->domainsHandler) {
            $this->domainsHandler = new Domains($this);
        }
        return $this->domainsHandler;
    }

    /**
     * Get AntiSpam handler
     *
     * @return AntiSpam
     */
    public function antiSpam(): AntiSpam
    {
        if (!$this->antiSpamHandler) {
            $this->antiSpamHandler = new AntiSpam($this);
        }
        return $this->antiSpamHandler;
    }

    /**
     * Get MailBoxes handler
     *
     * @return MailBoxes
     */
    public function mailBoxes(): MailBoxes
    {
        if (!$this->mailBoxesHandler) {
            $this->mailBoxesHandler = new MailBoxes($this);
        }
        return $this->mailBoxesHandler;
    }

    /**
     * Get Aliases handler
     *
     * @return Aliases
     */
    public function aliases(): Aliases
    {
        if (!$this->aliasesHandler) {
            $this->aliasesHandler = new Aliases($this);
        }
        return $this->aliasesHandler;
    }

    /**
     * Get FwdHost handler
     *
     * @return FwdHost
     */
    public function fwdhosts(): FwdHost
    {
        if (!$this->fwdhostsHandler) {
            $this->fwdhostsHandler = new FwdHost($this);
        }
        return $this->fwdhostsHandler;
    }

    /**
     * Get Quarantine handler
     *
     * @return Quarantine
     */
    public function quarantine(): Quarantine
    {
        if (!$this->quarantineHandler) {
            $this->quarantineHandler = new Quarantine($this);
        }
        return $this->quarantineHandler;
    }

    /**
     * Get DKIM handler
     *
     * @return DKIM
     */
    public function dkim(): DKIM
    {
        if (!$this->dkimHandler) {
            $this->dkimHandler = new DKIM($this);
        }
        return $this->dkimHandler;
    }

    /**
     * Get AppPasswords handler
     *
     * @return AppPasswords
     */
    public function appPasswords(): AppPasswords
    {
        if (!$this->appPasswordsHandler) {
            $this->appPasswordsHandler = new AppPasswords($this);
        }
        return $this->appPasswordsHandler;
    }

    /**
     * Get Resources handler
     *
     * @return Resources
     */
    public function resources(): Resources
    {
        if (!$this->resourcesHandler) {
            $this->resourcesHandler = new Resources($this);
        }
        return $this->resourcesHandler;
    }

    /**
     * Get Ratelimits handler
     *
     * @return Ratelimits
     */
    public function ratelimits(): Ratelimits
    {
        if (!$this->ratelimitsHandler) {
            $this->ratelimitsHandler = new Ratelimits($this);
        }
        return $this->ratelimitsHandler;
    }

    /**
     * Get QueueManager handler
     *
     * @return QueueManager
     */
    public function queueManager(): QueueManager
    {
        if (!$this->queueManagerHandler) {
            $this->queueManagerHandler = new QueueManager($this);
        }
        return $this->queueManagerHandler;
    }

    /**
     * Get Fail2Ban handler
     *
     * @return Fail2Ban
     */
    public function fail2ban(): Fail2Ban
    {
        if (!$this->fail2banHandler) {
            $this->fail2banHandler = new Fail2Ban($this);
        }
        return $this->fail2banHandler;
    }

    /**
     * Get Status handler
     *
     * @return Status
     */
    public function status(): Status
    {
        if (!$this->statusHandler) {
            $this->statusHandler = new Status($this);
        }
        return $this->statusHandler;
    }

    /**
     * Get Logs handler
     *
     * @return Logs
     */
    public function logs(): Logs
    {
        if (!$this->logsHandler) {
            $this->logsHandler = new Logs($this);
        }
        return $this->logsHandler;
    }

    /**
     * Get Routing handler
     *
     * @return Routing
     */
    public function routing(): Routing
    {
        if (!$this->routingHandler) {
            $this->routingHandler = new Routing($this);
        }
        return $this->routingHandler;
    }

    /**
     * Get oAuth handler
     *
     * @return oAuth
     */
    public function oAuth(): oAuth
    {
        if (!$this->oAuthHandler) {
            $this->oAuthHandler = new oAuth($this);
        }
        return $this->oAuthHandler;
    }

    /**
     * Get DomainAdmin handler
     *
     * @return DomainAdmin
     */
    public function domainAdmin(): DomainAdmin
    {
        if (!$this->domainAdminHandler) {
            $this->domainAdminHandler = new DomainAdmin($this);
        }
        return $this->domainAdminHandler;
    }

    /**
     * Get AddressRewrite handler
     *
     * @return AddressRewrite
     */
    public function addressRewrite(): AddressRewrite
    {
        if (!$this->addressRewriteHandler) {
            $this->addressRewriteHandler = new AddressRewrite($this);
        }
        return $this->addressRewriteHandler;
    }

    /**
     * Get TLSPolicy handler
     *
     * @return TLSPolicy
     */
    public function tlsPolicy(): TLSPolicy
    {
        if (!$this->tlsPolicyHandler) {
            $this->tlsPolicyHandler = new TLSPolicy($this);
        }
        return $this->tlsPolicyHandler;
    }

    /**
     * Get SSO handler
     *
     * @return SSO
     */
    public function SSO(): SSO
    {
        if (!$this->SSOHandler) {
            $this->SSOHandler = new SSO($this);
        }
        return $this->SSOHandler;
    }

    /**
     * Get CORS handler
     *
     * @return CORS
     */
    public function CORS(): CORS
    {
        if (!$this->CORSHandler) {
            $this->CORSHandler = new CORS($this);
        }
        return $this->CORSHandler;
    }

    /**
     * Get IdentityProvider handler
     *
     * @return IdentityProvider
     */
    public function IdentityProvider(): IdentityProvider
    {
        if (!$this->IdentityProviderHandler) {
            $this->IdentityProviderHandler = new IdentityProvider($this);
        }
        return $this->IdentityProviderHandler;
    }
}
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
use Psr\Http\Message\ResponseInterface;

class MailCowAPI
{
    private $httpClient;
    private $credentials;
    private $apiToken;
    private $domainsHandler;
    private $antiSpamHandler;
    private $mailBoxesHandler;
    private $aliasesHandler;
    private $fwdhostsHandler;
    private $quarantineHandler;
    private $dkimHandler;
    private $appPasswordsHandler;
    private $resourcesHandler;
    private $ratelimitsHandler;
    private $fail2banHandler;
    private $queueManagerHandler;
    private $statusHandler;
    private $logsHandler;
    private $routingHandler;
    private $oAuthHandler;
    private $domainAdminHandler;
    private $addressRewriteHandler;
    private $tlsPolicyHandler;

    /**
     * MailCowAPI constructor.
     *
     * @param string $token API Token for all requests
     * @param null $httpClient
     */
    public function __construct(string $url, string $token, $httpClient = null) {
        $this->apiToken = $token;
        $this->setHttpClient($httpClient);
        $this->setCredentials($token, $url);
    }


    public function setHttpClient(Client $httpClient = null)
    {
        $this->httpClient = $httpClient ?: new Client([
            'allow_redirects' => false,
            'follow_redirects' => false,
            'timeout' => 120,
            'http_errors' => false,
            'return_transfer' => true

        ]);
    }
    
    public function setCredentials($credentials, $url)
    {
        if (!$credentials instanceof Credentials) {
            $credentials = new Credentials($url,$credentials);
        }

        $this->credentials = $credentials;
    }

    /**
     * @return Client
     */
    public function getHttpClient(): Client
    {
        return $this->httpClient;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->apiToken;
    }


    /**
     * @return Credentials
     */
    private function getCredentials(): Credentials
    {
        return $this->credentials;
    }


    /**
     * @param string $actionPath The resource path you want to request, see more at the documentation.
     * @param array $params Array filled with request params
     * @param string $method HTTP method used in the request
     *
     * @return ResponseInterface
     *
     * @throws ParameterException If the given field in params is not an array
     */
    private function request(string $actionPath, array $params = [], string $method = 'GET'): ResponseInterface
    {
        $url = $this->getCredentials()->getUrl() . $actionPath;

        if (!is_array($params)) {
            throw new ParameterException();
        }

        $params['x-api-key'] = $this->apiToken;

        switch ($method) {
            case 'GET':
                return $this->getHttpClient()->get($url, ['verify' => false, 'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json', 'x-api-key' => $this->apiToken]]);
            case 'POST':
                return $this->getHttpClient()->post($url, ['verify' => false, 'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json', 'x-api-key' => $this->apiToken], 'json' => $params,]);
            case 'PUT':
                return $this->getHttpClient()->put($url, ['verify' => false, 'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json', 'x-api-key' => $this->apiToken], 'json' => $params,]);
            case 'DELETE':
                return $this->getHttpClient()->delete($url, ['verify' => false, 'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json', 'x-api-key' => $this->apiToken], 'json' => $params,]);
            case 'PATCH':
                return $this->getHttpClient()->patch($url, ['verify' => false, 'headers' => ['Content-Type' => 'application/json', 'Accept' => 'application/json', 'x-api-key' => $this->apiToken], 'json' => $params,]);
            default:
                throw new ParameterException('Wrong HTTP method passed');
        }
    }

    private function processRequest(ResponseInterface $response)
    {
        $response = $response->getBody()->__toString();
        $result = json_decode($response);
        if (json_last_error() == JSON_ERROR_NONE) {
            return $result;
        } else {
            return $response;
        }
    }

    public function get($actionPath, $params = [])
    {
        $response = $this->request($actionPath, $params);

        return $this->processRequest($response);
    }

    public function post($actionPath, $params = [])
    {
        $response = $this->request($actionPath, $params, 'POST');

        return $this->processRequest($response);
    }

    public function put($actionPath, $params = [])
    {
        $response = $this->request($actionPath, $params, 'PUT');

        return $this->processRequest($response);
    }

    public function delete($actionPath, $params = [])
    {
        $response = $this->request($actionPath, $params, 'DELETE');

        return $this->processRequest($response);
    }

    public function patch($actionPath, $params = [])
    {
        $response = $this->request($actionPath, $params, 'PATCH');

        return $this->processRequest($response);
    }

    /**
     * @return Domains
     */
    public function domains(): Domains
    {
        if (!$this->domainsHandler) $this->domainsHandler = new Domains($this);
        return $this->domainsHandler;
    }

    /**
     * @return AntiSpam
     */
    public function antiSpam(): AntiSpam
    {
        if (!$this->antiSpamHandler) $this->antiSpamHandler = new AntiSpam($this);
        return $this->antiSpamHandler;
    }

    /**
     * @return MailBoxes
     */
    public function mailBoxes (): MailBoxes
    {
        if (!$this->mailBoxesHandler) $this->mailBoxesHandler = new MailBoxes($this);
        return $this->mailBoxesHandler;
    }

    /**
     * @return Aliases
     */
    public function aliases (): Aliases
    {
        if (!$this->aliasesHandler) $this->aliasesHandler = new Aliases($this);
        return $this->aliasesHandler;
    }

    public function fwdhosts (): FwdHost {
        if(!$this->fwdhostsHandler) $this->fwdhostsHandler = new FwdHost($this);
        return $this->fwdhostsHandler;
    }

    public function quarantine (): Quarantine {
        if(!$this->quarantineHandler) $this->quarantineHandler = new Quarantine($this);
        return $this->quarantineHandler;
    }

    public function dkim (): DKIM {
        if(!$this->dkimHandler) $this->dkimHandler = new DKIM($this);
        return $this->dkimHandler;
    }

    public function appPasswords (): AppPasswords {
        if(!$this->appPasswordsHandler) $this->appPasswordsHandler = new AppPasswords($this);
        return $this->appPasswordsHandler;
    }

    public function resources (): Resources {
        if(!$this->resourcesHandler) $this->resourcesHandler = new Resources($this);
        return $this->resourcesHandler;
    }

    public function ratelimits (): Ratelimits {
        if(!$this->ratelimitsHandler) $this->ratelimitsHandler = new Ratelimits($this);
        return $this->ratelimitsHandler;
    }

    public function queueManager (): QueueManager {
        if(!$this->queueManagerHandler) $this->queueManagerHandler = new QueueManager($this);
        return $this->queueManagerHandler;
    }

    public function fail2ban (): Fail2Ban {
        if(!$this->fail2banHandler) $this->fail2banHandler = new Fail2Ban($this);
        return $this->fail2banHandler;
    }

    public function status (): Status {
        if(!$this->statusHandler) $this->statusHandler = new Status($this);
        return $this->statusHandler;
    }

    public function logs (): Logs {
        if(!$this->logsHandler) $this->logsHandler = new Logs($this);
        return $this->logsHandler;
    }

    public function routing (): Routing {
        if(!$this->routingHandler) $this->routingHandler = new Routing($this);
        return $this->routingHandler;
    }

    public function oAuth (): oAuth {
        if(!$this->oAuthHandler) $this->oAuthHandler = new oAuth($this);
        return $this->oAuthHandler;
    }

    public function domainAdmin (): DomainAdmin {
        if(!$this->domainAdminHandler) $this->domainAdminHandler = new DomainAdmin($this);
        return $this->domainAdminHandler;
    }

    public function addressRewrite (): AddressRewrite {
        if(!$this->addressRewriteHandler) $this->addressRewriteHandler = new AddressRewrite($this);
        return $this->addressRewriteHandler;
    }

    public function tlsPolicy (): TLSPolicy {
        if(!$this->tlsPolicyHandler) $this->tlsPolicyHandler = new TLSPolicy($this);
        return $this->tlsPolicyHandler;
    }


}

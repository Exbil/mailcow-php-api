<?php


namespace Exbil;

use GuzzleHttp\Client;
use Exbil\Mailcow\Aliases\Aliases;
use Exbil\Mailcow\AntiSpam\AntiSpam;
use Exbil\Mailcow\Domains\Domains;
use Exbil\Mailcow\Exception\ParameterException;
use Exbil\Mailcow\MailBoxes\MailBoxes;
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


}

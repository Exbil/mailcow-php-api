<?php


namespace Exbil;

class Credentials
{
    private $token;
    private $url;

    /**
     * Credentials constructor.
     * @param string $token
     * @param string $url only Host URL without HTTP/s or /
     */
    public function __construct(string $url, string $token)
    {
        $this->token = $token;
        $this->url = 'https://' . $url . '/api/v1/';
    }

    public function __toString()
    {
        return sprintf(
            '[Host: %s], [Token: %s].',
            $this->url,
            $this->token
        );
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

}

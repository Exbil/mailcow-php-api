<?php
namespace Exbil\Mailcow\Logs;

use Exbil\MailCowAPI;

class Logs {
    private $MailCowAPI;

    private $services = [
        "acme",
        "api",
        "autodiscover",
        "dovecot",
        "netfilter",
        "postfix",
        "ratelimited",
        "rspamd-history",
        "sogo",
        "watchdog"
    ];

    public function __construct(MailCowAPI $MailCowAPI){
        $this->MailCowAPI = $MailCowAPI;
        define("SERVICE_ACME", "acme");
        define("SERVICE_API", "api");
        define("SERVICE_AUTODISCOVER", "autodiscover");
        define("SERVICE_DOVECOT", "dovecot");
        define("SERVICE_NETFILTER", "netfilter");
        define("SERVICE_POSTFIX", "postfix");
        define("SERVICE_RATELIMIT", "ratelimited");
        define("SERVICE_RSPAMD", "rspamd-history");
        define("SERVICE_SOGO", "sogo");
        define("SERVICE_WATCHDOG", "watchdog");
    }

    /**
     * `getLog()` - Get the log of specified service
     * @param string $type The type of log, e.g 'acme' or use the class constants, e.g. Logs::SERVICE_ACME
     * @param int $count
     * @return string
     */
    public function getLog(string $type, int $count = 50){
        if(in_array($type, $this->services)){
            return $this->MailCowAPI->get('get/logs/' . $type . "/" . $count);
        } else {
            return "Specified service does not exist, therefore no log file can be found.";
        }
    }
}
<?php 

namespace Automate\Driver\Proxy;

use Facebook\WebDriver\Remote\WebDriverCapabilityType;

/**
 * Not implemented yet.
 * @todo User proxy
 */
class HttpProxy {

    /**
     * proxy type
     * @var string
     */
    private $type = 'manual';

    /**
     * http proxy adress
     * @var string
     */
    private $adresse = null;

    /**
     * http proxy port
     * @var int
     */
    private $port = null;

    public function __construct(string $adresse = null, int $port) {
        $this->adresse = $adresse;
        $this->port = $port;
    }
    
    public function getAdresse() : string {
        return $this->adresse;
    }

    public function getPort() : int {
        return $this->port;
    }

    public function getType() : string {
        return $this->type;
    }

    public function getAsCapability() : array {
        return ['proxyType' => $this->type, 'httpProxy'=>sprintf('%s:%s', $this->adresse, $this->port)];
    }
}
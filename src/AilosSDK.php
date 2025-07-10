<?php 

namespace Senna\AilosSdkPhp;

use Banco\Cob\Config as CobConfig;
use Banco\Cob\Auth as CobAuth;
use Banco\Cob\CobService;

use Banco\Pix\Config as PixConfig;
use Banco\Pix\Auth as PixAuth;
use Banco\Pix\PixService;

class AilosSDK {
    public CobService $cob;
    public PixService $pix;

    public function __construct(array $configs) {
        $cobConfig = new CobConfig($configs['cob']);
        $this->cob = new CobService($cobConfig, new CobAuth($cobConfig));

        $pixConfig = new PixConfig($configs['pix']);
        $this->pix = new PixService($pixConfig, new PixAuth($pixConfig));
    }
}

?>

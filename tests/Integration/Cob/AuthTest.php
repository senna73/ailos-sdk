<?php

use AilosSDK\API\Cob\CobAPI;
use PHPUnit\Framework\TestCase;
use Dotenv\Dotenv;

class AuthTest extends TestCase
{
    public CobAPI $cob;

    public function setUp(): void
    {
        static $loaded = false;

        if (!$loaded && file_exists(__DIR__ . '/.env')) {
            $dotenv = Dotenv::createImmutable(__DIR__);
            $dotenv->safeLoad();
            $loaded = true;
        }

        $this->cob = new CobAPI();
    }
}
<?php
    use AilosSDK\API\Cob\Auth;
    use PHPUnit\Framework\TestCase;
    use Dotenv\Dotenv;
    use AilosSDK\API\Cob\Config;

    class AuthTest extends TestCase
    {
        private $auth;

        protected function setUp(): void
        {
            static $loaded = false;

            if (!$loaded && file_exists(__DIR__ . '/.env')) {
                $dotenv = Dotenv::createImmutable(__DIR__);
                $dotenv->safeLoad();
                $loaded = true;
            }

            $config = new Config();
            $this->auth = new Auth($config);
        }

        public function testGetAccessTokenIntegration()
        {
            if (!isset($_ENV["AILOS_CONSUMER_KEY"], $_ENV["AILOS_CONSUMER_SECRET"])) {
                $this->markTestSkipped('Chaves de acesso do primeiro token não estão definidas.');
            }

            $response = $this->auth->getAccessToken(
                $_ENV["AILOS_CONSUMER_KEY"],
                $_ENV["AILOS_CONSUMER_SECRET"]
            );

            $data = $response->getData();

            $this->assertArrayHasKey('access_token', $data);
            $this->assertArrayHasKey('token_type', $data);
            $this->assertNotEmpty($data['access_token']);
            $this->assertNotEmpty($data['token_type']);
        }
    }
<?php


namespace fanyi;

class Fanyi{
    private $config;

    private $gateways;

    /**
     * Pay constructor.
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->config = $config;
    }

    /**
     * 指定操作网关
     * @param string $gateway
     * @return GatewayInterface
     */
    public function gateway($gateway = 'youdao')
    {
        return $this->gateways = $this->createGateway($gateway);
    }

    /**
     * 创建操作网关
     * @param string $gateway
     * @return mixed
     */
    protected function createGateway($gateway)
    {
        if (!file_exists(__DIR__ . '/driver/'. ucfirst($gateway) . 'Fanyi.php')) {
            throw new \Exception("Gateway [$gateway] is not supported.");
        }
        $gateway_class = __NAMESPACE__ . '\\driver\\' . ucfirst($gateway) . 'Fanyi';

        if(!$this->config){
            $file = __DIR__ . '/config/Config.php';
            if (!file_exists($file)) {
                throw new \Exception("config [$gateway] is not supported.");
            }

            $this->config = include $file;
        }

        // 判断配置是否存在
        if(!isset($this->config[strtolower($gateway)])){
            throw new \Exception("config [$gateway] is not supported.");
        }

        return new $gateway_class($this->config[strtolower($gateway)]);
    }
}
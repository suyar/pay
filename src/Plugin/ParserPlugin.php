<?php

declare(strict_types=1);

namespace Yansongda\Pay\Plugin;

use Closure;
use Yansongda\Pay\Contract\ParserInterface;
use Yansongda\Pay\Contract\PluginInterface;
use Yansongda\Pay\Exception\InvalidConfigException;
use Yansongda\Pay\Pay;
use Yansongda\Pay\Rocket;

class ParserPlugin implements PluginInterface
{
    /**
     * @throws \Yansongda\Pay\Exception\ServiceNotFoundException
     * @throws \Yansongda\Pay\Exception\ContainerException
     * @throws \Yansongda\Pay\Exception\ContainerDependencyException
     * @throws \Yansongda\Pay\Exception\InvalidConfigException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        $packer = $this->getPacker($rocket);

        return $rocket->setDestination($packer->parse($rocket->getDestination()));
    }

    /**
     * @throws \Yansongda\Pay\Exception\ContainerDependencyException
     * @throws \Yansongda\Pay\Exception\ContainerException
     * @throws \Yansongda\Pay\Exception\InvalidConfigException
     * @throws \Yansongda\Pay\Exception\ServiceNotFoundException
     */
    protected function getPacker(Rocket $rocket): ParserInterface
    {
        $packer = Pay::get($rocket->getDirection() ?? ParserInterface::class);

        $packer = is_string($packer) ? Pay::get($packer) : $packer;

        if (!($packer instanceof ParserInterface)) {
            throw new InvalidConfigException(InvalidConfigException::INVALID_PACKER);
        }

        return $packer;
    }
}

<?php

declare(strict_types=1);

namespace Yansongda\Pay\Plugin\Alipay\Shortcut;

use Yansongda\Pay\Contract\ShortcutInterface;
use Yansongda\Pay\Exception\InvalidParamsException;
use Yansongda\Pay\Plugin\Alipay\Fund\TransOrderQueryPlugin;
use Yansongda\Pay\Plugin\Alipay\Trade\FastPayRefundQueryPlugin;
use Yansongda\Pay\Plugin\Alipay\Trade\QueryPlugin;

class QueryShortcut implements ShortcutInterface
{
    /**
     * @throws \Yansongda\Pay\Exception\InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        $typeMethod = ($params['_type'] ?? 'default').'Plugins';

        if (method_exists($this, $typeMethod)) {
            return $this->{$typeMethod}();
        }

        throw new InvalidParamsException(InvalidParamsException::QUERY_TYPE_ERROR, "Query type [$typeMethod] not supported");
    }

    protected function defaultPlugins(): array
    {
        return [
            QueryPlugin::class,
        ];
    }

    protected function refundPlugins(): array
    {
        return [
            FastPayRefundQueryPlugin::class,
        ];
    }

    protected function transferPlugins(): array
    {
        return [
            TransOrderQueryPlugin::class,
        ];
    }
}

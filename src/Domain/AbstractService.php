<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Domain;

use Aura\Auth;
use Aura\Payload\Payload;
use Aura\Payload_Interface\PayloadStatus as Status;
use Aura\Payload_Interface\PayloadInterface;

abstract class AbstractService
{
    protected $service;

    protected $protoPayload;

    public function __construct(
        $service,
        PayloadInterface $payload = null
    ) {
        $this->service = $service;
        $this->protoPayload = $payload ?? new Payload;
    }

    protected function newPayload()
    {
        return clone $this->protoPayload;
    }
}

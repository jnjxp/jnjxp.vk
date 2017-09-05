<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Domain;

use Aura\Auth;
use Aura\Payload\Payload;
use Aura\Payload_Interface\PayloadStatus as Status;
use Aura\Payload_Interface\PayloadInterface;

class Logout extends AbstractService
{
    public function __construct(
        Auth\Service\LogoutService $service,
        PayloadInterface $payload = null
    ) {
        parent::__construct($service, $payload);
    }

    public function __invoke(Auth\Auth $auth) : PayloadInterface
    {
        $payload = $this->newPayload();

        try {

            $this->service->logout($auth);

            $payload
                ->setStatus(Status::SUCCESS)
                ->setOutput($auth);

        } catch (\Exception $exception) {
            $payload
                ->setStatus(Status::ERROR)
                ->setOutput($exception);
        }

        return $payload;
    }
}

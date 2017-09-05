<?php
// @codingStandardsIgnoreFile

namespace Jnjxp\Vk\Domain;

use Aura\Auth;
use Aura\Payload_Interface\PayloadStatus as Status;
use Aura\Payload_Interface\PayloadInterface;

class Login extends AbstractService
{
    public function __construct(
        Auth\Service\LoginService $service,
        PayloadInterface $payload = null
    ) {
        parent::__construct($service, $payload);
    }

    public function __invoke(Auth\Auth $auth, array $input = []) : PayloadInterface
    {
        $payload = $this->newPayload();
        $payload->setInput($input);

        try {

            $this->service->login($auth, $input);

            $payload
                ->setStatus(Status::AUTHENTICATED)
                ->setOutput($auth);

        } catch (Auth\Exception\UsernameMissing $exception) {

            $payload
                ->setStatus(Status::NOT_VALID)
                ->setMessages(['username' => 'Username is required'])
                ->setOutput($exception);

        } catch (Auth\Exception\PasswordMissing $e) {

            $payload
                ->setStatus(Status::NOT_VALID)
                ->setMessages(['password' => 'Password is required'])
                ->setOutput($exception);

        } catch (Auth\Exception $exception) {

            $payload
                ->setStatus(Status::NOT_AUTHENTICATED)
                ->setOutput($exception);

        } catch (\Exception $exception) {

            $payload
                ->setStatus(Status::ERROR)
                ->setOutput($exception);
        }

        return $payload;
    }
}

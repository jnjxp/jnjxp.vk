<?php
/**
 * Voight-Kampff Authentication
 *
 * PHP version 5
 *
 * This program is free software: you can redistribute it and/or modify it
 * under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or (at your
 * option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Service
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2015 Jake Johns
 * @license   http://www.gnu.org/licenses/agpl-3.0.txt AGPL V3
 * @link      https://github.com/jnjxp/jnjxp.vk
 */

namespace Jnjxp\Vk;

use Aura\Auth\Auth;
use Aura\Auth\Service\LoginService as AuraLogin;
use Aura\Auth\Exception as AuraException;

use Aura\Payload_Interface\PayloadInterface as Payload;
use Aura\Payload_Interface\PayloadStatus as Status;

use Exception;

/**
 * Login Service
 *
 * @category Service
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt AGPL V3
 * @link     https://github.com/jnjxp/jnjxp.vk
 */
class Login extends AbstractService
{
    /**
     * Aura\Auth Login Service
     *
     * @var AuraLogin
     *
     * @access protected
     */
    protected $auraLogin;

    /**
     * Signal Prefix
     *
     * @var string
     *
     * @access protected
     */
    protected $signal = 'jnjxp/vk:logout';

    /**
     * Create Login service
     *
     * @param Payload   $payload   Domain payload prototype
     * @param AuraLogin $auraLogin Aura\Auth login service
     * @param callable  $event     Event notifier
     *
     * @access public
     */
    public function __construct(
        Payload $payload,
        AuraLogin $auraLogin,
        callable $event = null
    ) {
        parent::__construct($payload, $event);
        $this->auraLogin = $auraLogin;
    }

    /**
     * Attempt authentication with underlying Aura\Auth
     *
     * @param Auth   $auth     Aura\Auth current user representation
     * @param string $username Username string
     * @param string $password Password string
     *
     * @return void
     *
     * @throws AuraException see Aura\Auth exceptions
     *
     * @access protected
     *
     * @see https://github.com/auraphp/Aura.Auth#logging-in
     */
    protected function doLogin(Auth $auth, $username, $password)
    {
        $this->auraLogin->login(
            $auth,
            [
                'username' => $username,
                'password' => $password,
            ]
        );
    }

    /**
     * Attempt login
     *
     * @param Auth   $auth     Aura\Auth current user representation
     * @param string $username Username string
     * @param string $password Password string
     *
     * @return Payload
     *
     * @access public
     */
    public function __invoke(Auth $auth, $username, $password)
    {
        $this->init(
            $auth,
            [
                'username' => $username,
                'password' => (bool) $password
            ]
        );

        try {
            $this->doLogin($auth, $username, $password);

            if (! $auth->isValid()) {
                $error = new Exception('Unknown Authentication Error');
                $this->error($error);
                return $this->payload;
            }

            $this->success();

        } catch (AuraException $e) {
            $this->failure($e);
        } catch (Exception $e) {
            $this->error($e);
        }

        return $this->payload;
    }

    /**
     * Success
     *
     * @return void
     *
     * @access protected
     */
    protected function success()
    {
        $this->payload->setStatus(Status::SUCCESS)
            ->setOutput(
                [
                    'username' => $this->auth->getUserName(),
                    'data'     => $this->auth->getUserData()
                ]
            );

        $this->notify();
    }

    /**
     * Failure
     *
     * Aura\Auth throws various exceptions on failure
     *
     * @param AuraException $exception Failure exception
     *
     * @return void
     *
     * @access protected
     *
     * @see https://github.com/auraphp/Aura.Auth#logging-in
     */
    protected function failure(AuraException $exception)
    {
        $this->payload
            ->setStatus(Status::FAILURE)
            ->setOutput($exception);
        $this->notify();
    }
}

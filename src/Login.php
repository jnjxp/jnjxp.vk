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
        $payload = clone $this->payload;

        $payload->setInput(
            [
                'username' => $username,
                'password' => (bool) $password
            ]
        )->setExtras(['auth' => $auth]);

        try {
            $this->doLogin($auth, $username, $password);

            if (! $auth->isValid()) {
                // if not valid after login
                $payload->setStatus(Status::ERROR)->setOutput(
                    new Exception('Unknown Authentication Error')
                );
                $this->notify('login.error', $payload);
                return $payload;
            }

            $payload->setStatus(Status::SUCCESS)
                ->setOutput(
                    [
                        'username' => $auth->getUserName(),
                        'data'     => $auth->getUserData()
                    ]
                );

            $this->notify('login.success', $payload);

        } catch (AuraException $e) {
            // Aura\Auth throws various exceptions on failure
            // @see https://github.com/auraphp/Aura.Auth#logging-in
            $payload->setStatus(Status::FAILURE)->setOutput($e);
            $this->notify('login.failure', $payload);
            return $payload;

        } catch (Exception $e) {
            $payload->setStatus(Status::ERROR)->setOutput($e);
            $this->notify('login.error', $payload);
            return $payload;
        }

        return $payload;
    }
}

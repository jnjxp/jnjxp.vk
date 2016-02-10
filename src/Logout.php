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
use Aura\Auth\Status as AuthStatus;
use Aura\Auth\Service\LogoutService as AuraLogout;
use Aura\Auth\Exception as AuraException;

use Aura\Payload_Interface\PayloadInterface as Payload;
use Aura\Payload_Interface\PayloadStatus as Status;

use Exception;

/**
 * Logout
 *
 * @category Service
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt AGPL V3
 * @link     https://github.com/jnjxp/jnjxp.vk
 */
class Logout extends AbstractService
{
    /**
     * Aura\Auth Logout Service
     *
     * @var AuraLogout
     *
     * @access protected
     */
    protected $auraLogout;

    /**
     * Create logout service
     *
     * @param Payload    $payload    Domain payload prototype
     * @param AuraLogout $auraLogout Aura\Auth logout service
     * @param callable   $event      Event notifier
     *
     * @access public
     */
    public function __construct(
        Payload $payload,
        AuraLogout $auraLogout,
        callable $event = null
    ) {
        parent::__construct($payload, $event);
        $this->auraLogout = $auraLogout;
    }

    /**
     * Attempt logout with underlying Aura\Auth service
     *
     * @param Auth   $auth   Aura\Auth current user representation
     * @param string $status Desired status after logout
     *
     * @return void
     *
     * @access protected
     */
    protected function doLogout(Auth $auth, $status = AuthStatus::ANON)
    {
        $this->auraLogout->logout($auth, $status);
    }

    /**
     * Attempt logout
     *
     * @param Auth   $auth   Aura\Auth current user representation
     * @param string $status Desired status after logout
     *
     * @return Payload
     *
     * @access public
     */
    public function __invoke(Auth $auth, $status = AuthStatus::ANON)
    {
        $payload = clone $this->payload;

        $payload->setExtras(['auth' => $auth]);

        try {

            // attept underlying authentication
            $this->doLogout($auth, $status);

            $domainStatus = Status::SUCCESS;
            $signal = 'logout.success';

            $result = $auth->getStatus();

            if ($result !== $status) {
                // if staus is not what we expect after logout
                $domainStatus = Status::ERROR;
                $payload->setOutput(
                    new Exception(
                        sprintf(
                            'Expected status "%s", got "%s"',
                            $status, $result
                        )
                    )
                );
                $signal = 'logout.failure';
            }

            $payload->setStatus($domainStatus);
            $this->notify($signal, $payload);

        } catch (Exception $e) {
            $payload->setStatus(Status::ERROR)->setOutput($e);
            $this->notify('logout.error', $payload);
        }

        return $payload;
    }
}

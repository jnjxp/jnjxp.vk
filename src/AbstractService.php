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

use Aura\Payload_Interface\PayloadInterface as Payload;
use Aura\Payload_Interface\PayloadStatus as Status;

use Exception;

/**
 * Abstract base service for login and logout
 *
 * Initialized payload property and callable event notifier
 *
 * @category Service
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt AGPL V3
 * @link     https://github.com/jnjxp/jnjxp.vk
 */
abstract class AbstractService
{

    /**
     * Domain Payload Prototype
     *
     * @var Payload
     *
     * @access protected
     */
    protected $protoPayload;

    /**
     * Domain Payload Object
     *
     * @var Payload
     *
     * @access protected
     */
    protected $payload;

    /**
     * Event Notifier
     *
     * @var callable
     *
     * @access protected
     */
    protected $event;

    /**
     * Auth object
     *
     * @var Auth
     *
     * @access protected
     */
    protected $auth;

    /**
     * Create authentication service
     *
     * @param Payload  $payload Domain payload prototype
     * @param callable $event   Event notifier
     *
     * @access public
     */
    public function __construct(Payload $payload, callable $event = null)
    {
        $this->protoPayload = $payload;
        $this->event   = $event;
    }

    /**
     * Initialize new payload and notify system of processing
     *
     * @param Auth  $auth  Auth Object
     * @param array $input Input parameters
     *
     * @return void
     *
     * @access protected
     */
    protected function init(Auth $auth, array $input = null)
    {
        $this->payload = clone $this->protoPayload;
        $this->payload
            ->setStatus(Status::PROCESSING)
            ->setInput($input)
            ->setExtras(['auth' => $auth]);
        $this->auth = $auth;
        $this->notify();
    }

    /**
     * Notify system of authentication event
     *
     * @return void
     *
     * @access protected
     */
    protected function notify()
    {
        $event = $this->event;
        if ($event) {
            $event($this, $this->payload);
        }
    }

    /**
     * Error
     *
     * @param Exception $exception Error exception
     *
     * @return void
     *
     * @access protected
     */
    protected function error(Exception $exception)
    {
        $this->payload
            ->setStatus(Status::ERROR)
            ->setOutput($exception);
    }
}

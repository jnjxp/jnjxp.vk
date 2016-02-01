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

use Aura\Payload_Interface\PayloadInterface as Payload;

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
     * Signal Prefix
     *
     * @var string
     *
     * @access protected
     */
    protected $signal = 'jnjxp/vk:';


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
        $this->payload = $payload;
        $this->event   = $event;
    }

    /**
     * Notify system of authentication event
     *
     * @param string  $signal  String to pass as event name
     * @param Payload $payload Current state of domain payload
     *
     * @return void
     *
     * @access protected
     */
    protected function notify($signal, Payload $payload)
    {
        $event = $this->event;
        if ($event) {
            $prefix = $this->signal;
            $event($prefix . $signal, $payload, $this);
        }
    }
}

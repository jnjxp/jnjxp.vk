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
 * @category  Router
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2015 Jake Johns
 * @license   http://www.gnu.org/licenses/agpl-3.0.txt AGPL V3
 * @link      https://github.com/jnjxp/jnjxp.vk
 */

namespace Jnjxp\Vk\Router;

use Vperyod\AuthHandler\AuthRequestAwareTrait;

use Aura\Auth\Auth;
use Aura\Auth\Status;
use Aura\Router\Route;
use Aura\Router\Rule\RuleInterface;

use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * AuthRule
 *
 * @category Router
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt AGPL V3
 * @link     https://github.com/jnjxp/jnjxp.vk
 *
 * @see RuleInterface
 */
class AuthRule implements RuleInterface
{
    use AuthRequestAwareTrait;

    /**
     * Check that authentication status is allowed by the route
     *
     * @param Request $request PSR7 Server Request
     * @param Route   $route   Route
     *
     * @return bool
     *
     * @access public
     */
    public function __invoke(Request $request, Route $route)
    {
        $auth = $route->auth;
        if (null !== $auth) {
            $status = $this->getAuthStatus($request);
            if ($auth) {
                // true ? require valid
                return ($status === Status::VALID);
            }
            // false ? require non-valid
            return in_array($status, [Status::ANON, Status::EXPIRED, Status::IDLE]);
        }
        return true;
    }
}

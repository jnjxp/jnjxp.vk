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

use Jnjxp\Vk\AuthAttributeTrait;

use Aura\Auth\Auth;
use Aura\Session\CsrfToken;

use Aura\Router\Route;
use Aura\Router\Rule\RuleInterface;

use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * CsrfRule
 *
 * @category Router
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt AGPL V3
 * @link     https://github.com/jnjxp/jnjxp.vk
 *
 * @see RuleInterface
 */
class CsrfRule implements RuleInterface
{
    use AuthAttributeTrait;

    /**
     * CSRF key in request body
     *
     * @var string
     *
     * @access protected
     */
    protected $csrfKey = '__csrf_value';

    /**
     * Cross-site request forgery token tools.
     *
     * @var CsrfToken
     *
     * @access protected
     */
    protected $token;

    /**
     * __construct
     *
     * @param CsrfToken $token Aura Cross-site request forgery token tools
     *
     * @access public
     */
    public function __construct(CsrfToken $token)
    {
        $this->token = $token;
    }

    /**
     * Set CSRF Key
     *
     * @param string $key key in request body to expect token
     *
     * @return $this
     *
     * @access public
     */
    public function setCsrfKey($key)
    {
        $this->csrfKey = $key;
        return $this;
    }

    /**
     * Check if CSRF detected on unsafe authenticated requests
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

        if ($route->auth
            && $this->isUnsafe($request)
            && $this->isAuthenticated($request)
        ) {
            return $this->isValid($request);
        }

        return true;
    }

    /**
     * Is CSRF value present and valid?
     *
     * @param Request $request PSR7 Request
     *
     * @return bool
     *
     * @access protected
     */
    protected function isValid(Request $request)
    {
        $body = $request->getParsedBody();
        return $this->token->isValid(
            isset($body[$this->csrfKey])
            ? $body[$this->csrfKey]
            : null
        );
    }

    /**
     * Is the request method something unsafe?
     *
     * @param Request $request PSR7 Request
     *
     * @return bool
     *
     * @access protected
     */
    protected function isUnsafe(Request $request)
    {
        $method = $request->getMethod();

        return $method == 'POST'
            || $method == 'PUT'
            || $method == 'PATCH'
            || $method == 'DELETE';
    }

    /**
     * Is user authenticated?
     *
     * @param Request $request PSR7 Request
     *
     * @return bool
     *
     * @access protected
     *
     * @throws InvalidArgumentException if auth attribute is no `Auth`
     */
    protected function isAuthenticated(Request $request)
    {
        $auth = $request->getAttribute($this->authAttribute);

        if (! $auth instanceof Auth) {
            throw new \InvalidArgumentException(
                'Auth attribute not available in request'
            );
        }

        return $auth->isValid();
    }
}

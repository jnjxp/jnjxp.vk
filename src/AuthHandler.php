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
 * @category  Middleware
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2015 Jake Johns
 * @license   http://www.gnu.org/licenses/agpl-3.0.txt AGPL V3
 * @link      https://github.com/jnjxp/jnjxp.vk
 */

namespace Jnjxp\Vk;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Aura\Auth\Auth;
use Aura\Auth\Service\ResumeService as Resume;

/**
 * AuthHandler
 *
 * @category Middleware
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt AGPL V3
 * @link     https://github.com/jnjxp/jnjxp.vk
 */
class AuthHandler
{
    use AuthAttributeTrait;

    /**
     * Resume
     *
     * @var Resume
     *
     * @access protected
     */
    protected $resume;

    /**
     * Auth
     *
     * @var Auth
     *
     * @access protected
     */
    protected $auth;

    /**
     * __construct
     *
     * @param Auth   $auth   Aura\Auth current user representation
     * @param Resume $resume Aura\Auth Resume service
     *
     * @access public
     */
    public function __construct(Auth $auth, Resume $resume)
    {
        $this->auth   = $auth;
        $this->resume = $resume;
    }

    /**
     * Resume
     *
     * @return Aura\Auth\Auth
     *
     * @access protected
     */
    protected function resume()
    {
        $this->resume->resume($this->auth);
        return $this->auth;
    }

    /**
     * Resumes Authenticated Session
     *
     * @param Request  $request  PSR7 HTTP Request
     * @param Response $response PSR7 HTTP Response
     * @param callable $next     Next callable middleware
     *
     * @return Response
     *
     * @access public
     */
    public function __invoke(Request $request, Response $response, callable $next)
    {
        $request = $request->withAttribute(
            $this->authAttribute,
            $this->resume()
        );
        return $next($request, $response);
    }
}

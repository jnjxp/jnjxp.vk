<?php
/**
 * Voight-Kampff - Authentication
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
 * @category  Config
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2016 Jake Johns
 * @license   http://www.gnu.org/licenses/agpl-3.0.txt AGPL V3
 * @link      https://github.com/jnjxp/jnjxp.vk
 */

namespace Jnjxp\Vk;

use Aura\Di\Container;
use Aura\Di\ContainerConfig;

/**
 * Config
 *
 * @category Config
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt AGPL V3
 * @link     https://github.com/jnjxp/jnjxp.vk
 *
 * @see ContainerConfig
 */
class Config extends ContainerConfig
{

    /**
     * Define
     *
     * @param Container $di Aura\Di Container
     *
     * @return void
     *
     * @access public
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function define(Container $di)
    {
        $di->values['cookie'] = $_COOKIE;

        $this->defineAuth($di);
        $this->defineService($di);
        $this->defineRouteRules($di);
        $this->defineSession($di);
        $this->defineUi($di);
    }

    /**
     * Define UI
     *
     * @param Container $di Aura\Di Container
     *
     * @return void
     *
     * @access protected
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    protected function defineUi(Container $di)
    {
        $di->params['Jnjxp\Vk\AuthHandler'] = [
            'auth' => $di->lazyGet('aura/auth:auth'),
            'resume' => $di->lazyGet('aura/auth:resume')
        ];

        if (! $di->has('aura/view:view')) {
            $di->set('aura/view:factory', $di->lazyNew('Aura\View\ViewFactory'));
            $di->set(
                'aura/view:view',
                $di->lazyGetCall('aura/view:factory', 'newInstance')
            );
        }

        $di->params['Jnjxp\Vk\AbstractResponder'] = [
            'view' => $di->lazyGet('aura/view:view')
        ];
    }

    /**
     * Define session
     *
     * @param Container $di Aura\Di Container
     *
     * @return void
     *
     * @access protected
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    protected function defineSession(Container $di)
    {
        $di->set(
            'aura/session:factory',
            $di->lazyNew('Aura\Session\SessionFactory')
        );

        $di->set(
            'aura/session:session',
            $di->lazyGetCall(
                'aura/session:factory',
                'newInstance',
                $di->lazyValue('cookie')
            )
        );

        $di->set(
            'aura/session:csrf',
            $di->lazyGetCall(
                'aura/session:session',
                'getCsrfToken'
            )
        );
    }

    /**
     * Define route rules
     *
     * @param Container $di Aura\Di Container
     *
     * @return void
     *
     * @access protected
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    protected function defineRouteRules(Container $di)
    {
        $di->params['Jnjxp\Vk\Router\CsrfRule'] = [
            'token' => $di->lazyGet('aura/session:csrf')
        ];
    }

    /**
     * Define Service
     *
     * @param Container $di Aura\Di Container
     *
     * @return void
     *
     * @access protected
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    protected function defineService(Container $di)
    {
        $di->params['Jnjxp\Vk\AbstractService'] = [
            'payload' => $di->lazyNew('Aura\Payload\Payload')
        ];

        $di->params['Jnjxp\Vk\Login'] = [
            'auraLogin' => $di->lazyGet('aura/auth:login')
        ];

        $di->params['Jnjxp\Vk\Logout'] = [
            'auraLogout' => $di->lazyGet('aura/auth:logout')
        ];
    }

    /**
     * Define Auth
     *
     * @param Container $di Aura\Di Container
     *
     * @return void
     *
     * @access protected
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    protected function defineAuth(Container $di)
    {
        $di->params['Aura\Auth\AuthFactory']['cookie'] = $di->lazyValue('cookie');

        $di->set(
            'aura/auth:factory',
            $di->lazyNew('Aura\Auth\AuthFactory')
        );

        if (! $di->has('aura/auth:adapter')) {
            $di->set(
                'aura/auth:adapter',
                $di->lazyNew('Aura\Auth\Adapter\NullAdapter')
            );
        }

        $di->set(
            'aura/auth:auth',
            $di->lazyGetCall('aura/auth:factory', 'newInstance')
        );

        $di->set(
            'aura/auth:login',
            $di->lazyGetCall(
                'aura/auth:factory',
                'newLoginService',
                $di->lazyGet('aura/auth:adapter')
            )
        );

        $di->set(
            'aura/auth:logout',
            $di->lazyGetCall(
                'aura/auth:factory',
                'newLogoutService',
                $di->lazyGet('aura/auth:adapter')
            )
        );

        $di->set(
            'aura/auth:resume',
            $di->lazyGetCall(
                'aura/auth:factory',
                'newResumeService',
                $di->lazyGet('aura/auth:adapter')
            )
        );
    }

    /**
     * Modify
     *
     * @param Container $di Aura\Di Container
     *
     * @return void
     *
     * @access public
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    public function modify(Container $di)
    {
        $di->get('aura/view:view')->addData(
            [
                'auth' => $di->get('aura/auth:auth'),
                'csrf' => $di->get('aura/session:csrf')->getValue()
            ]
        );
    }
}

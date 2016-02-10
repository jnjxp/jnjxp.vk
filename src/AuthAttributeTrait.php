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
 * @category  Trait
 * @package   Jnjxp\Vk
 * @author    Jake Johns <jake@jakejohns.net>
 * @copyright 2015 Jake Johns
 * @license   http://www.gnu.org/licenses/agpl-3.0.txt AGPL V3
 * @link      https://github.com/jnjxp/jnjxp.vk
 */

namespace Jnjxp\Vk;

/**
 * Auth Attibute Trait
 *
 * Trait for objects which need to know where the auth attribute is stored in
 * the request.
 *
 * @category Trait
 * @package  Jnjxp\Vk
 * @author   Jake Johns <jake@jakejohns.net>
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt AGPL V3
 * @link     https://github.com/jnjxp/jnjxp.vk
 */
trait AuthAttributeTrait
{
    /**
     * Attribute on request where auth is stored
     *
     * @var string
     *
     * @access protected
     */
    protected $authAttribute = 'aura/auth:auth';

    /**
     * Set auth attribute
     *
     * @param string $attr attribute on request where auth is stored
     *
     * @return $this
     *
     * @access public
     */
    public function setAuthAttribute($attr)
    {
        $this->authAttribute = $attr;
        return $this;
    }
}

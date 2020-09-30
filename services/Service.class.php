<?php

/**
 * Copyright Patrick Bogdan. All rights reserved.
 * See LICENSE.txt for license details.
 *
 * @author     Patrick Bogdan
 * @copyright  2020 Patrick Bogdan
 * @license    https://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 */

namespace BIWS\CPTBuilder\services;

use Exception;

/**
 * Extendable singleton service implementation
 *
 * @since      1.0.0
 *
 * @package    BIWS\CPTBuilder
 * @subpackage services
 */
class Service
{
    /**
     * @since 1.0.0
     * @access private
     * @static
     *
     * @var Service[] $instances Holder for the instantiated services.
     */
    private static array $instances = array();

    /**
     * Protected service constructor
     *
     * @since 1.0.0
     * @access protected
     */
    protected function __construct() {
    }

    /**
     * Not clonable
     *
     * @since 1.0.0
     * @access protected
     * 
     * @throws Exception if trying to create a clone of a service
     */
    protected function __clone() {
        throw new Exception("Cannot clone singleton service");
    }

    /**
     * Not deserializable
     *
     * @since 1.0.0
     * 
     * @throws Exception if trying to deserialize a service
     */
    public function __wakeup()
    {
        throw new Exception("Cannot deserialize singleton service");
    }

    /**
     * Returns a singlton instance of this service
     * 
     * @since 1.0.0
     * @static
     *
     * @return mixed A new instance if the service was not yet registered,
     *               the previously registered service instance otherwise.
     */
    public static function getInstance()
    {
        $service = static::class;
        if (!isset(self::$instances[$service])) {
            self::$instances[$service] = new static();
        }

        return self::$instances[$service];
    }
}

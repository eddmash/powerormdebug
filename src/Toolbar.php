<?php

/**
 * This file is part of the powercomponents package.
 *
 * (c) Eddilbert Macharia (http://eddmash.com)<edd.cowan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eddmash\PowerOrmDebug;

use DebugBar\StandardDebugBar;
use Eddmash\PowerOrm\BaseOrm;
use Eddmash\PowerOrm\Components\Component;

class Toolbar extends Component
{
    public $instance;

    public function ready(BaseOrm $baseOrm)
    {
        $this->instance = new Debugger($baseOrm);

//        $this->instance->setDebugBar(new StandardDebugBar());
    }

    /**
     * True if it this component is accessible as an attribute of the orm.
     *
     * @return bool
     *
     * @since 1.1.0
     *
     * @author Eddilbert Macharia (http://eddmash.com) <edd.cowan@gmail.com>
     */
    public function isQueryable()
    {
        return true;
    }

    /**
     * Instance to to return if the component is queryable.
     *
     * @return mixed
     *
     * @since 1.1.0
     *
     * @author Eddilbert Macharia (http://eddmash.com) <edd.cowan@gmail.com>
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * Name to use when querying this component.
     *
     * @return mixed
     *
     * @since 1.1.0
     *
     * @author Eddilbert Macharia (http://eddmash.com) <edd.cowan@gmail.com>
     */
    public function getName()
    {
        return 'debugger';
    }
}

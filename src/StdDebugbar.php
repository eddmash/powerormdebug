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

class StdDebugbar extends StandardDebugBar
{

    public function getJavascriptRenderer($baseUrl = null, $basePath = null)
    {
        return new JsRenderer($this, $baseUrl, $basePath);
    }
}

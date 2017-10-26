<?php

namespace Eddmash\PowerOrmDebug;

use DebugBar\JavascriptRenderer;
use DebugBar\StandardDebugBar;
use Eddmash\PowerOrm\BaseOrm;
use function Symfony\Component\VarDumper\Dumper\esc;

/**
 * This file is part of the powercomponents package.
 *
 * (c) Eddilbert Macharia (http://eddmash.com)<edd.cowan@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class Debugger
{
    public static $debugbarRenderer;
    private $staticBaseUrl;
    /**
     * @var BaseOrm
     */
    private $orm;

    /**
     * @inheritDoc
     */
    public function __construct(BaseOrm $orm)
    {
        $this->orm = $orm;
    }


    /**
     * @return \DebugBar\JavascriptRenderer
     *
     * @since 1.1.0
     *
     * @author Eddilbert Macharia (http://eddmash.com) <edd.cowan@gmail.com>
     */
    public function setupToolbar()
    {
        $debugbar = new StdDebugbar();
        $debugbarRenderer = $debugbar->getJavascriptRenderer($this->staticBaseUrl);
        $debugStack = new \Doctrine\DBAL\Logging\DebugStack();
        $connection = \Eddmash\PowerOrm\BaseOrm::getDbConnection();

        $connection->getConfiguration()->setSQLLogger($debugStack);

        $debugbar->addCollector(new \DebugBar\Bridge\DoctrineCollector($debugStack));

        self::$debugbarRenderer = $debugbarRenderer;
    }

    /**
     * @return JsRenderer
     */
    private function getDebugbarRenderer()
    {
        if (!self::$debugbarRenderer):
            $this->setupToolbar();
        endif;
        return self::$debugbarRenderer;
    }

    /**
     * @return mixed
     */
    public function getStaticBaseUrl()
    {
        return $this->staticBaseUrl;
    }

    /**
     * @param mixed $staticBaseUrl
     */
    public function setStaticBaseUrl($staticBaseUrl)
    {
        $this->staticBaseUrl = $staticBaseUrl;
    }

    /**
     * Outputs the assets needed to display the debug toolbar.
     * @return string
     * @since 1.1.0
     *
     * @author Eddilbert Macharia (http://eddmash.com) <edd.cowan@gmail.com>
     */
    public function show()
    {
        echo $this->getDebugbarRenderer()->renderHead();
        echo $this->getDebugbarRenderer()->render();

    }


}


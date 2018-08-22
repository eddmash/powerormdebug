<?php

namespace Eddmash\PowerOrmDebug;

use DebugBar\DebugBar;
use DebugBar\JavascriptRenderer;
use DebugBar\StandardDebugBar;
use Eddmash\PowerOrm\BaseOrm;
use Eddmash\PowerOrm\Components\ComponentInterface;
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

    /**
     * @var DebugBar
     */
    public $debugBar;

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
     * @throws \DebugBar\DebugBarException
     * @throws \Eddmash\PowerOrm\Exception\OrmException
     */
    public function setupToolbar()
    {
        if (!self::$debugbarRenderer):

            if (is_null($this->debugBar)):
                // use the default one
                $this->debugBar = new StdDebugbar();
            endif;
            if ($this->staticBaseUrl):
                $debugbarRenderer = $this->debugBar->getJavascriptRenderer($this->staticBaseUrl);
            else:
                $debugbarRenderer = $this->debugBar->getJavascriptRenderer();
            endif;
            $debugStack = new \Doctrine\DBAL\Logging\DebugStack();

            $this->orm->getDatabaseConnection()->getConfiguration()->setSQLLogger($debugStack);

            dump($debugStack);
            $this->debugBar->addCollector(new \DebugBar\Bridge\DoctrineCollector($debugStack));

            self::$debugbarRenderer = $debugbarRenderer;
        endif;
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
     * @since 1.1.0
     *
     * @author Eddilbert Macharia (http://eddmash.com) <edd.cowan@gmail.com>
     */
    public function show()
    {
        $this->setupToolbar();
        echo $this->getDebugbarRenderer()->renderHead();
        echo $this->getDebugbarRenderer()->render();
    }

    /**
     * @return DebugBar
     */
    public function getDebugBar()
    {
        $this->setupToolbar();
        return $this->debugBar;
    }

    /**
     * @param DebugBar $debugBar
     */
    public function setDebugBar(DebugBar $debugBar)
    {
        $this->debugBar = $debugBar;
    }
}

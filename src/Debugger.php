<?php

namespace Eddmash\PowerOrmDebug;

use DebugBar\Bridge\DoctrineCollector;
use DebugBar\DebugBar;
use Doctrine\DBAL\Logging\DebugStack;
use Eddmash\PowerOrm\BaseOrm;

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

    /**
     * The base path from where static files will be served from.
     *
     * This path appended at the begining of /vendor/maximebf/debugbar/src/DebugBar/Resources
     * where the php-debugger resourses are located.
     *
     * @var string
     */
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

        $this->debugBar = new StdDebugbar();

        $debugStack = new DebugStack();
        $this->orm->getDatabaseConnection()->getConfiguration()->setSQLLogger($debugStack);
        $this->debugBar->addCollector(new DoctrineCollector($debugStack));
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


        if ($this->staticBaseUrl):
            $staticUrl = sprintf('%s%s%s',
                $this->staticBaseUrl, DIRECTORY_SEPARATOR,
                '/vendor/maximebf/debugbar/src/DebugBar/Resources'
            );
            $debugbarRenderer = $this->debugBar->getJavascriptRenderer($staticUrl);
        else:
            $debugbarRenderer = $this->debugBar->getJavascriptRenderer();
        endif;


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

}

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

    private $assetsDir;

    private $assetsName = 'debugger';

    public function __construct(BaseOrm $orm)
    {
        $this->orm = $orm;

        $this->debugBar = new StdDebugbar();

        $debugStack = new DebugStack();
        $this->orm->getDatabaseConnection()->getConfiguration()->setSQLLogger($debugStack);
        $this->debugBar->addCollector(new DoctrineCollector($debugStack));
    }

    /**
     * @since 1.1.0
     *
     * @author Eddilbert Macharia (http://eddmash.com) <edd.cowan@gmail.com>
     */
    public function setupToolbar()
    {
//        if ($this->staticBaseUrl):
//            $staticUrl = sprintf('%s%s%s',
//                $this->staticBaseUrl, DIRECTORY_SEPARATOR,
//                '/vendor/maximebf/debugbar/src/DebugBar/Resources'
//            );
//            $debugbarRenderer = $this->debugBar->getJavascriptRenderer($staticUrl);
//        else:
        $debugbarRenderer = $this->debugBar->getJavascriptRenderer();
//        endif;

        $debugbarRenderer->dumpCssAssets(sprintf('%s/%s.css', $this->assetsDir,
            $this->assetsName));
        $debugbarRenderer->dumpJsAssets(sprintf('%s/%s.js', $this->assetsDir,
            $this->assetsName));

        self::$debugbarRenderer = $debugbarRenderer;
    }

    /**
     * @return JsRenderer
     */
    private function getDebugbarRenderer()
    {
        if (!self::$debugbarRenderer) {
            $this->setupToolbar();
        }
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
     *
     * @since 1.1.0
     *
     * @author Eddilbert Macharia (http://eddmash.com) <edd.cowan@gmail.com>
     */
    public function show()
    {
        $this->setupToolbar();

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

    public function setAssetsDirectory($assetsDir)
    {
        $this->assetsDir = $assetsDir;
    }

    public function renderAssets($baseurl = '/')
    {
        $baseurl = rtrim($baseurl, '/');
        $file = sprintf('%s/%s/%s', $baseurl, $this->assetsDir,
            $this->assetsName);
        $html = '';
        $html .= sprintf('<link rel="stylesheet" type="text/css" href="%s.css">'."\n", $file);
        $html .= sprintf('<script type="text/javascript" src="%s.js"></script>'."\n", $file);

        return $html;
    }
}

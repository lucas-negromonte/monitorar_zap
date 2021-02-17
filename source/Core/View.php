<?php

namespace Source\Core;

use League\Plates\Engine;

/**
 * Class View
 * @package Source\Core
 */
class View
{
    /**
     * @var Engine
     */
    private $engine;

    /**
     * @param string $path
     * @param string $ext
     */
    public function __construct($path = CONF_VIEW_PATH, $ext = CONF_VIEW_EXT)
    {
        $this->engine = new Engine($path, $ext);
    }

    /**
     * @param string $name
     * @param string $path
     * @return View
     */
    public function path($name, $path)
    {
        $this->engine->addFolder($name, $path);
        return $this;
    }

    /**
     * @param string $templateName
     * @param array $data
     * @return string
     */
    public function render($templateName, $data)
    {
        return $this->engine->render($templateName, $data);
    }

    /**
     * @return \League\Plates\Engine
     */
    public function engine()
    {
        return $this->engine();
    }
}

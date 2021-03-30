<?php

namespace Source\Core\Router;


/**
 * Class Source\Core\Router Router
 *
 * @package Source\Core\Router
 */
class Router extends Dispatch
{
    /**
     * Router constructor.
     *
     * @param string $projectUrl
     * @param null|string $separator
     */
    public function __construct($projectUrl, $separator = ":")
    {
       
        if (empty($_GET["route"])) {
            redirect("/home/"); 
            exit;  
        }

        $queryString = explode("?", $_SERVER["REQUEST_URI"]);
        $queryString = !empty($queryString[1]) ? "?" . $queryString[1] : "";

        // Validando se a URL finaliza com uma "/". Se não tiver: redireciona
        if (mb_substr($_GET["route"], -1) != '/') {
            redirect($_GET["route"] . "/" . $queryString);
        }   

        parent::__construct($projectUrl, $separator);
    }

    /**
     * @param string $route
     * @param $handler
     * @param string|null $name
     */
    public function post($route, $handler, $name = null)
    {
        $this->addRoute("POST", $route, $handler, $name);
    }

    /**
     * @param string $route
     * @param $handler
     * @param string|null $name
     */
    public function get($route, $handler, $name = null)
    {
        $this->addRoute("GET", $route, $handler, $name);
    }

    /**
     * @param string $route
     * @param $handler
     * @param string|null $name
     */
    public function put($route, $handler, $name = null)
    {
        $this->addRoute("PUT", $route, $handler, $name);
    }

    /**
     * @param string $route
     * @param $handler
     * @param string|null $name
     */
    public function patch($route, $handler, $name = null)
    {
        $this->addRoute("PATCH", $route, $handler, $name);
    }

    /**
     * @param string $route
     * @param $handler
     * @param string|null $name
     */
    public function delete($route, $handler, $name = null)
    {
        $this->addRoute("DELETE", $route, $handler, $name);
    }
}

<?php

namespace Source\Core\Router;

/**
 * Class Source\Core\Router Dispatch
 *
 * @package Source\Core\Router
 */
abstract class Dispatch
{
    /** @var null|array */
    protected $route;

    /** @var bool|string */
    protected $projectUrl;

    /** @var string */
    protected $separator;

    /** @var null|string */
    protected $namespace;

    /** @var null|string */
    protected $group;

    /** @var null|array */
    protected $data;

    /** @var int */
    protected $error;

    /** @var int Bad Request */
    protected $badRequest = 400;

    /** @var int Not Found */
    protected $notFound = 404;

    /** @var int Method Not Allowed */
    protected $methodNotAllowed = 405;

    /** @var int Not Implemented */
    protected $notImplemented = 501;

    /** @var array */
    protected $routes;

    /** @var string */
    protected $patch;

    /** @var string */
    protected $httpMethod;

    /**
     * Dispatch constructor.
     *
     * @param string $projectUrl
     * @param null|string $separator
     */
    public function __construct($projectUrl, $separator = ":")
    {
       
        $this->projectUrl = (substr($projectUrl, "-1") == "/" ? substr($projectUrl, 0, -1) : $projectUrl);
        $this->patch = !empty($_GET["route"]) ? filter_input(INPUT_GET, "route", FILTER_DEFAULT) : "/";
        $this->separator = (!empty($separator) ? $separator : ":");
        $this->httpMethod = $_SERVER['REQUEST_METHOD'];
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return $this->routes;
    }

    /**
     * @param null|string $namespace
     * @return Dispatch
     */
    public function setNamespace($namespace)
    {
        $this->namespace = ($namespace ? ucwords($namespace) : null);
        return $this;
    }

    /**
     * @param null|string $group
     * @return Dispatch
     */
    public function group($group)
    {
        $this->group = ($group ? str_replace("/", "", $group) : null);
        return $this;
    }

    /**
     * @return null|array
     */
    public function data()
    {
        return $this->data;
    }

    /**
     * @return null|int
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * @return bool
     */
    public function dispatch()
    {
        if (empty($this->routes) || empty($this->routes[$this->httpMethod])) {
            $this->error = $this->notImplemented;
            return false;
        }

        $this->route = null;
        foreach ($this->routes[$this->httpMethod] as $key => $route) {
            if (preg_match("~^" . $key . "$~", $this->patch, $found)) {
                $this->route = $route;
            }
        }

        return $this->execute();
    }

    /**
     * @return bool
     */
    private function execute()
    {
        if ($this->route) {
            if (is_callable($this->route['handler'])) {
                call_user_func($this->route['handler'], (!empty($this->route['data']) ? $this->route['data'] : array()));
                return true;
            }

            $controller = $this->route['handler'];
            $method = $this->route['action'];

            if (class_exists($controller)) {
                $newController = new $controller($this);
                if (method_exists($controller, $method)) {
                    $newController->$method(!empty($this->route['data']) ? $this->route['data'] : array());
                    return true;
                }

                $this->error = $this->methodNotAllowed;
                return false;
            }

            $this->error = $this->badRequest;
            return false;
        }

        $this->error = $this->notFound;
        return false;
    }

    /**
     * httpMethod form spoofing
     */
    protected function formSpoofing()
    {
        $post = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        if (!empty($post['_method']) && in_array($post['_method'], array("PUT", "PATCH", "DELETE"))) {
            $this->httpMethod = $post['_method'];
            $this->data = $post;

            unset($this->data["_method"]);
            return;
        }

        if ($this->httpMethod == "POST") {
            $this->data = filter_input_array(INPUT_POST, FILTER_DEFAULT);

            unset($this->data["_method"]);
            return;
        }

        if (in_array($this->httpMethod, array("PUT", "PATCH", "DELETE")) && !empty($_SERVER['CONTENT_LENGTH'])) {
            parse_str(file_get_contents('php://input', false, null, 0, $_SERVER['CONTENT_LENGTH']), $putPatch);
            $this->data = $putPatch;

            unset($this->data["_method"]);
            return;
        }

        $this->data = array();
        return;
    }

    /**
     * ROUTER TRAIT
     */

    /**
     * @param string $name
     * @param array|null $data
     * @return string|null
     */
    public function route($name, $data = null)
    {
        foreach ($this->routes as $http_verb) {
            foreach ($http_verb as $route_item) {
                if (!empty($route_item["name"]) && $route_item["name"] == $name) {
                    return $this->treat($route_item, $data);
                }
            }
        }
        return null;
    }

    /**
     * @param string $route
     * @param array|null $data
     */
    public function redirect($route, $data = null)
    {
        if ($name = $this->route($route, $data)) {
            header("Location: {$name}");
            exit;
        }

        if (filter_var($route, FILTER_VALIDATE_URL)) {
            header("Location: {$route}");
            exit;
        }

        $route = (substr($route, 0, 1) == "/" ? $route : "/{$route}");
        header("Location: {$this->projectUrl}{$route}");
        exit;
    }

    /**
     * @param string $method
     * @param string $route
     * @param string|callable $handler
     * @param null|string
     */
    protected function addRoute($method, $route, $handler, $name = null)
    {
        if ($route == "/") {
            $this->addRoute($method, "", $handler, $name);
        }

        preg_match_all("~\{\s* ([a-zA-Z_][a-zA-Z0-9_-]*) \}~x", $route, $keys, PREG_SET_ORDER);
        $routeDiff = array_values(array_diff(explode("/", $this->patch), explode("/", $route)));

        $this->formSpoofing();
        $offset = ($this->group ? 1 : 0);
        foreach ($keys as $key) {
            $this->data[$key[1]] = (!empty($routeDiff[$offset]) ? $routeDiff[$offset] : null);
            $offset++;
        }

        $route = (!$this->group ? $route : "/{$this->group}{$route}");
        $data = $this->data;
        $namespace = $this->namespace;
        $router = $this->myRouter($method, $handler, $data, $route, $name, $namespace);

        $route = preg_replace('~{([^}]*)}~', "([^/]+)", $route);
        $this->routes[$method][$route] = $router;
    }

    /**
     * @param [type] $method
     * @param [type] $handler
     * @param [type] $data
     * @param [type] $route
     * @param [type] $name
     * @param [type] $namespace
     * @return void
     */
    private function myRouter($method, $handler, $data, $route, $name, $namespace)
    {
        return array(
            "route" => $route,
            "name" => $name,
            "method" => $method,
            "handler" => $this->handler($handler, $namespace),
            "action" => $this->action($handler),
            "data" => $data
        ); 
    }

    /**
     * @param $handler
     * @param $namespace
     * @return string|callable
     */
    private function handler($handler, $namespace)
    {
        if (!is_string($handler)) {
            return $handler;
        }
        $separator = explode($this->separator, $handler);
        return "{$namespace}\\" . $separator[0];
    }

    /**
     * @param $handler
     * @return null|string
     */
    private function action($handler)
    {
        if (!is_string($handler)) {
            return null;
        }
        $separator = explode($this->separator, $handler);
        if (!empty($separator[1])) {
            return $separator[1];
        }

        return null;
        // return (!is_string($handler) ?: (explode($this->separator, $handler)[1] ?? null));
    }

    /**
     * @param array $route_item
     * @param array|null $data
     * @return string|null
     */
    private function treat($route_item, $data = null)
    {
        $route = $route_item["route"];
        if (!empty($data)) {
            $arguments = array();
            $params = array();
            foreach ($data as $key => $value) {
                if (!strstr($route, "{{$key}}")) {
                    $params[$key] = $value;
                }
                $arguments["{{$key}}"] = $value;
            }
            $route = $this->process($route, $arguments, $params);
        }

        return "{$this->projectUrl}{$route}";
    }

    /**
     * @param string $route
     * @param array $arguments
     * @param array|null $params
     * @return string
     */
    private function process($route, $arguments, $params = null)
    {
        $params = (!empty($params) ? "?" . http_build_query($params) : null);
        return str_replace(array_keys($arguments), array_values($arguments), $route) . "{$params}";
    }
}

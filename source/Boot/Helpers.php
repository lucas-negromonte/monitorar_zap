<?php

/**
 * ##### Validate #########################
 * ########################################
 */


/**
 * @param string $email
 * @return bool
 */
function is_email($email)
{
    return filter_variable($email, "mail");
}

/**
 * @param string $password
 * @return bool
 */
function is_password($password)
{
    /** se for uma hash válida o algo sera diferente de zero */
    // if (
    //     password_get_info($password)['algo']
    //     || (mb_strlen($password) >= CONF_PASS_MIN_LEN && mb_strlen($password) <= CONF_PASS_MAX_LEN)
    // ) {
    //     return true;
    // }

    if (mb_strlen($password) >= CONF_PASS_MIN_LEN && mb_strlen($password) <= CONF_PASS_MAX_LEN) {
        return true;
    }
    return false;
}

/**
 * @param string $password
 * @return string
 */
function hash_password($password)
{
    // usado SHA para manter o padrão dos usuarios atuais
    return SHA1($password);

    // código abaixo não funciona no PHP 5.3
    // 
    // if (!empty(password_get_info($password)['algo'])) {
    //     return $password;
    // }
    // return password_hash($password, CONF_PASS_ALGO, CONF_PASS_OPTION);
}

/**
 * @param string $password
 * @param string $hash
 * @return boolean
 */
function check_pass($password, $hash)
{
    // return password_verify($password, $hash);
    return ($password == $hash ? true : false);
}

/**
 * @param mixed $needle
 * @param mixed $haystack
 * @param string $field
 * @return string|null
 */
function mark_checkbox($needle, $haystack, $field = null)
{
    if (is_array($haystack)) {
        foreach ($haystack as $key => $value) {
            if ($key == $needle) {

                $val = (!empty($field) && $field == "status" ? 2 : 1);

                if ($value[$field] == $val) {
                    return "checked";
                }
            }
        }
        return null;
    }

    return ($needle == $haystack ? "checked" : null);
}

/**
 * @param mixed $needle
 * @param mixed $haystack
 * @return string|null
 */
function mark_selectbox($needle, $haystack)
{
    if (is_array($haystack)) {
        return in_array($needle, $haystack) ? "selected" : null;
    }
    return (strtolower($needle) == strtolower($haystack) ? "selected" : null);
}

/**
 * @param mixed $needle
 * @param mixed $haystack
 * @param string $class
 * @return string|null
 */
function mark_active($needle, $haystack, $class = "active")
{
    if (is_array($haystack)) {
        return in_array($needle, $haystack) ? $class : null;
    }
    return ($needle == $haystack ? $class : null);
}

/**
 * @param string $hash
 * @return bool
 */
function rehash_password($hash)
{
    return password_needs_rehash($hash, CONF_PASS_ALGO, CONF_PASS_OPTION);
}

/**
 * Verifica a ocorrência de um texto em uma string
 * @param string $search
 * @param string $where
 * @return boolean
 */
function has_text($search, $where)
{
    $pattern = "/" . $search . "/";

    // var_dump($search, $where);

    if (strstr($where, $search)) {
        // if (preg_match($pattern, $where)) {
        return true;
    }

    return false;
}

/**
 * Verifica se a URL é válida
 *
 * @param string $url
 * @return bool
 */
function check_url($url)
{
    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        return false;
    }

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url); // Inicia uma nova sessão do cURL
    curl_setopt($curl, CURLOPT_TIMEOUT, 500); // Define um tempo limite da requisição
    curl_setopt($curl, CURLOPT_NOBODY, true); // Define que iremos realizar uma requisição "HEAD"
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, false); // Não exibir a saída no navegador
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false); // Não verificar o certificado do site

    curl_exec($curl);  // Executa a sessão do cURL
    $status = curl_getinfo($curl);
    curl_close($curl); // Fecha a sessão do cURL

    if (!$status) {
        return false;
    }

    // 200 = OK | 301 = Moved Permanently
    if ($status["http_code"] == 200 || $status["http_code"] == 301 || $status["http_code"] == 302) {
        return true;
    }
    return false;
}

/**
 * Pega a URL entre aspas de um elemento HTML enviado como string: iframe, a, img, etc
 *
 * @param string $attr = src, href, etc...
 * @param string $code
 * @return string|null
 */
function get_url_attr($attr, $code)
{
    // pega a url entre aspas simples
    $pattern = "/" . $attr . "='(.*?)'/";
    if (preg_match($pattern, $code, $url)) {
        return $url[1];
    }
    // pega a url entre aspas dupla
    $pattern = '/' . $attr . '="(.*?)"/';
    if (preg_match($pattern, $code, $url)) {
        return $url[1];
    }
    return null;
}

/**
 * Valida se o código ou url do Pixel/Postback é válido
 *
 * @param string $type
 * @param string $code
 * @return bool
 */
function check_postback($type, $code)
{
    // validando iframe
    if ($type == 1) {
        if (!has_text("<iframe", $code)) {
            return false;
        }
        if (!has_text("</iframe>", $code)) {
            return false;
        }
        // validando se existe URL no iframe
        $url = get_url_attr("src", $code);
        if (empty($url)) {
            return false;
        }
        if (!check_url($url)) {
            return false;
        }
        return true;
    }
    // validando image
    if ($type == 2) {
        if (!has_text("<img", $code)) {
            return false;
        }
        // validando se existe URL na imagem
        $url = get_url_attr("src", $code);
        if (empty($url)) {
            return false;
        }
        if (!check_url($url)) {
            return false;
        }
        return true;
    }
    // validando javascript
    if ($type == 3) {
        if (!has_text("<script", $code)) {
            return false;
        }
        if (!has_text("</script>", $code)) {
            return false;
        }
        return true;
    }
    // validando URL de postback
    if ($type == 4) {
        if (!check_url($code)) {
            return false;
        }
        return true;
    }
    return false;
}

/**
 * ##### Request ##########################
 * ########################################
 */


/**
 * @return string 
 */
function csrf()
{
    $session = session();
    $session->csrf();

    return "<input type='hidden' name='csrf' value='" . (!empty($session->csrf_token) ? $session->csrf_token : "") . "' />";
}

/**
 * @param $request
 * @return bool
 */
function check_csrf($request)
{
    $session = session();
    if (empty($session->csrf_token) || empty($request['csrf']) || $request['csrf'] != $session->csrf_token) {
        return false;
    }
    return true;
}

/**
 * @return null|string
 */
function flash_message()
{
    // $session = new \Source\Core\Session();
    // if ($session->flash()) {
    //     echo $session->flash();
    // }
    if ($flash = session()->flash()) {
        echo $flash;
    }
    return null;
}

/**
 * ##### URL ##############################
 * ########################################
 */

/**
 * @param string $path
 * @return string
 */
function url($path = null)
{

    if (stristr($_SERVER['HTTP_HOST'], 'localhost')) {

        if ($path) {
            return CONF_URL_TEST . ($path[0] == "/" ?  $path :  "/" . $path);
        }
        return CONF_URL_TEST;
    }

    if ($path) {
        return CONF_URL_BASE . ($path[0] == "/" ?  $path :  "/" . $path);
    }

    return CONF_URL_BASE;
}

/**
 * @param string|null $path
 * @param string $theme
 * @return string
 */
function theme($path = null, $theme = CONF_VIEW_THEME)
{
    if (str_replace("www.", "", $_SERVER['HTTP_HOST']) == "localhost") {
        if ($path) {
            return CONF_URL_TEST . "/themes/{$theme}/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_TEST . "/themes/{$theme}";
    }

    if ($path) {
        return CONF_URL_BASE . "/themes/{$theme}/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }
    return CONF_URL_BASE . "/themes/{$theme}";
}

/**
 * @param string $url
 * @return void
 */
function redirect($url)
{
    header("HTTP/1.1 302 Redirect");
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        header("Location: {$url}");
        exit();
    }

    if (filter_input(INPUT_GET, "route", FILTER_DEFAULT) != $url) {
        $location = url($url);
        header("Location: {$location}");
        exit();
    }
}

/**
 * @return string|null
 */
function url_referral()
{
    if (!empty($_SERVER["HTTP_REFERER"])) {
        return $_SERVER["HTTP_REFERER"];
    }
    return null;
}

/**
 * @param string $route
 * @return array
 */
function route($route)
{
    $route = explode("/", $route);

    $newRoute = array();

    foreach ($route as $key => $value) {
        if (empty($value)) {
            unset($key);
        } else {
            $newRoute[] = filter_variable($value, "stripped");
        }
    }

    return $newRoute;
}

/**
 * Monta os links de filtros que aparecem na URL
 *
 * @param string $params
 * @return string|null
 */
function filter_links($params = null)
{
    parse_str($params, $parameters);
    $filter = array_filter($parameters);
    unset($filter["route"]);

    if (!empty($filter["period"])) {
        if ($filter["period"] == "Custom" || $filter["period"] == "Outro período" || $filter["period"] == "Otro periodo") {
            unset($filter["period"]);
        } else {
            unset($filter["date_start"]);
            unset($filter["date_end"]);
        }
    }
    $filters = "";
    foreach ($filter as $key => $value) {
        if (is_array($value)) {
            $value = implode(", ", $value);
        }
        $filters .= "<span class='badge badge-secondary mr-1'>{$key}: {$value}</span>";
    }
    return $filters;
}


/**
 * @param string $get
 * @param string $value
 * @return String|null
 */
function select_params($get)
{
    //Pega Url Atual
    $url = $_SERVER["REQUEST_URI"];

    //Separa Os Parametros da Url se Existir
    $params = explode("?", $url);
    $params = (array)(!empty($params[1]) ? $params[1] : "");
    parse_str($params, $params);
    $params = (object)$params;

    // Verifica se Valor é valido
    if (!empty($params->$get)) {
        return $params->$get;
    } else {
        return null;
    }
}


/**
 * @param string $get
 * @param string $value
 * @return String|null
 */
function select_filter($get, $value)
{
    $select_params = select_params($get);
    // Faz a comparação se get da url é igual ao Valor inserido
    if (!empty($select_params) && !empty($value) && $select_params == $value) {
        return "selected";
    } else {
        return "";
    }
}


/**
 * ##### Date and Time ####################
 * ########################################
 */


/**
 * @param string $date
 * @param bool $time
 * @param string $language
 * @return void
 */
function date_fmt($date, $time = false, $language = null)
{
    // $language = empty($language) ? session()->user->language : $language;

    // if ($language == "en") {

    //     return !empty($time) ? date("M d, Y h:i:s a", strtotime($date)) : date("M d, Y", strtotime($date));
    // }

    return !empty($time) ? date("d/m/Y H:i:s", strtotime($date)) : date("d/m/Y", strtotime($date));
}

/**
 * ##### Strings ##########################
 * ########################################
 */


/**
 * @param string $string
 * @param int $limit
 * @param string $pointer
 * @return string
 */
function limit_chars($string, $limit, $pointer = "...")
{
    $string = trim($string);
    if (strlen($string) <= $limit) {
        return $string;
    }

    $string = preg_replace("/\r|\n/", "", $string);

    $chars = substr($string, 0, $limit);
    // $chars = mb_substr($string, 0, mb_strrpos(mb_substr($string, 0, $limit), " "));
    return "{$chars}{$pointer}";
}

/**
 * @param int $value
 * @return string
 */
function format_number($value)
{
    return number_format($value, 0, ".", ".");
}

/**
 * @param string|array $data
 * @return string|array
 */
function format_text($data)
{
    if (is_array($data)) {
        $text = array();
        foreach ($data as $key => $value) {
            if ($key == "email" || $key == "user_email" || $key == "paypal_email" || $key == "language") {
                $text[$key] = trim(strtolower($value));
            } elseif ($key == "password" || $key == "confirm_pass" || $key == "new_password") {
                $text[$key] = $value;
            } else {
                $text[$key] = trim(ucwords(strtolower($value)));
            }
        }
        return $text;
    }

    return trim(ucwords(strtolower($data)));
}

/**
 * @param string $string
 * @return string
 */
function only_number($string)
{
    return preg_replace("/[^0-9]/", "", $string);
}

/**
 * @param array|string $amount
 * @param string|null $currency
 * @param bool|null $showIcon
 * @return string
 */
function currency($amount, $currency = null, $showIcon = false)
{
    if (is_array($amount)) {
        $amount = array_sum($amount);
    }
    $icon = "";
    if (!empty($showIcon)) {
        if ($currency == "USD") {
            $icon = "US$ ";
        } else if ($currency == "MXN") {
            $icon = '$MXN ';
        } else {
            $icon = "R$ ";
        }
    }

    if (!empty($currency)) {
        if ($currency == "USD" || $currency == "MXN") {
            return $icon . number_format($amount, 2, ".", ",");
        }
        return $icon . number_format($amount, 2, ",", ".");
    }
    return $icon . number_format($amount, 2, ",", ".");
}

/**
 * @param float|int $dividend
 * @param float|int $divisor
 * @return float|string
 */
function average($dividend, $divisor, $scale = 2)
{
    if (empty($dividend) || empty($divisor)) {
        return number_format(0, 2, ",", ".");
    }
    return bcdiv($dividend, $divisor, $scale);
}

/**
 * @param float|int $dividend
 * @param float|int $divisor
 * @return float|string
 */
function percent($dividend, $divisor, $scale = 10)
{
    if (empty($dividend) || empty($divisor)) {
        return number_format(0, 2, ",", ".");
    }
    $value = bcdiv($dividend, $divisor, $scale);
    return bcmul($value, 100, 2);
}

/**
 * Recupera a data correta de acordo com o que a pessoa selecionou no DateRangePicker
 * 
 * @param string $string
 * @return array|null
 */
function date_period($string)
{
    $string = strtolower(clear_accent($string));
    $today = date("Y-m-d");

    if ($string == "today" || $string == "hoje" || $string == "hoy") {
        $start = $today;
        $end = $today;
        $period = "today";
    } elseif ($string == "yesterday" || $string == "ontem" || $string == "ayer") {
        $start = date("Y-m-d", strtotime("-1Days"));
        $end = date("Y-m-d", strtotime("-1Days"));
        $period = "yesterday";
    } elseif ($string == "last 7 days" || $string == "ultimos 7 dias" || $string == "los ultimos 7 días") {
        $start = date("Y-m-d", strtotime("-6Days"));
        $end = $today;
        $period = "last_7_days";
    } elseif ($string == "last 15 days" || $string == "ultimos 15 dias" || $string == "los ultimos 15 días") {
        $start = date("Y-m-d", strtotime("-14Days"));
        $end = $today;
        $period = "last_15_days";
    } elseif ($string == "last 30 days" || $string == "ultimos 30 dias" || $string == "los ultimos 30 días") {
        $start = date("Y-m-d", strtotime("-29Days"));
        $end = $today;
        $period = "last_30_days";
    } elseif ($string == "this month" || $string == "esse mes" || $string == "ese mes") {
        $start = date("Y-m-01");
        $end = $today;
        $period = "this_month";
    } elseif ($string == "last month" || $string == "ultimo mes" || $string == "el mes pasado") {
        $start = date("Y-m-01", strtotime("-1Month"));
        $end = date("Y-m-t", strtotime("-1Month"));
        $period = "last_month";
    } else {
        return null;
    }

    return array(
        "start" => $start,
        "end" => $end,
        "period" => label($period)
    );
}

/**
 * @param string $dateStart
 * @param string $dateEnd
 * @return string|null
 */
function period($dateStart, $dateEnd)
{
    $today = date("Y-m-d");

    if ($dateStart == $today && $dateEnd == $today) {
        return "today";
    }

    return null;
}

/**
 * @param string $string
 * @return string
 */
function clear_accent($string)
{
    $mask = array(
        'á' => 'a',
        'à' => 'a',
        'ã' => 'a',
        'â' => 'a',
        'é' => 'e',
        'ê' => 'e',
        'í' => 'i',
        'ó' => 'o',
        'ô' => 'o',
        'õ' => 'o',
        'ú' => 'u',
        'ü' => 'u',
        'ç' => 'c',
        'Á' => 'A',
        'À' => 'A',
        'Ã' => 'A',
        'Â' => 'A',
        'É' => 'E',
        'Ê' => 'E',
        'Í' => 'I',
        'Ó' => 'O',
        'Ô' => 'O',
        'Õ' => 'O',
        'Ú' => 'U',
        'Ü' => 'U',
        'Ç' => 'C'
    );

    return strtr($string, $mask);
}

/**
 * ##### Filters ##########################
 * ########################################
 */

/**
 * filter_fields_type: Principais campos de consulta para os relatórios. O que não estiver
 * nessa lista será tratado como FILTER_SANITIZE_STRIPPED pelos helpers de filtro
 * @return array
 */
function filter_fields_type()
{
    $filterFields = array(
        "route" => FILTER_SANITIZE_STRIPPED,
        "product" => FILTER_SANITIZE_NUMBER_INT,
        "id" => FILTER_SANITIZE_NUMBER_INT,
        "product_id" => FILTER_SANITIZE_NUMBER_INT,
        "country" => FILTER_SANITIZE_STRIPPED,
        "device" => FILTER_SANITIZE_NUMBER_INT,
        "redirect" => FILTER_SANITIZE_ENCODED,
        "status" => FILTER_SANITIZE_NUMBER_INT,
        "upsell" => FILTER_SANITIZE_NUMBER_INT,
        "paymentMethod" => FILTER_SANITIZE_NUMBER_INT,
        "company_id" => FILTER_SANITIZE_NUMBER_INT,
        "affiliate" => FILTER_SANITIZE_NUMBER_INT,
        "level" => FILTER_SANITIZE_NUMBER_INT,
        "group_id" => FILTER_SANITIZE_NUMBER_INT,
        "report_id" => FILTER_SANITIZE_NUMBER_INT,
        "offer" => FILTER_SANITIZE_NUMBER_INT,
        "offerId" => FILTER_SANITIZE_NUMBER_INT,
        "id_offer" => FILTER_SANITIZE_NUMBER_INT,
        "id_method" => FILTER_SANITIZE_NUMBER_INT,
        "zipcode" => FILTER_SANITIZE_NUMBER_INT,
        "phone" => FILTER_SANITIZE_NUMBER_INT,
        "account_number" => FILTER_SANITIZE_NUMBER_INT,
        "paypal_email" => FILTER_VALIDATE_EMAIL,
        "user_email" => FILTER_VALIDATE_EMAIL,
        "timezone_id" => FILTER_SANITIZE_NUMBER_INT,
        "timezone" => FILTER_SANITIZE_NUMBER_INT,
        "postbackId" => FILTER_SANITIZE_NUMBER_INT,
        "id_postback" => FILTER_SANITIZE_NUMBER_INT,
        "postback_type" => FILTER_SANITIZE_NUMBER_INT,
    );
    return $filterFields;
}

/**
 * filter_array: Filtrar campos de array ou globais GET e POST
 * @param array $array
 * @param string|null $type
 * @return array
 */
function filter_array($array, $type = null)
{
    $filterFields = filter_fields_type();

    $filterArr = array();
    $newArray = array();

    foreach ($array as $key => $value) {
        if (is_array($value)) {
            foreach ($value as $item) {
                if (!is_numeric($key) && in_array($key, array_keys($filterFields))) {
                    $filterArr[$key] = $filterFields[$key];
                } else {
                    $filterArr[$key] = FILTER_SANITIZE_STRIPPED;
                }
                if (empty($type)) {
                    $newArray[$key][] = filter_var($item, $filterArr[$key]);
                } else {
                    $newArray[$key][] = filter_variable($item, $type);
                }
            }
        } else {
            if (!is_numeric($key) && in_array($key, array_keys($filterFields))) {
                $filterArr[$key] = $filterFields[$key];
            } else {
                $filterArr[$key] = FILTER_SANITIZE_STRIPPED;
            }
            if (empty($type)) {
                $newArray[$key] = filter_var($value, $filterArr[$key]);
            } else {
                $newArray[$key] = filter_variable($value, $type);
            }
        }
    }
    return $newArray;
}

/**
 * @param string $string
 * @param string $type = int, string, chars, etc
 * @return string
 */
function filter_variable($string, $type = null)
{
    if (!empty($type)) {
        $type = mb_convert_case($type, MB_CASE_LOWER);

        if ($type == 'default') {
            return filter_var($string, FILTER_DEFAULT);
        } elseif ($type == 'int') {
            return filter_var($string, FILTER_SANITIZE_NUMBER_INT);
        } elseif ($type == 'string') {
            return filter_var($string, FILTER_SANITIZE_STRING);
        } elseif ($type == 'chars') {
            return filter_var($string, FILTER_SANITIZE_SPECIAL_CHARS);
        } elseif ($type == 'mail' || $type == 'email') {
            return filter_var($string, FILTER_VALIDATE_EMAIL);
        } elseif ($type == "url") {
            return filter_var($string, FILTER_SANITIZE_URL);
        } else {
            return filter_var($string, FILTER_SANITIZE_STRIPPED);
        }
    }
    return filter_var($string, FILTER_SANITIZE_STRIPPED);
}


/**
 * ##### Instances ########################
 * ########################################
 */


/**
 * @return \Source\Core\Session
 */
function session()
{
    return new \Source\Core\Session();
}


/**
 * ##### Getting data from Database #######
 * ########################################
 */

function affiliates()
{
    $user = session()->user;
    $obj = new \Source\Models\Affiliate();

    $terms = null;
    if (!empty($user->restriction)) {
        $restrict = unserialize($user->restriction);

        if (in_array("affiliate_address", array_keys($restrict))) {
            $terms = $restrict["affiliate_address"];
        }
    }
    return $obj->find("status = 2", "", "id_aff, company")
        ->leftJoin("affiliate_address", "id_aff", $terms, "", "country")
        ->order("company")
        ->fetch(true);
}

/**
 * @return \Source\Models\Timezone
 */
function timezone()
{
    $objTimezone = new \Source\Models\Timezone();
    return $objTimezone->find()->order("timezone, timezone_name")->fetch(true);
}

/**
 * @param string|null $label
 * @param bool $format
 * @return null|string|object
 */
function label($key = null, $format = false)
{   
    return user_translate($key, "label", $format);
}

/**
 * @param string|null $label
 * @param bool $format
 * @return null|string|object
 */
function msg($key = null, $format = false)
{
        return user_translate($key, "message", $format);
}

/**
 * @param string|null $label
 * @param string $type
 * @param bool $format
 * @return null|string|object
 */
function user_translate($label = null, $type, $format)
{
    
    $user = session()->user;

    if ($user) {
        $language = $user->language;
    } elseif (!empty($_COOKIE["user_language"])) {
        $language = $_COOKIE["user_language"];
    } else {
        $language = "pt";
    }
    // $language = "pt";

    $file = CONF_TRANSLATE_PATH . "/" . $type . "/" . $language . ".json";

    $file = @file_get_contents($file);
    $file = json_decode($file);

    if (!empty($label)) {
        if (is_array($label)) {
            $arrLab = array();
            foreach ($label as $lab) {
                $lab = strtolower($lab);
                if (!empty($file->$lab)) {
                    $arrLab[] = $file->$lab;
                }
            }
            if (!empty($format)) {
                return ucfirst(strtolower(implode(" ", $arrLab)));
            }
            return implode(" ", $arrLab);
        }

        $label = strtolower($label);
        if (!empty($file->$label)) {
            if (!empty($format)) {
                return ucfirst(strtolower($file->$label));
            }
            return $file->$label;
        }
        return null;
    }

    return (object) $file;
}

// /**
//  * Insere os parametros no link do relatorio
//  *
//  * @param string $report
//  * @return string|null
//  */
// function default_report($report)
// {
//     $obj = new \Source\Models\SavedReport();
//     $default = $obj->default_report($report);

//     if (!$default) {
//         return null;
//     }

//     return "?" . $default->param;
// }

/**
 * Monta os dados do grafico para a Home e PerformanceReport
 *
 * @param object $data
 * @param string $dateStart
 * @return object
 */
function chart($data, $dateStart)
{
    $chartData = array();
    $chartSeries = "";

    if (empty($data)) {
        $date = $dateStart;
        $chartSeriesItem = array();
        $chartSeries .= "{name: '', data: [";

        while (strtotime($date) <= strtotime(date("Y-m-d"))) {
            $chartSeriesItem[] = 0;
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }
        $chartSeries .= implode(",", $chartSeriesItem) . "]}";
    } else {
        if (is_array($data)) {
            foreach ($data as $item) {
                $chartData[$item->offer][$item->date]["conversions"] = $item->conversions;
                $chartData[$item->offer][$item->date]["payout"] = $item->payout;
            }
            foreach ($chartData as $key => $value) {
                $date = $dateStart;
                $chartSeriesItem = array();
                $chartSeries .= "{name: '{$key}', data: [";
                while (strtotime($date) <= strtotime(date("Y-m-d"))) {
                    if (!empty($value[$date])) {
                        $chartSeriesItem[] = $value[$date]["conversions"];
                    } else {
                        $chartSeriesItem[] = 0;
                    }
                    $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
                }
                $chartSeries .= implode(",", $chartSeriesItem) . "]}";
            }
        } else {
            $date = $dateStart;
            $chartSeriesItem = array();
            $chartSeries .= "{name: '{$data->offer}', data: [";
            while (strtotime($date) <= strtotime(date("Y-m-d"))) {
                if ($data->date == $date) {
                    $chartSeriesItem[] = $data->conversions;
                } else {
                    $chartSeriesItem[] = 0;
                }
                $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
            }
            $chartSeries .= implode(",", $chartSeriesItem) . "]}";
        }
    }
    $chartSeries = str_replace("}{", "},{", $chartSeries);

    $chartCategory = array();
    $date = $dateStart;
    while (strtotime($date) <= strtotime(date("Y-m-d"))) {
        $chartCategory[] = "'" . date_fmt($date) . "'";
        $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
    }
    $chartCategory = implode(", ", $chartCategory);

    $chart = array(
        "series" => $chartSeries,
        "category" => $chartCategory
    );

    return (object) $chart;
}



function pagination_body($pagina_atual, $ultima_pag, $url_atual)
{
    // HTML DA PAGINAÇÃO
    try {

        $html = '<nav aria-label="Navegação de página" style="margin: 30px;">
              <ul class="pagination pagination-md justify-content-center" style="font-size: large;">';

        $url_formatada =  str_replace('&pag=' . $pagina_atual, '', $url_atual);

        if ($pagina_atual > 1) {
            $html .= ' 	<li class="page-item">
                  <a class="page-link" href="' . $url_formatada . '&pag=' . ($pagina_atual - 1) . '" tabindex="-1" style="height: 100%;" ><i class="fas fa-angle-double-left"></i></a>
                </li>';
        }

        if ($pagina_atual < $ultima_pag - 3) {
            $fim_paginacao = $pagina_atual + 3;
            $mostra_ultima_pg = '	<li class="page-item" ><a class="page-link" style="cursor: auto;">...</a></li>
                            <li class="page-item"><a class="page-link"  href="' . $url_formatada . '&pag=' . $ultima_pag . '">' . $ultima_pag . '</a></li>';
        } else {
            $mostra_ultima_pg = '';
            $fim_paginacao = $ultima_pag;
        }

        if ($pagina_atual > 4) {
            $inicio_paginacao = $pagina_atual - 3;
            $mostra_primeira_pg = '	<li class="page-item"><a class="page-link"  href="' . $url_formatada . '&pag=1">1</a></li>
                                <li class="page-item" ><a class="page-link" style="cursor: auto;">...</a></li>';
        } else {
            $inicio_paginacao = 1;
            $mostra_primeira_pg = '';
        }

        $html .= $mostra_primeira_pg;

        for ($i = $inicio_paginacao; $i <= $fim_paginacao; $i++) {
            $html .= '<li class="page-item ' . ($pagina_atual == $i ? "active" : "") . '"><a class="page-link"  href="' . $url_formatada . '&pag=' . $i . '">' . $i . '</a></li>';
        }

        $html .= $mostra_ultima_pg;

        if ($pagina_atual < $ultima_pag) {
            $html .= ' 	<li class="page-item">
                  <a class="page-link" href="' . $url_formatada . '&pag=' . ($pagina_atual + 1) . '" style="height: 100%;"><i class="fas fa-angle-double-right"></i></a>
                </li>';
        }
        $html .= '</ul>
                </nav>';
        return $html;
    } catch (PDOException $e) {
        return 'ERROR PAGINAÇÃO: ' . $e->getMessage();
    }
}

function pagination_start()
{
    $pagination = new \Source\Support\Pagination();
    return $pagination->paginationStart() + 1;
}


function show_pagination($totalRows)
{
    $pagination = new \Source\Support\Pagination();
    return (!empty($totalRows) ? $pagination->showPagination($totalRows) : '');
}

function current_url()
{
    $arrUrl = (!empty($_GET["route"]) ? route($_GET["route"]) : '');
    $url = '';
    foreach ($arrUrl as $key => $value) {
        $url .= ($key > 0 ? '/' : '') . $value;
    }
    return $url;
}

function query_string()
{
    $server = (!empty($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '');
    if (stristr($server, '?')) {
        $queryString = explode('?', $server);
        $queryString = (!empty($queryString[1]) ? $queryString[1]  : '');
    }
    return (!empty($queryString) ? $queryString : '');
}

<?php

/**
 * Main Setup
 */
define("CONF_URL_BASE", "http://zap.monitorar.info");
define("CONF_URL_TEST", "http://localhost/monitorar_zap");
define("CONF_SITE_NAME", "Monitorar Whatsapp");
define("CONF_MAIL", "nao-responder@tisoftware.net.br");
define("CONF_ADM_EMAIL", "nao-responder@tisoftware.net.br");
define("CONF_TRACKING_LINK", "https://www.ourtrklink.com/clk/");

/**  
 * View Setup
 */
define("CONF_VIEW_PATH", __DIR__ . "/../../themes");
define("CONF_VIEW_EXT", "php");
define("CONF_VIEW_THEME", "monitorar");

/** 
 * Pass Setup
 */
define("CONF_PASS_MIN_LEN", 6);
define("CONF_PASS_MAX_LEN", 40);
define("CONF_PASS_ALGO", "");
define("CONF_PASS_OPTION", serialize(array("cost" => 10)));

/**
 * Database Setup
 */
define("CONF_DB_HOST", "104.238.136.203");
define("CONF_DB_USER", "tisoftware");
define("CONF_DB_PASS", '$Edivan2018');
define("CONF_DB_NAME", "monitor_zap");
define("CONF_TIMEZONE", "-03:00");

// // localhost
// define("CONF_DB_HOST", "localhost");
// define("CONF_DB_USER", "root");
// define("CONF_DB_PASS", "");
// define("CONF_DB_NAME", "tracking");

/**
 * Database System Setup
 */
define("CONF_DB_HOST_SYSTEM", "gstv-rdbms.cnqdpix5zbfx.sa-east-1.rds.amazonaws.com");
define("CONF_DB_USER_SYSTEM", "tisoftwa_system");
define("CONF_DB_PASS_SYSTEM", "GoodShop611");
define("CONF_DB_NAME_SYSTEM", "tisoftwa_system");


/**
 * sessões
 */
define("CONF_SESSION_AUTH", strtoupper(strrev(CONF_DB_USER . 'AUTH' . CONF_DB_NAME)));
define("CONF_SESSION_USER", strtoupper(strrev(CONF_DB_USER . 'USER' . CONF_DB_NAME)));
define("CONF_COOKIE_AUTH", strtoupper(strrev(CONF_DB_USER . 'AUTH' . CONF_DB_NAME)));


/**
 * Storage Setup
 */
define("CONF_TRANSLATE_PATH", __DIR__ . "/../../storage/translate");
// define("CONF_SESSION_PATH", __DIR__ . "/../../storage/sessions");

/**
 * Mail Sender Setup
 * Port: 25, 587 (for unencrypted/TLS connections)
 * Port: 465     (for SSL connections)
 */
define("CONF_MAIL_HOST", "mail.tisoftware.net.br");
define("CONF_MAIL_PORT", "587");
define("CONF_MAIL_USER", CONF_MAIL);
define("CONF_MAIL_PASS", "#GoodShop611");
define("CONF_MAIL_SENDER", serialize(array("name" => CONF_SITE_NAME, "address" => CONF_MAIL)));
define("CONF_MAIL_OPTION_LANG", "br");
define("CONF_MAIL_OPTION_HTML", true);
define("CONF_MAIL_OPTION_AUTH", true);
define("CONF_MAIL_OPTION_SECURE", "tls");
define("CONF_MAIL_OPTION_CHARSET", "utf-8");

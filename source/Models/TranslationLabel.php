<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * TranslationLabel Model
 * @package Source\Models
 */
class TranslationLabel extends Model
{
    /**
     * TranslationLabel Constructor
     */
    public function __construct()
    {
        // $entity, $fieldId, $protected, $required, $class
        parent::__construct(
            "translation_labels",
            "id",
            array("id"),
            array("label", "pt"),
            __CLASS__
        );
    }

    /**
     * Busca no banco de dados as traduções para as labels e gera os arquivos
     * @return void
     */
    public function all()
    {
        $dir = CONF_TRANSLATE_PATH;
        if (!file_exists($dir) || !is_dir($dir)) {
            mkdir($dir, 0755);
        }
        $dir = CONF_TRANSLATE_PATH . "/label";
        if (!file_exists($dir) || !is_dir($dir)) {
            mkdir($dir, 0755);
        }

        $filePT = CONF_TRANSLATE_PATH . "/label/pt.json";
        $fileEN = CONF_TRANSLATE_PATH . "/label/en.json";
        $fileES = CONF_TRANSLATE_PATH . "/label/es.json";

        $fileOpenPT = @fopen($filePT, "w");
        $fileOpenEN = @fopen($fileEN, "w");
        $fileOpenES = @fopen($fileES, "w");

        $labels = $this->find("", "", "label, pt, en, es")->order("label")->fetch(true,"SET NAMES utf8");

        if ($labels) {
            foreach ($labels as $label) {
                // var_dump($label);
                // continue;
                
                $lab["pt"][$label->label] = $label->pt;
                $lab["en"][$label->label] = $label->en;
                $lab["es"][$label->label] = $label->es;
            }

            @fwrite($fileOpenPT, json_encode($lab["pt"]));
            @fwrite($fileOpenEN, json_encode($lab["en"]));
            @fwrite($fileOpenES, json_encode($lab["es"]));
        }
        @fclose($fileOpenPT);
        @fclose($fileOpenEN);
        @fclose($fileOpenES);
    }
}

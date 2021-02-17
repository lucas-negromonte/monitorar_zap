<?php

namespace Source\Models\Translation;

use Source\Core\Model;

/**
 * Label Model
 * @package Source\Models\Translation
 */
class Label extends Model
{
    /** @var string */
    protected $entity;

    /** @var string = Main Entity ID */
    protected $fieldId;

    /** @var array */
    protected $protected;

    /** @var array */
    protected $required;

    /** @var string */
    protected $class;

    /**
     * Label Constructor
     */
    public function __construct()
    {
        $this->entity = "translation_label";
        $this->fieldId = "id";
        $this->protected = array("id", "created_at", "updated_at");
        $this->required = array("label", "pt");
        $this->class = __CLASS__;

        parent::__construct($this->entity, $this->fieldId, $this->protected, $this->required, $this->class);
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

        $fileOpenPT = fopen($filePT, "w");
        $fileOpenEN = fopen($fileEN, "w");
        $fileOpenES = fopen($fileES, "w");

        $labels = $this->find("", "", "label, pt, en, es")->order("label")->fetch(true);

        if ($labels) {
            foreach ($labels as $label) {
                $lab["pt"][$label->label] = $label->pt;
                $lab["en"][$label->label] = $label->en;
                $lab["es"][$label->label] = $label->es;
            }

            fwrite($fileOpenPT, json_encode($lab["pt"]));
            fwrite($fileOpenEN, json_encode($lab["en"]));
            fwrite($fileOpenES, json_encode($lab["es"]));
        }
        fclose($fileOpenPT);
        fclose($fileOpenEN);
        fclose($fileOpenES);
    }
}

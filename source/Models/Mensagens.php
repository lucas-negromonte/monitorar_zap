<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Mensagens Model
 * @package Source\Models
 */
class Mensagens extends Model
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

    public function __construct()
    {
        $this->entity = "mensagens";
        $this->fieldId = "id";
        $this->protected = array("imei", "nome_contato", "mensagem","data");
        $this->required = array();
        $this->class = __CLASS__;

        parent::__construct($this->entity, $this->fieldId, $this->protected, $this->required, $this->class);
    }
}

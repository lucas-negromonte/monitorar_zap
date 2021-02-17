<?php

namespace Source\Models;

use Source\Core\ModelSystem;

/**
 * Software Model
 * @package Source\Models
 */
class Software extends ModelSystem
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
        $this->entity = "software";
        $this->fieldId = "id_item";
        $this->protected = array("id_item");
        $this->required = array('id_item', 'licenca', 'ativo', 'atualizar', 'tipo', 'client', 'uso', 'validade', 'instalado', 'imei', 'suporte');
        $this->class = __CLASS__;

        parent::__construct($this->entity, $this->fieldId, $this->protected, $this->required, $this->class);
    }
}

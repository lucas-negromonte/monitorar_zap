<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Controle Model
 * @package Source\Models
 */
class Controle extends Model
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
        $this->entity = "controle";
        $this->fieldId = "imei";
        $this->protected = array("imei");
        $this->required = array('imei');
        $this->class = __CLASS__;

        parent::__construct($this->entity, $this->fieldId, $this->protected, $this->required, $this->class);
    }
}

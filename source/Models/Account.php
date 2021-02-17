<?php

namespace Source\Models;

use Source\Core\Model;

/**
 * Account Model
 * @package Source\Models
 */
class Account extends Model
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
        $this->entity = "account";
        $this->fieldId = "id";
        $this->protected = array("id", "imei", "email");
        $this->required = array('imei','nome','telefone');
        $this->class = __CLASS__;

        parent::__construct($this->entity, $this->fieldId, $this->protected, $this->required, $this->class);
    }
}

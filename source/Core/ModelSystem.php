<?php

namespace Source\Core;

use Source\Models\ErrorLog;
use Source\Support\Message;
use Source\Support\Pagination;

/**
 * ModelSystem Core
 * @package Source\Core
 */
abstract class ModelSystem
{
    /** @var object|null */
    protected $data; 

    /** @var \PDOException */
    protected $fail;

    /** @var Message|null */ 
    protected $message;

    /** @var string */
    protected $query;

    /** @var string */
    protected $join;

    /** @var object */
    protected $objJoin;

    /** @var string */
    protected $terms;

    /** @var string */
    protected $params;

    /** @var string */
    protected $columns;

    /** @var string */
    protected $order;

    /** @var int */
    protected $limit;

     /** @var string */
    protected $pagination;

    /** @var int */
    protected $offset;

    /** @var string $entity database table */
    protected $entity;

    /** @var array $protected no update or create */
    protected $protected;

    /** @var array $entity database table */
    protected $required;

    /** @var string active Class for PHP 5.3 */
    protected $class;

    /** @var string */
    protected $fieldId;

    /** @var int */
    protected $lastId;

    /**
     * @param string $entity
     * @param string $fieldId
     * @param array $protected
     * @param array $required
     * @param string $class
     */
    public function __construct($entity, $fieldId, $protected, $required, $class)
    {
        $this->entity = $entity;
        $this->fieldId = $fieldId;
        $this->protected = array_merge($protected, array("id_cliente"));
        $this->required = $required;
        $this->class = $class;

        $this->message = new Message();
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        if (empty($this->data)) {
            $this->data = new \stdClass();
        }

        $this->data->$name = $value;
    }

    /**
     * @param $name
     * @return void
     */
    public function __get($name)
    {
        return empty($this->data->$name) ? null : $this->data->$name;
    }

    /**
     * @param $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->data->$name);
    }


    public function data()
    {
        return $this->data;
    }

    /**
     * @return \PDOException|null
     */
    public function fail()
    {
        return $this->fail;
    }

    /**
     * @return string|null
     */
    public function message()
    {
        return $this->message;
    }

    /**
     * Este método vai montar a query juntando o distinct, column, entity, terms e join
     * 
     * @return ModelSystem
     */
    private function setQuery()
    {
        $distinct = (!empty($this->distinct) ? "DISTINCT({$this->distinct})" : '');
        if (!empty($distinct) && !empty($this->columns)) {
            $distinct .= ", ";
        }

        $this->query = "
            SELECT {$distinct}{$this->columns} 
            FROM {$this->entity} 
            {$this->join} 
            {$this->terms}
        ";
        return $this;
    }

    /**
     * Cria um distinct na coluna escolhida
     *
     * @param string $column a receber o DISTINCT
     * @return ModelSystem
     */
    public function distinct($column)
    {
        $this->distinct = $column;
        $this->setQuery();
        return $this;
    }

    /**
     * Adiciona termos a query . Já coloca o nome da tabela automaticamente
     * 
     * @param string $terms ex.: nome = :nome AND idade = :idade
     * @param string $params ex.: nome={$nome}&idade={$idade}
     * @param string $entity Nome da tabela, se nulo vai pegar a do ModelSystem
     * @return ModelSystem
     */
    private function setTerms($terms, $params, $entity = null)
    {
        $entity = empty($entity) ? $this->entity : $entity;

        $arr = array(" AND ", " AND", "AND ", " OR ", " OR", "OR ");
        $arrOp =  array("{AND}", "{OR}");
        $arrOpReplace = array("AND", "OR");
        $arrReplace = array(
            " {AND} {$entity}.",
            " {AND} {$entity}.",
            " {AND} {$entity}.",
            " {OR} {$entity}.",
            " {OR} {$entity}.",
            " {OR} {$entity}."
        );

        $where = empty($this->terms) ? "WHERE" : "{$this->terms} AND";

        $this->terms = "{$where} {$entity}." . str_replace(
            $arrOp,
            $arrOpReplace,
            str_replace(
                $arr,
                $arrReplace,
                $terms
            )
        );

        parse_str($params, $params);
        $this->params = !empty($this->params) ? array_merge($this->params, $params) : $params;

        return $this;
    }

    /**
     * @param string $columns
     * @param string|null $entity
     * @return ModelSystem
     */
    private function setColumns($columns, $entity = null)
    {
        $entity = empty($entity) ? $this->entity : $entity;

        $currentColumns = !empty($this->columns) ? "{$this->columns}, " : "";

        $this->columns = "{$currentColumns}{$entity}." . str_replace(",", ", {$entity}.", $columns);
        $this->columns = str_replace(". ", ".", $this->columns);

        return $this;
    }

    /**
     * @param string|null $terms
     * @param string|null $params
     * @param string $columns
     * @return ModelSystem|mixed
     */
    public function find($terms = null, $params = null, $columns = "*")
    {          
        $this->setColumns($columns);       
        if (!empty($terms)) { 
            $this->setTerms($terms, $params);
        }
        $this->setQuery();
        return $this;
    }
 
    /**
     * @param int $id
     * @param string $columns
     * @return null|mixed|ModeModelSysteml
     */
    public function findById($id, $columns = "*")
    {
        $find = $this->find("{$this->fieldId} = :id", "id={$id}", $columns);
        return $find->fetch();
    }

    /**
     * @param string $entity tabela a ser relacionada
     * @param string $joinId id da tabela a ser relacionada que vai fazer parte do join
     * @param string|null $terms termos busca(where) para a tabela da relação
     * @param string|null $params 
     * @param string|null $columns colunas a ser buscadas desta tabela da relação
     * @param string|null $entityJoinId caso queira mudar o id da tabela base na relação, por padrão é a chave primária
     * @param string|null $entityJoin caso queira mudar a tabela base da relação
     * @return ModelSystem
     */
    public function join(
        $entity,
        $joinId,
        $terms = null,
        $params = null,
        $columns = null,
        $entityJoinId = null, 
        $entityJoin = null
    ) {
        $this->objJoin = new \stdClass();
        $this->objJoin->type = "INNER";
        $this->objJoin->entity = $entity;
        $this->objJoin->joinId = $joinId;
        $this->objJoin->terms = $terms;
        $this->objJoin->params = $params;
        $this->objJoin->columns = $columns;
        $this->objJoin->entityJoinId = $entityJoinId;
        $this->objJoin->entityJoin = $entityJoin;

        return $this->setJoin();
    }

    /**
     * @param string $entity
     * @param string $joinId
     * @param string|null $terms
     * @param string|null $params
     * @param string|null $columns
     * @param string|null $entityJoinId
     * @param string|null $entityJoin
     * @return ModelSystem
     */
    public function leftJoin(
        $entity,
        $joinId,
        $terms = null,
        $params = null,
        $columns = null,
        $entityJoinId = null,
        $entityJoin = null
    ) {
        $this->objJoin = new \stdClass();
        $this->objJoin->type = "LEFT";
        $this->objJoin->entity = $entity;
        $this->objJoin->joinId = $joinId;
        $this->objJoin->terms = $terms;
        $this->objJoin->params = $params;
        $this->objJoin->columns = $columns;
        $this->objJoin->entityJoinId = $entityJoinId;
        $this->objJoin->entityJoin = $entityJoin;

        return $this->setJoin();
    }

    /**
     * @param string $entity
     * @param string $joinId
     * @param string|null $terms
     * @param string|null $params
     * @param string|null $columns
     * @param string|null $entityJoinId
     * @param string|null $entityJoin
     * @return ModelSystem
     */
    public function rightJoin(
        $entity,
        $joinId,
        $terms = null,
        $params = null,
        $columns = "*",
        $entityJoinId = null,
        $entityJoin = null
    ) {
        $this->objJoin = new \stdClass();
        $this->objJoin->type = "RIGHT";
        $this->objJoin->entity = $entity;
        $this->objJoin->joinId = $joinId;
        $this->objJoin->terms = $terms;
        $this->objJoin->params = $params;
        $this->objJoin->columns = $columns;
        $this->objJoin->entityJoinId = $entityJoinId;
        $this->objJoin->entityJoin = $entityJoin;

        return $this->setJoin();
    }

    /**
     * @return mixed|Model
     */
    private function setJoin()
    {
        $entityJoin = empty($this->objJoin->entityJoin) ? $this->entity : $this->objJoin->entityJoin;
        $entityJoinId = empty($this->objJoin->entityJoinId) ? $this->protected[0] : $this->objJoin->entityJoinId;

        if (!empty($this->objJoin->terms)) {
            $this->setTerms($this->objJoin->terms, $this->objJoin->params, $this->objJoin->entity);
        }

        $columns = !empty($this->objJoin->columns) ? $this->objJoin->columns : "*";
        $this->setColumns($columns, $this->objJoin->entity);

        $join = "{$this->objJoin->type} JOIN {$this->objJoin->entity} 
            ON ({$entityJoin}.{$entityJoinId} = {$this->objJoin->entity}.{$this->objJoin->joinId})
        ";
        $this->join = !empty($this->join) ? $this->join . $join : $join;

        $this->setQuery();

        return $this;
    }

    /**
     * @param string $column
     * @return ModelSystem|null
     */
    public function group($columns)
    {
        $this->group = " GROUP BY {$columns}";
        return $this;
    }

    /**
     * @param string $columnOrder
     * @return ModelSystem
     */
    public function order($columnOrder, $desc = false)
    {
        $desc = empty($desc) ? "" : " DESC";
        $this->order = " ORDER BY {$columnOrder}{$desc}";
        return $this;
    }

    /**
     * @param integer $limit
     * @return ModelSystem
     */
    public function limit($limit)
    {
        $this->limit = " LIMIT {$limit}";
        return $this;
    }

    /**
     * @param integer $offset
     * @return ModelSystem
     */
    public function offset($offset)
    {
        $this->offset = " OFFSET {$offset}";
        return $this;
    }


    public function pagination($count=false)
    {
        $pagination = new Pagination();
        $this->pagination = $pagination->basePagination($count);
        return $this;
    }



    /**
     * @param boolean $all
     * @return null|array|mixed|Model
     */
    public function fetch($all = false)
    {
        try {

            $limit = (!empty($this->pagination) ? $this->pagination : $this->limit . $this->offset);
            $stmt = Connect::getInstanceSystem()
                ->prepare($this->query . $this->group . $this->order . $limit );

            //   var_dump('<pre>',$stmt);
            //   exit;
            $stmt->execute($this->params);

            if (!$stmt->rowCount()) {
                return null;
            }

            if ($all) {
                if ($stmt->rowCount() > 1) {
                    return $stmt->fetchAll(\PDO::FETCH_CLASS, $this->class);
                }
            }

            return $stmt->fetchObject($this->class);
            // return $stmt->fetchObject(static::class);
        } catch (\PDOException $e) {
            $this->fail = $e;
            $this->errorLog();
            return null;
        }
    }

    /**
     * @param string $key 
     * @return integer
     */
    public function count($key = "id")
    {
        $stmt = Connect::getInstanceSystem()->prepare($this->query);
        $stmt->execute($this->params);
        return $stmt->rowCount();
    }


    /**
     * @param string $select
     * @param string|null $params
     * @return null|\PDOStatement
     */
    protected function read($select, $params = null, $count = false)
    {
        try {
            $stmt = Connect::getInstanceSystem()->prepare($select);
            if ($params) {
                parse_str($params, $params);
                $params = (array)$params;
                foreach ($params as $key => $value) {
                    if ($key == 'limit' || $key == 'offset') {
                        $stmt->bindValue(":{$key}", $value, \PDO::PARAM_INT);
                    } else {
                        $stmt->bindValue(":{$key}", $value, \PDO::PARAM_STR);
                    }
                }
            }

            // echo "<pre>";
            // var_dump($stmt);
            // exit;

            // ini_set('memory_limit', '-1');
            $stmt->execute();
            
            if($count){
                return  $stmt->rowCount();
             }

            if (!$stmt->rowCount()) {
                return null;
            }
           
            if ($stmt->rowCount() > 1) {
                return $stmt->fetchAll(\PDO::FETCH_CLASS, $this->class);
            }
            return $stmt->fetchObject($this->class);
        } catch (\PDOException $e) {
            
            $this->fail = $e;
            $this->errorLog();
            return null;
        }
    }

    /**
     * @param array $data
     * @return int|null
     */
    protected function create($data)
    {
        try {
            $columns = implode(", ", array_keys($data));
            $values = ":" . implode(", :", array_keys($data));
            $stmt = Connect::getInstanceSystem()->prepare("INSERT INTO {$this->entity} ({$columns}) VALUES ({$values})");
            // var_dump($stmt);
            // exit;
            $stmt->execute($this->filter($data));
            $this->lastId = Connect::getInstanceSystem()->lastInsertId();
            return $this->lastId;
        } catch (\PDOException $e) {
            $this->fail = $e;
            $this->errorLog();
            return null;
        }
    }

    /**
     * @param array $data
     * @param string $terms
     * @param string $params
     * @return int|null
     */
    protected function update($data, $terms, $params)
    {
        try {
            $dataSet = array();
            foreach ($data as $bind => $value) {
                $dataSet[] = "{$bind} = :{$bind}";
            }
            $dataSet = implode(", ", $dataSet);
            parse_str($params, $params);

            $stmt = Connect::getInstanceSystem()->prepare("UPDATE {$this->entity} SET {$dataSet} WHERE {$terms}");
            $stmt->execute($this->filter(array_merge($data, $params)));
            return ($stmt->rowCount() ? $stmt->rowCount() : 1);
        } catch (\PDOException $e) {
            $this->fail = $e;
            $this->errorLog();
            return null;
        }
    }

    /**
     * Verifica dados antes de inserir. Método criado para validar a inserção de dados em mais de uma
     * tabela em uma única requisição. Criado para não precisar usar apenas beginTransaction()
     *
     * @return bool
     */
    public function beforeSave()
    {
        if (!$this->required()) {
            $this->message->warning(msg("required_all"));
            return false;
        }
        return true;
    }

    /**
     * @return bool
     */
    public function save()
    {
        if (!$this->required()) {
            $this->message->warning(msg("required_all"));
            return false;
        }

        // Verificando se existe dados no banco
        $fieldId = $this->fieldId;
        $hasData = $this->findById($this->$fieldId, $fieldId);

        // Update
        if ($hasData) {
            $id = $this->$fieldId;
            $this->update($this->safe(), "{$this->fieldId} = :id", "id={$id}");
            if ($this->fail()) {
                $this->message->error(msg("update_fail"));
                return false;
            }
        }

        
        
        // Create
        if (!$hasData) {
            $id = $this->create($this->safe());
            if ($this->fail()) {
                $this->message->error(msg("insert_fail"));
                return false;
            }
        }

        $data = $this->findById($id);
        if ($data) {
            $this->data = $data->data();
        }
        return true;
    }

    /**
     * @return int
     */
    public function lastId()
    {
        return $this->lastId;
    }

    /**
     * @param string $terms
     * @param null|string $params
     * @return bool
     */
    public function delete($terms, $params)
    {
        try {
            $stmt = Connect::getInstanceSystem()->prepare("DELETE FROM {$this->entity} WHERE {$terms}");
            if ($params) {
                parse_str($params, $params);
                $stmt->execute($params);
                return true;
            }

            $stmt->execute();
            return true;
        } catch (\PDOException $e) {
            $this->fail = $e;
            $this->errorLog();
            return false;
        }
    }

    /**
     * Remove um objeto de modelo ativo
     * @return bool
     */
    public function destroy()
    {
        $fieldId = $this->fieldId;
        if (empty($this->$fieldId)) {
            return false;
        }

        $destroy = $this->delete("{$fieldId} = :id", "id={$this->$fieldId}");
        return $destroy;
    }

    /**
     * Para a execução e mostra a query
     *
     * @return ModelSystem
     */
    public function debug($consoleLog = false)
    {
        echo $this->query;
        exit;
    }

    /**
     * @return array|null
     */
    protected function safe()
    {
        $safe = (array) $this->data;
        foreach ($this->protected as $unset) {
            unset($safe[$unset]);
        }
        return $safe;
    }

    /**
     * @param array $data
     * @return array|null
     */
    private function filter($data)
    {
        $filter = array();
        foreach ($data as $key => $value) {
            $filter[$key] = (is_null($value) ? null : filter_variable($value, 'default'));
        }
        return $filter;
    }

    /**
     * @return boolean
     */
    protected function required()
    {
        $data = (array) $this->data();
        foreach ($this->required as $field) {
            if (empty($data[$field])) {
                return false;
            }
            return true;
        }
    }

    /**
     * @param string $code
     * @param string $file
     * @param int $line
     * @param string $description
     * @return void
     */
    protected function errorLog()
    {
        $error = new ErrorLog();
        $error->saveErrorLog($this->fail);
    }
}

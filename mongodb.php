<?php
class mongodb{

    private $_ip = '127.0.0.1';
    private $_port = '27017';
    protected $db = '';

    protected $manager = '';
    protected $bulk = '';
    protected $writeConcern = '';

    public function __construct($db){
        $this->db = $db;
        $this->manager = new MongoDB\Driver\Manager("mongodb://{$this->_ip}:{$this->_port}"); 
        $this->bulk = new MongoDB\Driver\BulkWrite;
        $this->writeConcern = new MongoDB\Driver\WriteConcern(1, 1000); // 写确认
    }

    /**
     * [insert 插入数据]
     * @param  [type] $collection [集合]
     * @param  [type] $data       [数据]
     * @return [type]             [description]
     * $mongodb->insert('runoob', ['sss' => 'sssssdd']);
     */
    public function insert($collection, $data) {
        try{
            $document = ['_id' => new MongoDB\BSON\ObjectID, 'data' => $data];
            $_id= $this->bulk->insert($document);
            $result = $this->manager->executeBulkWrite("{$this->db}.{$collection}", $this->bulk, $this->writeConcern);

            return true;
        }catch(Exception $e) {
            return false;
        }
    }

    /**
     * [query 查询数据]
     * @param  [type] $collection [集合]
     * @param  array  $filter     [过滤条件]
     * @param  array  $options    [查询选项]
     * @return [type]             [description]
     * $mongodb->query('runoob');
     */
    public function query($collection, $filter  = [], $options = []) {
        try{
            // 查询数据
            $query = new MongoDB\Driver\Query($filter, $options);
            $cursor = $this->manager->executeQuery("{$this->db}.{$collection}", $query);
            $data = [];
            foreach ($cursor as $document) {
                $data[] = $document;
            }

            return $data;    
        }catch(Exception $e) {
            return false;
        }
    }

    /**
     * [update 更新数据]
     * @param  [type] $collection [集合]
     * @param  [type] $filter     [过滤条件]
     * @param  [type] $data       [更新数据]
     * @param  [type] $option     [更新选项]
     * @return [type]             [description]
     * $mongodb->update('runoob', ['name' => '菜鸟教程'], ['name' => '我是新菜鸟教程']);
     */
    public function update($collection, $filter, $data, $option = ['multi' => true, 'upsert' => false]) {
        try{
            $this->bulk->update($filter, ['$set' => $data], $option);
            $result = $this->manager->executeBulkWrite("{$this->db}.{$collection}", $this->bulk, $this->writeConcern);

            return true;
        }catch(Exception $e) {
            return false;
        }
    }

    /**
     * [delete 删除数据]
     * @param  [type] $collection [集合]
     * @param  [type] $filter     [过滤条件]
     * @return [type]             [description]
     * $mongodb->delete('runoob', ['name' => '我是新菜鸟教程']);
     */
    public function delete($collection, $filter) {
        try{
            $this->bulk->delete($filter); 
            $result = $this->manager->executeBulkWrite("{$this->db}.{$collection}", $this->bulk, $this->writeConcern); 
            
            return true;
        }catch(Exception $e) {
            return false;
        }
    }
}
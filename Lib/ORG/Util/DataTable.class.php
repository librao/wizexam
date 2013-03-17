<?php
class DataTable {   
    private $__model;
    private $__fields;
    private $__init_map;
    private $__gbk_to_big;
    
    /**
     * DataTable::__construct()
     * 构造函数
     * @return void
     */
    public function __construct($model, $fields = array(), $map = array(), $gbk_to_big = true) {
        $this->__model = D($model);
        $this->__fields = $fields;
        $this->__init_map = $map;
        $this->__gbk_to_big = $gbk_to_big;
    }
    
    /**
     * DataTable::jsonOutput()
     * 输出符合DataTable的JSON语句
     * 
     * @param mixed $iTotalRecords 总数据条数
     * @param mixed $aaData 输出的数据数组
     * @return String
     */
    public function jsonOutput($iTotalRecords, $aaData = array()) {
        $output = array(
            'sEcho' => intval($_GET['sEcho']),
            'iTotalRecords' => $iTotalRecords,
            // Filter后的数据条数
            'iTotalDisplayRecords' => $this->__getTotalDisplayRecordsCount(),
            'aaData' => $aaData
        );
        return json_encode($output);
    }
    
    
    /**
     * DataTable::select()
     * 根据条件检索数据
     * @return
     */
    public function select() {
        return $this->__model->field(implode(',', $this->__fields))->where($this->__where())->order($this->__order())->limit($this->__limit())->select();
    }
    
    /**
     * DataTable::__getTotalRecordsCount()
     * 获得记录总数(Filter后)
     * @return Integer
     */
    private function __getTotalDisplayRecordsCount() {
        return (int)$this->__model->where($this->__where())->count();
    }
    
    /**
     * DataTable::__limit()
     * 获取分页语句
     * @return
     */
    private function __limit() {
        $sLimit = '';
        if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1'){
            $sLimit = mysql_real_escape_string($_GET['iDisplayStart']).','.mysql_real_escape_string($_GET['iDisplayLength']);
        }
        return $sLimit;
    }
    
    /**
     * DataTable::__order()
     * 获得排序条件
     * @return
     */
    private function __order() {
        $sOrder = '';
        if(isset($_GET['iSortCol_0'])){
            for($i = 0; $i < intval($_GET['iSortingCols']); $i++){
                if($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == 'true'){
                    $sOrder .= $this->__fields[intval($_GET['iSortCol_'.$i])].' '.mysql_real_escape_string($_GET['sSortDir_'.$i]).', ';
                }
            }
            
            $sOrder = substr_replace($sOrder, '', -2);
        }
        return $sOrder;
    }
    
    /**
     * DataTable::__where()
     * 获得Where条件
     * @return
     */
    private function __where() {
        $map = $this->__init_map;
        $where = array();
        if(isset($_GET['sSearch']) && $_GET['sSearch'] != ''){
            for($i = 0; $i < count($this->__fields); $i++){
                if($_GET['bSearchable_'.$i] == 'true'){
                    // 简体自动替换为繁体
                    // if($this->__gbk_to_big) $_GET['sSearch'] = gbkTobig5($_GET['sSearch']);
                    $where[$this->__fields[$i]] = array('like', '%'.mysql_real_escape_string($_GET['sSearch']).'%');
                }
            }
        }
        if(count($where) > 0){ // 复合查询,将初始查询条件和DataTable查询条件用AND组合,其中DataTable查询条件组合为OR
            $where['_logic'] = 'or';
            $map['_complex'] = $where;
        }
        return $map;
    }
}
?>
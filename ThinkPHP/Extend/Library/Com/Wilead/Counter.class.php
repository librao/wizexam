<?php
/**
 * 访问统计类
 * 
 * @author LinJue
 *
 */
class Counter{
	private $__uid = null;
	private $__model = null;
	
	/**
	 * 构造函数
	 * 
	 * @param string $model_name 模型名称
	 */
	public function __construct($uid){
		$this->__uid = intval($uid);
		$this->__model = D('UserCounter');
	}
	
	/**
	 * 记录访问数
	 */
	public function recordVisits(){
		$ip = get_client_ip();
		
		$vid = intval(session('auth'));
		$result = $this->__model->where("ip='%s' AND uid=%d AND vid=%d", $ip, $this->__uid, $vid)->find();
		if($result != NULL){
			if(!cookie('visits')){
				$this->__model->where("ip='%s' AND uid=%d AND vid=%d", $ip, $this->__uid, $vid)->setInc('counts');
			}
		}else{
			$data['ip'] = $ip;
			$data['counts'] = 1;
			$data['v_date'] = time();
			$data['uid'] = $this->__uid;
			$data['vid'] = $vid;
			$this->__model->add($data);
			cookie('visits', $ip, 3600 * 24);
		}
	}
	
	/**
	 * 获得总访问量
	 */
	public function getTotalVisits(){
		return $this->_getVisits();
	}
	
	/**
	 * 获得月访问量
	 */
	public function getMonthVisits(){
		$month_start = strtotime(date('Y').'-'.date('m').'-1');
		$month_end = strtotime(date('Y').'-'.date('m').'-'.date('t'));
		$where['v_date'] = array('between', $month_start.','.$month_end);
		return $this->_getVisits($where);
	}
	
	/**
	 * 获得周访问量
	 */
	public function getWeekVisits(){
		$week_timestamp = get_week_between();
		$where['v_date'] = array('between', $week_timestamp[0].','.$week_timestamp[1]);
		return $this->_getVisits($where);
	}
	
	/**
	 * 获得日访问量
	 */
	public function getDayVisits(){
		$day_start = strtotime(date('Y-m-d', time()));
		$where['v_date'] = array('between', $day_start.','.($day_start + 86400));
		return $this->_getVisits($where);
	}
	
	/**
	 * 获得昨日访问量
	 */
	public function getYesterdayVisits(){
		$day_start = strtotime(date('Y-m-d', time())) - 86400;
		$day_end = strtotime(date('Y-m-d', time())) - 1;
		$where['v_date'] = array('between', $day_start.','.$day_end);
		return $this->_getVisits($where);
	}
	
	/**
	 * 获取访问量公共方法
	 * 
	 * @param array $condition 检索条件
	 */
	protected function _getVisits($condition = array()){
		$where['uid'] = array('eq', $this->__uid);
		if(count($condition) == 0){
			return intval($this->__model->where($where)->sum('counts'));
		}else{
			$where = array_merge($where, $condition);
			return intval($this->__model->where($where)->sum('counts'));
		}
	}
}
?>
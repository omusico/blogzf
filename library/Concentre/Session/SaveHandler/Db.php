<?php

class Concentre_Session_SaveHandler_Db implements Zend_Session_SaveHandler_Interface {

	private $_session;
	private $_maxLifeTime;
	private $_maxTimeAccess;
	private $_db;
	private $_table = 'sessions';
	  
	public function __construct($database, $maxLifeTime = null) {
		$this->_db = &$database;
		$this->_maxAccessTime = time();
		$this->_maxLifeTime = $maxLifeTime==null?get_cfg_var('session.gc_maxlifetime'):$maxLifeTime;
	}
 
	public function open($save_path, $name)
	{
		return true;
	}
	  
	public function close()
	{
		$this->gc($this->_maxLifeTime);
		return true;
	}
	  
	public function read($id)
	{
		$allData = $this->_db->fetchOne("SELECT `data` FROM `$this->_table` WHERE `id` = :id",array(':id' => $id));
		return $allData==null?(string)$allData:'';
	}
 
	public function write($id, $data)
	{

		$set = array(
			':id' => $id,
		  	':access' => $this->_maxAccessTime,
			':data' => $data, 
			':remoteaddr' => $_SERVER['REMOTE_ADDR']
			);

		$stmt = $this->_db->query("REPLACE INTO sessions SET id = :id,  access = :access, data = :data, remoteaddr = :remoteaddr", $set);
		return $stmt->rowCount()>0?true:false;
	}
 
	public function destroy($id)
	{
		
		$where = $this->_db->quoteInto("`id` = ?", $id);	
		$rows_affected = $this->_db->delete($this->_table, $where);
               
		if ($rows_affected > 0) {
                        return true;
                } else {
                        return false;
                }
	}
 
 	
	public function gc($max)
	{
		$old = ($this->_maxAccessTime - $max);

		$where = $this->_db->quoteInto("`access` < ?", $old);	
		$this->_db->delete($this->_table, $where);

		return true;
	}
}
 
?>

<?php

/**
 * TreeMedel class deployment to work with nested sets data in database. 
 * It cakephp's native model class witch can be inherited in your application.
 * @author Samoshin Ivan aka azzis
 * @website http://my.kht.ru
 * @version 0.1.0
 * @date 2007-03-01 9:49
 * @license MIT 
 * @package  class
 */ 

abstract class Concentre_Db_Nested_Tree extends Zend_Db_Table  {
	
  protected $_dbh;
  protected $_error;
  protected $_left = 'lft';
  protected $_right = 'rgt';
  protected $_label = 'label';
  protected $_url = 'url';
 

	function __construct() {
		parent::__construct();
	
		$this->_dbh = $this->getAdapter();
	}
	
  /**
   * Reset database function
   * 
   * @param boolean $force - force clear data in tabel
   * @param string $rootname - name of the root node
   * @return error string if not successful
   */
  function cleanDB($force = false, $rootname = 'root')  {

    if (!$force) {
      if ($this->findCount() > 0) $continue = false;
      $this->error = "Your {$this->_name} table is not empty, and you didn't request a forced clean!\n";
      return $this->error;
    }

    if ($force || $continue) {
      $this->_dbh->query("delete from {$this->_name}");
      $this->_dbh->query("insert into {$this->_name} (name,{$this->_left},{$this->_right},id) values ('{$rootname}',1,2,1)");
      $this->_dbh->query("set insert_id=2");
    }

    if (mysql_errno() > 0) {
      $this->error = "Database error: ".mysql_error()."\n";
      return $this->error;
    }

  }

  /**
   * Get full tree from database function
   * 
   * @return array of rows from model's table
   */
  function enumTree() {
    $data = $this->_dbh->query("SELECT node.{$this->_label} as label, node.{$this->_primary} as id, 
                                 (count(parent.{$this->_label})-1) as depth, 
                                 IF((node.{$this->_right} = node.{$this->_left}+1), true, null) as is_leaf, (
                                   SELECT IF (n.{$this->_primary}, true, null)
                                   FROM {$this->_name} as n
                                   WHERE n.{$this->_left}=node.{$this->_left}-1
                                 ) as is_first, (
                                   SELECT IF (n.{$this->_primary}, true, null)
                                   FROM {$this->_name} as n
                                   WHERE n.{$this->_right}=node.{$this->_right}+1
                                 ) as is_last
                          FROM {$this->_name} as node, {$this->_name} as parent
                          WHERE node.{$this->_left} BETWEEN parent.{$this->_left} AND parent.{$this->_right}
                          GROUP BY node.{$this->_primary}
                          ORDER BY node.{$this->_left} ASC");
       
    return new Zend_Db_Table_Rowset(array(
    						'table'=> $this->_name, 
    						'data' => $this->_reduce($data->fetchAll())
                ));           
  }

  /**
   * Get node info
   * 
   * @param integer $id - node identifier
   * @return array of field values (id, name, lft, rgt, depth, is_leaf, is_first, is_last)
   */
  function enumNode($id=1)  {
  	
    $data = $this->_dbh->query("SELECT node.*,
                                 (count(parent.{$this->_label})-1) as depth, 
                                 IF((node.{$this->_right} = node.{$this->_left}+1), true, null) as is_leaf, (
                                   SELECT IF (n.id, true, null)
                                   FROM {$this->_name} as n
                                   WHERE n.{$this->_left}=node.{$this->_left}-1
                                 ) as is_first, (
                                   SELECT IF (n.id, true, null)
                                   FROM {$this->_name} as n
                                   WHERE n.{$this->_right}=node.{$this->_right}+1
                                 ) as is_last
                          FROM {$this->_name} as node, {$this->_name} as parent
                          WHERE node.{$this->_left} BETWEEN parent.{$this->_left} AND parent.{$this->_right} AND
                                node.id = {$id}
                          GROUP BY node.id");
    
    if (empty($data)) {
      $this->error = "Node with id = {$id} not found in tree!";
      return $this->error;
    }
    
    $data = $this->_reduce($data->fetchAll());
    return $data[0];
  }

  
  /**
   * Get only leaf nodes function
   * 
   * @return array of rows
   */
  function enumLeafNodes()  {
    $data = $this->_dbh->query("SELECT node.*, 
                                 (count(parent.{$this->_label})-1) as depth, 
                                 IF((node.{$this->_right} = node.{$this->_left}+1), true, null) as is_leaf, (
                                   SELECT IF (n.id, true, null)
                                   FROM {$this->_name} as n
                                   WHERE n.{$this->_left}=node.{$this->_left}-1
                                 ) as is_first, (
                                   SELECT IF (n.id, true, null)
                                   FROM {$this->_name} as n
                                   WHERE n.{$this->_right}=node.{$this->_right}+1
                                 ) as is_last
                          FROM {$this->_name} as node, {$this->_name} as parent
                          WHERE node.{$this->_left} BETWEEN parent.{$this->_left} AND parent.{$this->_right} AND
                                node.{$this->_right} = node.{$this->_left} + 1
                          GROUP BY node.id
                          ORDER BY node.{$this->_left} ASC");
    return $this->_reduce($data);
  }

  /**
   * Get full path to the node function
   * 
   * @param integer $id - node identifier
   * @param boolean $include_node - self node presence in path
   * @return array of rows
   */
  function enumPath($id, $include_node=false) {
    $inc = ($include_node) ? 0 : 1;
    $data = $this->_dbh->query("SELECT node.*,
                                 (count(parent.{$this->_label})-1) as depth, 
                                 IF((node.{$this->_right} = node.{$this->_left}+1), true, null) as is_leaf, (
                                   SELECT IF (n.id, true, null)
                                   FROM {$this->_name} as n
                                   WHERE n.{$this->_left}=node.{$this->_left}-1
                                 ) as is_first, (
                                   SELECT IF (n.id, true, null)
                                   FROM {$this->_name} as n
                                   WHERE n.{$this->_right}=node.{$this->_right}+1
                                 ) as is_last
                          FROM {$this->_name} as node, {$this->_name} as parent
                          WHERE node.{$this->_left} BETWEEN parent.{$this->_left} AND parent.{$this->_right} AND
                                node.id in (
                                  SELECT parent.id
                                  FROM {$this->_name} as node, {$this->_name} as parent
                                  WHERE node.{$this->_left} BETWEEN (parent.{$this->_left}+{$inc}) AND (parent.{$this->_right}-{$inc}) AND
                                        node.id = {$id}
                                )
                          GROUP BY node.id
                          ORDER BY node.{$this->_left} ASC");

    return $this->_reduce($data->fetchAll());
  }

  /**
   * Get parents of node
   * 
   * @param integer $id - node identifier
   * @param integer $count - count of the parents from node
   * @return array of rows
   */
  function enumParents($id, $count=null) {
    
    if ($count === null) {
      $limit = '';
    } else {
      $limit = " LIMIT {$count}";
    }
    
    $data = $this->_dbh->query("SELECT parent.*, 
                                (count(parent.{$this->_label})-1) as depth, 
                                 IF((node.{$this->_right} = node.{$this->_left}+1), true, null) as is_leaf, (
                                   SELECT IF (n.id, true, null)
                                   FROM {$this->_name} as n
                                   WHERE n.{$this->_left}=node.{$this->_left}-1
                                 ) as is_first, (
                                   SELECT IF (n.id, true, null)
                                   FROM {$this->_name} as n
                                   WHERE n.{$this->_right}=node.{$this->_right}+1
                                 ) as is_last
                          FROM {$this->_name} as node,
                               {$this->_name} as parent,
                               {$this->_name} as parent_
                          WHERE node.id = {$id} AND parent.{$this->_left} < node.{$this->_left} AND parent.{$this->_right} > node.{$this->_right} AND 
                                parent.{$this->_left} BETWEEN parent_.{$this->_left} AND parent_.{$this->_right}
                          GROUP BY parent.id
                          ORDER BY parent.{$this->_left} DESC ".$limit);
    
    return $this->_reduce($data);
  }

  /**
   * Get tree exclude node and his descendants
   * 
   * @param integer $id - node identifier
   * @return array of rows
   */
  function enumTreeExcludeNode($id)  {
    $data = $this->_dbh->query("SELECT node.{$this->_label} as label, node.{$this->_primary} as id,
                                 (count(parent.{$this->_label})-1) as depth, 
                                 IF((node.{$this->_right} = node.{$this->_left}+1), true, null) as is_leaf, (
                                   SELECT IF (n.{$this->_primary}, true, null)
                                   FROM {$this->_name} as n
                                   WHERE n.{$this->_left}=node.{$this->_left}-1
                                 ) as is_first, (
                                   SELECT IF (n.{$this->_primary}, true, null)
                                   FROM {$this->_name} as n
                                   WHERE n.{$this->_right}=node.{$this->_right}+1
                                 ) as is_last
                          FROM {$this->_name} as node, {$this->_name} as parent, {$this->_name} as n
                          WHERE node.{$this->_left} BETWEEN parent.{$this->_left} AND parent.{$this->_right} AND
                                n.{$this->_primary}={$id} AND node.{$this->_left} not BETWEEN n.{$this->_left} AND n.{$this->_right}
                          GROUP BY node.{$this->_primary}
                          ORDER BY node.{$this->_left} ASC");
    
                return new Zend_Db_Table_Rowset(array(
    						'table'=> $this->_name, 
    						'data' => $this->_reduce($data->fetchAll())
                )); 
                          
  }

  /**
   * Get sub tree
   * 
   * @param integer $id - node identifier
   * @return array of rows
   */
  function enumSubTree($id, $forest = false)  {
     
     $data = $this->_dbh->query("SELECT node.{$this->_url} as url, node.{$this->_label} as label, node.{$this->_primary} as id,
                                   (count(parent.{$this->_label})-1) as depth, 
                                   IF((node.{$this->_right} = node.{$this->_left}+1), true, null) as is_leaf, (
                                   SELECT IF (n.{$this->_primary}, true, null)
                                   FROM {$this->_name} as n
                                   WHERE n.{$this->_left}=node.{$this->_left}-1
                                 ) as is_first, (
                                   SELECT IF (n.{$this->_primary}, true, null)
                                   FROM {$this->_name} as n
                                   WHERE n.{$this->_right}=node.{$this->_right}+1
                                 ) as is_last
                            FROM {$this->_name} as node,
                                 {$this->_name} as parent,
                                 {$this->_name} as n
                            WHERE node.{$this->_left} BETWEEN parent.{$this->_left} AND
                                  parent.{$this->_right} AND
                                  n.{$this->_primary} = {$id} AND ".
                             
                             ($forest?
                             "node.{$this->_left} > n.{$this->_left} AND node.{$this->_left} < n.{$this->_right}":
                             "node.{$this->_left} BETWEEN n.{$this->_left} AND n.{$this->_right}") .
                             
                            " GROUP BY node.{$this->_primary} 
                             ORDER BY node.{$this->_left} ASC");
                            
    return new Zend_Db_Table_Rowset(array(
    						'table'=> $this->_name, 
    						'data' => $this->_reduce($data->fetchAll())
                )); 

  }

  /**
   * Get all descendants of the node
   * 
   * @param integer $id - node identifier
   * @param boolean $include_parent - include parent node
   * @return array of rows
   */
  function enumChildren($id, $include_parent=false)  {

    $compare = (!$include_parent) ? '=' : '<=';

    $data = $this->_dbh->fetchAll("SELECT node.*, 
                                 (count(parent.{$this->_label})-1) as depth, 
                                 IF((node.{$this->_right} = node.{$this->_left}+1), true, null) as is_leaf, (
                                   SELECT IF (n.id, true, null)
                                   FROM {$this->_name} as n
                                   WHERE n.{$this->_left}=node.{$this->_left}-1
                                 ) as is_first, (
                                   SELECT IF (n.id, true, null)
                                   FROM {$this->_name} as n
                                   WHERE n.{$this->_right}=node.{$this->_right}+1
                                 ) as is_last
                          FROM {$this->_name} as node,
                               {$this->_name} as parent,
                               {$this->_name} as n
                          WHERE node.{$this->_left} BETWEEN parent.{$this->_left} AND
                                parent.{$this->_right} AND
                                n.id = {$id} AND
                                node.{$this->_left} BETWEEN n.{$this->_left} AND n.{$this->_right} 
                          GROUP BY node.id
                          HAVING depth-1 {$compare} (
                            SELECT (COUNT(parent.{$this->_label}) - 1) AS depth
                            FROM {$this->_name} AS node,
                                 {$this->_name} AS parent
                            WHERE node.{$this->_left} BETWEEN parent.{$this->_left} AND
                                  parent.{$this->_right} AND
                                  node.id = {$id}                                   
                            GROUP BY node.id
                            ORDER BY node.{$this->_left}
                          )
                          ORDER BY node.{$this->_left} ASC");

    return $this->_reduce($data);
  }

  /**
   * Insert node below
   * 
   * @param integer $id - node below identifier
   * @param string $name - name of inserted node
   */
  function insertBelow($name, $id)  {

    $this->_dbh->query("LOCK TABLE {$this->_name} WRITE");

    $result = $this->_dbh->fetchRow("SELECT {$this->_right}
                         FROM {$this->_name}
                         WHERE id={$id}");
    
    $rgt = $result[$this->_right];

    $this->_dbh->query("UPDATE {$this->_name}
                  SET {$this->_right}={$this->_right}+2
                  WHERE {$this->_right} > {$rgt}");

    $this->_dbh->query("UPDATE {$this->_name}
                  SET {$this->_left}={$this->_left}+2
                  WHERE {$this->_left} > {$rgt}");

    $this->_dbh->query("INSERT INTO {$this->_name} ({$this->_label}, {$this->_left}, {$this->_right}) 
                  VALUES ('{$name}', {$rgt} + 1, {$rgt} + 2)");
                  
		$lastid = $this->_dbh->lastInsertId();
		
    $this->_dbh->query("UNLOCK TABLES;");

		return $lastid;
  }
  
  /**
   * Insert node into
   * 
   * @param integer $id - node into identifier
   * @param string $name - name of inserted node
   */
  function insertInto($data, $id)  {
    
	$this->_dbh->beginTransaction();

	try {
	
		
	    $result = $this->_dbh->fetchRow("SELECT {$this->_right}
                         FROM {$this->_name}
                         WHERE id={$id}");
                         
    	$rgt = $result[$this->_right];
    
    
    	$this->_dbh->query("UPDATE {$this->_name}
                  SET {$this->_left}={$this->_left}+2
                  WHERE {$this->_left} >= {$rgt}");
    
   		$this->_dbh->query("UPDATE {$this->_name}
                  SET {$this->_right}={$this->_right}+2
                  WHERE {$this->_right} >= {$rgt}");
 
 		unset($data['id']);
		unset($data[$this->_left]);
		unset($data[$this->_right]);
					
    	$sql = "INSERT INTO {$this->_name} (`" . implode('`,`',array_keys($data)) . "`,`{$this->_left}`,`{$this->_right}`) ";
    	$sql.= "VALUES ('". implode("','",$data)."', {$rgt}, {$rgt}+1)";

    	
    	$this->_dbh->query($sql);

		$lastid = $this->_dbh->lastInsertId();
		$this->_dbh->commit();
		return $lastid;
	} catch (Exception $e) {
    	$this->_dbh->rollBack();
		throw new Exception($e->getMessage());
	}  

  }


  /**
   * Delete node without of descentants
   * 
   * @param integer $id - deleted node
   */
  function deleteLeaf($id)  {

    $this->query("LOCK TABLE {$this->_name} WRITE");
    $result = $this->query("SELECT {$this->_left}, {$this->_right}, ({$this->_right}-{$this->_left}+1) as width 
                            FROM {$this->_name} 
                            WHERE id={$id}");
    
    if (empty($result)) {
      $this->query('UNLOCK TABLES');
      $this->error = "Node with id=$id not found in table ".$this->_name."!";
      return $this->error;
    }
    
    $result = $this->_reduce($result->fetchAll());
    $lft = $result[0][$this->_left];
    $rgt = $result[0][$this->_right];

    
    $this->query("DELETE FROM {$this->_name} WHERE {$this->_left}={$lft}");
    
    $this->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} - 1, {$this->_left} = {$this->_left} - 1 WHERE {$this->_left} BETWEEN {$lft} AND {$rgt}");
    $this->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} - 2, {$this->_left} = {$this->_left} - 2 WHERE {$this->_left} > {$rgt}");
    
    $this->query('UNLOCK TABLES');
  }

  /**
   * Delete node with all descentants
   * 
   * @param integer $id - deleted node
   */
  function deleteNode($id)  {

	$this->_dbh->beginTransaction();
	
	
    try {
    
    	$result = $this->_dbh->query("SELECT {$this->_left}, {$this->_right}, ({$this->_right}-{$this->_left}+1) as width 
                            FROM {$this->_name} 
                            WHERE id={$id}");
    
    	if (empty($result)) {
      		$this->error = "Node with id=$id not found in table ".$this->_name."!";
      		return $this->error;
    	}
    
    	$result = $this->_reduce($result->fetchAll());
    
    	$lft = $result[0][$this->_left];
    	$rgt = $result[0][$this->_right];
    	$width = $result[0]['width'];
    
    	$this->_dbh->query("DELETE FROM {$this->_name} WHERE {$this->_left} BETWEEN {$lft} AND {$rgt}");
    	$this->_dbh->query("UPDATE {$this->_name} SET {$this->_right}={$this->_right}-{$width} WHERE {$this->_right} > {$rgt}");
    	$this->_dbh->query("UPDATE {$this->_name} SET {$this->_left}={$this->_left}-{$width} WHERE {$this->_left} > {$rgt}");		


		$this->_dbh->commit();
	} catch (Exception $e) {
    	$this->_dbh->rollBack();
    	throw new Exception($e->getMessage());
	}  
  }

  /**
   * Move source node with descendants after destination
   * 
   * @param integer $src_id - moved node
   * @param integer $dst_id - destination node
   * @return new left and right positions for the moved node
   */
  function moveNodeAfter($src_id, $dst_id)
  {
    $src = $this->enumNode($src_id);
    $dst = $this->enumNode($dst_id);
    return $this->_moveSubtree($src, $dst[$this->_right]+1);
  }

  /**
   * Move source node with descendants before destination
   * 
   * @param integer $src_id - moved node
   * @param integer $dst_id - destination node
   * @return new left and right positions for the moved node
   */
  function moveNodeBefore($src_id, $dst_id)
  {
    $src = $this->enumNode($src_id);
    $dst = $this->enumNode($dst_id);
    return $this->_moveSubtree($src, $dst[$this->_left]);
  }

  /**
   * Move source node with descendants to first position into destination
   * 
   * @param integer $src_id - moved node
   * @param integer $dst_id - destination node
   * @return new left and right positions for the moved node
   */
  function moveToFirstChild($src_id, $dst_id)
  {
    $src = $this->enumNode($src_id);
    $dst = $this->enumNode($dst_id);
    return $this->_moveSubtree($src, $dst[$this->_left]+1);
  }

  /**
   * Move source node with descendants to last position into destination
   * 
   * @param integer $src_id - moved node
   * @param integer $dst_id - destination node
   * @return new left and right positions for the moved node
   */
  function moveToLastChild($src_id, $dst_id)
  {
    $src = $this->enumNode($src_id);
    $dst = $this->enumNode($dst_id);
	
    return $this->_moveSubtree($src, $dst[$this->_right]);
  }

  
   /**
   * Private helper method to merge array elements
   * 
   * @access protected
   * @param integer $data - array of rows from database
   * @return merged data array
   */
  function _reduce($data) {
    /*foreach ($data as $item) {
      $item = array_reduce($item, array('Concentre_Nested_Tree','__reduce_callback'));
    }*/
    return $data;
  }

  function __reduce_callback ($res_arr, $arr) {
    if (!is_array($res_arr)) $res_arr = array();
    $res_arr = array_merge($res_arr, $arr);
    return $res_arr;
  }	
  
   /**
   * Private helper method to move node
   * 
   * @access protected
   * @param array $src - node row from database
   * @param integer $to
   * @return new left and right positions for the moved node
   */
  function _moveSubtree ($src, $to) {

	$this->_dbh->beginTransaction();

	try {
  	
    $treesize = $src[$this->_right] - $src[$this->_left] + 1;

    $this->_dbh->query("UPDATE {$this->_name} SET {$this->_left} = {$this->_left} + {$treesize} WHERE {$this->_left} >= {$to}");
    $this->_dbh->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} + {$treesize} WHERE {$this->_right} >= {$to}");

    if($src[$this->_left] >= $to){
      $src[$this->_left] += $treesize;
      $src[$this->_right] += $treesize;
    }

    
    $this->_dbh->query("UPDATE {$this->_name} SET {$this->_left} = {$this->_left} + ".($to - $src[$this->_left])." WHERE {$this->_left} >= ".$src[$this->_left]." AND {$this->_left} <= ".$src[$this->_right]);
    $this->_dbh->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} + ".($to - $src[$this->_left])." WHERE {$this->_right} >= ".$src[$this->_left]." AND {$this->_right} <= ".$src[$this->_right]);
	
    
    $newpos = array($this->_left => $src[$this->_left] + $to - $src[$this->_left], 
                    $this->_right => $src[$this->_right] + $to - $src[$this->_left]);

    $this->_dbh->query("UPDATE {$this->_name} SET {$this->_left} = {$this->_left} + ".(-$treesize)." WHERE {$this->_left} >= ".($src[$this->_right] + 1));
    $this->_dbh->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} + ".(-$treesize)." WHERE {$this->_right} >= ".($src[$this->_right] + 1));


	$this->_dbh->commit();

    if ($src[$this->_left] <= $to)
    {
      $newpos[$this->_left] -= $treesize;
      $newpos[$this->_right] -= $treesize;
    }

    return $newpos;


	} catch (Exception $e) {
    	$this->_dbh->rollBack();
		throw new Exception($e->getMessage());
	}  

    
    
  }
  
 function _copySubtree ($src, $to) {

	$this->_dbh->beginTransaction();

	try {
  	
    $treesize = $src[$this->_right] - $src[$this->_left] + 1;

    /* make space to insert nodes */ 
    $this->_dbh->query("UPDATE {$this->_name} SET {$this->_left} = {$this->_left} + {$treesize} WHERE {$this->_left} >= {$to}");
    $this->_dbh->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} + {$treesize} WHERE {$this->_right} >= {$to}");

    if($src[$this->_left] >= $to){
      $src[$this->_left] += $treesize;
      $src[$this->_right] += $treesize;
    }
    
    $this->_dbh->query("UPDATE {$this->_name} SET {$this->_left} = {$this->_left} + ".($to - $src[$this->_left])." WHERE {$this->_left} >= ".$src[$this->_left]." AND {$this->_left} <= ".$src[$this->_right]);
    $this->_dbh->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} + ".($to - $src[$this->_left])." WHERE {$this->_right} >= ".$src[$this->_left]." AND {$this->_right} <= ".$src[$this->_right]);
    $newpos = array($this->_left => $src[$this->_left] + $to - $src[$this->_left], 
                    $this->_right => $src[$this->_right] + $to - $src[$this->_left]);

    $this->_dbh->query("UPDATE {$this->_name} SET {$this->_left} = {$this->_left} + ".(-$treesize)." WHERE {$this->_left} >= ".($src[$this->_right] + 1));
    $this->_dbh->query("UPDATE {$this->_name} SET {$this->_right} = {$this->_right} + ".(-$treesize)." WHERE {$this->_right} >= ".($src[$this->_right] + 1));


	$this->_dbh->commit();

    if ($src[$this->_left] <= $to)
    {
      $newpos[$this->_left] -= $treesize;
      $newpos[$this->_right] -= $treesize;
    }

    return $newpos;


	} catch (Exception $e) {
    	$this->_dbh->rollBack();
		throw new Exception($e->getMessage());
	}  

    
    
  }
}


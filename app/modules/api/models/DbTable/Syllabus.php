<?php

class Api_Model_DbTable_Syllabus extends Zend_Db_Table_Abstract
{
	protected $_name = 'base_syllabus';
	protected $_primary = array("eid","oid","subid","perid","escid","curid","courseid","turno");

	public function _save($data){
		try{
			if ($data['eid']=='' || $data['oid']=='' || $data['subid']=='' || $data['perid']=='' || $data['escid']=='' || $data['curid']=='' || $data['courseid']=='' || $data['turno']=='') return false;
			return $this->insert($data);
			return false;
		}catch (Exception $e){
				print "Error: Save Syllabus ".$e->getMessage();
		}
	}
	
	public function _update($data,$pk){
		try{
			if ($pk['eid']=='' || $pk['oid']=='' || $pk['subid']=='' || $pk['perid']=='' || $pk['escid']=='' || $pk['curid']=='' || $pk['courseid']=='' || $pk['turno']=='') return false;
			$where = "eid = '".$pk['eid']."' and oid='".$pk['oid']."' and subid='".$pk['subid']."' and perid='".$pk['perid']."' and escid='".$pk['escid']."' and curid='".$pk['curid']."' and courseid='".$pk['courseid']."' and turno='".$pk['turno']."'";
			return $this->update($data, $where);
			return false;
		}catch (Exception $e){
			print "Error: Update Syllabus ".$e->getMessage();
		}
	}
	
	public function _delete($data){
		try{
			if ($data['eid']=='' || $data['oid']=='' || $data['subid']=='' || $data['perid']=='' || $data['escid']=='' || $data['curid']=='' || $data['courseid']=='' || $data['turno']=='') return false;
			$where = "eid = '".$data['eid']."' and oid='".$data['oid']."' and subid='".$data['subid']."' and perid='".$data['perid']."' and escid='".$data['escid']."' and curid='".$data['curid']."' and courseid='".$data['courseid']."' and turno='".$data['turno']."'";
			return $this->delete($where);
			return false;
		}catch (Exception $e){
			print "Error: Delete Syllabus ".$e->getMessage();
		}
	}
	
	public function _getOne($where=array()){
		try{
			if ($where['eid']=='' || $where['oid']=='' || $where['subid']=='' || $where['perid']=='' || $where['escid']=='' || $where['curid']=='' || $where['courseid']=='' || $where['turno']=='') return false;
			$wherestr="eid = '".$where['eid']."' and oid='".$where['oid']."' and subid='".$where['subid']."' and perid='".$where['perid']."' and escid='".$where['escid']."' and curid='".$where['curid']."' and courseid='".$where['courseid']."' and turno='".$where['turno']."'";
			$row = $this->fetchRow($wherestr);
			if($row) return $row->toArray();
			return false;
		}catch (Exception $e){
			print "Error: Read One Syllabus ".$e->getMessage();
		}
	}

}

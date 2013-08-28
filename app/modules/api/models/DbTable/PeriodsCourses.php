<?php

class Api_Model_DbTable_PeriodsCourses extends Zend_Db_Table_Abstract
{
	protected $_name = 'base_periods_courses';
	protected $_primary = array("eid", "oid", "perid", "courseid", "escid", "subid", "curid", "turno");

	public function _save($data){
		try {
			if($data["eid"]=='' || $data["oid"]=='' ||  $data["perid"]=='' ||  $data["courseid"] =='' || $data["escid"]=='' || $data["subid"] =='' || $data["curid"] =='' || $data["turno"]=='')
				return false;
				return $this->insert($data);
				return false;
		} catch (Exception $e) {
			print "Error: Save Periods_Courses ".$e->getMessage();
		}
	}

	public function _update($data,$pk){
		try {
				if ($pk["eid"]=='' || $pk["oid"]=='' ||  $pk["perid"]=='' ||  $pk["courseid"] =='' || $pk["escid"]=='' || $pk["subid"] =='' || $pk["curid"] =='' || $pk["turno"]=='') return false;
				$where="eid = '".$pk['eid']."' and oid='".$pk['oid']."' 
						and courseid='".$pk['courseid']."' and escid='".$pk['escid']."' 
						and perid='".$pk['perid']."' and turno='".$pk['turno']."' and subid='".$pk['subid']."' and curid='".$pk['curid']."' ";
				return $this->update($data, $where);
				return false;
		} catch (Exception $e) {
			print "Error: Update Periods_Courses".$e->getMessage();
		}
	}

	public function _delete($data)
	{
		try{
			if ($data['eid']=="" || $data['oid']=="" || $data['courseid']=="" || $data['escid']=='' || $data['perid']==""  || $data['turno']=='' || $data['subid']=='' || $data['curid']=='') return false;
			$where="eid = '".$data['eid']."' and oid='".$data['oid']."' and courseid='".$data['courseid']."' 
					and escid='".$data['escid']."' and perid='".$data['perid']."' and turno='".$data['turno']."'
					 and subid='".$data['subid']."' and curid='".$data['curid']."' ";
			return $this->delete($where);
			return false;
		}catch (Exception $e){
			print "Error: Delete ".$e->getMessage();
		}
	}

	public function _getOne($where=array()){
		try{
			if ($where['eid']=="" || $where['oid']=="" || $where['courseid']=="" || $where['escid']=='' || $where['perid']==""  || $where['turno']=='' || $where['subid']=='' || $where['curid']=='') return false;
			$wherestr="eid = '".$where['eid']."' and oid='".$where['oid']."' 
						and courseid='".$where['courseid']."' and escid='".$where['escid']."' 
						and perid='".$where['perid']."' and turno='".$where['turno']."' and subid='".$where['subid']."' and curid='".$where['curid']."' ";
			$row = $this->fetchRow($wherestr);
			if($row) return $row->toArray();
			return false;
		}catch (Exception $e){
			print "Error: Read One Periods_Courses ".$e->getMessage();
		}
	}

	
	public function _getAll($where=null,$order='',$start=0,$limit=0)
	{
		try {
			if ($where['eid']=="" || $where['oid']=="" || $where['escid']=='' || $where['perid']=="" || $where['subid']=='' || $where['curid']=='')	
				$wherestr=null;
			else 
				$wherestr= "eid = '".$where['eid']."' and oid='".$where['oid']."' and escid='".$where['escid']."' and perid='".$where['perid']."' and subid='".$where['subid']."' and curid='".$where['curid']."' ";
			if ($limit==0) $limit=null;
			if ($start==0) $start=null;
			
			$rows=$this->fetchAll($wherestr,$order,$start,$limit);
			if($rows) return $rows->toArray();
			return false;

		} catch (Exception $e) {
			print "Error: Read All Periods_Courses ".$e->getMessage();
		}
	}

	public function _getFilter($where=null,$attrib=null,$orders=null){
		try{
			if($where['eid']=='' || $where['oid']=='') return false;
				$select = $this->_db->select();
				if ($attrib=='') $select->from("base_periods_courses");
				else $select->from("base_periods_courses",$attrib);
				foreach ($where as $atri=>$value){
					$select->where("$atri = ?", $value);
				}
				if ($orders<>null || $orders<>"") {
					if (is_array($orders))
						$select->order($orders);
				}
				
				$results = $select->query();
				$rows = $results->fetchAll();
				if ($rows) return $rows;
				return false;
		}catch (Exception $e){
			print "Error: Read Filter Periods_Courses ".$e->getMessage();
		}
	}

	public function _getCourse_Teacher($where=null,$attrib=null,$order=null){
		try {
			if($where=='' && $attrib=='') return false;
				$base_course_teacher = new Api_Model_DbTable_Coursexteacher();
				$data_course_teacher = $base_course_teacher->_getFilter($where,$attrib,$order);
			if($data_course_teacher) return $data_course_teacher;
			return false;	
		} catch (Exception $e) {
			print "Error: Read Course_Teacher";
		}
	}

	public function _getinfoCourse($where=null,$attrib=null,$order=null){
		try {
			if ($where=='' && $attrib=='' ) return false;
				$base_course = new Api_Model_DbTable_Course();
				$data_course = $base_course ->_getFilter($where,$attrib,$order);
			if($data_course) return $data_course;
			return false;
		} catch (Exception $e) {
			print "Error: Read info Course ";
		}
	}

	public function _getCourseTeacher($where=null){
		try {
			if ($where['perid']=='' || $where['rid']=='' || $where['uid']=='' || $where['is_main']=='') return false; 

				$select = $this->_db->select()
									->distinct()
									->from(array('ct'=>'base_course_x_teacher'),
												array('ct.uid','s.facid','ct.escid','ct.courseid',
													'ct.curid','ct.subid','ct.oid','ct.perid',
													  'ct.semid','f.name','s.name','c.name','ct.turno',
													  'pc.type_rate'))
									->join(array('pc'=>'base_periods_courses'),
												'ct.oid=pc.oid and ct.eid=pc.eid and ct.escid=pc.escid and 
												  ct.subid=pc.subid and ct.curid=pc.curid and ct.perid=pc.perid and
												  ct.courseid=pc.courseid and ct.turno=pc.turno')
									->join(array('c'=>'base_courses'),
												'ct.courseid = c.courseid and ct.escid=c.escid and ct.curid=c.curid and 
												pc.courseid=c.courseid and pc.escid=c.escid and pc.curid=c.curid')
									->join(array('u'=>'base_users'),'ct.uid=u.uid')
									->join(array('s'=>'base_speciality'),'c.escid=s.escid')
									->join(array('f'=>'base_faculty'),'s.facid=f.facid')
									->where('ct.is_main = ?',$where['is_main'])
									->where('ct.uid = ?',$where['uid'])
									->where('ct.perid = ?',$where['perid'])
									->where('u.rid = ?',$where['rid'])
									->order('f.facid');

				$results = $select->query();
				$rows = $results->fetchAll();
				if ($rows) return $rows;
				return false;
		} catch (Exception $e) {
			print "Error: Read Course_Teacher";
		}
	}

	public function _getCoursesxPeriodxspecialityxsemester($where=null){
      try
        {
            if ($where['eid']=="" || $where['oid'] =="" || $where['perid']=="" || $where['escid']=="" || $where['semid']=="") return false;
            $select = $this->_db->select()
            ->from(array('pc'=>'base_periods_courses'),array('pc.curid','pc.escid','pc.semid','pc.courseid'))
            	->join(array('c'=>'base_courses'),'pc.courseid=c.courseid and pc.eid=c.eid and pc.oid=c.oid and pc.curid=c.curid',array('c.name'))
            	->join(array('tc'=>'base_course_x_teacher'),'pc.courseid=tc.courseid and pc.eid=tc.eid and pc.oid=tc.oid and pc.curid=tc.curid and pc.perid=tc.perid and pc.escid=tc.escid and pc.turno=tc.turno',array('tc.hours_t','tc.hours_p','tc.hours_total'))
            	->where('pc.eid = ?', $where['eid'])->where('pc.oid = ?', $where['oid'])
            	->where('pc.escid = ?', $where['escid'])->where('pc.perid = ?', $where['perid'])
            	->where('pc.semid = ?', $where['semid'])
            	->order('pc.courseid')
            	->order('pc.turno');
            $results = $select->query();
            $rows = $results->fetchAll();
            if($rows) return $rows; 	
            return false;
        }  
        catch (Exception $ex)
        {
            print "Error: get".$ex->getMessage();
        }
    }

	public function _getAllcoursesXescidXsemester($where=null)
	{
		try{
			if ($where['eid']=="" || $where['oid'] =="" ||  $where['curid'] =="" || $where['perid']=="" || $where['escid']=="" || $where['semid']=="") return false;
			$select = $this->_db->select()
			->from(array('pc' => 'base_periods_courses'),array('pc.courseid','pc.perid','pc.curid','pc.eid','pc.oid','pc.turno','pc.escid','pc.subid','pc.state_record','pc.state'))
				->join(array('c' => 'base_courses'),'pc.courseid=c.courseid and pc.eid=c.eid and pc.oid=c.oid and pc.escid=c.escid and pc.curid=c.curid', array('c.name'))
				->where('pc.eid = ?', $where['eid'])->where('pc.oid = ?', $where['oid'])
				->where('pc.escid = ?', $where['escid'])->where('pc.curid = ?', $where['curid'])
				->where('pc.perid = ?', $where['perid'])->where('pc.semid = ?', $where['semid'])
				->order('pc.courseid');
			$results = $select->query();			
			$rows = $results->fetchAll();
			if($rows) return $rows;
			return false;
		}catch (Exception $e){
			print "Error: Read UserInfo ".$e->getMessage();
		}
	}


	public function _getTeacherXPeridXEscid($eid,$oid,$escid,$perid){
        try{
            $sql=$this->_db->query("
             select distinct on(DC.uid)DC.uid,DC.pid,PC.courseid,PC.perid,PC.curid,PC.escid,PC.turno,DC.is_main,DC.is_com,P.last_name0, P.last_name1, P.first_name from base_periods_courses PC inner join base_course_x_teacher DC 
            on PC.perid=DC.perid and PC.escid=DC.escid and PC.eid=DC.eid  and PC.oid=DC.oid and PC.curid=DC.curid and PC.courseid=DC.courseid inner join base_person P
            on DC.pid=P.pid and P.eid=DC.eid where PC.eid='$eid' and PC.oid='$oid' and PC.perid='$perid' and PC.escid='$escid'order by DC.uid ASC
             ");          
            return $sql->fetchAll(); 
            }  catch (Exception $ex){
                print $ex->getMessage();
            }            
        }
     public function _getTeacherXPeridXEscid1($where=null){
         try{
             if ($where['perid']=='' || $where['escid']=='' || $where['oid']=='' || $where['eid']=='') return false; 
                 $select = $this->_db->select()
									->distinct()
									->from(array('pc'=>'base_periods_courses'),
												array('pc.courseid','pc.perid','pc.escid','pc.turno'))
									->join(array('ct'=>'base_course_x_teacher'),'ct.oid=pc.oid and ct.eid=pc.eid and ct.perid=pc.perid and 
												  ct.escid=pc.escid and ct.curid=pc.curid and ct.courseid=pc.courseid',
												  array('ct.uid','ct.pid','ct.is_main','ct.is_com'))
									->join(array('p'=>'base_person'),'ct.pid = p.pid and ct.eid=p.eid',
										array('p.last_name0','p.last_name1','p.first_name'))											
									
									->where('pc.eid = ?',$where['eid'])
									->where('pc.oid = ?',$where['oid'])
									->where('pc.perid = ?',$where['perid'])
									->where('pc.escid = ?',$where['escid'])
									->order('ct.uid');

				$results = $select->query();
				$rows = $results->fetchAll();
				 if ($rows) return $rows;
				return false;      
         }  catch (Exception $ex){
             print "Error: Obteniendo datos de un Docente que dicta un curso".$ex->getMessage();
         }
     }

	public function _getInfocourseXescidXperidXcourseXturno($where=null)
	{
		try{
			if ($where['eid']=="" || $where['oid'] =="" ||  $where['curid'] =="" || $where['perid']=="" || $where['escid']=="" || $where['courseid']=="" || $where['turno']=="") return false;
			$select = $this->_db->select()
			->from(array('pc' => 'base_periods_courses'),array('pc.perid','pc.turno','pc.escid','pc.subid','pc.state_record','pc.state','pc.type_rate'))
				->join(array('c' => 'base_courses'),'pc.courseid=c.courseid and pc.eid=c.eid and pc.oid=c.oid and pc.escid=c.escid and pc.curid=c.curid and pc.subid=c.subid', array('c.*'))
				->where('pc.eid = ?', $where['eid'])->where('pc.oid = ?', $where['oid'])
				->where('pc.escid = ?', $where['escid'])->where('pc.curid = ?', $where['curid'])
				->where('pc.perid = ?', $where['perid'])->where('pc.courseid = ?', $where['courseid'])
				->where('pc.turno = ?', $where['turno']);
			$results = $select->query();			
			$rows = $results->fetchAll();
			if($rows) return $rows;
			return false;
		}catch (Exception $e){
			print "Error: Read Info ".$e->getMessage();
		}
	}



	    /* Devuelve los turnos de un curso($cursoid) de una escuela($escid) en un periodo($perid) */
    public function _getCourseXTur($where=null){
      try{
          // if($where['perid']==""||$where['eid']==""||$where['oid']==""||$where['subid']==""||$where['escid']==""||$where['curid']=="") return false;
          			$select = $this->_db->select()->distinct()
			->from(array('pc' => 'base_periods_courses'),array('turno'))
				->where('perid = ?', $where['perid'])->where('curid = ?', $where['curid'])
				->where('escid = ?', $where['escid'])->where('courseid = ?', $where['courseid'])
				->where('eid = ?', $where['eid'])->where('oid = ?', $where['oid'])
				->where('subid = ?', $where['subid']);
			$results = $select->query();			
			$rows = $results->fetchAll();
			if($rows) return $rows;
			return false;
            
      }catch (Exception $ex){
          print "Error: Obteniendo turnos de un curso establecido".$ex->getMessage();
      }
    } 


}


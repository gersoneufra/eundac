<?php

class Api_Model_DbTable_StudentAssistance extends Zend_Db_Table_Abstract
{
    protected $_name = 'base_student_assistance';
    protected $_primary = array('eid', 'oid', 'escid', 'subid', 'perid', 'coursoid', 'curid', 'turno', 'uid', 'pid', 'regid');
    

    public function _save($data){
        try {
            if($data["eid"]=='' || $data["oid"]=='' ||  $data["escid"]=='' ||  $data["subid"] =='' || $data["coursoid"] =='' || $data["curid"] =='' 
                | $data["turno"]=='' || $data["perid"]=='' || $data["uid"]=='' || $data["pid"]=='')
                return false;
                return $this->insert($data);
                return false;
        } catch (Exception $e) {
            print "Error: Save cours ".$e->getMessage();
        }
    }
    public function _update($data,$pk){
        try{

            if ($pk["eid"]=='' || $pk["oid"]=='' ||  $pk["escid"]=='' ||  $pk["subid"] =='' || 
                $pk["coursoid"]=='' || $pk["curid"] =='' || $pk["turno"] =='' || $pk["perid"]=='' || 
                $pk["uid"]=='' || $pk["pid"]=='' || $pk['regid']=='') return false;

                $where = "eid = '".$pk['eid']."' and oid='".$pk['oid']."' and escid='".$pk['escid']."' 
                    and subid='".$pk['subid']."' and coursoid='".$pk['coursoid']."' and curid='".$pk['curid']."'
                    and turno='".$pk['turno']."' and perid='".$pk['perid']."' and uid='".$pk['uid']."' 
                    and pid='".$pk['pid']."' and regid='".$pk['regid']."'";

            return $this->update($data, $where);
            return false;
        }catch (Exception $e){
            print "Error: Update Organization ".$e->getMessage();
        }
    }

    public function _updateAll($data,$pk){
        try {
             if ($pk["eid"]=='' || $pk["oid"]=='' ||  $pk["escid"]=='' ||  $pk["subid"] =='' || 
                $pk["coursoid"]=='' || $pk["curid"] =='' || $pk["turno"] =='' || $pk["perid"]=='' 
                ) return false;

                $where = "eid = '".$pk['eid']."' and oid='".$pk['oid']."' and escid='".$pk['escid']."' 
                    and subid='".$pk['subid']."' and coursoid='".$pk['coursoid']."' and curid='".$pk['curid']."'
                    and turno='".$pk['turno']."' and perid='".$pk['perid']."'";
        return $this->update($data,$where);
        return false;

        } catch (Exception $e) {
            print "Error: Update all Assistance".$e->getMessage();
        }
    }
    public function _delete($pk){
        try{
            if ($pk['oid']=='' || $pk['eid']=='' || $pk['escid']=='' || $pk['subid']=='' || $pk["courseid"]=='' || $pk["curid"] =='' || $pk["turno"] =='' || $pk["perid"]=='' || $pk["uid"]=='' || $pk["pid"]=='') return false;
            $where = "eid = '".$pk['eid']."' and oid='".$pk['oid']."' and escid='".$pk['escid']."' and subid='".$pk['subid']."' and courseid='".$pk['courseid']."' and curid='".$pk['curid']."' and turno='".$pk['turno']."' and perid='".$pk['perid']."' and uid='".$pk['uid']."' and pid='".$pk['pid']."'";
            return $this->delete($where);
            return false;
        }catch (Exception $e){
            print "Error: Delete TeacherCourse ".$e->getMessage();
        }
    }

    public function _getOne($where=array()){
        try{
            if ($where["eid"]=='' || $where["oid"]=='' ||  $where["escid"]=='' ||  
                $where["subid"] =='' || $where["courseid"]=='' || $where["curid"] =='' || 
                $where["turno"] =='' || $where["perid"]=='' || $where["uid"]=='' || 
                $where["pid"]=='') return false;

            $wherestr="eid = '".$where['eid']."' and oid='".$where['oid']."' and escid='".$where['escid']."' and subid='".$where['subid']."' and courseid='".$where['courseid']."' and curid='".$where['curid']."' and turno='".$where['turno']."' and perid='".$where['perid']."' and uid='".$where['uid']."' and pid='".$where['pid']."'";
            $row = $this->fetchRow($wherestr);
            if($row) return $row->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read One Course ".$e->getMessage();
        }
    }

    public function _getAll($where=array()){
        try{
            if ($where["eid"]=='' || $where["oid"]=='' ||  $where["escid"]=='' ||  
                $where["subid"] =='' || 
                $where["coursoid"]=='' || $where["curid"] =='' || $where["turno"] =='' || 
                $where["perid"]=='') return false;

            $wherestr="eid = '".$where['eid']."' and oid='".$where['oid']."' and escid='".
                    $where['escid']."' and subid='".$where['subid']."' and coursoid='".$where['coursoid']."' 
                    and curid='".$where['curid']."' and turno='".$where['turno']."' and perid='".$where['perid']."'";
            $rows = $this->fetchAll($wherestr);
            if($rows) return $rows->toArray();
            return false;
        }catch (Exception $e){
            print "Error: Read All Assistance ".$e->getMessage();
        }
    }

    public function _getFilter($where=null,$attrib=null,$orders=null){
        try{
            if($where['eid']=='' || $where['oid']=='') return false;
                $select = $this->_db->select();
                if ($attrib=='') $select->from("base_student_assistance");
                else $select->from("base_student_assistance",$attrib);
                foreach ($where as $atri=>$value){
                    $select->where("$atri = ?", $value);
                }
                
                if($orders){
                    foreach ($orders as $key => $order) {
                        $select->order($order);
                    }
                }
                $results = $select->query();
                $rows = $results->fetchAll();
                if ($rows) return $rows;
                return false;
        }catch (Exception $e){
            print "Error: Read Filter Courses Teacher ".$e->getMessage();
        }
    }


    public function _getinfoTeacher($where=null,$attrib=null,$order=null){
        try {
            if ($where=='' && $attrib=='' ) return false;
                $base_teacher = new Api_Model_DbTable_Users();
                $data_teacher = $base_teacher->_getinfoUser($where,$attrib,$order);
            if($data_teacher) return $data_teacher;
            return false;
        } catch (Exception $e) {
            print "Error: Read info Course ";

        }
    }


    public function _getinfoasisstance($where=null){
        try{
            if ($where['eid']=='' || $where['oid']=='' || $where['perid']=='' || $where['curid']=="" ||
                 $where['escid']=="" || $where['subid']=="" || $where['coursoid']=='' || $where['turno']=='') return false;
            $select = $this->_db->select()
            ->from(array('p' => 'base_person'),array('p.last_name0','p.last_name1','p.first_name'))
                ->join(array('rc' => 'base_student_assistance'),'rc.pid=p.pid and p.eid=rc.eid', array('rc.*'))
                ->where('rc.eid = ?', $where['eid'])->where('rc.oid = ?', $where['oid'])
                ->where('rc.subid = ?', $where['subid'])->where('rc.escid = ?', $where['escid'])
                ->where('rc.curid = ?', $where['curid'])->where('rc.perid = ?', $where['perid'])
                ->where('rc.coursoid = ?', $where['courseid'])->where('rc.turno = ?', $where['turno'])
                ->order('p.last_name0');
            $results = $select->query();            
            $rows = $results->fetchAll();
            if($rows) return $rows;
            return false; 
        }  catch (Exception $ex){
            print "Error: lista Docentes  ".$ex->getMessage();
        }

    }


    public function _assistence($where=null){
        try{
            $sql = $this->_db->query("
select perid, a.curid,a.coursoid,c.name,turno,a_sesion_1,a_sesion_2,a_sesion_3,a_sesion_4,a_sesion_5,a_sesion_6,a_sesion_7,a_sesion_8,a_sesion_9,a_sesion_10,a_sesion_11,a_sesion_12,a_sesion_13,a_sesion_14,a_sesion_15,a_sesion_16,a_sesion_17,
a_sesion_18,a_sesion_19,a_sesion_20,a_sesion_21,a_sesion_22,a_sesion_23,a_sesion_24,a_sesion_25,a_sesion_26,a_sesion_27,a_sesion_28,a_sesion_29,a_sesion_30,a_sesion_31,a_sesion_32,a_sesion_33,a_sesion_34 from base_student_assistance  a 
inner join base_courses c
on a.eid=c.eid and  a.oid=c.oid  and a.escid=c.escid  and  a.curid=c.curid and  a.coursoid=c.courseid 
where uid='".$where['uid']."' and  a.escid='".$where['escid']."' and pid='".$where['pid']."' and a.perid='".$where['perid']."'
                    "); 
            if ($sql) return $sql->fetchAll();
            return false;
        }  catch (Exception $ex){
                        print "Error: Obteniendo datos de tabla 'Matricula Curso'".$ex->getMessage();
        }
        }


}
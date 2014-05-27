
<?php

class Acreditacion_TutorshipController extends Zend_Controller_Action {

    public function init()
    {
       $sesion  = Zend_Auth::getInstance();
        if(!$sesion->hasIdentity() ){
            $this->_helper->redirector('index',"index",'default');
        }
        $login = $sesion->getStorage()->read();
        $this->sesion = $login;
    }

    public function indexAction(){

    }

    public function listteachersAction(){
    	$this->_helper->layout()->disableLayout();

    	//$server = new Eundac_Connect_openerp();

    	//DataBase
		$teacherDb  = new Api_Model_DbTable_Users();
		$personDb   = new Api_Model_DbTable_Person();
		$semesterDb = new Api_Model_DbTable_Semester();

		$eid   = $this->sesion->eid;
		$oid   = $this->sesion->oid;
		$escid = $this->sesion->escid;
		$subid = $this->sesion->subid;
		$perid = $this->sesion->period->perid;

    	$where = array(	'eid'   => $eid,
						'oid'   => $oid,
						'escid' => $escid,
						'subid' => $subid,
						'state' => 'A',
						'rid'   => 'DC' );
    	$attrib = array('uid', 'pid');

    	$teachers = $teacherDb->_getFilter($where, $attrib);
    	foreach ($teachers as $c => $teacher) {
    		$where = array(	'eid' => $eid,
    						'pid' => $teacher['pid'] );
    		$attrib = array('last_name0', 'last_name1', 'first_name');
    		$name = $personDb->_getFilter($where, $attrib);
			$teachersData[$c]['full_name'] = $name[0]['last_name0'].' '.$name[0]['last_name1'].' '.$name[0]['first_name'];
			$teachersData[$c]['uid']       = $teacher['uid'];
			$teachersData[$c]['pid']       = $teacher['pid'];

			//Consultar si ya esta asignado un docente
			$query = array(
	                      array('column'   => 'identification_id',
	                            'operator' => '=',
	                            'value'    =>  $teacher['pid'],
	                            'type'     => 'string' )
	                    );
	        $idTeacher  = $server->search('hr.employee', $query);
	        $attributes     = array('id');
	        $dataTeacher = $server->read($idTeacher, $attributes, 'hr.employee');

	        $query = array(
	                      array('column'   => 'employee_id',
	                            'operator' => '=',
	                            'value'    =>  $dataTeacher[0]['id'],
	                            'type'     => 'int' ),

	                      array('column'   => 'perid',
	                            'operator' => '=',
	                            'value'    =>  $perid,
	                            'type'     => 'string' )
	                    );
	        $idTeacher = $server->search('tutoring', $query);
	        if ($idTeacher) {
	        	$teachersData[$c]['asigned'] = 'yes';
		        $attributes = array('semid', 'number', 'id');
		        $dataTeacher = $server->read($idTeacher, $attributes, 'tutoring');
				$teachersData[$c]['semid']      = $dataTeacher[0]['semid'];
				$teachersData[$c]['number']     = $dataTeacher[0]['number'];
				$teachersData[$c]['tutoringId'] = $dataTeacher[0]['id'];
	        	$query = array(
                      array(	'column'   => 'tutoring_id',
	                            'operator' => '=',
	                            'value'    =>  $dataTeacher[0]['id'],
	                            'type'     => 'int' ),
	                    );
				$idsStudents = $server->search('tutoring.students', $query);
				if ($idsStudents) {
					$teachersData[$c]['alreadyStudents'] = 'yes';
				}else{
					$teachersData[$c]['alreadyStudents'] = 'no';
				}
	        }else{
				$teachersData[$c]['asigned'] = 'no';
				$teachersData[$c]['semid']   = '-';
	        }
    	}
    	$this->view->teachers = $teachersData;

    	$where = array(	'eid'   => $eid,
						'oid'   => $oid,
						'escid' => $escid,
						'perid' => $perid );
    	$semesters = $semesterDb->_getSemesterXPeriodsXEscid($where);
    	$this->view->semesters = $semesters;
    }

    public function listasignedteacherAction(){
    	$this->_helper->layout()->disableLayout();
    	$server = new Eundac_Connect_openerp();

    	$escid = $this->sesion->escid;
    	$perid = $this->sesion->period->perid;

    	//Sacando el Departmentid
    	$query = array(
                     	array(	'column'   => 'of_id',
	                            'operator' => '=',
	                            'value'    =>  $escid,
	                            'type'     => 'string' )
	                    );

    	$idDepartment = $server->search('hr.department', $query);
    	$attributes = array('id');
    	$department = $server->read($idDepartment, $attributes, 'hr.department');
    	
    	//Sacando id's de los tutores por escuela
    	$query = array(
                      	array(	'column'   => 'department_id',
	                            'operator' => '=',
	                            'value'    =>  $department[0]['id'],
	                            'type'     => 'int' ),

                      	array(	'column'   => 'perid',
	                            'operator' => '=',
	                            'value'    =>  $perid,
	                            'type'     => 'string' )
	                    );
    	$idsTutoring = $server->search('tutoring', $query);
    	$attributes = array('employee_id', 'semid', 'number');
    	$tutorings = $server->read($idsTutoring, $attributes, 'tutoring');
    	if ($tutorings) {
	    	foreach ($tutorings as $c => $tutor) {
	    		//Data del susodicho
	    		if ($tutor['semid'] == 1) {
	    			$semestre = 'I';
	    		}elseif ($tutor['semid'] == 2){
	    			$semestre = 'II';
	    		}elseif ($tutor['semid'] == 3){
	    			$semestre = 'III';
	    		}elseif ($tutor['semid'] == 4){
	    			$semestre = 'IV';
	    		}elseif ($tutor['semid'] == 5){
	    			$semestre = 'V';
	    		}elseif ($tutor['semid'] == 6){
	    			$semestre = 'VI';
	    		}elseif ($tutor['semid'] == 7){
	    			$semestre = 'VII';
	    		}elseif ($tutor['semid'] == 8){
	    			$semestre = 'VIII';
	    		}elseif ($tutor['semid'] == 9){
	    			$semestre = 'IX';
	    		}elseif ($tutor['semid'] == 10){
	    			$semestre = 'X';
	    		}elseif ($tutor['semid'] == 11){
	    			$semestre = 'XI';
	    		}elseif ($tutor['semid'] == 12){
	    			$semestre = 'XII';
	    		}
				$teachersAsigned[$c]['semestre']  = $semestre.' Semestre';
				$teachersAsigned[$c]['number']    = $tutor['number'];
				$teachersAsigned[$c]['full_name'] = utf8_encode($tutor['employee_id'][1]);
	    	}
	    	$this->view->teachersAsigned = $teachersAsigned;
    	}
    }

    public function cantstudentsxsemAction(){
    	$this->_helper->layout()->disableLayout();
    	//dataBases
    	$registerDb = new Api_Model_DbTable_Registration();

    	$semid = $this->_getParam('semid');

    	if ($semid) {
	    	$eid = $this->sesion->eid;
	    	$oid = $this->sesion->oid;
	    	$perid = $this->sesion->period->perid;
	    	$escid = $this->sesion->escid;
	    	$subid = $this->sesion->subid;

	    	$where = array(	'eid'   => $eid,
							'oid'   => $oid,
							'perid' => $perid,
							'semid' => $semid,
							'escid' => $escid,
							'subid' => $subid,
							'state' => 'M');
	    	$attrib = array('uid');
	    	$students = $registerDb->_getFilter($where, $attrib);
	    	$cantStudents = count($students);
    	}else{
    		$cantStudents = '-';
    	}

    	echo $cantStudents;
    }

    public function asignardocenteAction(){
    	$this->_helper->layout()->disableLayout();

    	$server = new Eundac_Connect_openerp();

    	$escid = $this->sesion->escid;
    	$perid = $this->sesion->period->perid;
		$nameSpeciliaty = $this->sesion->speciality->name;
    	$formData = $this->getRequest()->getPost();

    	if ($formData['semid'] != '' and is_numeric($formData['cantStudents']) == true) {
	    	$pid = $formData['pid'];

	    	//Id de Empleado
	    	$query = array(
	                          array('column'   => 'identification_id',
	                                'operator' => '=',
	                                'value'    =>  $pid,
	                                'type'     => 'string' )
	                        );
	        $idTeacher  = $server->search('hr.employee', $query);
	        $attributes     = array('id');
	        $dataTeacher = $server->read($idTeacher, $attributes, 'hr.employee');

	        //Id de Departamento
	        $query = array(
	        				array(	'column'   => 'of_id',
									'operator' => '=',
									'value'    => $escid,
									'type'     => 'string')
	        	);

	        $idDepartment = $server->search('hr.department', $query);
	        $dataDepartment = $server->read($idDepartment, $attributes, 'hr.department');

	        $dataTutoring = array(	'employee_id'   => $dataTeacher[0]['id'],
									'number'        => $formData['cantStudents'],
									'department_id' => $dataDepartment[0]['id'],
									'semid'         => $formData['semid'],
									'name'          => 'Tutoria '.$nameSpeciliaty.' '.$formData['semid']. 'Semestre',
									'perid'         => $perid );

	        $create = $server->create('tutoring', $dataTutoring);
	        if ($create) {
	        	$result = array('tutoringId' =>	$create,
								'success'    => 2);
	        }else{
	        	$result = array('tutoringId' =>	'',
								'success'    => 2);
            }
            print json_encode($result);
    	}else{
    		echo '0-1';
    	}

    }

    public function editasignaciondocenteAction(){
    	$this->_helper->layout()->disableLayout();

    	$server = new Eundac_Connect_openerp();

    	$formData = $this->getRequest()->getPost();
    	if (is_numeric($formData['cantStudents']) == true) {
	        if ($formData['semid']) {
	    		$data = array(	'number' => $formData['cantStudents'],
								'semid'  => $formData['semid'] );
	        }else{
	        	$data = array(	'number' => $formData['cantStudents'] );
	        }
			$idTutoring[0] = $formData['tutoringId'];
			$update = $server->write('tutoring', $data, $idTutoring);
			if ($update == 1) {
				$result = array('success' => 1);
			}else{
				$result = array('success' => 2);
			}
            print json_encode($result);
    	}else{
    		echo 0;
    	}

    }

    public function desasignedteacherAction(){
    	$this->_helper->layout()->disableLayout();

    	$server = new Eundac_Connect_openerp();
    	$tutoringId = $this->_getParam('tutoringid');
    	$tId[0] = $tutoringId;
        $deleteTutoring = $server->unlink($tId, 'tutoring');
        if ($deleteTutoring == 1) {
        	echo 1;
        }
    }

    public function reportteacherAction(){
    	$server = new Eundac_Connect_openerp();

    	//dataBases
		$pid   = $this->sesion->pid;
		$perid = $this->sesion->period->perid;

    	//id del Empleado
    	$query = array(
                      array('column'   => 'identification_id',
                            'operator' => '=',
                            'value'    =>  $pid,
                            'type'     => 'string' )
                    );
		
		$idEmployee  = $server->search('hr.employee', $query);
		$attributes  = array('id');
		$dataTeacher = $server->read($idEmployee, $attributes, 'hr.employee');

    	//Preguntar si esta asignado a alguna tutoria
    	$query = array(
                      array('column'   => 'employee_id',
                            'operator' => '=',
                            'value'    =>  $dataTeacher[0]['id'],
                            'type'     => 'int' ),

                      array('column'   => 'perid',
                            'operator' => '=',
                            'value'    =>  $perid,
                            'type'     => 'string' ),
                    );
		$idTutoring   = $server->search('tutoring', $query);

		$sendDataTutoring = '';
		$sendDataStudents = '';
		if ($idTutoring) {
			$attributes   = array('employee_id', 'name', 'id', 'number');
			$dataTutoring = $server->read($idTutoring, $attributes, 'tutoring');
			//print_r($dataTutoring);
			
			$sendDataTutoring['id']         = $dataTutoring[0]['id'];
			$sendDataTutoring['name']       = $dataTutoring[0]['name'];
			$sendDataTutoring['tutor_name'] = $dataTutoring[0]['employee_id'][1];
			$sendDataTutoring['number'] = $dataTutoring[0]['number'];
			$sendDataTutoring['cant_register'] = 0;

			//Alumnos Matriculados
			$query = array(
                      array('column'   => 'tutoring_id',
                            'operator' => '=',
                            'value'    =>  $sendDataTutoring['id'],
                            'type'     => 'int' ),
                    );
			$idsStudents = $server->search('tutoring.students', $query);
			$attributes = array('name', 'id', 'registered_code', 'f_atention', 'resumen', 'motivo');
			$dataStudents = $server->read($idsStudents, $attributes, 'tutoring.students');
			if ($dataStudents) {
				$sendDataTutoring['cant_register'] = count($dataStudents);
				foreach ($dataStudents as $c => $student) {
					$sendDataStudents[$c]['id']         = $student['id'];
					$sendDataStudents[$c]['full_name']  = utf8_encode($student['name']);
					$sendDataStudents[$c]['uid']        = $student['registered_code'];
					if ($student['f_atention']) {
						$f_atention = date('d-m-Y', strtotime($student['f_atention']));
					}else{
						$f_atention = '';
					}
					$sendDataStudents[$c]['f_atention'] = $f_atention;
					$sendDataStudents[$c]['resumen']    = $student['resumen'];
					$sendDataStudents[$c]['motivo']     = $student['motivo'];

				}
			}
		}
		//print_r($sendDataStudents);

		$this->view->dataTutoring = $sendDataTutoring;
		$this->view->dataStudents = $sendDataStudents;
	}

	public function saveatentionAction(){
		$this->_helper->layout()->disableLayout();

		$server = new Eundac_Connect_openerp();

		$formData = $this->getRequest()->getPost();

		if ($formData['FechaAtencion'] != '' and $formData['Resumen'] != '' and $formData['Motivo'] != '') {

			$fecha =  date('Y-m-d', strtotime($formData['FechaAtencion']));
			//print_r($fecha);
			$data = array(	'f_atention' => date('Y-m-d', strtotime($formData['FechaAtencion'])),
							'resumen'    => utf8_encode($formData['Resumen']),
							'motivo'     => utf8_encode($formData['Motivo']) );
			$idStudent[0] = $formData['idStudent'];
			$update = $server->write('tutoring.students', $data, $idStudent);
			if ($update == 1) {
				echo 1;
			}else{
				echo 0;
			}
		}else{
			echo 2;
		}
	}

	public function infotutoriaAction(){
		try {
			$this->_helper->layout()->disableLayout();
		} catch (Exception $e) {
			print "Error: ".$e->getMessage();
		}
	}

}

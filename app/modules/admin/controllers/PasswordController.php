<?php

class Admin_PasswordController extends Zend_Controller_Action 
{
    public function init() 
    {

      $this->eid='20154605046';
      $this->oid='1';
    }
    
    public function indexAction() 
    {
        $this->_helper->redirector("search");
    }

    public function searchAction()
    {
        try
        {
            $eid = $this->sesion->eid;
            $oid = $this->sesion->oid;
            $fm=new Admin_Form_Password();
            $this->view->fm=$fm;
        }
        catch (Exception $ex) 
        {
          print $ex->getMessage();
        }
    }

    public function listAction() 
    {
        try 
        {   
             $this->_helper->layout()->disableLayout();
             $eid = $this->sesion->eid;
             $oid = $this->sesion->oid;
             $rid=$this->_getParam('rol');
             $uid=$this->_getParam('uid');
             $nom=$this->_getParam('nom');
             $nombre=$this->_getParam('nombre');
             $data['eid']= $this->eid;
             $data['oid']= $this->oid;
             $data['uid']= $uid;

            if ($rid=='docente')
            {
              $rid='DC';
            }
            if ($rid=='alumno')
            {
              $rid='AL';
            }
             if ($rid=='otros')
            {                             
              $rid='';
            }


             $nom=mb_strtoupper($nom, 'UTF-8');
             $bdu = new Api_Model_DbTable_Users ();
            if ($rid<>'') {
                if ($uid=='' && $nom<>'')
                {
                    $datos = $bdu->_getUsuarioXNombre(strtoupper($nom),$rid,$eid,$oid);
                    //print_r($datos);
                }
                if ($uid<>'' && $nom=='')
                {
                    
                    $data['rid']= $rid;
                    $datos = $bdu->_getUserXRidXUid($data);
                                        
                }
                $this->view->datos=$datos;
            }
            else{

                //echo ("Debe seleccionar Docente o Alumno");
                if ($uid=='' && $nom<>'')
                {
                    $datos = $bdu->_getUsuarioXNombreXsinRol(strtoupper($nom),$eid,$oid);
                    
                } 

                if ($uid<>'' && $nom=='')
                {
                    $datos = $bdu->_getUserXUid($data);
                }
                $this->view->datos=$datos;                

            }

        }
        catch (Exception $ex) 
        {
          print "Error Buscando Usuario deacuerdo a su coidgo o nombre: ".$ex->getMessage();
        }
    }

    public function keychangeAction() 
    {
        try 
        {
          $eid = $this->_getParam("eid");
          $oid = $this->_getParam("oid");
           $uid = $this->_getParam("uid");
           $escid = $this->_getParam("escid");
           $pid = $this->_getParam("pid");
           $subid = $this->_getParam("subid");
            $fm=new Admin_Form_Keychange();
            $fm->guardar->setLabel("Guardar");
            $this->view->fm=$fm;

             if ($this->getRequest()->isPost())
             {
                $formData = $this->getRequest()->getPost();
                //print_r($formData);break;
                 unset($formData["enviar"]);
                if (($formData["ncla"]==($formData["rcla"])) && ($formData["ncla"]<>"" || $formData["rcla"]<>""))
                {
                    $data['password']=$formData["ncla"];
                    $pk['uid']=$uid;
                    $pk['eid']=$eid;
                    $pk['oid']=$oid;
                    $pk['pid']=$pid;
                    $pk['escid']=$escid;
                    $pk['subid']=$subid;
                    $bdu = new Api_Model_DbTable_Users();
                    //print($uid.$pid.$eid.$oid.$escid.$pass);
                    $datos = $bdu->_update($data,$pk);           
                    if ($datos)
                    {
                        $this->view->mensaje="Guardo";
                        echo "
                        <div class='alert alert-error' style='display:block'>
                        <button type='button' class='close' data-dismiss='alert'>x</button>
                        <strong>Contraseña Guardada Correctamente</strong>
                        .</spam>";
                           
                        $this->_helper->redirector("search");
                    }
                }
                else
                {
                    $this->view->mensaje="Contraseñas no cinciden";
                    /*echo "
                    <div class='alert alert-error'>
                            <button type='button' class='close' data-dismiss='alert'>x</button>
                            <strong>Contraseñas no Coinciden</strong>
                            </div>";*/
                }
            }
        }
        catch (Exception $ex) 
        {
            print "Error al momento de modificar la clave de Usuario: ".$ex->getMessage();
        }
    }
}
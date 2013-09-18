<?php
class Profile_Form_Userinfo extends Zend_Form{    

    public function init(){
        
        $dni= new Zend_Form_Element_Text('numdoc');
        $dni->removeDecorator('Label')->removeDecorator("HtmlTag")->removeDecorator("Label");
        $dni->setAttrib("maxlength", "8")->setAttrib("size", "10");
        $dni->setRequired(true)->addErrorMessage('Este campo es requerido');
        $dni->setAttrib("title","DNI");
        $dni->setAttrib("class","input-sm");

        $birthday= new Zend_Form_Element_Text("birthday");
        $birthday->removeDecorator('Label')->removeDecorator("HtmlTag")->removeDecorator("Label");
        $birthday->setAttrib("maxlength", "10")->setAttrib("size", "10");
        $birthday->setRequired(true)->addErrorMessage('Este campo es Obligatorio');
        $birthday->setAttrib("title","Birthday");
        $birthday->setAttrib("class","input-sm");

        $sex = new Zend_Form_Element_Select('sex');
        $sex->removeDecorator('HtmlTag')->removeDecorator('Label');     
        $sex->setRequired(true)->addErrorMessage('Es necesario que selecciones el estado.');
        $sex->addMultiOption("F","Femenino");
        $sex->addMultiOption("M","Masculino");
        $sex->setAttrib("class","form-control");

        $civil = new Zend_Form_Element_Select('civil');
        $civil->removeDecorator('HtmlTag')->removeDecorator('Label');     
        $civil->setRequired(true)->addErrorMessage('Es necesario que selecciones el estado.');
        $civil->addMultiOption("S","Soltero/a");
        $civil->addMultiOption("C","Casado/a");
        $civil->addMultiOption("D","Divorciado/a");
        $civil->addMultiOption("V","Viudo/a");
        $civil->setAttrib("class","form-control");

        $mail_person= new Zend_Form_Element_Text("mail_person");
        $mail_person->removeDecorator('Label')->removeDecorator("HtmlTag")->removeDecorator("Label");
        $mail_person->setAttrib("maxlength", "50")->setAttrib("size", "30");
        $mail_person->setRequired(true)->addErrorMessage('Este campo es Obligatorio');
        $mail_person->setAttrib("title","Email");
        $mail_person->setAttrib("class","input-sm");

        $mail_work= new Zend_Form_Element_Text("mail_work");
        $mail_work->removeDecorator('Label')->removeDecorator("HtmlTag")->removeDecorator("Label");
        $mail_work->setAttrib("maxlength", "50")->setAttrib("size", "30");
        $mail_work->setRequired(true)->addErrorMessage('Este campo es Obligatorio');
        $mail_work->setAttrib("title","Email Work");
        $mail_work->setAttrib("class","input-sm");

        $phone= new Zend_Form_Element_Text("phone");
        $phone->removeDecorator('Label')->removeDecorator("HtmlTag")->removeDecorator("Label");
        $phone->setAttrib("maxlength", "10")->setAttrib("size", "30");
        $phone->setRequired(true)->addErrorMessage('Este campo es Obligatorio');
        $phone->setAttrib("title","Phone");
        $phone->setAttrib("class","input-sm");

        $cellular= new Zend_Form_Element_Text("cellular");
        $cellular->removeDecorator('Label')->removeDecorator("HtmlTag")->removeDecorator("Label");
        $cellular->setAttrib("maxlength", "9")->setAttrib("size", "30");
        $cellular->setRequired(true)->addErrorMessage('Este campo es Obligatorio');
        $cellular->setAttrib("title","Cellular");
        $cellular->setAttrib("class","input-sm");

        $submit = new Zend_Form_Element_Submit('save');
        $submit->setAttrib('class','btn btn-info pull-right');
        $submit->setLabel('Guardar');
        $submit->removeDecorator("HtmlTag")->removeDecorator("Label");
        
        $this->addElements(array($dni, $birthday, $sex, $civil, $mail_person, $mail_work, $phone, $cellular, $submit));
    }
}
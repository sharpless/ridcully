<?php
    /**
    * A form for editing the user profile.
    *
    * @package RidcullyCore
    */
    class CFormUserProfile extends CForm {

      /**
       * Constructor
       */
      public function __construct($object, $user) {
        parent::__construct();
        $this->AddElement(new CFormElementText('acronym', array('readonly'=>true, 'value'=>$user->profile->acronym)))
             ->AddElement(new CFormElementPassword('password'))
             ->AddElement(new CFormElementPassword('password1', array('label'=>'Password again:')))
             ->AddElement(new CFormElementSubmit('change_password', array('callback'=>array($object, 'DoChangePassword'))))
             ->AddElement(new CFormElementText('name', array('value'=>$user->profile->name, 'required'=>true)))
             ->AddElement(new CFormElementText('email', array('value'=>$user->profile->email, 'required'=>true)))
             ->AddElement(new CFormElementSubmit('save', array('callback'=>array($object, 'DoProfileSave'))));
      }

    }
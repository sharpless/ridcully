<?php
/**
* A user controller to manage content.
*
* @package RidcullyCore
*/
class CCContent extends CObject implements IController {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function Index() {
        $content = new CMContent();
        $this->views->SetTitle('Content Controller');
        $this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
                        'contents' => $content->ListAll(),
                    ), 'primary');
    }
    
    public function Edit($id = null) {
        if ($this->user["hasRoleUser"] == false) {
            $this->RedirectToController();
        }
        $content = new CMContent($id);
        $form = new CFormContent($content);
        $status = $form->Check();
        if($status === false) {
            $this->AddMessage('notice', 'The form could not be processed.');
            $this->RedirectToController('edit', $id);
        } else if($status === true) {
            $this->RedirectToController('edit', $content['id']);
        }
        
        $title = isset($id) ? 'Edit' : 'Create';
        $this->views->SetTitle("$title content: $id");
        $this->views->AddInclude(__DIR__ . '/edit.tpl.php', array(
                        'user'=>$this->user, 
                        'content'=>$content,
                        'form'=>$form,
                    ), 'primary');
    }
    
    public function Create() {
        $this->Edit();
    }
    
    public function Init() {
        if ($this->user["hasRoleUser"] == false) {
            $this->RedirectToController();
            exit;
        }
        $content = new CMContent();
        $content->Init();
        $this->RedirectToController();            
    }
}
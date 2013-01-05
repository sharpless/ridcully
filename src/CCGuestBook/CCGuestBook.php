<?php

/**
 * A guestbook using Ridcully
 * 
 * @package RidcullyCore
 */

class CCGuestBook extends CObject implements IController {

  private $guestbookModel;  


  public function __construct() {
    parent::__construct();
    $this->guestbookModel = new CMGuestBook();
  }
  
    public function Index() {
    $this->views->SetTitle("Gästbok");
    $this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
        'posts'=>  $this->guestbookModel->ReadAll(),
        'formAction' => $this->request->CreateUri('guestbook/post')
    ), 'primary');

  }

  public function Post() {
    if (isset($_POST['submit'])) {
      if (isset($_POST['author']) && isset($_POST['message'])
              && isset($_POST['password']) && $_POST['password'] == '13') {
        $author = htmlentities($_POST['author'], ENT_QUOTES, 'UTF-8');
        $message = htmlentities($_POST['message'], ENT_QUOTES, 'UTF-8');
        $this->guestbookModel->Add($message, $author);
      }
    } elseif (isset ($_POST['clear'])) {
      $this->guestbookModel->DeleteAll();
    } elseif (isset ($_POST['create'])) {
      $this->guestbookModel->Init();
    }

    header('Location: ' . $this->request->CreateUri('guestbook'));

  }
  
}
?>

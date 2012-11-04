    <?php
    /**
    * Standard controller layout.
    *
    * @package RidcullyCore
    */
    class CCIndex implements IController {

       /**
        * Implementing interface IController. All controllers must have an index action.
        */
       public function Index() {   
          global $r;
          $r->data['title'] = "The Index Controller";
          $r->data['main'] = "This is me, and I'm Ridcully";
          $r->data['header'] = "<h1>Ridcully, the best</h1>";
       }

    } 
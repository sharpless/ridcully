    <?php
    /**
    * A container to hold a bunch of views.
    *
    * @package RidcullyCore
    */
    class CViewContainer {

       /**
        * Members
        */
       private $data = array();
       private $views = array();
       

       /**
        * Constructor
        */
       public function __construct() { ; }


       /**
        * Getters.
        */
      public function GetData() { return $this->data; }
     
     
       /**
        * Set the title of the page.
        *
        * @param $value string to be set as title.
        */
       public function SetTitle($value) {
         $this->SetVariable('title', $value);
         return $this;
      }


       /**
        * Set any variable that should be available for the theme engine.
        *
        * @param $value string to be set as title.
        */
       public function SetVariable($key, $value) {
         $this->data[$key] = $value;
         return $this;
      }

      public function AddStyle($style)
      {
        if (isset($this->data['style'])) {
          $this->data['style'] .= "{$style}\n";
        } else {
          $this->data['style'] = "{$style}\n";
        }
        return $this;
      }
      /**
       * Add a view as file to be included and optional variables
       * 
       * @param string $file      path to the file that is to be included
       * @param array  $variables contains variables available for the included file
       * @param string $region    set where to print the content
       *
       * @return object $this
       */
       public function AddInclude($file, $variables=array(), $region='default') {
         $this->views[$region][] = array('type' => 'include', 'file' => $file, 'variables' => $variables);
         return $this;
      }
      /**
       * Add a view as string and optional variables
       * @param string $string    content to be included
       * @param array  $variables contains variables available for the string
       * @param string $region    set where to print the content
       */
      public function AddString($string, $variables=array(), $region='default')
      {
        $this->views[$region][] = array('type'=>'string', 'string' => $string, 'variables' => $variables);
        return $this;
      }

      /**
       * Check if there exists content to be displayed in the specified region(s)
       * 
       * @param string/array $region the region(s) to be checked
       * @return boolean true if something to display, else false
       */
      public function RegionHasView($region)
      {
        if (is_array($region)) {
          foreach ($region as $val) {
            if (isset($this->views[$val])) {
              return true;
            }
          }
          return false;
        } else {
          return (isset($this->views[$region]));
        }
      }

      /**
       * Render all views
       * @param string $region set where to print the content
       */
      public function Render($region='default') {
        if(empty($this->views[$region])) return;
        foreach($this->views[$region] as $view) {
          switch($view['type']) {
            case 'include':
              extract($view['variables']);
              include($view['file']);
              break;
            case 'string':
              extract($view['variables']); 
              echo $view['string'];
              break;
          }
         }
      }

    }
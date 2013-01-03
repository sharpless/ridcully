    <?php
    /**
    * A test controller for themes.
    *
    * @package RidcullyCore
    */
    class CCTheme extends CObject implements IController {


      /**
       * Constructor
       */
      public function __construct() { parent::__construct(); }


      /**
       * Display what can be done with this controller.
       */
      public function Index() {
        $this->views->SetTitle('Theme');
        $this->views->AddInclude(__DIR__ . '/index.tpl.php', array(
                      'theme_name' => $this->config['theme']['name'],
                    ));
      }

      public function SomeRegions()
      {
        $this->views->SetTitle('Some Regions')
                    ->AddString('This is the primary region, now with a <a href="http://dbwebb.se">link</a>', array(), 'primary')
                    ->AddStyle('#primary { background-color: #aaf; }');
        if (func_num_args()) {
          foreach (func_get_args() as $val) {
            $this->views->AddString("This is region: {$val}", array(), $val)
                        ->AddStyle("#{$val} { background-color: #aaf; }");
          }
        }
      }
      public function AllRegions()
      {
        $this->views->SetTitle('All Regions');
        foreach ($this->config['theme']['regions'] as $val) {
          $this->views->AddString("This is region: {$val}", array(), $val)
                      ->AddStyle("#{$val} { background-color: #aaf; }");
        }

      }
     /**
      * Display text as h1h6 and paragraphs with some inline formatting.
      */
        public function H1H6() {
          $this->views->SetTitle('Theme testing headers and paragraphs')
                      ->AddInclude(__DIR__ . '/h1h6.tpl.php', array(), 'primary');
        }
}
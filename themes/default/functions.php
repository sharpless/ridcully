    <?php
    /**
    * Helpers for the template file.
    */
    $r->data['header'] = empty($r->data['header']) ? "<h1>My name is Ridcully</h1>" : $r->data['header'];
    $r->data['footer'] = empty($r->data['footer']) ? "<p>Footer: &copy; Ridcully by Fredrik Larsson</h1>" : $r->data['footer'];


    /**
    * Print debuginformation from the framework.
    */
    function get_debug() {
      $r = CRidcully::Instance();
      if (!$r->config['debug']) {
        return '';
      }
      $html = "<h2>Debuginformation</h2><hr><p>The content of the config array:</p><pre>" . htmlentities(print_r($r->config, true)) . "</pre>";
      $html .= "<hr><p>The content of the data array:</p><pre>" . htmlentities(print_r($r->data, true)) . "</pre>";
      $html .= "<hr><p>The content of the request array:</p><pre>" . htmlentities(print_r($r->request, true)) . "</pre>";
      return $html;
    }
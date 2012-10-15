<?php

require_once 'lib/zero/core/Template.php';

use zero\core\Template;

class TemplateTest extends PHPUnit_Framework_TestCase {

  public function testDisplay() {
    $template = new Template('test/template/foo.php');
    $template->class = "red";

    ob_start();
    $template->display();
    $output = ob_get_contents();
    ob_end_clean();

    $this->assertEquals('<div class="red">foo</div>'."\n", $output);
  }
}

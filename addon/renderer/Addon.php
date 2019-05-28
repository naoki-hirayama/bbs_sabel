<?php

/**
 * Renderer_Addon
 *
 * @category   Addon
 * @package    addon.renderer
 * @author     Ebine Yutaka <ebine.yutaka@sabel.jp>
 * @copyright  2004-2008 Mori Reo <mori.reo@sabel.jp>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 */
class Renderer_Addon extends Sabel_Object implements Sabel_Addon
{
  const VERSION = 1.0;

  public function execute(Sabel_Bus $bus)
  {
    $bus->insertProcessor("router", new Renderer_Processor("renderer"), "after");
  }
}

class Renderer_Processor extends Sabel_Bus_Processor
{
  public function execute(Sabel_Bus $bus)
  {
    // Index moduleの場合はSabelデフォルトのRendererを使う
    $renderer = new Renderer_Sabel();
    // if ($bus->get("destination")->getModule() === "index") {
    //   $renderer = new Renderer_Sabel();
    // } else {
    //   require_once RUN_BASE . "/vendor/autoload.php";
    //   $renderer = new Renderer_Twig();
    // }

    if ($renderer->hasMethod("initialize")) {
      $renderer->initialize();
    }

    $bus->set("renderer", $renderer);
  }
}

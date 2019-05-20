<?php

class Renderer_Twig extends Sabel_View_Renderer
{
  public function rendering($template, $values, $path = null)
  {
    $loader = new Twig_Loader_Filesystem(array(
      dirname($path),
      RUN_BASE . '/app/admin/views',
      RUN_BASE . '/app/views',
    ));

    $twig = new Twig_Environment($loader, array(
      'cache'       => RUN_BASE . '/cache/twig',
      'auto_reload' => true,
    ));

    $twig->addFunction('uri',      new Twig_Function_Function('uri'));
    $twig->addFunction('linkto',   new Twig_Function_Function('linkto'));
    $twig->addFunction('mb_image', new Twig_Function_Function('mb_image'));
    $twig->addFunction('pc_image', new Twig_Function_Function('pc_image'));

    return $twig->render(basename($path), $values);
  }
}

class Renderer_Twig_Extension extends Twig_Extension
{
  public function getFilters()
  {
    return array(
      'uri'    => new Twig_Filter_Method($this, 'uri'),
      'linkto' => new Twig_Filter_Method($this, 'linkto'),
    );
  }

  public function uri($value)
  {
    return uri($value);
  }

  public function linkto($value)
  {
    return linkto($value);
  }

  public function getName()
  {
    return 'hamaco';
  }
}

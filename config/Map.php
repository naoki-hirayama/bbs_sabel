<?php

class Config_Map extends Sabel_Map_Configurator
{
  public function configure()
  {
        //   if (is_cli() === true) {
        //       $this->route("batch")
        //           ->uri("batch/:controller/:action/:param")
        //           ->module("batch")
        //           ->defaults(array(
        //               "param" => null,
        //           ));
        //   }

        // $this->route("login")->uri("login/:action")->module("index")->controller("login")->defaults(array(":action" => "index"));
        // $this->route("logout")->uri("logout")->module("index")->controller("login")->action("logout");

          $this->route("default")
              ->uri(":controller/:action/:param")
              ->module("index")
              ->defaults(array(
                  ":controller" => "index",
                  ":action"     => "index",
                  "param"       => null,
              ));

        // $this->route("default")
        //     ->uri(":controller/:action")
        //     ->module("index")
        //     ->defaults(array(
        //         ":controller" => "index",
        //         ":action"     => "index",
        //     ));


      $this->route("notfound")->uri("*")->module("index")->controller("index")->action("notFound");
  }
}

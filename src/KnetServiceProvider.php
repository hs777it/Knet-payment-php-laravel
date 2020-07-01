<?php

namespace Mgcoder\Knet;

use Illuminate\Support\ServiceProvider;

class KnetServiceProvider extends ServiceProvider
{

  public function boot()
  {
    $this->loadRoutesFrom(__DIR__.'/routes/knet.php');

    $this->publishes([
        __DIR__.'/config/knet.php' => config_path('knet.php'),
    ]);
  }

  public function register()
  {}
}
?>

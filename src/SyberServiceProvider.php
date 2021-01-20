<?php


namespace Kataki\Syber_pay;

use Illuminate\Support\ServiceProvider;

class SyberServiceProvider extends ServiceProvider
{
///boot 
	public function boot(){
			$this->loadRoutesFrom(__DIR__.'/route/api.php');
			$this->loadViewsFrom(__DIR__.'/views', 'syber-pay');
			$this->loadMigrationsFrom(__DIR__.'/database/migrations');
			$this->publishes([
        __DIR__.'/resources' => public_path('vendor/syber-pay'),
         __DIR__.'/config/Syber_pay.php' => config_path('Syber_pay.php'),
    ], 'public');
	$this->mergeConfigFrom(
        __DIR__.'/config/Syber_pay.php', 'syber-pay'
    );



	}


///register 
	public function register(){

		
	}	







	
}
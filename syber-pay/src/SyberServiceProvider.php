<?php


namespace Kataki\Syber_pay;

use Illuminate\Support\ServiceProvider;

class SyberServiceProvider extends ServiceProvider
{
///boot 
	public function boot(){
			$this->loadRoutesFrom(__DIR__.'/route/web.php');
			$this->loadViewsFrom(__DIR__.'/views', 'syber-pay');
			$this->loadMigrationsFrom(__DIR__.'/database/migrations');



	}


///register 
	public function register(){

		
	}	

///
public function test($test){
	return $test;
}	





	
}
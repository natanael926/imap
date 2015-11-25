<?php namespace NCrousset\Imap;

use Illuminate\Support\ServiceProvider;
use NCrousset\Imap\Imap;

class ImapServiceProvider extends ServiceProvider 
{
	public function register()
	{
		$this->app->singleton('NCrousset\Imap\Imap', function ($app) {
            return new Imap();
        });
	}
}
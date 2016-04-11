<?php namespace Sanatorium\Status\Providers;

use Cartalyst\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class StatusServiceProvider extends ServiceProvider {

	/**
	 * {@inheritDoc}
	 */
	public function boot()
	{
		// Register the attributes namespace
		$this->app['platform.attributes.manager']->registerNamespace(
			$this->app['Sanatorium\Status\Models\Status']
		);

		// Subscribe the registered event handler
		$this->app['events']->subscribe('sanatorium.status.status.handler.event');

		// Register status as status
        AliasLoader::getInstance()->alias('Status', 'Sanatorium\Status\Models\Status');  
	}

	/**
	 * {@inheritDoc}
	 */
	public function register()
	{
		// Register the repository
		$this->bindIf('sanatorium.status.status', 'Sanatorium\Status\Repositories\Status\StatusRepository');

		// Register the data handler
		$this->bindIf('sanatorium.status.status.handler.data', 'Sanatorium\Status\Handlers\Status\StatusDataHandler');

		// Register the event handler
		$this->bindIf('sanatorium.status.status.handler.event', 'Sanatorium\Status\Handlers\Status\StatusEventHandler');

		// Register the validator
		$this->bindIf('sanatorium.status.status.validator', 'Sanatorium\Status\Validator\Status\StatusValidator');
	
		// Register the manager
        $this->bindIf('sanatorium.status.manager', 'Sanatorium\Status\Repositories\StatusableRepository');
	}

}

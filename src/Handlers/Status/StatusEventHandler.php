<?php namespace Sanatorium\Status\Handlers\Status;

use Illuminate\Events\Dispatcher;
use Sanatorium\Status\Models\Status;
use Cartalyst\Support\Handlers\EventHandler as BaseEventHandler;

class StatusEventHandler extends BaseEventHandler implements StatusEventHandlerInterface {

	/**
	 * {@inheritDoc}
	 */
	public function subscribe(Dispatcher $dispatcher)
	{
		$dispatcher->listen('sanatorium.status.status.creating', __CLASS__.'@creating');
		$dispatcher->listen('sanatorium.status.status.created', __CLASS__.'@created');

		$dispatcher->listen('sanatorium.status.status.updating', __CLASS__.'@updating');
		$dispatcher->listen('sanatorium.status.status.updated', __CLASS__.'@updated');

		$dispatcher->listen('sanatorium.status.status.deleted', __CLASS__.'@deleted');
	}

	/**
	 * {@inheritDoc}
	 */
	public function creating(array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function created(Status $status)
	{
		$this->flushCache($status);
	}

	/**
	 * {@inheritDoc}
	 */
	public function updating(Status $status, array $data)
	{

	}

	/**
	 * {@inheritDoc}
	 */
	public function updated(Status $status)
	{
		$this->flushCache($status);
	}

	/**
	 * {@inheritDoc}
	 */
	public function deleted(Status $status)
	{
		$this->flushCache($status);
	}

	/**
	 * Flush the cache.
	 *
	 * @param  \Sanatorium\Status\Models\Status  $status
	 * @return void
	 */
	protected function flushCache(Status $status)
	{
		$this->app['cache']->forget('sanatorium.status.status.all');

		$this->app['cache']->forget('sanatorium.status.status.'.$status->id);
	}

}

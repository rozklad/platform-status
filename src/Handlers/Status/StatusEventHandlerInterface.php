<?php namespace Sanatorium\Status\Handlers\Status;

use Sanatorium\Status\Models\Status;
use Cartalyst\Support\Handlers\EventHandlerInterface as BaseEventHandlerInterface;

interface StatusEventHandlerInterface extends BaseEventHandlerInterface {

	/**
	 * When a status is being created.
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function creating(array $data);

	/**
	 * When a status is created.
	 *
	 * @param  \Sanatorium\Status\Models\Status  $status
	 * @return mixed
	 */
	public function created(Status $status);

	/**
	 * When a status is being updated.
	 *
	 * @param  \Sanatorium\Status\Models\Status  $status
	 * @param  array  $data
	 * @return mixed
	 */
	public function updating(Status $status, array $data);

	/**
	 * When a status is updated.
	 *
	 * @param  \Sanatorium\Status\Models\Status  $status
	 * @return mixed
	 */
	public function updated(Status $status);

	/**
	 * When a status is deleted.
	 *
	 * @param  \Sanatorium\Status\Models\Status  $status
	 * @return mixed
	 */
	public function deleted(Status $status);

}

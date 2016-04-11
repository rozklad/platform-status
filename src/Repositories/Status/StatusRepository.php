<?php namespace Sanatorium\Status\Repositories\Status;

use Cartalyst\Support\Traits;
use Illuminate\Container\Container;
use Symfony\Component\Finder\Finder;

class StatusRepository implements StatusRepositoryInterface {

	use Traits\ContainerTrait, Traits\EventTrait, Traits\RepositoryTrait, Traits\ValidatorTrait;

	/**
	 * The Data handler.
	 *
	 * @var \Sanatorium\Status\Handlers\Status\StatusDataHandlerInterface
	 */
	protected $data;

	/**
	 * The Eloquent status model.
	 *
	 * @var string
	 */
	protected $model;

	/**
	 * Constructor.
	 *
	 * @param  \Illuminate\Container\Container  $app
	 * @return void
	 */
	public function __construct(Container $app)
	{
		$this->setContainer($app);

		$this->setDispatcher($app['events']);

		$this->data = $app['sanatorium.status.status.handler.data'];

		$this->setValidator($app['sanatorium.status.status.validator']);

		$this->setModel(get_class($app['Sanatorium\Status\Models\Status']));
	}

	/**
	 * {@inheritDoc}
	 */
	public function grid()
	{
		return $this
			->createModel();
	}

	/**
	 * {@inheritDoc}
	 */
	public function findAll()
	{
		return $this->container['cache']->rememberForever('sanatorium.status.status.all', function()
		{
			return $this->createModel()->get();
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function find($id)
	{
		return $this->container['cache']->rememberForever('sanatorium.status.status.'.$id, function() use ($id)
		{
			return $this->createModel()->find($id);
		});
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForCreation(array $input)
	{
		return $this->validator->on('create')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function validForUpdate($id, array $input)
	{
		return $this->validator->on('update')->validate($input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function store($id, array $input)
	{
		return ! $id ? $this->create($input) : $this->update($id, $input);
	}

	/**
	 * {@inheritDoc}
	 */
	public function create(array $input)
	{
		// Create a new status
		$status = $this->createModel();

		// Fire the 'sanatorium.status.status.creating' event
		if ($this->fireEvent('sanatorium.status.status.creating', [ $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForCreation($data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Save the status
			$status->fill($data)->save();

			// Fire the 'sanatorium.status.status.created' event
			$this->fireEvent('sanatorium.status.status.created', [ $status ]);
		}

		return [ $messages, $status ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function update($id, array $input)
	{
		// Get the status object
		$status = $this->find($id);

		// Fire the 'sanatorium.status.status.updating' event
		if ($this->fireEvent('sanatorium.status.status.updating', [ $status, $input ]) === false)
		{
			return false;
		}

		// Prepare the submitted data
		$data = $this->data->prepare($input);

		// Validate the submitted data
		$messages = $this->validForUpdate($status, $data);

		// Check if the validation returned any errors
		if ($messages->isEmpty())
		{
			// Update the status
			$status->fill($data)->save();

			// Fire the 'sanatorium.status.status.updated' event
			$this->fireEvent('sanatorium.status.status.updated', [ $status ]);
		}

		return [ $messages, $status ];
	}

	/**
	 * {@inheritDoc}
	 */
	public function delete($id)
	{
		// Check if the status exists
		if ($status = $this->find($id))
		{
			// Fire the 'sanatorium.status.status.deleted' event
			$this->fireEvent('sanatorium.status.status.deleted', [ $status ]);

			// Delete the status entry
			$status->delete();

			return true;
		}

		return false;
	}

	/**
	 * {@inheritDoc}
	 */
	public function enable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => true ]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function disable($id)
	{
		$this->validator->bypass();

		return $this->update($id, [ 'enabled' => false ]);
	}

}

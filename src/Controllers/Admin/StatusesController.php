<?php namespace Sanatorium\Status\Controllers\Admin;

use Platform\Access\Controllers\AdminController;
use Sanatorium\Status\Repositories\Status\StatusRepositoryInterface;

class StatusesController extends AdminController {

	/**
	 * {@inheritDoc}
	 */
	protected $csrfWhitelist = [
		'executeAction',
	];

	/**
	 * The Status repository.
	 *
	 * @var \Sanatorium\Status\Repositories\Status\StatusRepositoryInterface
	 */
	protected $statuses;

	/**
	 * Holds all the mass actions we can execute.
	 *
	 * @var array
	 */
	protected $actions = [
		'delete',
		'enable',
		'disable',
	];

	/**
	 * Constructor.
	 *
	 * @param  \Sanatorium\Status\Repositories\Status\StatusRepositoryInterface  $statuses
	 * @return void
	 */
	public function __construct(StatusRepositoryInterface $statuses)
	{
		parent::__construct();

		$this->statuses = $statuses;
	}

	/**
	 * Display a listing of status.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/status::statuses.index');
	}

	/**
	 * Datasource for the status Data Grid.
	 *
	 * @return \Cartalyst\DataGrid\DataGrid
	 */
	public function grid()
	{
		$data = $this->statuses->grid();

		$columns = [
			'id',
			'name',
			'css_class',
		];

		$settings = [
			'sort'      => 'id',
			'direction' => 'asc',
		];

		$transformer = function($element)
		{
			$element->edit_uri = route('admin.sanatorium.status.statuses.edit', $element->id);

			return $element;
		};

		return datagrid($data, $columns, $settings, $transformer);
	}

	/**
	 * Show the form for creating new status.
	 *
	 * @return \Illuminate\View\View
	 */
	public function create()
	{
		return $this->showForm('create');
	}

	/**
	 * Handle posting of the form for creating new status.
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function store()
	{
		return $this->processForm('create');
	}

	/**
	 * Show the form for updating status.
	 *
	 * @param  int  $id
	 * @return mixed
	 */
	public function edit($id)
	{
		return $this->showForm('update', $id);
	}

	/**
	 * Handle posting of the form for updating status.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function update($id)
	{
		return $this->processForm('update', $id);
	}

	/**
	 * Remove the specified status.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function delete($id)
	{
		$type = $this->statuses->delete($id) ? 'success' : 'error';

		$this->alerts->{$type}(
			trans("sanatorium/status::statuses/message.{$type}.delete")
		);

		return redirect()->route('admin.sanatorium.status.statuses.all');
	}

	/**
	 * Executes the mass action.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function executeAction()
	{
		$action = request()->input('action');

		if (in_array($action, $this->actions))
		{
			foreach (request()->input('rows', []) as $row)
			{
				$this->statuses->{$action}($row);
			}

			return response('Success');
		}

		return response('Failed', 500);
	}

	/**
	 * Shows the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return mixed
	 */
	protected function showForm($mode, $id = null)
	{
		$statusable_repository = app('sanatorium.status.manager');

		$statusable_entities = $statusable_repository->getStatusables();

		// Do we have a status identifier?
		if (isset($id))
		{
			if ( ! $status = $this->statuses->find($id))
			{
				$this->alerts->error(trans('sanatorium/status::statuses/message.not_found', compact('id')));

				return redirect()->route('admin.sanatorium.status.statuses.all');
			}
		}
		else
		{
			$status = $this->statuses->createModel();
		}

		// Show the page
		return view('sanatorium/status::statuses.form', compact('mode', 'status', 'statusable_entities'));
	}

	/**
	 * Processes the form.
	 *
	 * @param  string  $mode
	 * @param  int  $id
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function processForm($mode, $id = null)
	{
		// Store the status
		list($messages) = $this->statuses->store($id, request()->all());

		// Do we have any errors?
		if ($messages->isEmpty())
		{
			$this->alerts->success(trans("sanatorium/status::statuses/message.success.{$mode}"));

			return redirect()->route('admin.sanatorium.status.statuses.all');
		}

		$this->alerts->error($messages, 'form');

		return redirect()->back()->withInput();
	}

}

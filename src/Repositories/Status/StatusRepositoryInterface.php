<?php namespace Sanatorium\Status\Repositories\Status;

interface StatusRepositoryInterface {

	/**
	 * Returns a dataset compatible with data grid.
	 *
	 * @return \Sanatorium\Status\Models\Status
	 */
	public function grid();

	/**
	 * Returns all the status entries.
	 *
	 * @return \Sanatorium\Status\Models\Status
	 */
	public function findAll();

	/**
	 * Returns a status entry by its primary key.
	 *
	 * @param  int  $id
	 * @return \Sanatorium\Status\Models\Status
	 */
	public function find($id);

	/**
	 * Determines if the given status is valid for creation.
	 *
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForCreation(array $data);

	/**
	 * Determines if the given status is valid for update.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Illuminate\Support\MessageBag
	 */
	public function validForUpdate($id, array $data);

	/**
	 * Creates or updates the given status.
	 *
	 * @param  int  $id
	 * @param  array  $input
	 * @return bool|array
	 */
	public function store($id, array $input);

	/**
	 * Creates a status entry with the given data.
	 *
	 * @param  array  $data
	 * @return \Sanatorium\Status\Models\Status
	 */
	public function create(array $data);

	/**
	 * Updates the status entry with the given data.
	 *
	 * @param  int  $id
	 * @param  array  $data
	 * @return \Sanatorium\Status\Models\Status
	 */
	public function update($id, array $data);

	/**
	 * Deletes the status entry.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function delete($id);

}

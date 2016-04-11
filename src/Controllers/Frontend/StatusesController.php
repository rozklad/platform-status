<?php namespace Sanatorium\Status\Controllers\Frontend;

use Platform\Foundation\Controllers\Controller;

class StatusesController extends Controller {

	/**
	 * Return the main view.
	 *
	 * @return \Illuminate\View\View
	 */
	public function index()
	{
		return view('sanatorium/status::index');
	}

}

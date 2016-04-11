<?php namespace Sanatorium\Status\Database\Seeds;

use DB;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class StatusesTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('statuses')->truncate();

		$statuses = [
			[
				'name' => 'Confirmed',
				'css_class' => 'badge badge-primary',
				'status_entity' => 'Sanatorium\Shoporders\Models\Order'
			],
			[
				'name' => 'Dispatched',
				'css_class' => 'badge badge-info',
				'status_entity' => 'Sanatorium\Shoporders\Models\Order'
			],
			[
				'name' => 'Delivered',
				'css_class' => 'badge badge-success',
				'status_entity' => 'Sanatorium\Shoporders\Models\Order'
			]
		];

		foreach( $statuses as $status ) {
			DB::table('statuses')->insert($status);
		}

	}

}

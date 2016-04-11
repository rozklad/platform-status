<?php namespace Sanatorium\Status\Traits;

use Sanatorium\Status\Models\Status;
use Carbon\Carbon;

trait StatusableTrait {

	/**
	 * Price
	 *
	 * Defines manyToMany polymorphic relationship on table `statusable`
	 * that goes by name `entity`, therefore will look for columns
	 * `entity_type` and `entity_id`.
	 *
	 * @return object Relation object
	 */
	public function statuses()
    {
        return $this->morphToMany('Sanatorium\Status\Models\Status', 'entity', 'statusable')->withTimestamps();
    }

    /**
     * Returns current status
     * @return [type] [description]
     */
    public function getStatusAttribute()
    {
    	if ( $status = $this->statuses()
    	         ->wherePivot('created_at', '<=', Carbon::now())
                 ->where(function($query){
                    $query->where('statusable.ended_at', '>=', Carbon::now())
                        ->orWhereNull('statusable.ended_at');
                 })
                 ->orderBy('statusable.created_at', 'DESC')
    	         ->first() ) {
    		return $status;
    	} else {
    		return new Status([
                'id' => 0,
    			'name' => trans('sanatorium/status::statuses/common.not_specified'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
    			]);
    	}
    }

}
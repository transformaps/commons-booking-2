<?php
/**
 * CB Timeframe
 *
 * @package   Commons_Booking
 * @author    Florian Egermann <florian@wielebenwir.de>
 * @copyright 2018 wielebenwir e.V.
 * @license   GPL 2.0+
 * @link      http://commonsbooking.wielebenwir.de
 */
/**
 * Build a calendar
 */
class CB_Calendar extends CB_Object {
	/**
	 * Dates
	 *
	 * @var array
	 */
	public $calendar = array();
	/**
	 * Dates
	 *
	 * @var array
	 */
	public $dates_array = array();
	/**
	 * Date start
	 *
	 * @var array
	 */
	public $timeframe_id;
	/**
	 * Date start
	 *
	 * @var array
	 */
	public $slots_array = array();
	/**
	 * Initialize the class
	 *
	 * @since 1.0.0
	 * 
	 */
	public function __construct( $timeframe_id, $date_start, $date_end ) {
		
		$this->timeframe_id = $timeframe_id;
		$this->date_start = $date_start;
		$this->date_end = $date_end;
		
		$this->create_days_array( );
		
		return $this->calendar;

	}

    public function create_days_array( ) {
		
		$this->dates_array = cb_dateRange( $this->date_start, $this->date_end );
		$this->slots_array = $this->add_slots();
		
		foreach ($this->dates_array as $date) {
			$this->add_date_meta( $date );
			$this->add_date_slots( $date );
		}

	}

    private function add_date_meta( $date ) {

		$weekday = date('N', strtotime( $date ) );  
		
		$weekname_array = CB_Strings::get_string( 'cal', 'weekday_names' );

		$this->calendar[$date]['meta'] = array ( 
			'date'		=> $date,
			'name' 		=> $weekname_array[ $weekday - 1 ],
			'number' 	=> $weekday
		);	
	}
    private function add_timeframe_meta( $date ) {

		$this->calendar[$date]['timeframe_id'] = $this->timeframe_id;
	}
    private function add_date_slots( $date ) {
		if ( ! empty ( $this->slots_array[$date] ) ) {
			$this->calendar[$date]['slots'] = $this->slots_array[$date];
		}
	
	}


	public function add_slots() {
		
		$slots = new CB_Slots( $this->timeframe_id, $this->dates_array );
		$slots_array = $slots->get_slots();
		return $slots_array;

	}
}
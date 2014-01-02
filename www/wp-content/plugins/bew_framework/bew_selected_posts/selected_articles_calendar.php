<?php


/*
 $dst_sa = new dstSelectedArticlesAbstract($args);
 
 if(!$dst_sa) // erreur
 else{
 
 	echo $dst_sa->html;
 	
 }
 */

class bewCalendar extends dstSelectedArticlesAbstract {
		
		
	public $args; // tableau de tous les arguments
	public $queries; // tableau des requetes de sélection des articles à executer
	public $html;
	public $transient_key;
	public $errors;
	public $week_begins;
	public $year;
	public $month;

	
	function __construct($args)
	{
			if(isset($_GET['calendar_y']) && isset($_GET['calendar_m'])){
			
				$now = strtotime(strval($_GET['calendar_y']).'-'.strval($_GET['calendar_m']).'-5');
			
			
			} else	$now = strtotime("now");
			
			
			
			
			global $wp_query;
			
			if(isset($wp_query->query['event_category'])) $event_cat = $wp_query->query['event_category']; else $event_cat = "";
			
	
			$extend = array(
				'position' => $now, /// date for calendar view, as timestamp.
				'event_category' => $event_cat,
				'disable_events_time_filtering' => 1
			);
			
			
			return parent::__construct($args, false, $extend);				

	}
			

function render(){
	
	//ob_start();
	
	global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
	
	

	$this->month = date('n', $this->args['position']);
	$this->year = date('Y', $this->args['position']);
	
	


	// week_begins = 0 stands for Sunday
	$this->week_begins = intval(get_option('start_of_week'));

	$this->unixmonth = mktime(0, 0 , 0, $this->month, 1, $this->year);

	/* translators: Calendar caption: 1: month name, 2: 4-digit year */
	$calendar_caption = _x('%1$s %2$s', 'calendar caption');
	
	
	//////////// requete de selection des posts /////
	
	foreach($this->queries as $q):
						
		$r = new WP_Query($q);	
		
		$this->posts = array();
		
		if ($r->have_posts()) :
						
			$this->posts = array_merge($this->posts, $r->posts);    
			
			                       

		endif;		
						
	endforeach; // $queries as $q 
	
	
	
	///////////////////// header et initiales de jours du calendrier ////////////
	
	
	$this->html.= $this->calendar_header();
	
	
	///////////////////// footer ////////////

	$calendar_output .= '<tfoot>
	<tr>';

	if ( $previous ) {
		$calendar_output .= "\n\t\t".'<td colspan="3" id="prev"><a href="' . $this->get_date_link($previous->year, $previous->month) . '" title="' . sprintf(__('View posts for %1$s %2$s'), $wp_locale->get_month($previous->month), date('Y', mktime(0, 0 , 0, $previous->month, 1, $previous->year))) . '">&laquo; ' . $wp_locale->get_month_abbrev($wp_locale->get_month($previous->month)) . '</a></td>';
	} else {
		$calendar_output .= "\n\t\t".'<td colspan="3" id="prev" class="pad">&nbsp;</td>';
	}

	$calendar_output .= "\n\t\t".'<td class="pad">&nbsp;</td>';

	if ( $next ) {
		$calendar_output .= "\n\t\t".'<td colspan="3" id="next"><a href="' . $this->get_date_link($next->year, $next->month) . '" title="' . esc_attr( sprintf(__('View posts for %1$s %2$s'), $wp_locale->get_month($next->month), date('Y', mktime(0, 0 , 0, $next->month, 1, $next->year))) ) . '">' . $wp_locale->get_month_abbrev($wp_locale->get_month($next->month)) . ' &raquo;</a></td>';
	} else {
		$calendar_output .= "\n\t\t".'<td colspan="3" id="next" class="pad">&nbsp;</td>';
	}

	$calendar_output .= '
	</tr>
	</tfoot>';
	
	
	///////////////////////// corps du calendrier ////////////////////////
	

	$calendar_output .= '<tbody>
	<tr>';

	

	if (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'camino') !== false || stripos($_SERVER['HTTP_USER_AGENT'], 'safari') !== false)
		$ak_title_separator = "\n";
	else
		$ak_title_separator = ', ';

	$ak_titles_for_day = $ak_post_titles = array();
	
	
	foreach($this->posts as $p){
	
		$p->dom = date('j', strtotime($p->post_date));	
			
		if(date('n', strtotime($p->post_date)) == $this->month && date('Y', strtotime($p->post_date)) == $this->year)
			$ak_post_titles[] = $p;
	}
	
	
	
	if ( $ak_post_titles ) {
		foreach ( (array) $ak_post_titles as $ak_post_title ) {

				$post_title = esc_attr( apply_filters( 'the_title', $ak_post_title->post_title, $ak_post_title->ID ) );

				if ( empty($ak_titles_for_day["$ak_post_title->dom"]) ) // first one
					$ak_titles_for_day["$ak_post_title->dom"] = $post_title;
				else
					$ak_titles_for_day["$ak_post_title->dom"] .= $ak_title_separator . $post_title;
		}
	}

	// See how much we should pad in the beginning
	$pad = calendar_week_mod(date('w', $this->unixmonth)-$this->week_begins);
	if ( 0 != $pad )
		$calendar_output .= "\n\t\t".'<td colspan="'. esc_attr($pad) .'" class="pad">&nbsp;</td>';

	$daysinmonth = intval(date('t', $this->unixmonth));
	for ( $day = 1; $day <= $daysinmonth; ++$day ) {
		if ( isset($newrow) && $newrow )
			$calendar_output .= "\n\t</tr>\n\t<tr>\n\t\t";
		$newrow = false;

		if ( $day == gmdate('j', current_time('timestamp')) && $this->month == gmdate('m', current_time('timestamp')) && $this->year == gmdate('Y', current_time('timestamp')) )
			$calendar_output .= '<td id="today">';
		else
			$calendar_output .= '<td>';

		if ( isset($ak_titles_for_day[$day]) ) // any posts today?
				$calendar_output .= '<a href="' . $this->get_date_link($this->year, $this->month, $day) . "\" title=\"" . esc_attr($ak_titles_for_day[$day]) . "\">$day</a>";
		else
			$calendar_output .= $day;
		$calendar_output .= '</td>';

		if ( 6 == calendar_week_mod(date('w', mktime(0, 0 , 0, $this->month, $day, $this->year))-$this->week_begins) )
			$newrow = true;
	}

	$pad = 7 - calendar_week_mod(date('w', mktime(0, 0 , 0, $this->month, $day, $this->year))-$this->week_begins);
	if ( $pad != 0 && $pad != 7 )
		$calendar_output .= "\n\t\t".'<td class="pad" colspan="'. esc_attr($pad) .'">&nbsp;</td>';

	$calendar_output .= "\n\t</tr>\n\t</tbody>\n\t</table>";


		
		
		$this->html .= $calendar_output;

	}
				
				
				
			
			
	function calendar_header(){
	
		global $wpdb, $m, $monthnum, $year, $wp_locale, $posts;
		
		

		
		$nextyear = $prevyear = $this->year;
		
		if($this->month - 1 < 1) {
		
			$prevmonth = 12; 
			$prevyear = $this->year - 1;
			
		}else $prevmonth = $this->month - 1;
		
		if($this->month + 1 > 12) {
		
			$nextmonth = 1; 
			$nextyear = $this->year + 1;
			
		}else $nextmonth = $this->month + 1;
		
		
			if(strlen($this->event_category) > 0) 
				$lk = "/events/$this->event_category";
			else
				$lk = "/events";
				
		$prev_link = $lk."?calendar_y=$prevyear&calendar_m=$prevmonth";
		$next_link = $lk."?calendar_y=$nextyear&calendar_m=$nextmonth";
	
		$calendar_output = '<table id="wp-calendar" summary="' . esc_attr__('Calendar') . '">
		<caption><a class="bew_cal_prev" href="'.$prev_link.'">&laquo;</a>' .$wp_locale->get_month($this->month).' '.date('Y', $this->position). '<a class="bew_cal_next" href="'.$next_link.'">&raquo;</a></caption>
		<thead>
		<tr>';
	
		$myweek = array();
	
		for ( $wdcount=0; $wdcount<=6; $wdcount++ ) {
			$myweek[] = $wp_locale->get_weekday(($wdcount+$this->week_begins)%7);
		}
	
		foreach ( $myweek as $wd ) {
			$day_name = (true == $initial) ? $wp_locale->get_weekday_initial($wd) : $wp_locale->get_weekday_abbrev($wd);
			$wd = esc_attr($wd);
			$calendar_output .= "\n\t\t<th scope=\"col\" title=\"$wd\">$day_name</th>";
		}
	
		$calendar_output .= '
		</tr>
		</thead>';
		
		return $calendar_output;
		
	}
	
	
	function get_date_link($y, $m, $d = false){
	
			
		if($this->post_type == 'events'){
		
		
			if(strlen($this->event_category) > 0) 
				return "/events/$this->event_category/?event_year=$y&event_month=$m&calendar_y=$y&calendar_m=$m".(($d > 0) ? "&event_day=$d" : "");
			else
				return "/events?event_year=$y&event_month=$m".(($d > 0) ? "&event_day=$d" : "");
			
		 
		}elseif(!$d){
		
			return get_month_link($y, $m);
		
			
		
		}else return get_day_link($y, $m);
	
	}

		
} // end class 



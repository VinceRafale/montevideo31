<?php


if ( !class_exists('WP_User_Search') ) :

class WP_User_Search {

	/**
	 * {@internal Missing Description}}
	 *
	 * @since unknown
	 * @access private
	 * @var unknown_type
	 */
	var $results;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since unknown
	 * @access private
	 * @var unknown_type
	 */
	var $search_term;

	/**
	 * Page number.
	 *
	 * @since unknown
	 * @access private
	 * @var int
	 */
	var $page;

	/**
	 * Role name that users have.
	 *
	 * @since unknown
	 * @access private
	 * @var string
	 */
	var $role;

	/**
	 * Raw page number.
	 *
	 * @since unknown
	 * @access private
	 * @var int|bool
	 */
	var $raw_page;

	/**
	 * Amount of users to display per page.
	 *
	 * @since unknown
	 * @access public
	 * @var int
	 */
	var $users_per_page = 50;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since unknown
	 * @access private
	 * @var unknown_type
	 */
	var $first_user;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since unknown
	 * @access private
	 * @var int
	 */
	var $last_user;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since unknown
	 * @access private
	 * @var string
	 */
	var $query_limit;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since 3.0.0
	 * @access private
	 * @var string
	 */
	var $query_orderby;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since 3.0.0
	 * @access private
	 * @var string
	 */
	var $query_from;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since 3.0.0
	 * @access private
	 * @var string
	 */
	var $query_where;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since unknown
	 * @access private
	 * @var int
	 */
	var $total_users_for_query = 0;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since unknown
	 * @access private
	 * @var bool
	 */
	var $too_many_total_users = false;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since unknown
	 * @access private
	 * @var unknown_type
	 */
	var $search_errors;

	/**
	 * {@internal Missing Description}}
	 *
	 * @since unknown
	 * @access private
	 * @var unknown_type
	 */
	var $paging_text;

	/**
	 * PHP4 Constructor - Sets up the object properties.
	 *
	 * @since unknown
	 *
	 * @param string $search_term Search terms string.
	 * @param int $page Optional. Page ID.
	 * @param string $role Role name.
	 * @return WP_User_Search
	 */
	function WP_User_Search ($search_term = '', $page = '', $role = '', $group = '',$withMail=false) {
		$this->search_term = $search_term;
		$this->raw_page = ( '' == $page ) ? false : (int) $page;
		$this->page = (int) ( '' == $page ) ? 1 : $page;
		$this->role = $role;
		$this->group = $group;
		
		if(isset($_GET['adu_group']) && !$group) $this->group = trim($_GET['adu_group']);

		$this->prepare_query();
		if($withMail==true)
		    $this->queryMail();
		else
		    $this->query();
		$this->prepare_vars_for_template_usage();
		$this->do_paging();
	}

	/**
	 * {@internal Missing Short Description}}
	 *
	 * {@internal Missing Long Description}}
	 *
	 * @since unknown
	 * @access public
	 */
	function prepare_query() {
		global $wpdb;
		$this->first_user = ($this->page - 1) * $this->users_per_page;

		$this->query_limit = $wpdb->prepare(" LIMIT %d, %d", $this->first_user, $this->users_per_page);
		$this->query_orderby = ' ORDER BY user_login';

		$search_sql = '';
		if ( $this->search_term ) {
			$searches = array();
			$search_sql = 'AND (';
			foreach ( array('user_login', 'user_nicename', 'user_email', 'user_url', 'display_name') as $col )
				$searches[] = $col . " LIKE '%$this->search_term%'";
				
			$searches[]= "$wpdb->usermeta.meta_value LIKE '%$search%'  COLLATE  	utf8_general_ci";
			
			$search_sql .= implode(' OR ', $searches);
			$search_sql .= ')';
		}

		$this->query_from = " FROM $wpdb->users  LEFT JOIN $wpdb->usermeta ON $wpdb->users.ID = $wpdb->usermeta.user_id";
		$this->query_where = " WHERE 1=1 $search_sql";
		
		
		if($this->group){
		
			$this->query_where .= " AND ( $wpdb->usermeta.meta_key LIKE 'adu_groups' AND $wpdb->usermeta.meta_value LIKE '%$this->group%' ) ";
		
		}

		if ( $this->role ) {
			$this->query_where .= $wpdb->prepare(" AND $wpdb->usermeta.meta_key = '{$wpdb->prefix}capabilities' AND $wpdb->usermeta.meta_value LIKE %s", '%' . $this->role . '%');
		} elseif ( is_multisite() ) {
			$level_key = $wpdb->prefix . 'capabilities'; // wpmu site admins don't have user_levels
			$this->query_from .= ", $wpdb->usermeta";
			$this->query_where .= " AND $wpdb->users.ID = $wpdb->usermeta.user_id AND meta_key = '{$level_key}'";
		}

		do_action_ref_array( 'pre_user_search', array( &$this ) );
	}

	/**
	 * {@internal Missing Short Description}}
	 *
	 * {@internal Missing Long Description}}
	 *
	 * @since unknown
	 * @access public
	 */
	function query() {
		global $wpdb;

		$this->results = $wpdb->get_col("SELECT DISTINCT($wpdb->users.ID)" . $this->query_from . $this->query_where . $this->query_orderby . $this->query_limit);

		if ( $this->results )
			$this->total_users_for_query = $wpdb->get_var("SELECT COUNT(DISTINCT($wpdb->users.ID))" . $this->query_from . $this->query_where); // no limit
		else
			$this->search_errors = new WP_Error('no_matching_users_found', __('No matching users were found!'));
	}
	
	
	function queryMail() {
		global $wpdb;

		$this->results = $wpdb->get_results("SELECT DISTINCT($wpdb->users.ID),user_email" . $this->query_from . $this->query_where . $this->query_orderby . $this->query_limit);

		if ( $this->results )
			$this->total_users_for_query = $wpdb->get_var("SELECT COUNT(DISTINCT($wpdb->users.ID))" . $this->query_from . $this->query_where); // no limit
		else
			$this->search_errors = new WP_Error('no_matching_users_found', __('No matching users were found!'));
	}

	/**
	 * {@internal Missing Short Description}}
	 *
	 * {@internal Missing Long Description}}
	 *
	 * @since unknown
	 * @access public
	 */
	function prepare_vars_for_template_usage() {
		$this->search_term = stripslashes($this->search_term); // done with DB, from now on we want slashes gone
	}

	/**
	 * {@internal Missing Short Description}}
	 *
	 * {@internal Missing Long Description}}
	 *
	 * @since unknown
	 * @access public
	 */
	function do_paging() {
		if ( $this->total_users_for_query > $this->users_per_page ) { // have to page the results
			$args = array();
			if( ! empty($this->search_term) )
				$args['usersearch'] = urlencode($this->search_term);
			if( ! empty($this->role) )
				$args['role'] = urlencode($this->role);

			$this->paging_text = paginate_links( array(
				'total' => ceil($this->total_users_for_query / $this->users_per_page),
				'current' => $this->page,
				'base' => 'users.php?%_%',
				'format' => 'userspage=%#%',
				'add_args' => $args
			) );
			if ( $this->paging_text ) {
				$this->paging_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>%s',
					number_format_i18n( ( $this->page - 1 ) * $this->users_per_page + 1 ),
					number_format_i18n( min( $this->page * $this->users_per_page, $this->total_users_for_query ) ),
					number_format_i18n( $this->total_users_for_query ),
					$this->paging_text
				);
			}
		}
	}

	/**
	 * {@internal Missing Short Description}}
	 *
	 * {@internal Missing Long Description}}
	 *
	 * @since unknown
	 * @access public
	 *
	 * @return unknown
	 */
	function get_results() {
		return (array) $this->results;
	}

	/**
	 * Displaying paging text.
	 *
	 * @see do_paging() Builds paging text.
	 *
	 * @since unknown
	 * @access public
	 */
	function page_links() {
		echo $this->paging_text;
	}

	/**
	 * Whether paging is enabled.
	 *
	 * @see do_paging() Builds paging text.
	 *
	 * @since unknown
	 * @access public
	 *
	 * @return bool
	 */
	function results_are_paged() {
		if ( $this->paging_text )
			return true;
		return false;
	}

	/**
	 * Whether there are search terms.
	 *
	 * @since unknown
	 * @access public
	 *
	 * @return bool
	 */
	function is_search() {
		if ( $this->search_term )
			return true;
		return false;
	}
}
endif;


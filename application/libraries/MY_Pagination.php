<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Pagination Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Pagination
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/pagination.html
 */
class MY_Pagination extends CI_Pagination {
	var $base_function = '';
	var $parameters = '';
	var $first_function_call = '';

	/**
	 * Constructor
	 *
	 * @access	public
	 * @param	array	initialization parameters
	 */
	public function __construct($params = array()) {
		parent::__construct($params);
	}

	// --------------------------------------------------------------------

	/**
	 * Generate the pagination links
	 *
	 * @access	public
	 * @return	string
	 */
	function create_links_ajax() {
		// If our item count or per-page total is zero there is no need to continue.
		if ($this->total_rows == 0 OR $this->per_page == 0) {
			return '';
		}

		// Calculate the total number of pages
		$num_pages = ceil($this->total_rows / $this->per_page);

		// Is there only one page? Hm... nothing more to do here then.
		if ($num_pages == 1) {
			return '';
		}

		// Set the base page index for starting page number
		if ($this->use_page_numbers) {
			$base_page = 1;
		} else {
			$base_page = 0;
		}

		// Determine the current page number.
		$CI =& get_instance();

		$this->cur_page = (int) $this->cur_page;
		
		// Set current page to 1 if using page numbers instead of offset
		if ($this->use_page_numbers AND $this->cur_page == 0) {
			$this->cur_page = $base_page;
		}

		$this->num_links = (int)$this->num_links;

		if ($this->num_links < 1) {
			show_error('Your number of links must be a positive number.');
		}

		if ( ! is_numeric($this->cur_page)) {
			$this->cur_page = $base_page;
		}

		// Is the page number beyond the result range?
		// If so we show the last page
		if ($this->use_page_numbers) {
			if ($this->cur_page > $num_pages) {
				$this->cur_page = $num_pages;
			}
		} else {
			if ($this->cur_page > $this->total_rows) {
				$this->cur_page = ($num_pages - 1) * $this->per_page;
			}
		}

		$uri_page_number = $this->cur_page;
		
		if ( ! $this->use_page_numbers) {
			$this->cur_page = floor(($this->cur_page/$this->per_page) + 1);
		}

		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;

		// Add a trailing slash to the base URL if needed
		$this->base_function = rtrim($this->base_function, ' ');
		
		if(!empty($this->parameters))
			$this->parameters = rtrim(rtrim($this->parameters, ' '), ',') . ', ';

		// And here we go...
		$output = '';

		// Render the "First" link
		if ($this->first_link !== FALSE AND $this->cur_page > ($this->num_links + 1)) {
			$first_function_call = ($this->first_function_call == '') ? $this->base_function : $this->first_function_call;
			$output .= $this->first_tag_open . '<a ' . $this->anchor_class . 'onclick="' . $first_function_call . '">' . $this->first_link . '</a>' . $this->first_tag_close;
		}

		// Render the "previous" link
		if  ($this->prev_link !== FALSE AND $this->cur_page != 1) {
			if ($this->use_page_numbers) {
				$i = $uri_page_number - 1;
			} else {
				$i = $uri_page_number - $this->per_page;
			}

			if ($i == 0 && $this->first_function_call != '') {
				$output .= $this->prev_tag_open . '<a ' . $this->anchor_class . 'onclick="' . $this->first_function_call . '">' . $this->prev_link . '</a>' . $this->prev_tag_close;
			} else {
				$output .= $this->prev_tag_open . '<a ' . $this->anchor_class . 'onclick="' . $this->base_function . '(' . $this->parameters . $i . ');">' . $this->prev_link . '</a>' . $this->prev_tag_close;
			}
		}

		// Render the pages
		if ($this->display_pages !== FALSE) {
			// Write the digit links
			for ($loop = $start -1; $loop <= $end; $loop++) {
				if ($this->use_page_numbers) {
					$i = $loop;
				} else {
					$i = ($loop * $this->per_page) - $this->per_page;
				}

				if ($i >= $base_page) {
					if ($this->cur_page == $loop) {
						$output .= $this->cur_tag_open . $loop . $this->cur_tag_close; // Current page
					} else {
						$n = ($i == $base_page) ? '1' : $i;

						if ($n == '' && $this->first_url != '') {
							$output .= $this->num_tag_open . '<a ' . $this->anchor_class . 'onclick="' . $this->first_function_call . '">' . $loop . '</a>' . $this->num_tag_close;
						} else {
							$output .= $this->num_tag_open . '<a ' . $this->anchor_class . 'onclick="' . $this->base_function . '(' . $this->parameters . $n . ');">' . $loop . '</a>' . $this->num_tag_close;
						}
					}
				}
			}
		}

		// Render the "next" link
		if ($this->next_link !== FALSE AND $this->cur_page < $num_pages) {
			if ($this->use_page_numbers) {
				$i = $this->cur_page + 1;
			} else {
				$i = ($this->cur_page * $this->per_page);
			}

			$output .= $this->next_tag_open . '<a ' . $this->anchor_class . 'onclick="' . $this->base_function . '(' . $this->parameters . $i . ');">' . $this->next_link . '</a>' . $this->next_tag_close;
		}

		// Render the "Last" link
		if ($this->last_link !== FALSE AND ($this->cur_page + $this->num_links) < $num_pages) {
			if ($this->use_page_numbers) {
				$i = $num_pages;
			} else {
				$i = (($num_pages * $this->per_page) - $this->per_page);
			}
			
			$output .= $this->last_tag_open . '<a ' . $this->anchor_class . 'onclick="' . $this->base_function . '(' . $this->parameters . $i . ');">' . $this->last_link . '</a>' . $this->last_tag_close;
		}

		// Add the wrapper HTML if exists
		$output = $this->full_tag_open . $output . $this->full_tag_close;

		return $output;
	}
}
// END Pagination Class

/* End of file Pagination.php */
/* Location: ./system/libraries/Pagination.php */
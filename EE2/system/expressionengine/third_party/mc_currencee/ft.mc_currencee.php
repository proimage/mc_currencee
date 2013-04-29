<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Currencee Class
 * @package   Currencee
 * @author    Michael Cohen (contact@pro-image.co.il)
 */

/**
 * Left Off:
 *
 */

class Mc_currencee_ft extends EE_Fieldtype {

	var $info = array(
			'name'             => 'MC CurrencEE',
			'version'          => '2.0.0',
			'desc'			=> 'Select from a list of currencies. Configurable to display either all or just common currencies.',
			// 'docs_url'		=> 'http://www.pro-image.co.il/labs/mc_currencee'
			);

	var $has_array_data = TRUE;

	var $common_currencies;
	var $currencies;
	var $modes;
	var $shown_currencies;

	function __construct()
	{
		parent::EE_Fieldtype();

		// Get lists of common and all currencies
		require(PATH_THIRD.'mc_currencee/currencies.php');

		$this->EE->lang->loadfile('mc_currencee');


		$this->modes = array(
				'single'	=> $this->EE->lang->line('single_currency'),
				'multiple'	=> $this->EE->lang->line('multiple_currencies')
			);

		$this->shown_currencies = array(
				'common'	=> $this->EE->lang->line('common_currencies'),
				'all'		=> $this->EE->lang->line('all_currencies')
			);
	}

	// --------------------------------------------------------------------

	function in_array_recursive($needle, $haystack)
	{

		$it = new RecursiveIteratorIterator(new RecursiveArrayIterator($haystack));

		foreach($it AS $element)
		{
			if($element == $needle)
			{
				return true;
			}
		}

		return false;
	}

	// --------------------------------------------------------------------

	/**
	 * Install Fieldtype
	 */
	function install()
	{
		return array(
			'mc_currencee_mode'				=> 'single',
			'mc_currencee_shown_currencies'	=> 'common'
		);
	}

	// --------------------------------------------------------------------


// GLOBAL SETTINGS
	function display_global_settings()
	{
		// Load "Table" library
		$this->EE->load->library('table');

		// Combine stored settings with submitted settings
		$settings = array_merge($this->settings, $_POST);

		// Prepare settings table column headers
		$this->EE->table->set_heading($this->EE->lang->line('option'), $this->EE->lang->line('value'));

		// Add settings rows
		$this->EE->table->add_row(
			form_label($this->EE->lang->line('default_selection_mode'), 'mc_currencee_mode'),
			form_dropdown('mc_currencee_mode', $this->modes, $settings['mc_currencee_mode'])
		);
		$this->EE->table->add_row(
			form_label($this->EE->lang->line('default_currencies_shown'), 'mc_currencee_shown_currencies'),
			form_dropdown('mc_currencee_shown_currencies', $this->shown_currencies, $settings['mc_currencee_shown_currencies'])
		);

		// Return complete settings table
		return $this->EE->table->generate();
	}

	function save_global_settings()
	{
		// Combine stored settings with submitted settings
		return array_merge($this->settings, $_POST);
	}

	// --------------------------------------------------------------------

/* CUSTOM FIELD SETUP
------------------------------------------------- */

	/**
	 * Display Field Settings
	 *
	 * @param  array  $settings  The field's settings
	 * @return array  Settings HTML (cell1, cell2, rows)
	 */
	function display_settings($field_settings)
	{
		$interface = $this->_display_settings($field_settings);

		$this->EE->table->add_row(
			$interface[0][0],
			$interface[0][1]
		);

		$this->EE->table->add_row(
			$interface[1][0],
			$interface[1][1]
		);
	}

	// --------------------------------------------------------------------

	/**
	 * Display Cell Settings
	 *
	 * @param  array  $cell_settings  The cell's settings
	 * @return array  Settings HTML (cell1, cell2, rows)
	 */
	function display_cell_settings($cell_settings)
	{
		$interface = $this->_display_settings($cell_settings);

		return $interface;
	}

	// --------------------------------------------------------------------

	/**
	 * Display Field Settings
	 *
	 * @param  array  $field_settings  The field's settings
	 * @return array  Settings HTML (cell1, cell2, rows)
	 */
	function _display_settings($field_settings)
	{
		// If individual settings don't exist, get global defaults
		$mode = (isset($field_settings['mc_currencee_mode'])) ? $field_settings['mc_currencee_mode'] : $this->settings['mc_currencee_mode'];
		$shown_currencies = (isset($field_settings['mc_currencee_shown_currencies'])) ? $field_settings['mc_currencee_shown_currencies'] : $this->settings['mc_currencee_shown_currencies'];

		// Add settings rows
		$settings_rows = array();
		$settings_rows[] = array(
			form_label($this->EE->lang->line('selection_mode'), 'mc_currencee_mode'),
			form_dropdown('mc_currencee_mode', $this->modes, $mode)
		);
		$settings_rows[] = array(
			form_label($this->EE->lang->line('currencies_shown'), 'mc_currencee_shown_currencies'),
			form_dropdown('mc_currencee_shown_currencies', $this->shown_currencies, $shown_currencies)
		);
		return $settings_rows;
	}

	// --------------------------------------------------------------------

	/**
	 * Save Field Settings
	 *
	 * @param  array  $data			The field's settings
	 * @return array  Settings HTML (cell1, cell2, rows)
	 */
	function save_settings($data)
	{
		// Save settings
		return array(
			'mc_currencee_mode'				=> $this->EE->input->post('mc_currencee_mode'),
			'mc_currencee_shown_currencies'	=> $this->EE->input->post('mc_currencee_shown_currencies')
		);
	}

	// --------------------------------------------------------------------

/* PUBLISH FORM
------------------------------------------------- */

	/**
	 * Display Field
	 *
	 * @param  mixed   $data      The field's current value
	 * @return string  The field's HTML
	 */
	function display_field($data)
	{
		return $this->_display_field($this->field_name, $data, $this->settings, 'field');
	}

	/**
	 * Display Cell
	 *
	 * @param  mixed   $data      The cell's current value
	 * @return string  HTML of cell
	 */
	function display_cell($data)
	{
		return $this->_display_field($this->cell_name, $data, $this->settings, 'cell');
	}

	function _display_field($field_name, $data, $field_settings, $context)
	{
		$this->EE->cp->add_to_head('<link rel="stylesheet" type="text/css" href="'.URL_THIRD_THEMES.'mc_shared/lib/chosen/chosen/chosen.css" />');
		$this->EE->cp->add_to_head('<style type="text/css">
				.chzn-container *,
				.chzn-container textarea,
				.chzn-container input[type="text"],
				.chzn-container input[type="email"],
				.chzn-container input[type="url"],
				.chzn-container input[type="number"],
				.chzn-container input[type="password"] { -webkit-box-sizing: content-box; -moz-box-sizing: content-box; box-sizing: content-box; }
				.chzn-container { width: auto !important; min-width: 175px; }
				.chzn-container a.chzn-single { text-decoration: none; }
			</style>');
		$this->EE->cp->add_to_foot('<script type="text/javascript" src="'.URL_THIRD_THEMES.'mc_shared/lib/chosen/chosen/chosen.jquery.min.js"></script>');
		$this->EE->javascript->output('
			$(document).ready(function()
			{
				$(".chzn-select").chosen({ allow_single_deselect: true, no_results_text: "'.$this->EE->lang->line('no_matching_currency').'" });
			});
		');

		if($context == 'cell')
		{
			$this->EE->javascript->output('
				Matrix.bind("mc_template_selector", "display", function(cell)
				{
					$(".chzn-select").chosen({ allow_single_deselect: true, no_results_text: "'.$this->EE->lang->line('no_matching_currency').'" });
				});
			');
		}


		// Unserialize the field data
		$data = unserialize(htmlspecialchars_decode($data));

		// Empty arrays serialize to 's:0:"";', but unserialize to blank variables, not empty arrays.
		if ( ! is_array($data))
		{
			$data = array();
		}

		// Are we allowing the selection of multiple currencies or just a single one?
		switch ($field_settings['mc_currencee_mode'])
		{

			// Multiple options
			case 'multiple':

				// Are we showing all currencies or just the common ones?
				switch ($field_settings['mc_currencee_shown_currencies'])
				{

					// Show all currencies
					case 'all':
						return form_multiselect(
							$field_name.'[]',
							$this->currencies,
							$data,
							'class="chzn-select" data-placeholder="'.$this->EE->lang->line('select_currencies').'"'
						);
						break;

					default:

					// Show common currencies (same as "all" option above, just with different dataset)
					case 'common':
						$currencies_multiselect = form_multiselect(
							$field_name.'[]',
							$this->common_currencies,
							$data,
							'class="chzn-select" data-placeholder="'.$this->EE->lang->line('select_currencies').'"'
						);

						// Output warning if existing stored values are not in limited set of common currencies
						$uncommon_currencies = array_diff($this->currencies, $this->common_currencies);
						$data_r = array_flip($data); // swaps keys <-> values
						$extra_currencies = array_intersect_key($data_r, $uncommon_currencies);
						$warning = '';
						$extra_currencies_multiselect = '';

						if (count($extra_currencies) > 0)
						{
							$warning = '<div class="notice">'.$this->EE->lang->line('extra_currencies_selected_warning').'</div>';

							$extra_currencies_multiselect = form_multiselect(
								$field_name.'[]',
								$extra_currencies,
								$data,
								'class="chzn-select" data-placeholder="'.$this->EE->lang->line('select_currencies').'"'
							);
						}

						return $currencies_multiselect.$warning.$extra_currencies_multiselect;
						break;

				} // END shown_currencies switch

				break;

			// Single option
			default:
			case 'single':

				// Are we showing all or just the common currencies?
				switch ($field_settings['mc_currencee_shown_currencies']) {

					// All currencies
					case 'all':

						// Prepare output
						$output = form_dropdown(
							$field_name.'[]',
							array(
								'',
								 // Common currencies
								$this->EE->lang->line('common_currencies') => $this->common_currencies,
								 // Everything minus what's in the common array
								$this->EE->lang->line('other_currencies') => array_diff_key($this->currencies, $this->common_currencies)
							),
							$data,
							'class="chzn-select" data-placeholder="'.$this->EE->lang->line('select_currencies').'"'
						);
						break;

					default:

					// Common currencies
					case 'common':

						$output = '';

						$dropdown = array(
								'' => '--',
								$this->EE->lang->line('common_currencies') => $this->common_currencies
						);

						// Output warning if existing stored values are not in limited set of common currencies
						$warning = '';
						$data_r = array_flip($data); // Keys <--> Values
						$uncommon_currencies = array_diff($this->currencies, $this->common_currencies); // build array of uncommon currencies
						$extra_currencies = array_intersect_key($data_r, $uncommon_currencies); // Find stored values that aren't in common currencies

						if (count($extra_currencies) > 0)
						{
							$warning = '<div class="notice">'.$this->EE->lang->line('extra_currency_selected_warning').'</div>';
							// foreach ($extra_currencies as $code => $index)
							// {
							// 	$combined_extra[$code] = $code . ' - ' . $this->currencies[$code];
							// }
							$dropdown[$this->EE->lang->line('preexisting_currencies')] = $extra_currencies;
						}


						$output .= form_dropdown(
							$field_name.'[]',
							$dropdown,
							$data,
							'class="chzn-select" data-placeholder="'.$this->EE->lang->line('select_currencies').'"'
						) . $warning;
						break;
				} // END shown_currencies switch

				// If multiple array elements
				if (count($data) > 1)
				{
					$output .= BR.'<div class="notice">'.$this->EE->lang->line('single_mode_multiple_currencies_selected_warning').'</div>';
				}
				return $output;
				break;
		} // END mode switch
	}

	// --------------------------------------------------------------------

	function validate($data)
	{
		// If no selection has been made, allow it
		if ($data[0] == '')
		{
			return TRUE;
		}
		// Otherwise, verify submitted data against valid currencies
		else
		{
			foreach ($data as $key => $value)
			{
				if ( ! array_key_exists($value, $this->currencies))
				{
					return $this->EE->lang->line('invalid_currency_specified') . '"' . $value . '"';
				}
			}
			return TRUE;
		}
	}

	// --------------------------------------------------------------------

	function save($data)
	{
		return serialize($data);
	}

	function save_cell($data)
	{
		return serialize($data);
	}

	// --------------------------------------------------------------------

	function replace_tag($data, $params = array(), $tagdata = FALSE)
	{
		$this->EE->load->helper('custom_field');

		$data = unserialize(htmlspecialchars_decode($data));

		// Empty arrays serialize to 's:0:"";', but unserialize to blank variables, not empty arrays.
		if ( ! is_array($data))
		{
			$data = array();
		}

		if ($tagdata)
		{
			return $this->_parse_multi($data, $params, $tagdata);
		}
		else
		{
			return $this->_parse_single($data, $params);
		}
	}

	// --------------------------------------------------------------------

	function _parse_single($data, $params)
	{
		// Parameters
		if (isset($params['markup']) && ($params['markup'] == 'ol' OR $params['markup'] == 'ul'))
		{
			$entry = '<'.$params['markup'].'>'.NL;

			foreach($data as $dv)
			{
				$entry .= '	'; // tab character
				$entry .= '<li>';
				$entry .= $dv;
				$entry .= '</li>'.NL;
			}

			$entry .= '</'.$params['markup'].'>';
		}
		elseif (isset($params['separator']))
		{
			$entry = implode(htmlspecialchars($params['separator']), $data);
		}
		else
		{
			$entry = implode(', ', $data);
		}

		return $this->EE->functions->encode_ee_tags($entry);
	}

	// --------------------------------------------------------------------

	function _parse_multi($data, $params, $tagdata)
	{
		$chunk = '';

		// Variables
		$vars['total_results'] = count($data);	// {total_results}

		foreach($data as $key => $code)
		{
			$vars['code'] = $code;		// {code}
			$vars['count'] = $key + 1;	// {count}

			$tmp = $this->EE->functions->prep_conditionals($tagdata, $vars);
			$chunk .= $this->EE->functions->var_swap($tmp, $vars);
		}

		// Everybody loves backspace
		if (isset($params['backspace']))
		{
			$chunk = substr($chunk, 0, - $params['backspace']);
		}

		// Typography!
		return $this->EE->functions->encode_ee_tags($chunk);
	}
}

// END mc_currencee_ft class

/* End of file ft.mc_currencee.php */
/* Location: ./system/expressionengine/third_party/mc_currencee/ft.mc_currencee.php */
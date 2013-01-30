<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
 * Currencee Class
 * @package   Currencee
 * @author    Michael Cohen (contact@pro-image.co.il)
 */

/**
 * Left Off:
 * Multi-select table is sortable but "Select All" checkbox header is as well despite being told not to be.
 *
 */

class Mc_currencee_ft extends EE_Fieldtype {

	var $info = array(
			'name'             => 'MC CurrencEE',
			'version'          => '2.0.0',
			'desc'			=> 'Select currencies from the 176 active codes of the official ISO 4217 3 digit currency codes and names. Configurable to display either all or just common currencies.',
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
				'single'	=> lang('single_currency'),
				'multiple'	=> lang('multiple_currencies')
			);

		$this->shown_currencies = array(
				'common'	=> lang('common_currencies'),
				'all'		=> lang('all_currencies')
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
		$this->EE->table->set_heading(lang('option'), lang('value'));

		// Add settings rows
		$this->EE->table->add_row(
			form_label(lang('default_selection_mode'), 'mc_currencee_mode'),
			form_dropdown('mc_currencee_mode', $this->modes, $settings['mc_currencee_mode'])
		);
		$this->EE->table->add_row(
			form_label(lang('default_currencies_shown'), 'mc_currencee_shown_currencies'),
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
/*		// Load "Table" library
		$this->EE->load->library('table');

		// If individual settings don't exist, get global defaults
		$mode = (isset($field_settings['mode'])) ? $field_settings['mode'] : $this->settings['mode'];
		$shown_currencies = (isset($field_settings['shown_currencies'])) ? $field_settings['shown_currencies'] : $this->settings['shown_currencies'];

		// Add settings rows
		$this->EE->table->add_row(
			form_label(lang('selection_mode'), 'mode'),
			form_dropdown('mode', $this->modes, $mode)
		);
		$this->EE->table->add_row(
			form_label(lang('currencies_shown'), 'shown_currencies'),
			form_dropdown('shown_currencies', $this->shown_currencies, $shown_currencies)
		);*/
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
			form_label(lang('selection_mode'), 'mc_currencee_mode'),
			form_dropdown('mc_currencee_mode', $this->modes, $mode)
		);
		$settings_rows[] = array(
			form_label(lang('currencies_shown'), 'mc_currencee_shown_currencies'),
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
		return $this->_display_field($this->field_name, $data, $this->settings);
	}

	/**
	 * Display Cell
	 *
	 * @param  mixed   $data      The cell's current value
	 * @return string  HTML of cell
	 */
	function display_cell($data)
	{
		return $this->_display_field($this->cell_name, $data, $this->settings);
		// return "hello world $data";
	}

	function _display_field($field_name, $data, $field_settings)
	{
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

				$this->EE->load->library('table');
				$this->EE->table->clear();

				// Prepare column headers for currencies
				$this->EE->table->set_columns(array(
					'code'		=> array('header' => lang('currency_code')),
					'name'		=> array('header' => lang('currency_name')),
					'_check'	=> array(
						//'header' => form_checkbox(array('value' => 'true', 'name' => 'select_all', 'class' => 'toggle_all')),
						'header' => form_checkbox('select_all', 'true', FALSE, 'class="toggle_all"'),
						'sort' => FALSE
					)
				));

				// Are we showing all currencies or just the common ones?
				switch ($field_settings['mc_currencee_shown_currencies'])
				{

					// Show all currencies
					case 'all':

						// Init vars
						$table_data = array();

						// Iterate over the complete list of currencies to build each table row
						foreach ($this->currencies as $code => $label)
						{
							// // Is $code found in the $data array?
							// $checked = ($this->in_array_recursive($code, $data)) ? TRUE : FALSE;

							// Is $code found in the $data array?
							$checked = (in_array($code, $data)) ? TRUE : FALSE;

							// Prepare checkbox parameters
							$params = array(
								'name'		=> $field_name.'[]',
								'id'		=> $code,
								'value'		=> $code,
								'checked'	=> $checked,
								'class'		=> 'toggle'
							);
							// Add row as array to table array
							$table_data[] = array(
								'code'		=> form_label($code,$code),
								'name'		=> form_label($label,$code),
								'_check'	=> form_checkbox($params)
							);
						} // End table rows loop

						// With all rows prepared, buid complete table
						$this->EE->table->set_data($table_data);
						return $this->EE->table->generate();
						break;

					default:

					// Show common currencies (same as "all" option above, just with different dataset)
					case 'common':
						$table_data = array();
						foreach ($this->common_currencies as $code => $label)
						{
							// Is $code found in the $data array?
							$checked = (in_array($code, $data)) ? TRUE : FALSE;

							$params = array(
								'name'		=> $field_name.'[]',
								'id'		=> $code,
								'value'		=> $code,
								'checked'	=> $checked,
								'class'		=> 'toggle'
							);
							$table_data[] = array(
								'code'		=> form_label($code,$code),
								'name'		=> form_label($label,$code),
								'_check'	=> form_checkbox($params)
							);
						}

						$this->EE->table->set_data($table_data);
						$currencies_table = $this->EE->table->generate($table_data);

						// Output warning if existing stored values are not in limited set of common currencies
						$uncommon_currencies = array_diff($this->currencies, $this->common_currencies);
						$data_r = array_flip($data); // swaps keys <-> values
						$extra_currencies = array_intersect_key($data_r, $uncommon_currencies);
						$warning = '';
						$extra_currencies_table = '';

						if (count($extra_currencies) > 0)
						{
							$warning = '<div class="notice">'.lang('extra_currencies_selected_warning').'</div>';

							$this->EE->table->clear();
							// Prepare column headers for currencies
							$this->EE->table->set_columns(array(
								'code'		=> array('header' => lang('currency_code')),
								'name'		=> array('header' => lang('currency_name')),
								'_check'	=> array(
									//'header' => form_checkbox(array('value' => 'true', 'name' => 'select_all', 'class' => 'toggle_all')),
									'header' => form_checkbox('select_all', 'true', FALSE, 'class="toggle_all"'),
									'sort' => FALSE)
							));

							$extra_table_data = array();
							foreach ($extra_currencies as $code => $index)
							{
								$params = array(
									'name'		=> $field_name.'[]',
									'id'		=> $code,
									'value'		=> $code,
									'checked'	=> TRUE,
									'class'		=> 'toggle'
								);
								$extra_table_data[] = array(
									'code'		=> form_label($code,$code),
									'name'		=> form_label($uncommon_currencies[$code],$code),
									'_check'	=> form_checkbox($params)
								);
							}
							$this->EE->table->set_data($extra_table_data);

							$extra_currencies_table = $this->EE->table->generate($extra_table_data);
						}

						return $currencies_table.$warning.$extra_currencies_table;
						break;

				} // END shown_currencies switch

				break;

			// Single option
			default:
			case 'single':

				// Init vars
				$combined_all = array();
				$combined_common = array();

				// Are we showing all or just the common currencies?
				switch ($field_settings['mc_currencee_shown_currencies']) {

					// All currencies
					case 'all':

						// Build 'all' and 'common' arrays for <select> lists according to pattern:
						//
						//		array('USD' => 'USD - United States Dollars')
						//
						foreach ($this->currencies as $key => $value)
						{
							$combined_all[$key] = $key . ' - ' . $value;
						}
						foreach ($this->common_currencies as $key => $value)
						{
							$combined_common[$key] = $key . ' - ' . $value;
						}
						// Prepare output
						$output = form_dropdown(
							$field_name.'[]',
							array(
								'' => '--',
								 // Common currencies
								lang('common_currencies') => $combined_common,
								 // Everything minus what's in the common array
								lang('other_currencies') => array_diff_key($combined_all, $combined_common)
							),
							$data
						);
						break;

					default:

					// Common currencies
					case 'common':

						$output = '';

						foreach ($this->common_currencies as $key => $value)
						{
							$combined_common[$key] = $key . ' - ' . $value;
						}

						$dropdown = array(
								'' => '--',
								lang('common_currencies') => $combined_common
						);

						// Output warning if existing stored values are not in limited set of common currencies
						$warning = '';
						$data_r = array_flip($data); // Keys <--> Values
						$uncommon_currencies = array_diff($this->currencies, $this->common_currencies); // build array of uncommon currencies
						$extra_currencies = array_intersect_key($data_r, $uncommon_currencies); // Find stored values that aren't in common currencies

						if (count($extra_currencies) > 0)
						{
							$warning = '<div class="notice">'.lang('extra_currency_selected_warning').'</div>';
							foreach ($extra_currencies as $code => $index)
							{
								$combined_extra[$code] = $code . ' - ' . $this->currencies[$code];
							}
							$dropdown[lang('preexisting_currencies')] = $combined_extra;
						}


						$output .= form_dropdown(
							$field_name.'[]',
							$dropdown,
							$data
						) . $warning;
						break;
				} // END shown_currencies switch

				// If multiple array elements
				if (count($data) > 1)
				{
					$output .= BR.'<div class="notice">'.lang('single_mode_multiple_currencies_selected_warning').'</div>';
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
					return lang('invalid_currency_specified') . '"' . $value . '"';
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
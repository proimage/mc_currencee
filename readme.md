Plugin: MC CurrencEE
====================
Select currencies from the 176 active codes of the official ISO 4217 3 digit currency codes and names. Configurable to display either all or just common currencies.

---------------------------------------------------------------------

Changelog
=========
- 2.0.0 (2013-01-30):
	- Now EE2 compatible. All new features listed below are EE2-only.
	- Added global and field settings for single/multiple currency selection and displaying common/all currencies
	- Added tag pair support
	- Added "markup" and "separator" parameters to single-tag mode
	- Added {total_results}, {count}, and {code} variables to tag pair
	- Added "backspace" parameter to tag pair
- 1.0.0 (Not released publicly; EE1-only)
	- Single currency selection
	- Matrix celltype support

---------------------------------------------------------------------

Examples
========

Assume:
- Field short name is "my_currency"
- Selected single currency:
	- US Dollars
- Selected multiple currencies:
	- US dollar
	- Euro
	- Pound sterling

Single Tag
----------

### Single Currency

Code:

	{my_currency}
	
Output:
	
	USD


### Multiple Currencies

#### No Parameters

Code:

	{my_currency}

Output:

	USD, EUR, GBP



#### "Separator" Parameter

Code:

	{my_currency separator="|"}

Output:

	USD|EUR|GBP



#### "Markup" Parameter

Code:

	{my_currency markup="ol"}

Output:

	<ol>
		<li>USD</li>
		<li>EUR</li>
		<li>GBP</li>
	</ol>


Tag Pair
--------

### Single Currency

Code:

	{my_currency}
		Selected Currency:
		{count}. {code}
	{/my_currency}
	
Output:
	
	Selected Currency:
	1. USD


### Multiple Currencies

#### Basic Example

Code:

	{my_currency backspace="2"}{code}; {/my_currency}

Output:

	USD; EUR; GBP

Note that whitespace is counted as a character for the "backspace" parameter.



#### Advanced Example

Code:

	{my_currency}
	{if count == 1}
		<dl>
	{/if}
			<dt>{count}</dt>
			<dd>{code}</dd>
	{if count == total_results}
		</dl>
		Total Selected Currencies: {total_results}
	{/if}
	{/my_currency}

Output:

	<dl>
		<dt>1</dt>
		<dd>USD</dd>

		<dt>2</dt>
		<dd>EUR</dd>

		<dt>3</dt>
		<dd>GBP</dd>
	</dl>
	Total Selected Currencies: 3
<?php
/*
Whois.php        PHP classes to conduct whois queries

Copyright (C)1999,2005 easyDNS Technologies Inc. & Mark Jeftovic

Maintained by David Saez (david@ols.es)

For the most recent version of this package visit:

http://www.phpwhois.org

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

/* tvcorp.whois 1.1	David Saez Padros <david@ols.es> */

if (!defined('__TVCORP_HANDLER__'))
	define('__TVCORP_HANDLER__', 1);

require_once('whois.parser.php');

class tvcorp_handler
	{

	function parse($data_str, $query)
		{

		$items = array(
                'owner' => 'Registrant',
                'admin' => 'Admin',
                'tech' =>	'Technical',
                'billing' => 'Billing',
                'domain.nserver.' => 'Domain servers:',
                'domain.created' => 'Record created on',
				'domain.expires' => 'Record expires on'
		            );

		$r = get_blocks($data_str, $items);
		
		if (isset($r['owner'])) $r['owner'] = get_contact($r['owner']);			
		if (isset($r['admin'])) $r['admin'] = get_contact($r['admin']);
		if (isset($r['tech'])) $r['tech'] = get_contact($r['tech']);
		if (isset($r['billing'])) $r['billing'] = get_contact($r['billing']);

		$r = format_dates($r, 'myd');
		return ($r);
		}
	}
?>

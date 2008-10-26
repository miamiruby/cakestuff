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

/* mxnic.whois	1.0	Torfinn Nome <torfinn@nome.no> 2003-02-15 */
/* Based upon info.whois by David Saez Padros <david@ols.es> */

if (!defined('__MX_HANDLER__'))
	define('__MX_HANDLER__', 1);

require_once('whois.parser.php');

class mx_handler
	{

	function parse($data_str, $query)
		{

		$contacts = array(
                    'admin' 	=> 'ADMINISTRATIVO',
                    'tech' 		=> 'TECNICO',
                    'billing' 	=> 'DE PAGO'
		                );

		$items = array(
                    'name' 		=> 'DOMINIO:',
                    'created' 	=> 'FECHA DE CREACION:',
                    'changed' 	=> 'FECHA DE ULTIMA MODIFICACION:'
		              );

		$r['regrinfo'] = array();
		$r['regrinfo']['domain']['nserver'] = array();
		$r['regrinfo']['admin'] = array();
		$r['regrinfo']['tech'] = array();
		$r['regrinfo']['billing'] = array();
		$r['regrinfo']['owner'] = array();

		$lastk = '';

		while (list($key, $val) = each($data_str['rawdata']))
			{
			$val = trim($val);

			if ($val != '')
				{

				foreach($contacts as $key => $contact)
					{
					if (strstr($val, "CONTACTO $contact:"))
						{
						preg_match("/CONTACTO $contact:\s*(.+?)\s*\[(.+?)\]/", $val, $refs);
						$r['regrinfo'][$key]['name'] = $refs[1];
						$r['regrinfo'][$key]['handle'] = $refs[2];
						$lastk = $key;
						break;
						}
					}
					
				if (strstr($val, 'ORGANIZACION:'))
					{
					preg_match('/ORGANIZACION:\s*(.+?)\s*\[(.+?)\]/', $val, $refs);
					$r['regrinfo']['owner']['name'] = $refs[1];
					$r['regrinfo']['owner']['handle'] = $refs[2];
					continue;
					}
					
				if (strstr($val, 'SERVIDOR DNS '))
					{
					$r['regrinfo']['domain']['nserver'][] = trim(substr($val, 16));
					continue;				
					}
				
				if (strstr($val, 'DOMICILIO:'))
					{
					if ($lastk == '') $lastk = 'owner';
					$r['regrinfo'][$lastk]['address'] = trim(substr($val, 11));
					continue;
					}
				
				reset($items);

				while (list($field, $match) = each($items))
				
				if (strstr($val, $match))
					{
					$r['regrinfo']['domain'][$field] = trim(substr($val, strlen($match)));
					break;
					}
				}
			}

		if (!empty($r['regrinfo']['owner']['name']))
			{
			$r['regyinfo'] = array(
                            'referrer' => 'http://www.nic.mx',
                            'registrar' => 'NIC-Mexico'
			                       );
			}
		else
			$r = '';

		format_dates($r, 'dmy');
		return ($r);
		}

	}

?>

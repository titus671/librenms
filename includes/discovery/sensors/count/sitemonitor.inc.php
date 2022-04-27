<?php
/**
 * sitemonitor.inc.php
 *
 * LibreNMS current discovery module for Packetflux SiteMonitor
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 * @link       https://www.librenms.org
 * @author     Trendal Toews @ Stream IT Networks
 *                4-22-2022
 */

// Get the expansion unit description, if it exists, if not TODO
$expansion_module = snmp_get($device, '.1.3.6.1.4.1.32050.2.1.25.2.1', '-Oqv');

switch ($expansion_module) {

  // Run discovery of Tri Star MPPT Charge Controller
  case 'TriStarMPPTChargeModeRevH':

    $sensors = (object) [

      // divisor mapping
      '18' => 1,
      '19' => 1,
      '20' => 1,
      '21' => 1,
      '23' => 1,
      '24' => 1,
      '25' => 1,
      '26' => 1,
      '35' => 1,
      '36' => 1,
    ];

    $base_oid = '.1.3.6.1.4.1.32050.2.1.27.';
    $idx_index = '1.';
    $desc_index = '2.';
    $value_index = '5.';

    // $idx will be the sensor index on the Packetflux
    foreach ($sensors as $idx => $arr) {
        $index = snmp_get($device, $base_oid . $idx_index . $idx, '-Oqv');
        $desc = snmp_get($device, $base_oid . $desc_index . $idx, '-Oqv');
        $value = snmp_get($device, $base_oid . $value_index . $idx, '-Oqv');

        $value = $value / $sensors->$idx;

        discover_sensor($valid['sensor'], 'count', $device,
        $base_oid . $value_index . $idx, $idx, 'sitemonitor', $desc,
        $sensors->$idx, 1, null, null, null, null, $value);
    }

    break;

  default:

  }

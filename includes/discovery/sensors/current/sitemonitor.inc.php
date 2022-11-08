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
 *
 * @copyright  2017 Neil Lathwood
 * @author     Neil Lathwood <gh+n@laf.io>
 * @author     Trendal Toews @ Stream IT Networks
 *                4-22-2022
 *                Expansion Modules support and other minor adjustments
 */
$oid = '.1.3.6.1.4.1.32050.2.1.27.5.1';
$current = (snmp_get($device, $oid, '-Oqv') / 100);
$desc = (snmp_get($device, '.1.3.6.1.4.1.32050.2.1.27.2.1', '-Oqv'));
discover_sensor($valid['sensor'], 'current', $device, $oid, 1, 'sitemonitor', $desc, 100, 1, null, null, null, null, $current);

$oid = '.1.3.6.1.4.1.32050.2.1.27.5.4';
$current = (snmp_get($device, $oid, '-Oqv') / 100);
$desc = (snmp_get($device, '.1.3.6.1.4.1.32050.2.1.27.2.4', '-Oqv'));
discover_sensor($valid['sensor'], 'current', $device, $oid, 4, 'sitemonitor', $desc, 100, 1, null, null, null, null, $current);

// Get the expansion unit description, if it exists, if not TODO
$expansion_module = snmp_get($device, '.1.3.6.1.4.1.32050.2.1.25.2.1', '-Oqv');

switch ($expansion_module) {

  // Run discovery of Morningstar ProStar MPPT Charge Controller
  case 'ProStar Gen3 RevH':

    $sensors = (object) [

      // divisor mapping
      '13' => 1000,
      '15' => 1000,
      '16' => 1000,
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

        discover_sensor($valid['sensor'], 'current', $device,
        $base_oid . $value_index . $idx, $idx, 'sitemonitor', $desc,
        $sensors->$idx, 1, null, null, null, null, $value);
    }

    break;

  case 'TriStarChargeMode':

    $sensors = (object) [

      // divisor mapping
      '10' => 100,
      '11' => 100,
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

        discover_sensor($valid['sensor'], 'current', $device,
        $base_oid . $value_index . $idx, $idx, 'sitemonitor', $desc,
        $sensors->$idx, 1, null, null, null, null, $value);
    }

    break;

  default:

  }

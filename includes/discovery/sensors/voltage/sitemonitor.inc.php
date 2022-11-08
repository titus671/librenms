<?php
/**
 * sitemonitor.inc.php
 *
 * LibreNMS voltage discovery module for Packetflux SiteMonitor
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

/*
*   Shunt Input code below moved to the current/sitemonitor.inc.php module
*/
//$oid = '.1.3.6.1.4.1.32050.2.1.27.5.1';
//$current = (snmp_get($device, $oid, '-Oqv') / 10);
//discover_sensor($valid['sensor'], 'voltage', $device, $oid, 1, 'sitemonitor', 'Shunt Input', 10, 1, null, null, null, null, $current);

/*
* This is the start of the original code that is left in place to support existing behavior
*/
$oid = '.1.3.6.1.4.1.32050.2.1.27.5.2';
$current = (snmp_get($device, $oid, '-Oqv') / 10);
$desc = (snmp_get($device, '.1.3.6.1.4.1.32050.2.1.27.2.2', '-Oqv'));
discover_sensor($valid['sensor'], 'voltage', $device, $oid, 2, 'sitemonitor', $desc, 10, 1, null, null, null, null, $current);

$oid = '.1.3.6.1.4.1.32050.2.1.27.5.3';
$current = (snmp_get($device, $oid, '-Oqv') / 10);
$desc = (snmp_get($device, '.1.3.6.1.4.1.32050.2.1.27.2.3', '-Oqv'));
discover_sensor($valid['sensor'], 'voltage', $device, $oid, 3, 'sitemonitor', $desc, 10, 1, null, null, null, null, $current);

/*
* This isthe end of the original code
*/


// Get the expansion unit description, if it exists
$expansion_module = snmp_get($device, '.1.3.6.1.4.1.32050.2.1.25.2.1', '-Oqv');

switch ($expansion_module) {

  // Run discovery of Morningstar ProStar MPPT Charge Controller
  case 'ProStar Gen3 RevH':

    $sensors = (object) [

      // divisor mapping
      '9' => 1000,
      '10' => 1000,
      '11' => 1000,
      '12' => 1000,
      '14' => 1000,
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

        discover_sensor($valid['sensor'], 'voltage', $device,
        $base_oid . $value_index . $idx, $idx, 'sitemonitor', $desc,
        $sensors->$idx, 1, null, null, null, null, $value);
    }

    break;

    // Run discovery of Morningstar TriStar PWM Charge Controller
    case 'TriStarChargeMode':

      $sensors = (object) [

        // divisor mapping
        '7' => 100,
        '8' => 100,
        '9' => 100,
        '12' => 100,
        '23' => 100,
        '24' => 100,
        '25' => 100,
        '28' => 100,
        '29' => 100,
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

          discover_sensor($valid['sensor'], 'voltage', $device,
          $base_oid . $value_index . $idx, $idx, 'sitemonitor', $desc,
          $sensors->$idx, 1, null, null, null, null, $value);
      }

      break;


  case '6Voltmeter':

    // Run discovery of the 6 Port Volt Monitoring module
    $sensors = (object) [

      // divisor mapping
      '7' => 10,
      '8' => 10,
      '9' => 10,
      '10' => 10,
      '11' => 10,
      '12' => 10,
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

        discover_sensor($valid['sensor'], 'voltage', $device,
        $base_oid . $value_index . $idx, $idx, 'sitemonitor', $desc,
        $sensors->$idx, 1, null, null, null, null, $value);
    }

    break;

  default:

  }

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
 */
$index = 0;

$oid = '.1.3.6.1.4.1.32050.2.1.27.5.1';
$current = (snmp_get($device, $oid, '-Oqv') / 10);
discover_sensor($valid['sensor'], 'voltage', $device, $oid, $index, 'sitemonitor', 'Shunt Input', 10, 1, null, null, null, null, $current);

$index++;

$oid = '.1.3.6.1.4.1.32050.2.1.27.5.2';
$current = (snmp_get($device, $oid, '-Oqv') / 10);
discover_sensor($valid['sensor'], 'voltage', $device, $oid, $index, 'sitemonitor', 'Power 1', 10, 1, null, null, null, null, $current);

$index++;

$oid = '.1.3.6.1.4.1.32050.2.1.27.5.3';
$current = (snmp_get($device, $oid, '-Oqv') / 10);
discover_sensor($valid['sensor'], 'voltage', $device, $oid, $index, 'sitemonitor', 'Power 2', 10, 1, null, null, null, null, $current);

$index++;
/**
 * This section of code was added by Stream IT Networks
 * to add support for the functionality of the Packetflux
 * Sitemonitor Expansion modules\
 *
*$oid = '.1.3.6.1.4.1.32050.2.1.27.5.6';
$desc_oid = '.1.3.6.1.4.1.32050.2.1.27.2.6';
$index_oid = '.1.3.6.1.4.1.32050.2.1.27.1.6';

$expansion_module_oid = '.1.3.6.1.4.1.32050.2.1.25.2.1';
$expansion_module = snmp_get($device, $expansion_module_oid, "-Oqv");

if ($expansion_module == "TriStarMPPTChargeModeRevH"){
  for ($x = 0; $x <=32; $x++){
    $oid++;
    $current = (snmp_get($device, $oid, '-Oqv') /10);
    $desc_oid++;
    $desc = snmp_get($device, $desc_oid, '-Oqv');
    $index_oid++;
    $index = snmp_get($device, $index_oid, '-Oqv');

    discover_sensor($valid['sensor'], 'voltage', $device, $oid, $index, 'sitemonitor', $desc, 10, 1, null, null, null, null, $current);

  }elseif ($expansion_module == '6Voltmeter'){
    for ($x = 0; $x <=6; $x++){
      $oid++;
      $current = (snmp_get($device, $oid, '-Oqv') /10);
      $desc_oid++;
      $desc = snmp_get($device, $desc_oid, '-Oqv');
      $index_oid++;
      $index = snmp_get($device, $index_oid, '-Oqv');
      $validity = '0'
      discover_sensor($validity['sensor'], 'voltage', $device, $oid, $index, 'sitemonitor', $desc, 10, 1, null, null, null, null, $current);
    }
  }
}
*/

$expansion_module = snmp_get($device, '.1.3.6.1.4.1.32050.2.1.25.2.1', "-Oqv");

switch ($expansion_module) {

  case "TriStarMPPTChargeModeRevH":

    //
    //  Sensor type mapping and divisor for the TriStar MPPT
    //  https://docs.librenms.org/Developing/os/Health-Information/#sensors
    //
    $sensors = (object) [

      "0" => ["temperature", 10],
      "1" => ["current", 10],
      "2" => ["voltage", 10],
      "3" => ["voltage", 10],
      "4" => ["current", 10],
      "5" => ["temperature", 10],
      "6" => ["temperature", 10],
      "7" => ["voltage", 100],
      "8" => ["voltage", 100],
      "9" => ["voltage", 100],
      "10" => ["voltage", 100],
      "11" => ["current", 100],
      "12" => ["current", 100],
      "13" => ["temperature", 1],
      "14" => ["temperature", 1],
      "15" => ["temperature", 1],
      "16" => ["voltage", 100],
      "17" => ["current", 100],
      "18" => ["count", 1],
      "19" => ["count", 1],
      "20" => ["count", 1],
      "21" => ["count", 1],
      "22" => ["voltage", 100],
      "23" => ["current", 10],
      "24" => ["current", 10],
      "25" => ["power_consumed", 1],
      "26" => ["power_consumed", 1],
      "27" => ["power", 100],
      "28" => ["power", 100],
      "29" => ["power", 100],
      "30" => ["voltage", 100],
      "31" => ["voltage", 100],
      "32" => ["voltage", 100],
      "33" => ["voltage", 100],
      "34" => ["voltage", 100],
      "35" => ["count", 1],
      "36" => ["count", 1],
      "37" => ["voltage", 100],
      "38" => ["voltage", 100]
    ];

    $base_oid = "'.1.3.6.1.4.1.32050.2.1.27.";
    $idx_index = "1";
    $desc_index = "2";
    $value_index = "5";

    // hard coded to 38 sensors that are common with the TriStar MPPT
    for ($idx=0;$idx<39;$idx++) {

      $index = snmp_get($device, $base_oid.$idx_index.$idx, "-0qv");
      $desc = snmp_get($device, $base_oid.$desc_index.$idx, "-0qv");
      $value = snmp_get($device, $base_oid.$value_index.$idx, "-0qv");

      discover_sensor($valid['sensor'], $sensors->$index[0], $device,
        $base_oid.$value_index.$idx, $idx, 'sitemonitor', $desc,
        $sensors->$index[1], 1, null, null, null, null, $value);
    }

    break;

  case "6Voltmeter":

   $sensors = (object) [

     "0" => ["temperature", 10],
     "1" => ["current", 10],
     "2" => ["voltage", 10],
     "3" => ["voltage", 10],
     "4" => ["current", 10],
     "5" => ["temperature", 10],
     "6" => ["temperature", 10],
     "7" => ["voltage", 10],
     "8" => ["voltage", 10],
     "9" => ["voltage", 10],
     "10" => ["voltage", 10],
     "11" => ["current", 10],
     "12" => ["current", 10],
   ];

    //TODO
    break;
}

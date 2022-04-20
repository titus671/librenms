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
 */
$oid = '.1.3.6.1.4.1.32050.2.1.27.5.6';
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
      $validity = 0
      discover_sensor($validity['sensor'], 'voltage', $device, $oid, $index, 'sitemonitor', $desc, 10, 1, null, null, null, null, $current);
    }
  }
}

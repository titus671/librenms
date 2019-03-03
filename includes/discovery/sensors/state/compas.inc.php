<?php
/**
 * compas.inc.php
 *
 * LibreNMS state sensor discovery module for Alpha Comp@s UPS
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
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package    LibreNMS
 * @link       http://librenms.org
 * @copyright  2019 Paul Parsons
 * @author     Paul Parsons <paul@cppmonkey.net>
 */
$batteryTestState = snmp_get($device, 'es1dc1DataBatBatTestState.0', '-Ovqe', 'SITE-MONITORING-MIB');
$curOID = '.1.3.6.1.4.1.26854.3.2.1.20.1.20.1.13.3.72.0';
$index = 'es1dc1DataBatBatTestState';
if (is_numeric($batteryTestState)) {
    //Create State Index
    $state_name = 'es1dc1DataBatBatTestState';
    $states = array(
        array('value' => 0, 'generic' => 0, 'graph' => 0, 'descr' => 'Never Tested'),
        array('value' => 1, 'generic' => 0, 'graph' => 0, 'descr' => 'Success'),
        array('value' => 2, 'generic' => 1, 'graph' => 0, 'descr' => 'On Going'),
        array('value' => 3, 'generic' => 1, 'graph' => 0, 'descr' => 'Failed: Timeout'),
        array('value' => 4, 'generic' => 1, 'graph' => 0, 'descr' => 'Failed: Vbus Too Low'),
        array('value' => 5, 'generic' => 1, 'graph' => 0, 'descr' => 'Failed: Load Too Low'),
        array('value' => 6, 'generic' => 2, 'graph' => 0, 'descr' => 'Failed: AC Failure'),
        array('value' => 7, 'generic' => 1, 'graph' => 0, 'descr' => 'Failed: Canceled'),
        array('value' => 8, 'generic' => 1, 'graph' => 0, 'descr' => 'Failed: LVD Opened'),
        array('value' => 9, 'generic' => 1, 'graph' => 0, 'descr' => 'Failed: No Battery'),
    );
    create_state_index($state_name, $states);

    $descr = 'Battery Test Status';
    //Discover Sensors
    discover_sensor($valid['sensor'], 'state', $device, $curOID, $index, $state_name, $descr, '1', '1', null, null, null, null, $batteryTestState);
    //Create Sensor To State Index
    create_sensor_to_state_index($device, $state_name, $index);
}
$dcMode = snmp_get($device, 'es1dc1DataSystemDCMode.0', '-Ovqe', 'SITE-MONITORING-MIB');
$curOID = '.1.3.6.1.4.1.26854.3.2.1.20.1.20.1.13.3.1.0';
$index = 'es1dc1DataSystemDCMode';
if (is_numeric($dcMode)) {
    //Create State Index
    $state_name = 'es1dc1DataSystemDCMode';
    $states = array(
        array('value' => 0, 'generic' => 0, 'graph' => 0, 'descr' => 'Float'),
        array('value' => 1, 'generic' => 0, 'graph' => 0, 'descr' => 'Equalize'),
        array('value' => 2, 'generic' => 1, 'graph' => 0, 'descr' => 'Battery Test'),
        array('value' => 3, 'generic' => 2, 'graph' => 0, 'descr' => 'AC Failure'),
        array('value' => 5, 'generic' => 0, 'graph' => 0, 'descr' => 'Safe'),
    );
    create_state_index($state_name, $states);

    $descr = 'System DC Mode';
    //Discover Sensors
    discover_sensor($valid['sensor'], 'state', $device, $curOID, $index, $state_name, $descr, '1', '1', null, null, null, null, $dcMode);
    //Create Sensor To State Index
    create_sensor_to_state_index($device, $state_name, $index);
}
<?php
/**
 * sitemonitor.inc.php
 *
 * LibreNMS state discovery module for Packetflux SiteMonitor Base II
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
 * @copyright  2020 Josh Baird
 * @author     Josh Baird <joshbaird@gmail.com>
 *
 * @author     Trendal Toews <trendal.toews@streamitnet.com>
 *              11-08-2022
 *              Adding support for Morningstar charge state
 */
$switch = snmp_get($device, '.1.3.6.1.4.1.32050.2.1.26.5.3', '-Ovqe');

if ($switch) {
    //Create State Index
    $state_name = 'switchInput';
    $states = [
        ['value' => 0, 'generic' => 1, 'graph' => 1, 'descr' => 'Open'],
        ['value' => 1, 'generic' => 0, 'graph' => 1, 'descr' => 'Closed'],
    ];
    create_state_index($state_name, $states);

    $sensor_index = 3;
    discover_sensor(
        $valid['sensor'],
        'state',
        $device,
        '.1.3.6.1.4.1.32050.2.1.26.5.3',
        $sensor_index,
        $state_name,
        'Switch Input',
        1,
        1,
        null,
        null,
        null,
        null
    );

    create_sensor_to_state_index($device, $state_name, $sensor_index);
}

/*
*
*   Morningstar charge controller support
*
*/
$vendor_value = snmp_get($device, '.1.3.6.1.4.1.32050.2.1.28.5.0', '-Oqv');

if (str_contains('Morningstar')) {

  $product_value = snmp_get($device, '.1.3.6.1.4.1.32050.2.1.28.5.1', '-Oqv');

  switch ($product_value) {

    case 'PS-PWM-15':

      $charge_state = snmp_get($device, '.1.3.6.1.4.1.32050.2.1.28.5.3', '-Oqv');

      discover_sensor($valid['sensor'], 'state', $device,
      '.1.3.6.1.4.1.32050.2.1.28.5.3', 3, 'sitemonitor', "Charge State",
      3, 1, null, null, null, $charge_state);

    break;

    default:

    }
}

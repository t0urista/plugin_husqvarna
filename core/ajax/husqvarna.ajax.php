<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */
global $mower_dt_log;
//const MOWER_LOG_FILE = "../tmp/log/gps_log.txt";
const MOWER_LOG_FILE = "/var/www/html/tmp/log/gps_log.txt";

// ==========================================================
// Fonction de recuperation des donnees de log de la tondeuse
// ==========================================================
function get_mower_dt_log()
{
  global $mower_dt_log;
  
  // ouverture du fichier de log
  $flog = fopen(MOWER_LOG_FILE, "r");

  // lecture des donnees
  $line = 0;
  $mower_dt_log["log"] = [];
  if ($flog) {
    while (($buffer = fgets($flog, 4096)) !== false) {
      $mower_dt_log["log"][$line] = $buffer;
	  $line = $line + 1;
    }
  }

  fclose($flog);

  return;
}



try {
    require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
    include_file('core', 'authentification', 'php');

    if (!isConnect('admin')) {
        throw new Exception(__('401 - Accès non autorisé', __FILE__));
    }

	ajax::init();

    if (init('action') == 'force_detect_movers') {
		$husqvarnaCmd = husqvarna::force_detect_movers();
		ajax::success($husqvarnaCmd);
    }
  else if (init('action') == 'getLogData') {
    log::add('husqvarna', 'debug', 'get_mower_dt_log - Ajax:');
    get_mower_dt_log();
    $ret_json = json_encode ($mower_dt_log);
    //log::add('husqvarna', 'debug', 'get_mower_dt_log - Ajax:'.$ret_json);
    ajax::success($ret_json);
  }

    throw new Exception(__('Aucune methode correspondante à : ', __FILE__) . init('action'));
    /*     * *********Catch exeption*************** */
} catch (Exception $e) {
    ajax::error(displayExeption($e), $e->getCode());
}
?>

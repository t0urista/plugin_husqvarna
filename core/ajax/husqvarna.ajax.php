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
const MOWER_LOG_FILE = '/../../data/mower_log.txt';

// ==========================================================
// Fonction de recuperation des donnees de log de la tondeuse
// ==========================================================
function get_mower_dt_log($ts_start, $ts_end)
{
  global $mower_dt_log;
  
  // ouverture du fichier de log
  $log_fn = dirname(__FILE__).MOWER_LOG_FILE;
  $flog = fopen($log_fn, "r");

  // lecture des donnees
  $line = 0;
  $mower_dt_log["log"] = [];
  if ($flog) {
    while (($buffer = fgets($flog, 4096)) !== false) {
      // extrait le timestamp du log courant
      list($ts, $st, $lat, $lon) = explode(",", $buffer);
      $tsi = intval($ts);
      if (($tsi>=$ts_start) && ($tsi<=$ts_end)) {
        $mower_dt_log["log"][$line] = $buffer;
        $line = $line + 1;
      }
    }
  }

  fclose($flog);

  return;
}


// =====================================
// Gestion des commandes recues par AJAX
// =====================================
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
    //log::add('husqvarna', 'debug', 'param0:'.init('param')[0]);
    //log::add('husqvarna', 'debug', 'param1:'.init('param')[1]);
    // Param 0 et 1 sont les timestamp de debut et fin de la periode de log demandée
    get_mower_dt_log(intval (init('param')[0]), intval (init('param')[1]));
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

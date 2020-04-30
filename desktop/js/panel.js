
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
 var globalEqLogic = $("#eqlogic_select option:selected").val();
 var isCoutVisible = false;
$(".in_datepicker").datepicker();

var mower_dtlog = [];
// definition des la position de la carte
var MAP_T_LAT  = 44.79974;
var MAP_L_LON  = -0.83752;
var MAP_B_LAT  = 44.79933;
var MAP_R_LON  = -0.83692;
var MAP_WIDTH  = 600;
var MAP_HEIGHT = 587;

// Fonctions realisées au chargement de la page: charger les données sur la période par défaut,
// et afficher les infos correspondantes
// ============================================================================================
loadData();

// capturer les donnees depuis le serveur
// ======================================
function loadData(){
    var param = [];
    param[0]= (Date.parse($('#in_startDate').value())/1000);  // Time stamp en seconde
    param[1]= (Date.parse($('#in_endDate').value())/1000);
    $.ajax({
        type: 'POST',
        url: 'plugins/husqvarna/core/ajax/husqvarna.ajax.php',
        data: {
            action: 'getLogData',
            eqLogic_id: globalEqLogic,
            param: param
        },
        dataType: 'json',
        error: function (request, status, error) {
            alert("loadData:Error"+status+"/"+error);
            handleAjaxError(request, status, error);
        },
        success: function (data) {
            console.log("[loadData] Objet téléinfo récupéré : " + globalEqLogic);
            if (data.state != 'ok') {
                $('#div_alert').showAlert({message: data.result, level: 'danger'});
                return;
            }
            dt_log = jQuery.parseJSON(data.result);
            nb_dt = dt_log.log.length;
            //alert("getLogData:data nb="+nb_dt);
            mower_dtlog = [];
            for (p=0; p<nb_dt; p++) {
              mower_dtlog[p] = dt_log.log[p];
            }
            //alert("getLogData:"+mower_dtlog);
            draw_lines(rb_get_mode_value());
            
        }
    });
}

// Affiche les positions du mower_dtlog
// ====================================
function draw_lines(mode_value) {
    // mise en forme des données
    nb_pts = mower_dtlog.length;
    var dtlog_ts  = [];
    var dtlog_st  = [];
    var dtlog_lat = [];
    var dtlog_lon = [];
    var idx=0;
    var mode = 0;
	if (mode_value == "lines")
		mode = 0;
    else if (mode_value == "circles")
		mode = 1;
    for (i=0; i<nb_pts; i++) {
      tmp = mower_dtlog[i].split(',');
      if (tmp[1] == 2) {  //si etat = "OK_CUTTING"
        dtlog_ts[idx]  = tmp[0];
        dtlog_lat[idx] = tmp[2];
        dtlog_lon[idx] = tmp[3];
        idx++;
      }
    }
    // tracé des points
    var lat_height = MAP_B_LAT - MAP_T_LAT;
    var lon_width  = MAP_R_LON - MAP_L_LON;
    var canvas = document.querySelector('.myCanvas');
    var ctx = canvas.getContext('2d');
	ctx.clearRect(0, 0, canvas.width, canvas.height);
    ctx.globalAlpha = 1.0;
    ctx.setLineDash([5,5]);
    ctx.lineWidth = 2;
    //alert("draw_lines:dtlog_lat="+dtlog_lat);
    rnb_pts = dtlog_ts.length;
    var prev_ts=0;
    for (i=0; i<rnb_pts; i++) {
      // calcul de la position du point sur la carte
      xpos = Math.round(MAP_WIDTH  * (dtlog_lon[i]-MAP_L_LON) / lon_width);
      ypos = Math.round(MAP_HEIGHT * (dtlog_lat[i]-MAP_T_LAT) / lat_height);
      //alert("draw_lines:xpos="+xpos);
      if (mode == 0) {
        if ((i==0) || (dtlog_ts[i] - prev_ts>100)) {
          ctx.beginPath();
          ctx.strokeStyle = 'red';
          ctx.moveTo(xpos,ypos);
          prev_ts = dtlog_ts[i];
        }
        else {
          ctx.lineTo(xpos,ypos);
          ctx.stroke();
          ctx.beginPath();
          ctx.strokeStyle = 'red';
          ctx.moveTo(xpos,ypos);
          prev_ts = dtlog_ts[i];
        }
      }
      else if (mode == 1) {
        // trace cercle autour du point courant
        ctx.beginPath();
        ctx.globalAlpha = 0.4;
		ctx.fillStyle='red';  //"#FF4422"
        ctx.arc(xpos, ypos, 8, 0, 2 * Math.PI);
        ctx.fill();
      
      }
    }

}

// gestion des radio buttons : mode d'historique
// =============================================
const rb_lines = document.querySelector('#rb_lines');
const rb_circles = document.querySelector('#rb_circles');

rb_lines.addEventListener('change', update_GPS_History);
rb_circles.addEventListener('change', update_GPS_History);

function update_GPS_History(e) {
  //alert("update_GPS_History:"+e.target.value);
  draw_lines(e.target.value);
}

function rb_get_mode_value() {
  var rb_list = document.getElementsByName('hist_mode');;
  //alert(rb_list.length);
  for (i=0; i<rb_list.length; i++) {
    if (rb_list[i].checked == true) {
      //alert(rb_list[i].value + ' you got a value');     
      return rb_list[i].value;
    }
  }
  
}
// gestion du bouton mise à jour de la période
// ===========================================
$('#bt_validChangeDate').on('click',function(){
  loadData();
});

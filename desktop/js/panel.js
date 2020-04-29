
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
 var globalEqLogic = $( "#eqlogic_select option:selected" ).val();
 var isCoutVisible = false;
$(".in_datepicker").datepicker();


    var canvas = document.querySelector('.myCanvas');
    var ctx = canvas.getContext('2d');
    ctx.globalAlpha = 1.0;
    hist = "263,154/313,192/328,213/214,202/270,189/339,168/314,268/298,287/336,287/330,277/234,226/262,175/272,165/250,224/251,170/329,216/353,239/286,296/291,312/311,264/312,159/314,119/287,173/282,271/343,242/324,205/243,159/297,211/359,274/291,255/283,218/327,157/321,269/311,283/270,181/268,186/347,259/319,280/332,293/313,218/282,133/297,234/310,309/330,216/316,155/317,178/323,268/288,340/287,340/280,319/";
    list_of_points = hist.split('/');
    ctx.setLineDash([5,5]);
    ctx.lineWidth = 2;
    var i;
    for (i=0; i<51; i++) {
      point = list_of_points[i].split(',');
      if (i==0) {
        ctx.beginPath();
        ctx.strokeStyle = 'red';
        ctx.moveTo(point[0]*2,point[1]*2);
        }
      else {
        ctx.lineTo(point[0]*2,point[1]*2);
        ctx.stroke();
        ctx.beginPath();
        ctx.globalAlpha = 1.0-(i*0.015);
        ctx.strokeStyle = 'red';
        ctx.moveTo(point[0]*2,point[1]*2);
        }
    }
    // trace cercle autour du point courant
    ctx.beginPath();
    ctx.strokeStyle = 'GreenYellow';
    ctx.globalAlpha = 1.0;
    ctx.lineWidth = 4;
    ctx.setLineDash([]);
    point = list_of_points[0].split(',');
    ctx.arc(point[0]*2, point[1]*2, 8, 0, 2 * Math.PI);
    ctx.stroke();

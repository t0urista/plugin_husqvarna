<?php
if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$date = array(
	'start' => date('Y-m-d', strtotime(config::byKey('history::defautShowPeriod') . ' ' . date('Y-m-d'))),
	'end' => date('Y-m-d'),
);
sendVarToJS('eqType', 'husqvarna');
sendVarToJs('object_id', init('object_id'));
$eqLogics = eqLogic::byType('husqvarna');
?>


<div class="row" id="div_husqvarna">
    <div class="row">
        <div class="col-lg-8 col-lg-offset-2" style="height: 450px;padding-top:10px;">

			<form class="form-horizontal">
				<fieldset style="border: 1px solid #e5e5e5; border-radius: 5px 5px 0px 5px;background-color:#f8f8f8">
					<div style="padding-top:10px;padding-left:24px;color: #333;font-size: 1.5em;"> <span id="spanTitreResume">Historique d'utilisation du robot tondeuse</span>
            <select id="eqlogic_select" style="color: #555;font-size: 15px;border-radius: 3px;border:1px solid #ccc;">
              <?php
              foreach ($eqLogics as $eqLogic) {
                echo '<option value="' . $eqLogic->getId() . '">"' . $eqLogic->getHumanName(true) . '"</option>';
              }
              ?>
            </select>
            <a style="margin-right:5px;" class="pull-right btn btn-success btn-sm tooltips" id='bt_validChangeDate' title="{{Attention une trop grande plage de dates peut mettre très longtemps à être calculée ou même ne pas s'afficher}}">{{Ok}}</a>
            <input id="in_endDate" class="pull-right form-control input-sm in_datepicker" style="display : inline-block; width: 87px;" value="<?php echo $date['end']?>"/>
            <input id="in_startDate" class="pull-right form-control input-sm in_datepicker" style="display : inline-block; width: 87px;" value="<?php echo $date['start']?>"/>
          </div>

					<div class="form-horizontal" style="min-height: 35px;">
					</div>

					<?php
            echo '<img class="pull-right" src="plugins/husqvarna/ressources/automower.png" height="255" width="480" />';
					?>

				</fieldset>
			</form>
        </div>
		<div class="col-lg-2">
		</div>
        </div>
        <div class="row">
    		<div class="col-lg-8 col-lg-offset-2">
                <form class="form-horizontal">
                     <fieldset style="border: 1px solid #e5e5e5; border-radius: 5px 5px 5px 5px;background-color:#f8f8f8">
                         <div style="padding-top:10px;padding-left:24px;padding-bottom:25px;color: #333;font-size: 1.5em;">
                             <i style="font-size: initial;" class="fas fa-chart-line"></i> {{Statistiques d'utilisation}}
                         </div>
                         <div id='div_hist_usage'></div>

                         <div style="padding-top:10px;padding-left:24px;padding-bottom:25px;color: #333;font-size: 1.5em;">
                             <i style="font-size: initial;" class="fas fa-chart-bar"></i> {{Historique des positions GPS}}
                         </div>
                         <div id='div_hist_gps'>
						   <div style="width:800px;height:783px;">
						     <canvas class="myCanvas" width="800" height="783" style="background:url('plugins/husqvarna/ressources/maison_pn.png') no-repeat center center;border:5px solid #000000;">
						     </canvas>
						   </div> 
						 </div>
                     </br>
                     </fieldset>
                     <div style="min-height: 10px;"></div>
                 </form>
    		</div>
        </div>
    </div>


<?php include_file('desktop', 'panel', 'js', 'husqvarna');?>
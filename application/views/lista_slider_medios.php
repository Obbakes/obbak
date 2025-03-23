<?php
	if($medios != false){
		$i = 0;
		foreach ($medios as $medio) { 
			if($administrador){
				if($i == 0){
					echo '<div><span style="display: inline-block; width: 5px;height: 10px"></span><img style="height: 87px;" src="' . base_url() . $medio->logo . '" /><span style="display: inline-block; width: 5px;height: 10px"></span></div>';
				}
				else{
					echo '<div><span style="display: inline-block; width: 5px;height: 10px"></span><img style="height: 87px;" src="' . base_url() . $medio->logo . '" /><span style="display: inline-block; width: 5px;height: 10px"></span></div>';
				}
				$i++;
			}
			else{
				if($i == 0){
					echo '<div><span style="display: inline-block; width: 5px;height: 10px"></span><a href=""><img src="' . base_url() . $medio->logo . '" /></a><span style="display: inline-block; width: 5px;height: 10px"></span></div>';
				}
				else{
					echo '<div><span style="display: inline-block; width: 5px;height: 10px"></span><a href=""><img src="' . base_url() . $medio->logo . '" /></a><span style="display: inline-block; width: 5px;height: 10px"></span></div>';
				}
				$i++;
			}
		} 	
    }
?>

<?php
	if(!empty($medios)){
		foreach($medios as $medio){
?>
<option value="<?php echo $medio->id_medio; ?>" <?php echo ($medio->id_medio == $seleccionado) ? 'selected="selected"' : ''; ?>
		data-logo="<?php echo base_url() . $medio->logo; ?>">
	<?php echo $medio->nombre; ?>
</option>
<?php
		}
	}
?>
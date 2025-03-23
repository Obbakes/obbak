<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	</head>
	<body>
		<div align="center">
			<h2 style="max-width: 694px; text-align: center; font-weight: normal; font-size: 26px; color: #002684; width: 100%; 
					font-family: calibri; line-height: 22px; padding: 5%;">
				<img src="<?php echo base_url(); ?>images/logo.png" style="height: 50px; padding-bottom: 4%;" />
				<br />
				Su Gestor de Medios, <?php echo $nombre; ?>, ha sido dado de alta en Bimads
			</h2>
			<table cellspacing="0" cellpadding="0" border="0" style="border: 1px solid lightgray; width: 100%; border-spacing: 0; 
					font-family: calibri; color: #333; margin-bottom: 10px;">
				<tr>
					<td width="100%" style="padding: 15px 23px 5px 23px; text-align: center;" bgcolor="white">
						<span style="">
							Sus datos de acceso son:
						</span>
					</td>
				</tr>
				<tr>
					<td width="100%" style="padding: 5px 23px 5px 23px; text-align: center;" bgcolor="white">
						<span style="font-weight: bold; font-size: 18px; color: gray;">
							Usuario: <?php echo $usuario; ?>
						</span>
					</td>
				</tr>
				<tr>
					<td width="100%" style="padding: 5px 23px 5px 23px; text-align: center;" bgcolor="white">
						<span style="font-weight: bold; font-size: 18px; color: gray;">
							Pass: <?php echo $pass; ?>
						</span>
					</td>
				</tr>
				<tr>
					<td width="100%" height="5px" style="" bgcolor="white">
						<hr style="width: 96%; background-color: #D3D3D3; height: 1px; border: 0px none;">
					</td>
				</tr>
				<tr>
					<td width="100%" style="text-align: right; padding: 5px 15px 15px 0px;" bgcolor="white">
						<a href="<?php echo base_url(); ?>" target="_blank" style="text-decoration: none;">
							<div style="margin-left: auto; padding: 5px 0; border-radius: 4px; background: #ff8600; 
									text-align: center; color: white; font-weight: bold; font-size: 15px; width: 150px;">
								Ir a Bimads
							</div>
						</a>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>
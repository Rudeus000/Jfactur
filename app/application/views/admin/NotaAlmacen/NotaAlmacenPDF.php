<img width="150px" id="logo-archivo" src="<?= base_url_app('assets/uploads/logo/'.$logo) ?>" alt="Logo">
<div><?php echo $empresa->nombre_comercial;?></div>
<div><?php echo $empresa->ruc_emp;?></div>
<div><?php echo $empresa->direcc_emp.' '.$empresa->ubicacion;?></div>
<div>&nbsp;</div>
<table class="table table-bordered">
	<tbody>
		<tr>
			<td colspan="7"  align="center"><h2><?php 
			if($datos[0]->Tipo_Nota =="I"){
				?>
				Nota de ingreso <?php echo $datos[0]->Serie_Nota; ?>-<?php echo $datos[0]->Num_Nota;?>
				<?php
			}
			else{
				?>
				Nota de salida <?php echo $datos[0]->Serie_Nota ;?>-<?php echo $datos[0]->Num_Nota;?>
				<?php				
			}
				?></h2></td>
			
		</tr>
		<tr>

			<td colspan="4">Moneda</td>
			<td><?php
			if($datos[0]->ccod_mon=="S"){
				echo "Soles";
			}else{
				echo "Dolares";
			}				
			?></td>	
			<td>Tipo cambio</td>
			<td><?php echo $datos[0]->nt_cambio ;?></td>	
		</tr>
		<tr>
			<td>Fecha</td>
			<td colspan="6" ><?php echo $datos[0]->Fecha_Nota ;?></td>			
		</tr>
		<tr>
			<td>Razón social</td>
			<td colspan="6" ><?php echo $datos[0]->doc_cliente ;?> - <?php echo $datos[0]->nomb_cliente ;?></td>			
		</tr>
		<tr>
			<td>Motivo</td>
			<td colspan="6" ><?php echo $datos[0]->des_motivo ;?></td>			
		</tr>
		<tr>
			<td>Doc. Referencia</td>
			<td colspan="6" ><?php echo $datos[0]->nom_tipdocumento ;?> - <?php echo $datos[0]->serie_doc_ref ;?> - <?php echo $datos[0]->num_doc_ref ;?></td>			
		</tr>
		<tr>
			<th align="center">ITEM</th>
			<th align="center">CODIGO</th>
			<th align="center">DESCRIPCION</th>
			<th align="center">UNIDAD</th>
			<th align="center">CANTIDAD</th>
			<th align="center">COSTO</th>			
			<th align="center">SUBTOTAL</th>			
		</tr>
		<?php
		$item=1;
		$total=0;
		foreach ($datos as $d): ?>		
		<tr>
			<td><?php echo $item;?></td>
			<td><?php echo $d->ccod_art; ?></td>
			<td><?php echo $d->cdsc_art; ?></td>
			<td><?php echo $d->nomb_tipunidad; ?></td>
			<td align="right"><?php echo number_format($d->nund,3,".",","); ?></td>			
			<td align="right"><?php echo number_format($d->ncosto,3,".",","); ?></td>
			<td align="right"><?php echo number_format(($d->ncosto*$d->nund),3,".",","); ?></td>
		</tr>
		<?php 
		$total=$total+($d->ncosto*$d->nund);
		$item++;
		endforeach 
		?>
		<tr>
			<td>TOTAL</td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
			<td></td>			
			<td align="right"><?php echo number_format($total,3,".",",");?></td>			
		</tr>
	</tbody>
</table>
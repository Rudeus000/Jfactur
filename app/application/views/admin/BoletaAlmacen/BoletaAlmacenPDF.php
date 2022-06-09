<table class="table table-bordered">
	<tbody>
		<tr>
			<td colspan="5" ><?php 
			if($datos[0]->Tipo_Nota =="I"){
				?>
				Boleta de ingreso <?php echo $datos[0]->Serie_Nota; ?>-<?php echo $datos[0]->Num_Nota;?>
				<?php
			}
			else{
				?>
				Boleta de salida <?php echo $datos[0]->Serie_Nota ;?>-<?php echo $datos[0]->Num_Nota;?>
				<?php				
			}
				?></td>
			
		</tr>
		<tr>
			<td>Almacén</td>
			<td colspan="4" ><?php echo $datos[0]->nomb_almacen ;?></td>			
		</tr>
		<tr>
			<td>Fecha</td>
			<td colspan="4" ><?php echo $datos[0]->Fecha_Nota ;?></td>			
		</tr>
		<tr>
			<td>Razón social</td>
			<td colspan="4" ><?php echo $datos[0]->doc_cliente ;?> - <?php echo $datos[0]->nomb_cliente ;?></td>			
		</tr>
		<tr>
			<td>Motivo</td>
			<td colspan="4" ><?php echo $datos[0]->des_motivo ;?></td>			
		</tr>
		<tr>
			<td>Doc. Referencia</td>
			<td colspan="4" ><?php echo $datos[0]->nom_tipdocumento ;?> - <?php echo $datos[0]->serie_doc_ref ;?> - <?php echo $datos[0]->num_doc_ref ;?></td>			
		</tr>
		<tr>
			<th>ITEM</th>
			<th>CODIGO</th>
			<th>DESCRIPCION</th>
			<th>UNIDAD</th>
			<th>CANTIDAD</th>		
		</tr>
		<?php
		$item=1;
		foreach ($datos as $d): ?>		
		<tr>
			<td><?php echo $item;?></td>
			<td><?php echo $d->ccod_art; ?></td>
			<td><?php echo $d->cdsc_art; ?></td>
			<td><?php echo $d->ccod_undmed; ?></td>
			<td><?php echo $d->nund; ?></td>			
		</tr>
		<?php 
		$item++;
		endforeach 
		?>
	</tbody>
</table>
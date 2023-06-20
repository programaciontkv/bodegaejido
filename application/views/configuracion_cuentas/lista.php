<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<section class="content-header">
      <h1>
        Configuracion de Cuentas para Asientos
      </h1>
</section>
<section class="content">
	<div class="box box-solid">
		<div class="box box-body">
			<div class="row">
				<div class="col-md-12">
					<form  id="frm_save" role="form" action="<?php echo $action?>" method="post" autocomplete="off">
					<table id="tbl_list" class="table table-bordered table-list table-hover">
						<thead>
							<tr>
								<div class="row">
									<div class="col-md-12">
										<?php 
										if($permisos->rop_insertar){
										?>
											<button type="button" onclick="save()" class="btn btn-primary">Guardar</button>
										<?php 
										}
										?>
									</div>	
								</div>
							</tr>
							<tr>
								<th>No</th>
								<th>Parametro</th>
						        <th>Cuenta</th>
						        <th>Descripcion</th>
						        <th></th>
							</tr>	
						</thead>
						<tbody>
						<?php 
						$grup="";
				        $tipo="";
				        $n=0;
						foreach ($configuraciones as $conf) {
							$n++;
							if($grup!=$conf->cas_tipo_doc){
								switch ($conf->cas_tipo_doc) {
									case 1:
										$tipo="FACTURA";
										break;
									case 2:
										$tipo="NOTA DE CREDITO";
										break;	
									case 3:
										$tipo="NOTA DE DEBITO";
										break;		
									case 4:
										$tipo="RETENCION";
										break;			
									case 5:
										$tipo="REGISTRO FACTURA";
										break;
									case 6:
										$tipo="REGISTRO NOTA DE CREDITO";
										break;		
									case 7:
										$tipo="REGISTRO NOTA DE DEBITO";
										break;
									case 8:
										$tipo="REGISTRO RETENCION";
										break;				
									
									
								}
					            
							
						?>	
							<tr>
								<td colspan="4" class="total">
									 <b><?php echo $tipo?></b>
								</td>
							</tr>
						<?php
						}
						?>	
						<tr>
							<td><?php echo $n ?></td>
							<td><?php echo $conf->cas_descripcion ?></td>
							<td>
								<input type="text" name="pln_codigo<?php echo $n?>" id="pln_codigo<?php echo $n?>" class="form-control" lang="<?php echo $n?>" value="<?php echo $conf->pln_codigo?>" onchange="traer_cuenta(this)" list="list_cuentas">
								<input type="text" name="pln_id<?php echo $n?>" id="pln_id<?php echo $n?>" value="<?php echo $conf->pln_id?>" lang="<?php echo $n?>" hidden>
								<input type="text" name="cas_id<?php echo $n?>" id="cas_id<?php echo $n?>" value="<?php echo $conf->cas_id?>" lang="<?php echo $n?>" hidden>
							</td>
							<td>
								<input type="text" name="pln_descripcion<?php echo $n?>" id="pln_descripcion<?php echo $n?>" class="form-control" lang="<?php echo $n?>" value="<?php echo $conf->pln_descripcion?>" readonly>
							</td>
						</tr>
						<?php
						$grup=$conf->cas_tipo_doc;
						}
						?>	
							
						</tbody>
					</table>
				</form>
				<input type="text" name="detalle" id="detalle" class="form-control" value="<?php echo $n?>" readonly>
				</div>	
			</div>
		</div>
	</div>
	<datalist id="list_cuentas">
	    <?php
	        foreach ($cuentas as $cuenta) {
	    ?>
	        <option value="<?php echo $cuenta->pln_id?>"><?php echo $cuenta->pln_codigo.' '.$cuenta->pln_descripcion?></option>
	    <?php        
	        }
	    ?>
	</datalist>
	<script type="text/javascript">
	
	var base_url='<?php echo base_url();?>';
	var mensaje ='<?php echo $mensaje;?>';

    swal("", mensaje, "info");
	
	function alert(){
		

		if ($('#val_inventario2').prop('checked') == true) {
		 			Swal.fire("Aviso!", "Ha retirado la validación de existencias en facturación.\n Esto puede provocar inconvenientes o negativos en bodega.!", "warning");
		 		}
	}
		
		function save(){


			 	Swal.fire({
		  	title: '¿Esta seguro guardar la información?',
			  showCancelButton: true,
			  confirmButtonText: 'Guardar',
			  denyButtonText: `Cancelar`,
				}).then((result) => {
		  		
		 		 if (result.isConfirmed) {
		  			$('#frm_save').submit();
		  		} else if (result.isDenied) {
		  			///accion si no
		  		}
				})
                   
           
		}

	function traer_cuenta(obj) {
        var uri = base_url+'configuracion_cuentas/traer_cuenta/'+ $(obj).val();
        alert(uri);
        j=obj.lang;
        $.ajax({
            url: uri, //this is your uri
            type: 'GET', //this is your method
            dataType: 'json',
            success: function (response) {
                $("#pln_id"+j).val(response['pln_id']);
                $("#pln_codigo"+j).val(response['pln_codigo']);
                $("#pln_descripcion"+j).val(response['pln_descripcion']);
            },
            error : function(xhr, status) {
                alert('No existe Cuenta');
                $("#pln_id"+j).val('0');
                $("#pln_codigo"+j).val('');
                $("#pln_descripcion"+j).val('');
            }
        });
    }	
		
	</script>


</section>


<section class="content">
  <table width="100%">
    <tr>
        <td>
            <table id="login">
                <tr>
                    <td><img src="<?php echo base_url().'imagenes/'.$retencion->emp_logo?>" width="250px" height="100px"></td>
                </tr>    
            </table>
        </td>
        <td rowspan="2" width="52%">
            <table id="encabezado2" width="100%">
                <tr>
                    <th class="titulo">R.U.C: </th>
                    <th class="titulo"><?php echo $retencion->emp_identificacion?></th>
                </tr>
                <tr>
                    <th class="titulo" colspan="2">COMPROBANTE DE RETENCION</th>
                </tr>    
                <tr>
                    <th colspan="2">No. <?php echo $retencion->ret_numero?></th>
                </tr>    
                <tr>
                    <th colspan="2">NUMERO DE AUTORIZACION</th>
                </tr>    
                <tr>
                    <th colspan="2"><?php echo $retencion->ret_autorizacion?></th>
                </tr>    
                <tr>
                    <th>FECHA Y HORA DE AUTORIZACION</th>
                    <th><?php echo $retencion->ret_fec_hora_aut?></th>
                </tr>    
                <tr>
                    <?php 
                    switch ($ambiente->con_valor) {
                      case 0:
                        $amb='';
                        break;
                      case 1:
                        $amb='PRUEBAS';
                        break;
                      case 2:
                        $amb='PRODUCCION';
                        break;  
                    }
                    ?>
                    <th>AMBIENTE</th>
                    <th><?php echo $amb?></th>
                </tr>    
                <tr>
                    <th>EMISION</th>
                    <th>NORMAL</th>
                </tr>    
                <tr>
                    <th>CLAVE DE ACCESO</th>
                </tr>
                <tr> 
                    <td colspan="2">
                        <img src="<?php echo base_url();?>barcodes/<?php echo $retencion->ret_clave_acceso?>.png" alt="" width="350px" height="40px">
                    </td>
                </tr> 
                <tr>
                    <th colspan="2" style="font-size: 11px; text-align: center"><?php echo $retencion->ret_clave_acceso?></th>
                 </tr>    
            </table>
        </td>
    </tr>    
    <tr>    
        <td width="48%" valign="bottom">
            <table id="encabezado1" width="98%">
                <tr>
                    <th colspan="2" class="titulo"><?php echo $retencion->emp_nombre?></th>
                </tr>    
                <tr>
                    <th>Direccion Matriz:</th>
                    <th><?php echo $retencion->emp_direccion?></th>
                </tr>
                <tr>
                    <th>Direccion Sucursal:</th>
                    <th><?php echo $retencion->emi_dir_establecimiento_emisor?></th>
                </tr>
                <?php 
                if(!empty($factura->emp_contribuyente_especial)){
                ?>
                <tr>
                    <th colspan="2">Cotribuyente Especial Nro:</th>
                    <th><?php echo $retencion->emp_contribuyente_especial?></th>
                </tr>  
                <?php 
                }
                ?>      
                <tr>
                    <th colspan="2">OBLIGADO A LLEVAR CONTABILIDAD:</th>
                    <th><?php echo $retencion->emp_obligado_llevar_contabilidad?></th>
                </tr>    
            </table>
        </td>
        
    </tr>
    <tr>
        <td colspan="2">
            <table id="encabezado3" width="100%">
                <tr>
                    <th>Razon Social / Nombres y Apellidos: <?php echo $retencion->ret_nombre?></th>
                    <th>Identificacion: <?php echo $retencion->ret_identificacion?></th>
                </tr>    
                <tr>
                    <th>Fecha de emision: <?php echo $retencion->ret_fecha_emision?></th>
                    
                </tr> 
            </table>
        </td>
    </tr>      
    <tr>
        <td colspan="2">
            <table id="detalle" width="100%" border="1">
                <thead>
                    <tr>
                        <th style="width:20px">Comprobante</th>
                        <th style="width:70px">Numero</th>
                        <th style="width:30px">Ejercicio Fiscal</th>
                        <th style="width:70px">Base Imponible para la Retencion</th>
                        <th style="width:30px">Impuesto</th>
                        <th style="width:30px">Porcentaje de Retencion</th>
                        <th style="width:30px">Valor Retenido</th>
                    </tr> 
                </thead> 
                <tbody>
                    <?php
                    $dec=$dec->con_valor;
                    $dcc=$dcc->con_valor;
                    foreach ($cns_det as $det) {
                        if($det->dtr_tipo_impuesto='IVA'){
                            $imp='IVA';
                        } else if($det->dtr_tipo_impuesto='IR'){
                            $imp='RENTA';
                        } else if($det->dtr_tipo_impuesto='IC'){
                            $imp='ICE';
                        }
                    ?>
                    <tr>
                        <td>FACTURA</td>
                        <td><?php echo $retencion->ret_num_comp_retiene?></td>
                        <td><?php echo $det->dtr_ejercicio_fiscal?></td>
                        <td class="numerico"><?php echo number_format($det->dtr_base_imponible,$dec)?></td>
                        <td><?php echo $imp?></td>
                        <td class="numerico"><?php echo number_format($det->dtr_procentaje_retencion,$dec)?></td>
                        <td class="numerico"><?php echo number_format($det->dtr_valor,$dec)?></td>
                    </tr>
                    <?php
                     } 
                    ?> 
                </tbody> 
                <tbody>
                    <tr>
                        <td id="info" colspan="7" valign="top">
                            <table>
                                <tr>
                                    <th>Informacion Adicional</th>
                                </tr>
                                <tr>
                                    <td>Direccion</td>
                                    <td><?php echo $retencion->ret_direccion?></td>
                                </tr>
                                <tr>
                                    <td>Email</td>
                                    <td><?php echo $retencion->cli_email?></td>
                                </tr>
                            </table>
                        </td>
                        
                    </tr>
                    
                </tbody>   
            </table>
        </td>
    </tr>    
</table>

<style type="text/css">
    *{
        font-size: 13px;
    }
    .numerico {
        text-align: right;
    }

    #encabezado1,#encabezado2, #encabezado3 {
        border: 1px solid;
        text-align: left;
    }

    #detalle {
        border: 1px solid;
        border-collapse: collapse;
    }

    #encabezado1 td, #encabezado1 th, #encabezado2 td, #encabezado2 th, #encabezado3 td, #encabezado3 th{
        text-align: left;
    }
    #detalle td, #detalle th{
        border: 1px solid;
        
    }

    #info td, #info th{
        border: none;
    }

    .titulo{
        font-size: 15px;
    }



</style>


         


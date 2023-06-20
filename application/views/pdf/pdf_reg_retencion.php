<section class="content">
  <!-- <td><img src="<?php echo base_url().'imagenes/logo_empresa.jpg'?>" width="250px" height="100px"></td> -->
  <table width="100%">
    <tr>
        <td>
            <table id="login">
                <tr>
                    <td>
                        
                    </td>
                </tr>    
            </table>
        </td>
        <td rowspan="2" width="52%">
            <table id="encabezado2" width="100%">
                <tr>
                    <th class="titulo">R.U.C: </th>
                    <td class="titulo"><?php echo $retencion->cli_ced_ruc?></td>
                </tr>
                <tr>
                    <th class="titulo" colspan="2">COMPROBANTE DE RETENCION</th>
                </tr>    
                <tr>
                    <th colspan="2">No. <?php echo $retencion->rgr_numero?></th>
                </tr>    
                <tr>
                    <th colspan="2">NUMERO DE AUTORIZACION</th>
                </tr>    
                <tr>
                    <th><?php echo $retencion->rgr_autorizacion?></th>
                </tr>    
                <tr>
                    <th>FECHA Y HORA DE AUTORIZACION</th>
                    <td><?php echo $retencion->rgr_fec_autorizacion?></td>
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
            </table>
        </td>
    </tr>    
    <tr>    
        <td width="48%">
            <table id="encabezado1" width="98%">
                <tr>
                    <th class="titulo"><?php echo $retencion->cli_raz_social?></th>
                </tr>    
                <tr>
                    <th>Direccion Matriz:</th>
                    <th><?php echo $retencion->cli_calle_prin?></th>
                </tr>
            </table>
        </td>
        
    </tr>
    <tr>
        <td colspan="2">
            <table id="encabezado3" width="100%">
                <tr>
                    <th>Razon Social / Nombres y Apellidos: <?php echo $retencion->emp_nombre?></th>
                    <th>Identificacion: <?php echo $retencion->emp_identificacion?></th>
                </tr>    
                <tr>
                    <th>Fecha de emision: <?php echo $retencion->rgr_fecha_emision?></th>
                    
                </tr> 
            </table>
        </td>
    </tr>      
    <tr>
        <td colspan="2">
            <table id="detalle" width="100%" border="1">
                <thead>
                    <tr>
                        <th>Comprobante</th>
                        <th>Numero</th>
                        <th>Ejercicio Fiscal</th>
                        <th style="width:70px">Base Imponible para la Retencion</th>
                        <th style="width:70px">Impuesto</th>
                        <th style="width:70px">Porcentaje de Retencion</th>
                        <th style="width:70px">Valor Retenido</th>
                    </tr> 
                </thead> 
                <tbody>
                    <?php
                    $dec=$dec->con_valor;
                    $dcc=$dcc->con_valor;
                    foreach ($cns_det as $det) {
                        if($det->drr_tipo_impuesto='IVA'){
                            $imp='IVA';
                        } else if($det->drr_tipo_impuesto='IR'){
                            $imp='RENTA';
                        } else if($det->drr_tipo_impuesto='IC'){
                            $imp='ICE';
                        }
                    ?>
                    <tr>
                        <td>FACTURA</td>
                        <td><?php echo $retencion->rgr_num_comp_retiene?></td>
                        <td><?php echo $det->drr_ejercicio_fiscal?></td>
                        <td class="numerico"><?php echo number_format($det->drr_base_imponible,$dcc)?></td>
                        <td><?php echo $imp?></td>
                        <td class="numerico"><?php echo number_format($det->drr_procentaje_retencion,$dec)?></td>
                        <td class="numerico"><?php echo number_format($det->drr_valor,$dec)?></td>
                    </tr>
                    <?php
                     } 
                    ?> 
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


         


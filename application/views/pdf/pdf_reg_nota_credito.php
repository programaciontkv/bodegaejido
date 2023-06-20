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
                    <td class="titulo"><?php echo $nota->cli_ced_ruc?></td>
                </tr>
                <tr>
                    <th class="titulo" colspan="2">NOTA DE CREDITO</th>
                </tr>    
                <tr>
                    <th colspan="2">No. <?php echo $nota->rnc_numero?></th>
                </tr>    
                <tr>
                    <th colspan="2">NUMERO DE AUTORIZACION</th>
                </tr>    
                <tr>
                    <th><?php echo $nota->rnc_autorizacion?></th>
                </tr>    
                <tr>
                    <th>FECHA Y HORA DE AUTORIZACION</th>
                    <td><?php echo $nota->rnc_fec_autorizacion?></td>
                </tr>    
            </table>
        </td>
    </tr>    
    <tr>    
        <td width="48%">
            <table id="encabezado1" width="98%">
                <tr>
                    <th class="titulo"><?php echo $nota->cli_raz_social?></th>
                </tr>    
                <tr>
                    <th>Direccion Matriz:</th>
                    <th><?php echo $nota->cli_calle_prin?></th>
                </tr>
            </table>
        </td>
        
    </tr>
    <tr>
        <td colspan="2">
            <table id="encabezado3" width="100%">
                <tr>
                    <th>Razon Social / Nombres y Apellidos: <?php echo $nota->emp_nombre?></th>
                    <th>Identificacion: <?php echo $nota->emp_identificacion?></th>
                </tr>    
                <tr>
                    <th>Fecha de emision: <?php echo $nota->rnc_fecha_emision?></th>
                    
                </tr> 
                <tr>
                    <th colspan="2" style="text-align:center">_____________________________________________________________________________________________</th>
                </tr>   
                <tr>
                    <th>Comprobante que se modifica: <?php echo $nota->rnc_num_comp_modifica?></th>
                </tr>   
                <tr>    
                    <th>Fecha Emision (Comprobante a modificar): <?php echo $nota->rnc_fecha_emi_comp?></th>
                </tr>    
                <tr>    
                    <th>Razon de Modificacion: <?php echo $nota->rnc_motivo?></th>
                </tr>    
            </table>
        </td>
    </tr>      
    <tr>
        <td colspan="2">
            <table id="detalle" width="100%">
                <thead>
                    <tr>
                        <th>Cod.Principal</th>
                        <th style="width:70px">Cantidad</th>
                        <th>Descripcion</th>
                        <th style="width:70px">P.U</th>
                        <th style="width:70px">Descuento</th>
                        <th style="width:70px">Precio Total</th>
                    </tr> 
                </thead> 
                <tbody>
                    <?php
                    $dec=$dec->con_valor;
                    $dcc=$dcc->con_valor;
                    foreach ($cns_det as $det) {
                    ?>
                    <tr>
                        <td><?php echo $det->pro_codigo?></td>
                        <td class="numerico"><?php echo number_format($det->cantidad,$dcc)?></td>
                        <td><?php echo $det->pro_descripcion?></td>
                        <td class="numerico"><?php echo number_format($det->pro_precio,$dec)?></td>
                        <td class="numerico"><?php echo number_format($det->pro_descuento,$dec)?></td>
                        <td class="numerico"><?php echo number_format($det->precio_tot,$dec)?></td>
                    </tr>
                    <?php
                     } 
                    ?> 
                </tbody> 
                <tbody>
                    <tr>
                        <td id="info" colspan="3" rowspan="8" valign="top">
                        </td>
                        <th colspan="2">SUBT 12%</th>
                        <td class="numerico"><?php echo number_format($nota->rnc_subtotal12,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">SUBT IVA 0%</th>
                        <td class="numerico"><?php echo number_format($nota->rnc_subtotal0,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">SUBT EX IVA</th>
                        <td class="numerico"><?php echo number_format($nota->rnc_subtotal_ex_iva,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">SUBT NO-OBJ IVA</th>
                        <td class="numerico"><?php echo number_format($nota->rnc_subtotal_no_iva,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">SUBT SIN IMPUESTOS</th>
                        <td class="numerico"><?php echo number_format($nota->rnc_subtotal,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">DESCUENTO</th>
                        <td class="numerico"><?php echo number_format($nota->rnc_total_descuento,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">IVA 12%</th>
                        <td class="numerico"><?php echo number_format($nota->rnc_total_iva,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">VALOR TOTAL</th>
                        <td class="numerico"><?php echo number_format($nota->rnc_total_valor,$dec)?></td>
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


         


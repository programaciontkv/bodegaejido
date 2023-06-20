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
                    <td class="titulo"><?php echo $factura->cli_ced_ruc?></td>
                </tr>
                <tr>
                    <th class="titulo" colspan="2">FACTURA</th>
                </tr>    
                <tr>
                    <th colspan="2">No. <?php echo $factura->reg_num_documento?></th>
                </tr>    
                <tr>
                    <th colspan="2">NUMERO DE AUTORIZACION</th>
                </tr>    
                <tr>
                    <th><?php echo $factura->reg_num_autorizacion?></th>
                </tr>    
            </table>
        </td>
    </tr>    
    <tr>    
        <td width="48%">
            <table id="encabezado1" width="98%">
                <tr>
                    <th class="titulo"><?php echo $factura->cli_raz_social?></th>
                </tr>    
                <tr>
                    <th>Direccion:</th>
                    <th><?php echo $factura->cli_calle_prin?></th>
                </tr>
            </table>
        </td>
        
    </tr>
    <tr>
        <td colspan="2">
            <table id="encabezado3" width="100%">
                <tr>
                    <th>Fecha de emision: <?php echo $factura->reg_femision ?></th>
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
                        <th colspan="3" rowspan="8" hidden></th>
                        <th colspan="2">SUBT 12%</th>
                        <td class="numerico"><?php echo number_format($factura->reg_sbt12,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">SUBT IVA 0%</th>
                        <td class="numerico"><?php echo number_format($factura->reg_sbt0,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">SUBT EX IVA</th>
                        <td class="numerico"><?php echo number_format($factura->reg_sbt_excento,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">SUBT NO-OBJ IVA</th>
                        <td class="numerico"><?php echo number_format($factura->reg_sbt_noiva,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">SUBT SIN IMPUESTOS</th>
                        <td class="numerico"><?php echo number_format($factura->reg_sbt,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">DESCUENTO</th>
                        <td class="numerico"><?php echo number_format($factura->reg_tdescuento,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">IVA 12%</th>
                        <td class="numerico"><?php echo number_format($factura->reg_iva12,$dec)?></td>
                    </tr>
                    <tr>
                        <th colspan="2">VALOR TOTAL</th>
                        <td class="numerico"><?php echo number_format($factura->reg_total,$dec)?></td>
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


         


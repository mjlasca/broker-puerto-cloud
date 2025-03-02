<!doctype html>
<html lang="es">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Póliza <?php echo $proposal->field_taker->entity->field_names->value." ".$proposal->field_taker->entity->field_lastname->value." - ".$proposal->nid->value ; ?></title>

  <style>
    body {
      font-family: 'Nunito', sans-serif;
      height: 100%;
    }

    .text-center {
      text-align: center;
    }

    .text-left {
      text-align: left;
    }

    .text-right {
      text-align: right;
    }

    table {
      width: 100%;
      border: solid 0px;
      padding: 0px;
      margin: 0;
      border-collapse: collapse;
    }

    .table-line {
      border: solid 1px;
    }

    .table-line td {
      border: solid 1px;
      padding: 5px;
    }

    p {
      text-align: justify;
      font-size: 12px;
    }

    th,
    td {
      text-align: justify;
      font-size: 11px;
    }

    .trgris {
      background-color: #ccc;
    }

    .footer {

      position: fixed;
      /*El div será ubicado con relación a la pantalla*/
      left: 0px;
      /*A la derecha deje un espacio de 0px*/
      right: 0px;
      /*A la izquierda deje un espacio de 0px*/
      bottom: 0px;
      /*Abajo deje un espacio de 0px*/

      height: 80px;
    }

    .page-break{
      page-break-before : always;
    }

    .sello {
      text-align: center;
      position: fixed;
      align-items: center;
      left: 5%;
      bottom: 170px;
    }

    .sello img {
      width: 280px;
    }

    .p1 {
      padding: 10px;
    }

    .tr-b td{
      border: solid 1px;
      padding: 0px;
    }

    .td-b{
      border: solid 1px;
    }

    #recibo{
      /*border : solid 1px #000;*/
    }
  </style>

</head>

<body>

  <table>
    <thead>
      <tr>
        <th></th>
        <th></th>
        <th>
          <p class="text-center"><b>SEGURO DE ACCIDENTES PERSONALES EN OCASIÓN DEL <br>TRABAJO - BARRIOS PRIVADOS</b></p>
        </th>
        <th class="text-right">
          <img width="150" src="img/imgsancor2.jpg" alt="">
        </th>
      </tr>
    </thead>
  </table>



  <div>
    <p class="text-center">
      Constancia de Póliza - P N°: <?php echo $proposal->nid->value ; ?>
    </p>
    <p>Por medio del presente, damos constancia que se otorga cobertura en el seguro de Accidentes Personales (con motivo y ocasión del trabajo) de Sancor
      Cooperativa de Seguros Ltda. las personas que se detallan a continuación y en las condiciones descriptas seguidamente, encontrándose la correspondiente
      póliza en trámite de emisión.
    </p>
  </div>


  <table class="table-line">
    <thead>
      <tr class="trgris">
        <th colspan="4" class="text-center">
          DATOS DEL TOMADOR
        </th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td style="width: 32%;" class="text-center">Nombres y Apellidos/Razón Social</td>
        <td colspan="3"><?php echo $proposal->field_taker->entity->field_names->value." ".$proposal->field_taker->entity->field_lastname->value; ?></td>
      </tr>

      <tr>
        <td class="text-center">Tipo y número de documento</td>
        <td colspan="3"><?php echo $proposal->field_taker->entity->field_document_type->entity->name->value." ".$proposal->field_taker->entity->field_document_number->value; ?></td>
      </tr>

      <tr>
        <td class="text-center">BARRIOS PRIVADOS en los que realizará la tarea declarada</td>
        <td colspan="3">
          A QUIEN CORRESPONDA<br>

          <?php
          $concatbarrios = "";
          $cont = 0;
            foreach ($proposal->field_neighborhood->referencedEntities() as $key => $value) {
              $concatbarrios .=  $value->title->value." - ";
              $cont++;
              if($cont > 2)
                break;
            }
          ?>
          <p><?php echo $concatbarrios; ?></p>
          <b>Ver listado completo de barrios en la parte de abajo</b>
        </td>
      </tr>
      <tr class="trgris">
        <td colspan="4" class="text-center">DATOS DEL BENEFICIARIO</td>
      </tr>
      <tr>
        <td colspan="4" class="text-left">Herederos legales</td>
      </tr>
      <tr class="trgris">
        <td colspan="4" class="text-center">Detalle de Personas a Asegurar</td>
      </tr>
      <tr>
        <td class="text-center">Apellido y Nombre </td>
        <td class="text-center">Tipo y No. Documento </td>
        <td class="text-center">Fecha Nacimiento (*) </td>
        <td class="text-center">Actividad<br>Tarea que realiza (**) </td>
      </tr>
      <?php
       $lines = $proposal->field_lines_proposal->referencedEntities();
       foreach ($lines as $key => $line) {
        ?>
        <tr>
          <td class="text-center"><?php echo $line->field_custommer->entity->field_lastname->value." ".$line->field_custommer->entity->field_names->value ?> </td>
          <td class="text-center"><?php echo $line->field_custommer->entity->field_document_type->entity->name->value." ".$line->field_custommer->entity->field_document_number->value; ?></td>
          <td class="text-center"> <?php echo $line->field_custommer->entity->field_birth_date->value; ?> </td>
          <td class="text-left"> <?php echo $line->field_activity->entity->name->value; ?> </td>
        </tr>
      <?php
       }

      ?>
    </tbody>
  </table>
<?php

$valid_since = new \DateTime($proposal->field_valid_since->value);
$valid_since->sub(new DateInterval('PT3H'));
$validity_until = new \DateTime($proposal->field_validity_until->value);
$validity_until->sub(new DateInterval('PT3H'));
 ?>
  <div>
    <p class="text-center"> <b> VIGENCIA : DEL <?php echo $valid_since->format('d/m/Y H:i:s ') . " AL ".$validity_until->format('d/m/Y H:i:s ');?>
      <br>

    </b></p>
    <p>
      <b>Nota: Verificar la exigencia del barrio y la cobertura ya que se dará cobertura a los barrios conforme Suma asegurada mencionada en el presente certificado. Si no adquieres la suma asegurada correcta el barrio puede no dejarte ingresar y tendrás que volver a aumentar la suma asegurada</b>
    </p>
    <p>
      (*) Se aclara que son asegurables personas de 14 a 70 años inclusive.
      <br>(**) Se deja constancia que se dará cobertura a la actividad declarada hasta 15 metros de altura.se deberá cumplir además con el resto de condiciones de asegurabilidad de Sancor Coop.de Seguros Ltda.
      <br>Coberturas y Capitales Asegurados
      <br>Hechos ocurridos a causa de las actividades y/o tareas declaradas en la correspondiente solicitud, exclusivamente cuando las mismas sean desempeñadas por el asegurado o los asegurados en los Barrios Privados declarados, incluido los trayectos para trasladarse de un barrio Privado a otro y/o in itinere.
      <br>- MUERTE ACCIDENTAL $ <?php echo number_format($proposal->field_coverage->entity->field_sum->value) ?>
      <br>- INVALIDEZ TOTAL Y PARCIAL PERMANENTE POR ACCIDENTE $<?php echo number_format($proposal->field_coverage->entity->field_sum->value) ?>
      <br>- ASISTENCIA MEDICO FARMACÉUTICA POR REINTEGRO $<?php echo number_format($proposal->field_coverage->entity->field_medical_expenses->value) ?>(con deducible de $<?php echo number_format($proposal->field_coverage->entity->field_deductible->value) ?>)
      <br>Cobertura in itinere incluyendo casos en que el vehículo de traslado sea motocicletas y/o bicicletas y/o vehículos similares
      <br>
      <?php
        $currentDate = new \DateTime();
        if(count($proposal->field_neighborhood->referencedEntities()) < 10){
      ?>
      <p><b>NO REPETICIÓN</b></p>
      <p>Se deja expresa constancia por medio de este endoso, que formará parte integrante de la póliza / certificado, que Sancor Cooperativas de Seguros Limitada renuncia forma expresa a iniciar toda acción de repetición contra  <?php echo $concatbarrios ; ?> ya sea con fundamentos en la Ley 24.557 o en cualquier otra norma jurídica, con motivo de las prestaciones en especie o dinerarias que se vea obligada a otorgar o abonar al Asegurado declarado en la presente Póliza / Certificado, comprendido en la cobertura de la presente Póliza/ Certificado de Accidentes Personales con motivo de la profesión o actividad declarada e In Itinere. Se extiende el presente en Benavidez, <?php echo $currentDate->format('d/m/Y') ;?> . Esta constancia tendrá validez si se presenta con el correspondiente recibo de pago.    </p>
      <?php } ?>


    </p>
  </div>




  <div class="sello text-center">
    <?php if($proposal->field_payment->value == 1) { ?>
    <img width="140" src="img/imgpago.png" alt=""><br>
    <small style="font-size: 9px">Documento Generado en <?php echo $currentDate->format('d/m/Y') ;?></small>
    <?php } ?>
  </div>

  <div class="footer">
    <table>
      <tr>
        <td class="text-right">
          <img width="140" src="img/brokerlogo.png" alt="">
        </td>
        <td>
          <p class="text-center">
            BROKER DEL PUERTO ...
            <br>TU TRANQUILIDAD VALE
            <br>www.brokerdelpuerto.com
            <br>barriosprivados@brokerdelpuerto.com
            <br>Tel. (03327-485189) Cel. 15-55841038
            <br>Sarmiento 3314 (1621 - Benavidez)
          </p>
        </td>
      </tr>
    </table>




  </div>

  <?php if(count($proposal->field_neighborhood->referencedEntities()) >= 10){ ?>
  <div class="page-break"></div>
      <div class="anexo">
        <img src="img/cabeceraanexo.png" width="100%" alt="">
      </div>
      <h4  class="text-center">
        SEGURO DE ACCIDENTES PERSONALES<br>
        EN OCASIÓN DEL TRABAJO - BARRIOS PRIVADOS<br>
        VIGENCIA : DEL
        @if($data[0]->codempresa)
        {{ $data[0]->fechaDesde }} A {{ $data[0]->fechaHasta }}
        @else
          {{ $data[0]->fechaDesde }} A {{  substr($data[0]->fechaHasta, 0,10) . " 00:00:00" }}
        @endif
      </h4>



      <p><b>PROPUESTA EN EMISIÓN : {{$data[0]->prefijo}}-{{$data[0]->idpropuesta}}</b></p>
      <p class="text-justify">Se deja expresa constancia por el presente que las personas que se detallan en la Propuesta No. {{$data[0]->prefijo}}-{{$data[0]->idpropuesta}} se encuentran
        cubiertas en esta aseguradora, amparadas por los riesgos de MUERTE e INVALIDEZ (total o parcial permanente) por
        ACCIDENTE y Asistencia Médica Farmacéutica según las condiciones contratadas
      </p>
      <p><b>Destino: Barrios Privados</b></p>
      <p><b>ANEXO DE NO REPETICIÓN:</b></p>
      <p class="text-justify">
        {{ $concatbarrios }}<br>
        Ya sea con fundamentos en la Ley 24.557 o en cualquier otra norma jurídica, con motivo de las prestaciones en especie o dinerarias que se vea obligada a
        otorgar o abonar al Asegurado declarado en la presente Póliza/Certificado, comprendido en la cobertura de la presente Póliza/Certificado de Accidentes
        Personales con motivo de la profesión o actividad declarada e In Itinere.
      </p>
  <?php } ?>

  <section class="recibo-tables" id="recibo">
    <div class="page-break"></div>

    <div style="z-index:9999;">
      <div style="padding : 10px;width: 47%;float: left;border:1px solid;">
        <table>
          <tr>
            <td colspan="3">
              <img src="img/imgsancor1.png" alt="">
            </td>

            <td class="text-right" colspan="3">
              No. <?php echo $proposal->nid->value ;?><br>
              Accidentes Personales
            </td>
          </tr>
          <tr class="tr-b" style="background-color: rgb(173, 173, 173)">
            <td class="text-center" >Ramo</td>
            <td class="text-center" >Prod</td>
            <td class="text-center" >Referencia</td>
            <td class="text-center" >No. Póliza</td>
            <td class="text-center" >Certif.</td>
            <td class="text-center" >Propuesta</td>
          </tr>
          <tr class="tr-b">
            <td class="text-center" >600</td>
            <td class="text-center" >27</td>
            <td class="text-center" >en trámite</td>
            <td class="text-center" ><?php echo $proposal->nid->value ;?></td>
            <td class="text-center" >0</td>
            <td class="text-center" ><?php echo $proposal->nid->value ;?></td>
          </tr>
          <tr class="tr-b" style="background-color: rgb(173, 173, 173)">
            <td class="text-center" colspan="2" >Organización</td>
            <td class="text-center" >Productor</td>
            <td class="text-center" colspan="2" >Cliente</td>
            <td class="text-center" >Asociado</td>
          </tr>
          <tr class="tr-b">
            <td class="text-center" colspan="2" ><?php echo $proposal->field_organizer->value ;?></td>
            <td class="text-center" ><?php echo $proposal->field_producer->value ;?></td>
            <td class="text-center" colspan="2" ><?php echo $proposal->field_taker->entity->field_document_type->entity->name->value." ".$proposal->field_taker->entity->field_document_number->value; ?></td>
            <td class="text-center" >0</td>
          </tr>
          <tr class="tr-b">
            <td style="padding-left: 10px;" colspan="6">
              <br>
              Sr/es : <?php echo $proposal->field_taker->entity->field_names->value." ".$proposal->field_taker->entity->field_lastname->value; ?><br>
              Domicilio : <?php echo $proposal->field_taker->entity->field_address->value; ?><br>
              Localidad : <?php echo $proposal->field_taker->entity->field_postal_code->entity->name->value; ?><br>
              <br>
            </td>
          </tr>
          <tr  style="background-color: rgb(173, 173, 173)">
            <td class="text-center td-b" colspan="2" >Vencimiento</td>
            <td class="text-center td-b" >Cuota</td>
            <td class="text-center" colspan="2" style="background-color: #fff" ></td>
            <td class="text-center td-b" >Importe</td>
          </tr>

          <tr>
            <td class="text-center td-b" colspan="2"><?php  echo $validity_until->format('d/m/Y H:i:s ');?></td>
            <td class="text-center td-b" >1/1</td>
            <td class="text-center" colspan="2"></td>
            <td class="text-center td-b" ><?php  echo $proposal->field_total_prize->value;?></td>
          </tr>
          <tr>

          <?php if($proposal->field_payment->value == 1) { ?>
              <td style="text-align: center" colspan="6">
              <img  width="70%" src="img/imgpago.png" alt="">
              <?php }else{?>
              <td>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
              <?php }?>
            </td>
          </tr>

        </table>
      </div>
      <div style="padding : 10px;width: 47%;float: left;border:1px solid;">
        <table>
          <tr>
            <td colspan="3">
              <img src="img/imgsancor1.png" alt="">
            </td>

            <td class="text-right" colspan="3">
              No. <?php echo $proposal->nid->value ;?><br>
              Accidentes Personales
            </td>
          </tr>
          <tr class="tr-b" style="background-color: rgb(173, 173, 173)">
            <td class="text-center" >Ramo</td>
            <td class="text-center" >Prod</td>
            <td class="text-center" >Referencia</td>
            <td class="text-center" >No. Póliza</td>
            <td class="text-center" >Certif.</td>
            <td class="text-center" >Propuesta</td>
          </tr>
          <tr class="tr-b">
            <td class="text-center" >600</td>
            <td class="text-center" >27</td>
            <td class="text-center" >en trámite</td>
            <td class="text-center" ><?php echo $proposal->nid->value ;?></td>
            <td class="text-center" >0</td>
            <td class="text-center" ><?php echo $proposal->nid->value ;?></td>
          </tr>
          <tr class="tr-b" style="background-color: rgb(173, 173, 173)">
            <td class="text-center" colspan="2" >Organización</td>
            <td class="text-center" >Productor</td>
            <td class="text-center" colspan="2" >Cliente</td>
            <td class="text-center" >Asociado</td>
          </tr>
          <tr class="tr-b">
            <td class="text-center" colspan="2" ><?php echo $proposal->field_organizer->value ;?></td>
            <td class="text-center" ><?php echo $proposal->field_producer->value ;?></td>
            <td class="text-center" colspan="2" ><?php echo $proposal->field_taker->entity->field_document_type->entity->name->value." ".$proposal->field_taker->entity->field_document_number->value; ?></td>
            <td class="text-center" >0</td>
          </tr>
          <tr class="tr-b">
            <td style="padding-left: 10px;" colspan="6">
              <br>
              Sr/es : <?php echo $proposal->field_taker->entity->field_names->value." ".$proposal->field_taker->entity->field_lastname->value; ?><br>
              Domicilio : <?php echo $proposal->field_taker->entity->field_address->value; ?><br>
              Localidad : <?php echo $proposal->field_taker->entity->field_postal_code->entity->name->value; ?><br>
              <br>
            </td>
          </tr>
          <tr  style="background-color: rgb(173, 173, 173)">
            <td class="text-center td-b" colspan="2" >Vencimiento</td>
            <td class="text-center td-b" >Cuota</td>
            <td class="text-center" colspan="2" style="background-color: #fff" ></td>
            <td class="text-center td-b" >Importe</td>
          </tr>

          <tr>
            <td class="text-center td-b" colspan="2"><?php  echo $validity_until->format('d/m/Y H:i:s ');?></td>
            <td class="text-center td-b" >1/1</td>
            <td class="text-center" colspan="2"></td>
            <td class="text-center td-b" ><?php  echo $proposal->field_total_prize->value;?></td>
          </tr>
          <tr>
            <?php if($proposal->field_payment->value == 1) { ?>
              <td style="text-align: center" colspan="6">
              <img  width="70%" src="img/imgpago.png" alt="">

              <?php }else{?>
              <td>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>
            <?php }?>
               </td>
          </tr>

        </table>
      </div>
    </div>
    <div style="display: block;">
      <div style="padding : 10px;margin-top: 320px;border:1px solid;">
        <table>
          <tr>
            <td colspan="2">
              <img src="img/imgsancor1.png" alt="">
            </td>
            <td colspan="4" class="text-center">
              <small>
              CASA CENTRAL Ruta 34 km. 257<br>
              Tel. (03493) 428500 (Alternativo 420151)<br>
              FAX(03492) 490979<br>
              2322 - SUNCHALES(Sta.Fe)<br>
              </small>
            </td>
            <td colspan="4" class="text-center">
              <small>
              C.U.I.T N° 30-50004946-0<br>
              Ingresos Brutos: C.M. 921-740719-3<br>
              Caja Previsión N°: 50004946<br>
              </small>
            </td>
            <td class="text-right" colspan="2">
              No. <?php echo $proposal->nid->value ;?><br>
              Accidentes Personales
            </td>
          </tr>
          <tr class="tr-b" style="background-color: rgb(173, 173, 173)">
            <td class="text-center" >Ramo</td>
            <td class="text-center" >Prod</td>
            <td class="text-center" >Referencia</td>
            <td class="text-center" >No. Póliza</td>
            <td class="text-center" >Certif.</td>
            <td class="text-center" >Propuesta</td>
            <td class="text-center" colspan="2" >Organización</td>
            <td class="text-center" >Productor</td>
            <td class="text-center" colspan="2" >Cliente</td>
            <td class="text-center" >Asociado</td>
          </tr>
          <tr >
            <td class="text-center td-b" >600</td>
            <td class="text-center td-b" >27</td>
            <td class="text-center td-b" >en trámite</td>
            <td class="text-center td-b" ><?php echo $proposal->nid->value ;?></td>
            <td class="text-center td-b" >0</td>
            <td class="text-center td-b" ><?php echo $proposal->nid->value ;?></td>
            <td class="text-center td-b" colspan="2" ><?php echo $proposal->field_organizer->value ;?></td>
            <td class="text-center td-b" ><?php echo $proposal->field_producer->value ;?></td>
            <td class="text-center td-b" colspan="2" ><?php echo $proposal->field_taker->entity->field_document_type->entity->name->value." ".$proposal->field_taker->entity->field_document_number->value; ?></td>
            <td class="text-center td-b" >0</td>
          </tr>
          <tr class="tr-b">
            <td style="padding-left: 10px;" colspan="12">
              <br>
              Sr/es : <?php echo $proposal->field_taker->entity->field_names->value." ".$proposal->field_taker->entity->field_lastname->value; ?><br>
              Domicilio : <?php echo $proposal->field_taker->entity->field_address->value; ?><br>
              Localidad : <?php echo $proposal->field_taker->entity->field_postal_code->entity->name->value; ?><br>
              Provincia : <br>
              <br>
            </td>
          </tr>
          <tr  style="background-color: rgb(173, 173, 173)">
            <td class="text-center td-b" >No. Cuota</td>
            <td class="text-center td-b" >Cant. Cuota</td>
            <td class="text-center td-b" colspan="2" >Vencimiento</td>
            <td class="text-center" colspan="6" style="background-color: #fff" ></td>
            <td class="text-center td-b"  colspan="2">Importe</td>
          </tr>
          <tr>
            <td class="text-center td-b" >1</td>
            <td class="text-center td-b" >1</td>
            <td class="text-center td-b" colspan="2"><?php  echo $validity_until->format('d/m/Y H:i:s ');?></td>
            <td class="text-center" colspan="6"></td>
            <td class="text-center td-b"  colspan="2"><?php  echo $proposal->field_total_prize->value;?></td>
          </tr>
          <tr>
          <?php if($proposal->field_payment->value == 1) { ?>
              <td style="text-align: center" colspan="12">
              <img  width="35%" src="img/imgpago.png" alt="">

              <?php }else{?>
              <td>
              <br>
              <br>
              <br>
              <br>
              <br>
              <br>

            </td>
          <?php }?>
          </tr>

        </table>
      </div>
    </div>
  </section>
</body>
</html>
<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use kartik\dialog\Dialog;
use yii2mod\alert\AlertAsset;
use backend\assets\DataTablesAsset;
use backend\assets\TemplateAsset;
use \backend\modules\lafabricadecontenidos\backlinks\models\Propuestas;
use backend\modules\lafabricadecontenidos\backlinks\models\PropuestasArchivos;
use backend\modules\lafabricadecontenidos\backlinks\models\PropuestaUrl;
use backend\modules\lafabricadecontenidos\backlinks\models\EstadosPropuestas;
use backend\modules\lafabricadecontenidos\backlinks\models\PropuestasReactivadas;



/* @var $this yii\web\View */
/* @var $model Propuestas | Propuestas[] */


//Comprueba que existan propuestas de url, para colocar el estado y modificar el estilo.
$propuestasUrl_cliente = PropuestaUrl::getUrlPropuesta($model->id,EstadosPropuestas::CLIENTE_SUGIERE_URL);
$propuestasUrl_medio = PropuestaUrl::getUrlPropuesta($model->id,EstadosPropuestas::MEDIO_SUGIERE_URL);


if (!is_array($model)) {
    $model = [$model];
}



?>

<style>
    #div_loader {
        position: fixed;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        z-index: 9999;
        background: url('/img/procesando02.gif') 50% 50% no-repeat rgb(249, 249, 249);
        opacity: .8;
    }

    .pad-50 {
        padding: 20px;
    }

    .mar-150 {
        margin-left: 150px;
    }

    .mar-50 {
        margin-left: 50px;
    }

    .mar-60 {
        margin-left: 60px;
    }

    .mar-70 {
        margin-left: 70px;
    }

    .mar-40 {
        margin-left: 40px;
    }

   
    .btn-w {
        width: 150px;
    }

    .bg-rosa {
        background-color: #fff3fd;
    }

    .pad-40 {
        padding-left: 40px;
    }

    .opaco-10 {
        background: rgba(142, 142, 142, 0.2)

    }

    ul {
        list-style-type: none;
        padding-left: 0;
    }

    .alert h4 {
        color: black;
    }

    .alert-dark {
        border-left: 0;
    }


    <?php if($propuestasUrl_cliente): ?>
    @media (min-width: 1000px) and (max-width: 1199px) {
    #headingStatus{
        left: 0%!important;
    }
}

    @media (min-width: 1200px) and (max-width: 1439px) {
        #headingStatus{
            left: 0%!important;
        }
    }


    @media (min-width: 1440px) and (max-width: 2560px){
        #headingStatus{
            left: 3%!important;
        }
    }


    
    @media (min-width: 1500px) and (max-width: 2560px){
        .wz-heading .progress {
            margin-left: 80px!important;
        }
    }

    <?php else: ?>
        
    <?php if($propuestasUrl_medio): ?>
    /* ESTILOS CUANDO ESTAN LOS ESTADO MEDIO PROPUESTA DE URL*/
    @media (min-width: 1000px) and (max-width: 1439px) {
        #headingStatus{
            left: 6%!important;
        }
    }


    @media (min-width: 1440px) and (max-width: 2560px){
        #headingStatus{
            left: 11%!important
        }
    }


    
    @media (min-width: 1500px) and (max-width: 2560px){
        .wz-heading .progress {
            margin-left: 80px!important;
        }
    }



    <?php else: ?>
    /* ESTILOS CUANDO ESTAN LOS ESTADOS CLIENTE Y MEDIO PROPUESTA DE URL*/
    @media (min-width: 1000px) and (max-width: 1439px) {
        #headingStatus{
            left: 12%!important;
        }
    }


    @media (min-width: 1440px) and (max-width: 2560px){
        #headingStatus{
            left: 15%!important
        }
    }


    
    @media (min-width: 1500px) and (max-width: 2560px){
        .wz-heading .progress {
            margin-left: 80px!important;
        }
    }

    <?php endif; ?>
    <?php endif; ?>

    .wz-heading .progress{
        width: 65%!important;
    }

    .mar-h-10{
        margin-left:14px!important;
        margin-right:0px!important;
    }
    
    .field-urlInput{
        text-align:left!important;
    }

    .field-comentarioUrl{
        text-align:left!important;
    }

</style>

<div class="col-sm-12 col-lg-12 mar-ver">



<?php include_once '_estado.php'; ?>

            <?php

            foreach ($model as $propuesta) {
                ?>


    <?php $propuesta_reactivadas = PropuestasReactivadas::find()->where(['propuesta_id' => $propuesta->id])->orderBy(['id' => SORT_DESC])->all(); ?>
    <?php $isReactive = count($propuesta_reactivadas) > 0; ?>
    <?php if($isReactive) include_once '_reactivado.php'; ?>

            
            <div class="panel-body">
                    <div class="alert media alert-dark opaco-10 text-left">
                        <div class="col-lg-7 col-md-6 col-sm-6">
                            <ul>
								<li><b style='font-size:10px;color:black;'>Nombre del sitio web</b></li>
                                <li><h4 class="alert-title" style='font-weight: 300;'>
                                         <?= $propuesta->mediosInfluencersRecursos->nombre ?></h4></li>
								<li style='margin-top: 10px;'><b style='font-size:10px;color:black;'>Url del sitio</b></li>
                                <li><h4 class="alert-title" style='font-weight: 300;'><?= $propuesta->mediosInfluencersRecursos->url ?></h4></li>
								<li style='margin-top: 10px;'><b style='font-size:10px;color:black;'>Nro. del pedido</b></li>
                                <li><h4 class="alert-title" style='font-weight: 300;'>Brief del Pedido #<?= $propuesta->id ?></h4></li>
                            </ul>

                        </div>
                        <div class="col-lg-5 col-md-6 col-sm-6">
                            <div class="media-right">
                                <ul>
								    <li><b style='font-size:10px;color:black;'>Tipo de paquete</b></li>
                                    <li><h4 class="alert-title" style='font-weight: 300;'><?= $propuesta->tipoPropuesta->nombre ?></h4></li>
								    <li style='margin-top: 10px;'><b style='font-size:10px;color:black;'>Precio del pedido</b></li>
                                    <li><h4 class="alert-title" style='font-weight: 300;'><?= $propuesta->valorPropuesta->monto_total ?>
                                            Créditos</h4>
                                    </li>
                                    <li style='margin-top: 10px;'><b style='font-size:10px;color:black;'>Cliente</b></li>
                                    <li><h4 class="alert-title" style='font-weight: 300;'><?= $propuesta->proyecto->cliente->nombres ." ".$propuesta->proyecto->cliente->apellidos ?> - <?=$propuesta->proyecto->cliente->user->email?></h4>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group text-left">
                                <label>Titulo sugerido</label>
                                <input class="form-control float-left" disabled="true"
                                       value="<?= $propuesta->propuestaMedio->titulo; ?>"/>

                            </div>
                        </div>


                        <?php if ($propuesta->estadoPropuesta->nombre_interno != 'compra'): ?>
                        <div class="col-md-4">
                            <div class="form-group text-left">
                                <label>Duración de la publicación</label>
                                <input class="form-control" disabled="true"
                                       value="<?= $propuesta->duracionPublicacionText; ?>"/>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group text-left">
                                <label>Número de enlace</label>
                                <input class="form-control" disabled="true"
                                       value="<?= $propuesta->propuestaMedio->numero_enlace; ?>"/>

                            </div>
                        </div>

                        <?php
                foreach ($propuesta->propuestasKeywordEnlaces as $propuestasKeywordEnlaces) {
                    ?>

                            <div class="col-md-4">
                                <div class="form-group text-left">
                                    <label>Keyword (anchor text)</label>
                                    <input class="form-control float-left" disabled="true"
                                           value="<?= $propuestasKeywordEnlaces->keyword; ?>"/>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group text-left">
                                    <label>Url</label>
                                    <input class="form-control" disabled="true"
                                           value="<?= $propuestasKeywordEnlaces->url; ?>"/>

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group text-left">
                                    <label>Tipo de enlace</label>
                                    <input class="form-control" disabled="true"
                                           value="<?= $propuestasKeywordEnlaces->tipo_enlace; ?>"/>

                                </div>
                            </div>
                    <?php
                }
                ?>

            <?php else: ?>

                <div class="col-md-4">
                            <div class="form-group text-left">
                                <label>Fecha límite de pedido</label>
                                <input class="form-control" disabled="true"
                                       value="<?= $propuesta->fecha_limite_publicacion; ?>"/>

                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group text-left">
                                <label>Número de enlace</label>
                                <input class="form-control" disabled="true"
                                       value="<?= $propuesta->propuestaMedio->numero_enlace; ?>"/>

                            </div>
                        </div>



            <?php endif; ?>
            <?php if (!empty($propuesta->propuestaMedio->comentario_cliente)){ ?>
                    <div class="col-md-12 bord-btm ">
                        
                            <div class="alert media fade in bg-gray-dark bord-all mar-ver mar-top-no text-dark text-left">
                                <div class="media-left">
                                    <span class="icon-wrap icon-wrap-xs icon-circle alert-icon">
                                    <i class="demo-pli-information icon-3x" style="color: black;"></i>
                                    </span>
                                </div>
                                <div class="media-body">
                                    <h4 class="alert-title">Nota importante del cliente:</h4>
                                    <p class="alert-message"><?=$propuesta->propuestaMedio->comentario_cliente?></p>
                                </div>
                            </div>
                        
                    </div>
            <?php } ?>
            <?php if($motivo_cancelacion){ ?>
                <div class="col-md-12">
                            <div class="form-group text-left">
                                <label>Motivo de cancelación de pedido</label>
                                <textarea class="form-control" disabled="true"><?= $motivo_cancelacion->cancelado_comentario; ?></textarea>
                            </div>
                </div>
            <?php } ?>
            
            <?php //desde aqui prueba Mily
            if (count($propuesta->archivoBrief) > 0){?>
            <div>
                <button type="button" class="btn btn-mint pull-left btn_descargas"> 
                        <i class="btn-label fa fa-download">
                        </i>
                    <span>Descargar adjunto(s)</span>
                </button>
                <?php } ?>
                
                <div class="modal fade archivos " tabindex="-1" role="dialog" aria-labelledby="demo-default-modal" aria-hidden="true" id="modal_descargas">
                        <div class="modal-dialog modal-md animated bounceIn">
                            <div class="modal-content">
                                <div class="modal-header bg-gray">
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                                    </button>
            
                                    <h4 class="modal-title" id="loginLabel"><b>Descargar los archivos</b></h4>                            

                                </div>
                                <div class="modal-body">
                                 <ul id="ul_arbol" class="list-group">
                                 <?php if (isset($propuesta->archivoBrief->archivosRelacionados)){?>
                                 <?php foreach ($propuesta->archivoBrief->archivosRelacionados as $archivo) { ?>
										<li class="list-group-item mar-top">
                                            <a href="<?= 'render-file-backlink?archivo='.$archivo->ruta?>" target="blank" download >
                                                <i class="fa fa-download"></i>
                                                <span> Descargar entrega </span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                <?php } ?>	  
								</ul>
									
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                    </div>
                </div>

                <?php
            }
            ?>



</div>


<?php

    $this->registerJs(' 
        let toogleViewBrief = false;
            $("#a-brief").click(function(){
                if(toogleViewBrief){
                    $("#a-brief ins").html("Ver brief completo");
                    toogleViewBrief = false;
                }else{
                    $("#a-brief ins").html("Mostrar menos");
                    toogleViewBrief = true;
                }
            })
    ');

?>
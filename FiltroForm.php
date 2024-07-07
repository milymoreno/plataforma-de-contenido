<?php


namespace backend\modules\lafabricadecontenidos\backlinks\models;

use app\models\Idiomas;
use backend\components\HasWizard;
use backend\models\AutoresCategorias;
use backend\models\CostosAutoresCategorias;
use backend\models\prub\EstHistorialPedidos;
use backend\models\prub\Pedidos;
use backend\models\Utilfecha;
use backend\modules\lafabricadecontenidos\backlinks\components\Mailer;
use common\components\Creditos;
use Stripe\Product;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\lafabricadecontenidos\backlinks\models\MediosInfluencersRecursos;
use backend\modules\lafabricadecontenidos\backlinks\models\MediosInfluencers;
use backend\modules\lafabricadecontenidos\backlinks\models\Valores;
use backend\modules\lafabricadecontenidos\backlinks\models\MediosInfluencersRecursosCategorias;
use yii\db\Expression;
use yii\helpers\ArrayHelper;
use backend\modules\lafabricadecontenidos\clientes\models\Clientes;
use yii\web\UploadedFile;
use backend\modules\lafabricadecontenidos\backlinks\models\ProyectoValidator;
use backend\models\Ordenesccconsumobkl;
use backend\models\Ordenesccdetalle;
use backend\models\Usuarios;

use \yii\helpers\VarDumper;

use DOMDocument;

use backend\models\Archivos;

class FiltroForm extends Model{

    use HasWizard;

    public $categorias;
    public $categorias_array;
    public $canales;    
    public $idiomas;
    public $paises;
    public $precio_min;
    public $precio_max;
    public $oferta;
    public $no_follow;
    public $post_medio;
    public $post_cliente;
    public $post_wac;
    public $publicidad;
    public $publicidad_cond;
    public $publicidad_no;
    public $metrica_da;
    public $metrica_as;
    public $metrica_ga_duracion_media;
    public $metrica_ga_nro_sesiones;
    public $metrica_ga_nro_usuarios_nuevo;
    public $metrica_ga_nro_usuarios;
    public $metrica_dr;
    public $metrica_ss;
    public $metrica_to; //trafico organico
    public $metrica_tog; //trafico organico de google analytics
    public $tipo_propuesta;
    public $metrica_ga_nro_visitas;
    public $busqueda;

    public $recursos;
    public $datos;
    public $proyecto_id;
    public $proyecto_name;

    public $proyectos;
    public $fecha_aceptacion;
    public $fecha_entrega;
    public $fecha_aprobacion;
    public $fecha_ajustes;
    public $fecha_publicacion;
    public $tipos_paquetes;

    public $cantidad_recursos;

    public $titulo;
    public $numero_enlace;

    public $lenguaje_tecnico;
    public $seo;
    public $keyword_seo;
    public $descripcion;
    public $keyword_principal;
    public $keyword_secundaria;
    public $keyword_principal_cliente;
    public $keyword_secundaria_cliente;

    public $enfoque_texto;
    public $temas_incluir;
    public $temas_eliminar;
    public $ejemplo;
    public $propuestas_archivos;
    public $propuestas_archivos_cliente;

    /*Agregado para nuevo boton archivo */
    public $propuestas_archivos_clientes_adjuntos;
    public $propuestas_archivos_medio_sugiere_url;
    public $propuestas_archivos_wac;

    public $lenguaje_tecnico_opciones;

    public $numero_enlaces;

    public $draft;
    /* @var $proyectoModel Proyectos */
    public $proyectoModel;

    public $paquetes_ids;

    public $idiomaData;
    public $categoriasData;
    public $rankingData;
    public $numeroEnlaceData;
    public $keywordsPropuestasData;
    public $rangosData;
    public $archivosData = [];
    public $archivosDataCliente = [];
    /*Nuevos valores Data */
    /*Nuevo Boton*/
    public $archivosDataClientesAdjuntos = [];
    public $archivosDataMedioSugiereUrl = [];
    public $archivosDataWac = [];


    public $archivosDataConfig;
    public $archivosDataConfigCliente;
    
    /*Nuevos valores config */
    /*Nuevo Boton*/
    public $archivosDataConfigClientesAdjuntos;
    public $archivosDataConfigMedioSugiereUrl;
    public $archivosDataConfigWac;

    public $archivosTemp;

    //Nuevo Boton
    public $archivosTempWac;
    public $archivosTempCliente;
    public $archivosTempMedio;

    public $max_archivos_cliente;
    public $max_archivos_cliente_sube_articulo;
    /*Nuevo Boton*/
    public $max_archivos_wac;
    public $max_archivos_clientes_adjuntos;

    public $extensiones_archivos;
    public $extensiones_archivos_cliente_sube_articulo;
    /*Nuevo Boton*/
    public $extensiones_archivos_wac;
    public $extensiones_archivos_clientes_adjuntos;

    public $condiciones;
    public $nro_enlaces;
    public $monto_total;
    public $cantidad_total;
    public $errores;
    public $saldo_disponible;
    public $error_propiedad_intelectual_cliente;
    public $error_propiedad_intelectual_wac;

    public $extensiones_archivos_post_cliente;
    public $page;
    public $length;

    public $paquetes_ids_reservados;
    public $tipos_paquetes_id;

    public $reasignado;

    public $informe;
    public $urlsData;
    public $cliente;
    public $odc;
    public $monto_odc;
    public $odc_detalle;
    public $grupoclientes;
    public $enOferta;

    public $pedido_texto;
    public $pedido_id;

    public $comentario_cliente;
    public $objetos_seleccionados;

    public $saldo_oc;
    public $verifica_cliente_oc;

    //  ----nuevas variables para plan optimo de bkl --- //
    public $presupuesto;
    public $presupuesto_guardado;
    public $check_presupuesto; 

    public $keywords_cliente;
    public $keywords_guardadas;
    // -------------------
    public $array_keyword; 
    public $tematicas; 
    public $metrica_dt; //Domain Trust metrica de SE Ranking

    public function init()
    {
        if($this->proyectoModel){
            //$this->cliente = $this->proyectoModel->cliente_id;
            $this->proyecto_id = $this->proyectoModel->id;
            $this->proyecto_name = $this->proyectoModel->nombre;

            // datos adicionales para plan optimo
            //$this->presupuesto = $this->proyectoModel->presupuesto_guardado; 
            $this->presupuesto_guardado = $this->proyectoModel->presupuesto_guardado;  
            $this->keywords_guardadas = $this->proyectoModel->keywords_guardadas;  
             
        }

       
        $this->lenguaje_tecnico_opciones = [1 => 'Obligatorio', 0 => 'No obligatorio'];
        //estos son los archivos de ejemplo que sube el cliente para el tipo de pedido medio hace el articulo
        $this->max_archivos_cliente = intval(Valores::getValorByName('max_archivos_cliente'));

        $this->max_archivos_cliente_sube_articulo = intval(Valores::getValorByName('max_archivos_cliente_sube_articulo'));
        /*Nuevo Boton*/
        $this->max_archivos_wac = intval(Valores::getValorByName('max_adjunto_wac'));
        $this->max_archivos_clientes_adjuntos = intval(Valores::getValorByName('max_adjunto_cliente_articulo'));

        $this->extensiones_archivos = explode(',',Valores::getValorByName('extensiones_archivos'));
        $this->extensiones_archivos_cliente_sube_articulo = explode(',',Valores::getValorByName('extensiones_archivos_cliente_sube_articulo'));
        $this->extensiones_archivos_post_cliente = Valores::getValorByName('extensiones_archivos_cliente_sube_articulo');
        /*Nuevo boton */
        $this->extensiones_archivos_wac = explode(',',Valores::getValorByName('extension_adjunto_brief_wac'));
        $this->extensiones_archivos_clientes_adjuntos = explode(',',Valores::getValorByName('extension_adjunto_brief_cliente_articulo'));

    }

    public function wizardScenarios(): array
    {
        return [
            1 => [],
            2 => ['proyecto_name','fecha_publicacion',],
            3 => [
                Propuestas::SCENARIO_POST_MEDIO =>[
                    'titulo',
                    'comentario_cliente',
                    'numero_enlace',
                    'descripcion',
                    'temas_eliminar',
                ],
                Propuestas::SCENARIO_POST_WAC =>[

                ],
                Propuestas::SCENARIO_POST_CLIENTE =>[
                    'titulo',
                    'comentario_cliente',
                    'numero_enlace'
                ],
                Propuestas::SCENARIO_POST_URL_ANTIGUA =>[
                    'titulo',
                    'comentario_cliente',
                    'numero_enlace',
                ],
                Propuestas::SCENARIO_POST_CLIENTE_SUGIERE =>[
                    'titulo',
                    'comentario_cliente',
                    'numero_enlace',
                ],
                Propuestas::SCENARIO_POST_MEDIO_SUGIERE =>[
                    'titulo',
                    'comentario_cliente',
                    'numero_enlace',
                ],

            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $validator = ProyectoValidator::className();
        
        return [
            [['paises','idiomas','nombre','descripcion','id_canal', 'id_categoria', 'id_idioma', 'id_pais','precio_min','precio_max','oferta','no_follow','categorias', 'check_presupuesto', // añadido check del plan optimo
            'tipo_propuesta','publicidad','publicidad_cond','publicidad_no','metrica_da','metrica_as','metrica_dt','metrica_dr','metrica_ss','metrica_to','metrica_tog','enOferta','metrica_ga_duracion_media','metrica_ga_nro_sesiones',
            'metrica_ga_nro_usuarios_nuevo','nro_enlaces','condiciones','metrica_ga_nro_usuarios','metrica_ga_nro_visitas','busqueda','datos','recursos','cantidad_recursos','ejemplo',
                'draft','propuestas_archivos','propuestas_archivos_clientes_adjuntos','propuestas_archivos_medio_sugiere_url','propuestas_archivos_wac','proyectoModel','paquetes_ids','paquetes_ids_reservados','canales','idiomaData','monto_total','cantidad_total',
                'categoriasData','rankingData','numeroEnlaceData','keywordsPropuestasData','rangosData','archivosData','archivosDataCliente','archivosDataClientesAdjuntos','archivosDataMedioSugiereUrl','archivosDataWac','archivosDataConfigCliente','max_archivos_cliente','extensiones_archivos',
                'max_archivos_wac','extensiones_archivos_wac','archivosDataConfig','archivosDataConfigClientesAdjuntos','archivosDataConfigMedioSugiereUrl','archivosDataConfigWac','archivosTemp','archivosTempWac','archivosTempCliente','archivosTempMedio','descripcion','fecha_entrega','fecha_ajustes','fecha_aceptacion','fecha_aprobacion','temas_incluir','errores','saldo_disponible','propuestas_archivos_cliente'
                ,'tipos_paquetes','extensiones_archivos_cliente_sube_articulo','extensiones_archivos_post_cliente','keyword_principal_cliente','error_propiedad_intelectual','keyword_secundaria_cliente','keyword_principal','keyword_secundaria','page','length','cliente','odc','monto_odc','odc_detalle','informe','tipos_paquetes_id','grupoclientes','enOferta','comentario_cliente','objetos_seleccionados'], 'safe'],
            [['proyecto_name','fecha_publicacion','titulo','numero_enlace',
                'descripcion','temas_eliminar','pedido_id'], 'required'],
                [['presupuesto','presupuesto_guardado','keywords_cliente', 'keywords_guardadas'], 'safe'],
            ['pedido_texto','required','message' => 'Debes seleccionar un pedido de texto'],
            ['titulo','string','max' => 200],
            ['comentario_cliente','string','max' => 1000],
            ['propuestas_archivos_cliente','required','message' => 'Debes adjuntar el archivo que enviarás al medio'],
            [['propuestas_archivos','propuestas_archivos_medio_sugiere_url'],'file','extensions'=> $this->extensiones_archivos,'maxFiles' => $this->max_archivos_cliente],
            [['propuestas_archivos_cliente'], 'file','extensions'=> $this->extensiones_archivos,'maxFiles' => $this->max_archivos_cliente_sube_articulo],
            [['propuestas_archivos_wac'], 'file','extensions'=> $this->extensiones_archivos_wac,'maxFiles' => $this->max_archivos_wac],
            [['propuestas_archivos_clientes_adjuntos'], 'file','extensions'=> $this->extensiones_archivos_clientes_adjuntos,'maxFiles' => $this->max_archivos_clientes_adjuntos],

            ['lenguaje_tecnico','safe'],

           ['proyecto_name',ProyectoValidator::className()],
           
            [['proyecto_name'], 'string', 'max' => 100],
            [['cliente'], 'integer'],
            
            [['reasignado','urlsData','tematicas'],'safe']

        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_canal' => 'Canales',
            'id_categoria' => 'Categoria',
            'id_idioma' => 'Idioma',
            'id_pais' => 'Pais',
            'datos' => 'Datos',
            'fecha_limite_aceptacion'=>'Fecha límite de aceptación',
            'fecha_limite_entrega'=>'Fecha límite de entrega',
            'fecha_aprobacion'=>'Fecha de aprobación',
            'fecha_publicacion'=>'Fecha límite de publicación',
            'proyecto_id'=>'Nombre del proyecto',
            'proyecto_name'=>'Nombre del proyecto',
            'titulo'=>'Título',
            'numero_enlace'=>'Número de enlace',
            'idioma_id'=>'Idiomas',
            'categorias_propuestas'=>'Categorias',
            'autor_categoria_id'=>'Ranking',
            'costo_autor_categoria_id'=>'Extensión máxima de palabras',
            'lenguaje_tecnico'=>'Lenguaje técnico',
            'descripcion'=>'Descripción',
            'keyword_principal'=>'Keyword principal sugerida',
            'keyword_principal_cliente'=>'Keyword principal sugerida',
            'keyword_secundaria'=>'Keyword secundaria sugerida',
            'keyword_secundaria_cliente'=>'Keyword secundaria sugerida',
            'propuestas_archivos_cliente'=>'Artículo',
            'enfoque_texto'=>'Enfoque de texto',
            'temas_eliminar'=>'Temas a eliminar',
            'pedido_texto'=>'Pedido de texto',
            'comentario_cliente' => 'Comentario del cliente a los medios'

        ];
    }
/*
* Función que busca los resultados de los recursos - paquetes
* de acuerdo a las condiciones que seleccione el cliente
*/

    public function buscar_filtro(){

        $sql ="";
        $item = 0;
        $devolucion = 0;
        $now = new \DateTime(null, new \DateTimeZone('America/Bogota'));
        $hoy = $now->format("Y-m-d");
        $fecha_1mes = date('Y-m-d', strtotime('+30 days', strtotime($hoy)));
        $result = array();
        //------------------------------------------------------------------
        $porc_wac = Valores::getValorByName("ganancia_wac");
        //datos del cliente
        $id_cliente = ($this->cliente!=null)? intval($this->cliente):Yii::$app->user->identity->id;

        $cliente = Clientes::getComisiones($id_cliente);
       // $retencion = $cliente->porcentaje_pais;
       $retencion = 0;
        $comision = $cliente->comision;
        //------------------------------------------------------------------
        
        $query = MediosInfluencersRecursos::find()
             // ->select(["fab_bkl_medios_influencers_recursos.id,fab_bkl_medios_influencers_recursos.code_url,fab_bkl_medios_influencers_recursos.email_recurso,fab_bkl_medios_influencers_recursos.telefono,fab_bkl_medios_influencers_recursos.verificado,fab_bkl_medios_influencers_recursos.imagen_url,fab_bkl_medios_influencers_recursos.tipo_verificacion_id,fab_bkl_medios_influencers_recursos.tiempo_publicacion_post,fab_bkl_medios_influencers_recursos.estado_medio_influencer_id,fab_bkl_medios_influencers_recursos.nombre, fab_bkl_medios_influencers_recursos.url, fab_bkl_medios_influencers_recursos.descripcion,fab_bkl_medios_influencers_recursos.medio_influencer_id, fab_bkl_medios_influencers_recursos.metricas, fab_bkl_medios_influencers_recursos.seccions, fab_bkl_medios_influencers_recursos.pais_id, fab_bkl_medios_influencers_recursos.publicidad_id, fab_bkl_medios_influencers_recursos.recurso_id, ((JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.dr')) / 100 )* JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.to')))  + ((JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.da')) / 100 )* JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.to'))) as prom_metricas ,fValidarDescuentoPaquete(fab_bkl_medios_recursos_paquetes.valor, fab_bkl_medios_recursos_paquetes.descuento,fab_bkl_medios_recursos_paquetes.fecha_desde, fab_bkl_medios_recursos_paquetes.fecha_hasta) as tiene_descuento"]);
              ->select(["fab_bkl_medios_influencers_recursos.id,fab_bkl_medios_influencers_recursos.code_url,
                        fab_bkl_medios_influencers_recursos.email_recurso,
                        fab_bkl_medios_influencers_recursos.telefono,
                        fab_bkl_medios_influencers_recursos.verificado,
                        fab_bkl_medios_influencers_recursos.imagen_url,
                        fab_bkl_medios_influencers_recursos.tipo_verificacion_id,
                        fab_bkl_medios_influencers_recursos.tiempo_publicacion_post,
                        fab_bkl_medios_influencers_recursos.estado_medio_influencer_id,
                        fab_bkl_medios_influencers_recursos.nombre, 
                        fab_bkl_medios_influencers_recursos.url, 
                        fab_bkl_medios_influencers_recursos.descripcion,
                        fab_bkl_medios_influencers_recursos.medio_influencer_id, 
                        fab_bkl_medios_influencers_recursos.metricas, 
                        fab_bkl_medios_influencers_recursos.seccions, 
                        fab_bkl_medios_influencers_recursos.pais_id, 
                        fab_bkl_medios_influencers_recursos.publicidad_id, 
                        fab_bkl_medios_influencers_recursos.recurso_id, 
                        CASE 
                        WHEN JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.tog')) = 0 THEN
                            ((JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.dr')) / 100 )* JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.to'))) + ((JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.dt')) / 100 )* JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.to'))) + ((JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.da')) / 100 )* JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.to')))
                        ELSE
                            ((JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.dr')) / 100 )* JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.tog'))) + ((JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.dt')) / 100 )* JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.tog'))) + ((JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.da')) / 100 )* JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.tog')))
                        END as prom_metricas,
                        fValidarDescuentoPaquete(fab_bkl_medios_recursos_paquetes.valor, 
                        fab_bkl_medios_recursos_paquetes.descuento,
                        fab_bkl_medios_recursos_paquetes.fecha_desde, 
                        fab_bkl_medios_recursos_paquetes.fecha_hasta) as tiene_descuento"]);
              
       /* $query->joinWith(['mediosRecursos']);
        $query->joinWith(['mediosRecursosPaquetes']);
        $query->joinWith(['mediosRecursosPaquetes.paquetes']);
        $query->joinWith(['mediosRecursosPaquetes.rangos']);
        $query->joinWith(['mediosRecursosPaquetes.rangos.rangos']);
        $query->joinWith(['mediosRecursosPaquetes.paquetes.tipoPropuestas']);
        $query->joinWith(['mediosInfluencersRecursosCategorias']);
        $query->joinWith(['estadosMediosInfluencers']);
        $query->joinWith(['influencersRecursos']);
        $query->joinWith(['mediosInfluencers']);
        $query->joinWith(['mediosInfluencers.user']);
        $query->joinWith(['idiomasIds']);*/
        $query->joinWith(['mediosRecursos']);
        $query->joinWith(['mediosRecursosPaquetes']);
       // $query->joinWith(['mediosRecursosPaquetes.paquetes']);
      //  $query->joinWith(['mediosRecursosPaquetes.rangos']);
        //$query->joinWith(['mediosRecursosPaquetes.rangos.rangos']);
        $query->joinWith(['mediosRecursosPaquetes.paquetes.tipoPropuestas']);
        $query->joinWith(['mediosInfluencersRecursosCategorias']);
      //  $query->joinWith(['estadosMediosInfluencers']);
       // $query->joinWith(['influencersRecursos']);
       // $query->joinWith(['tematicasNoAceptadasIds']);
        $query->joinWith(['mediosInfluencers']);
        $query->joinWith(['mediosInfluencers.user']);
        $query->joinWith(['idiomasIds']);
        $query->where(['=', 'fab_bkl_medios_influencers_recursos.estado_medio_influencer_id', 1]);
        $query->andWhere(['!=', 'fab_bkl_medios_influencers.id_user', $id_cliente]);
        $query->andWhere(['=', 'fab_bkl_tipo_propuestas.estado', 1]);
        $query->andWhere(['=', 'fab_bkl_medios_influencers.estado', 1]);
        $query->andWhere(['=', 'fab_user.activo_medios', 1]);
        $query->andWhere(['or',[ 'fab_bkl_medios_recursos.estado' => 0],
                        [ 'fab_bkl_medios_recursos.estado' => 1],
                        ]);
        $query->andWhere(['or',[ 'fab_bkl_medios_influencers_recursos.modo_vaciones' => 0],
                         ['and',[ 'fab_bkl_medios_influencers_recursos.modo_vaciones' => 1],
                         ['>','fecha_inicio_vaciones',$hoy]],
                          ]);
        $query->orWhere(['and', ['fab_bkl_medios_influencers_recursos.modo_vaciones' => 1],
                          ['and', ['<', 'fab_bkl_medios_influencers_recursos.fecha_inicio_vaciones', $hoy],
                              ['<', 'fab_bkl_medios_influencers_recursos.fecha_fin_vacaciones', $hoy]]]);

        if($this->reasignado){
            $query->andWhere(['<>', 'fab_bkl_medios_influencers_recursos.id', $this->reasignado]);
        }

        if (!empty($this->canales)){
            $query->andWhere(['in', 'fab_bkl_medios_influencers_recursos.recurso_id', $this->canales]);
        }
        if (!empty($this->busqueda)){
            $query->andWhere(['or', ['like','fab_bkl_medios_influencers_recursos.url',$this->busqueda],
                             ['or',['like','fab_bkl_medios_influencers_recursos.nombre',$this->busqueda]] ,
                             ['or',['like','fab_bkl_medios_influencers_recursos.descripcion',$this->busqueda]]
                             ]);

        }


        if (!empty($this->categorias)){

           if (count ($this->categorias)>1){
               $sql = "";
                foreach ($this->categorias as $categoria ){
                    if (empty($sql)){
                        $sql = "  (fab_bkl_medios_influencers_recursos_categorias.categoria_id = ".$categoria.")";
                    }else{
                        $sql.= " or (fab_bkl_medios_influencers_recursos_categorias.categoria_id = ".$categoria.")";
                    }

                }

                $query->andWhere($sql);
           }
           else{
               if($this->categorias[0] != ""){
                 $query->andWhere(['in', 'fab_bkl_medios_influencers_recursos_categorias.categoria_id', $this->categorias]);
                }

            }
        }


        if (!empty($this->idiomas)){

            if (count ($this->idiomas)>1){
                $sql = "";
                foreach ($this->idiomas as $idioma ){
                    if (empty($sql)){
                        $sql = "  (fab_bkl_medios_influencers_recursos_idiomas.idioma_id = ".$idioma.")";
                    }else{
                        $sql.= " or (fab_bkl_medios_influencers_recursos_idiomas.idioma_id = ".$idioma.")";
                    }

                }
                $query->andWhere($sql);
            }
            else{
                if($this->idiomas[0] != ""){
                $query->andWhere(['in', 'fab_bkl_medios_influencers_recursos_idiomas.idioma_id', $this->idiomas]);
                }
            }

        }

        if (!empty($this->paises)){
            if (count ($this->paises)>1){
                $sql = "";
                foreach ($this->paises as $pais ){
                    if (empty($sql)){
                        $sql = "  (fab_bkl_medios_influencers_recursos.pais_id = ".$pais.")";
                    }else{
                        $sql.= " or (fab_bkl_medios_influencers_recursos.pais_id = ".$pais.")";
                    }

                }
                $query->andWhere($sql);
            }
            else{
                if($this->paises[0] != ""){
                $query->andWhere(['in', 'fab_bkl_medios_influencers_recursos.pais_id', $this->paises]);
                }
            }
        }




        if (!empty($this->condiciones)){

            if (count ($this->condiciones)>1){
                $sql = "";
                foreach ($this->condiciones as $condicion){
                    if ($condicion == 'oferta'){
                        //$this->oferta = true;
                        $query->andWhere(['=', 'fValidarDescuentoPaquete(fab_bkl_medios_recursos_paquetes.valor, fab_bkl_medios_recursos_paquetes.descuento,fab_bkl_medios_recursos_paquetes.fecha_desde, fab_bkl_medios_recursos_paquetes.fecha_hasta)', 1]);

                    }
                    if ($condicion == 'no_follow'){
                        if (empty($sql)){
                            $sql = "  (fab_bkl_medios_recursos.links_no_follow = 1)";
                        }else{
                            $sql.= " or (fab_bkl_medios_recursos.links_no_follow = 1)";
                        }

                    }
                    if ($condicion == 'publicidad_cond'){
                        if (empty($sql)){
                            $sql = "  (fab_bkl_medios_influencers_recursos.publicidad_id = 3)";
                        }else{
                            $sql.= " or (fab_bkl_medios_influencers_recursos.publicidad_id = 3)";
                        }

                    }
                    if ($condicion == 'publicidad'){
                        if (empty($sql)){
                            $sql = "  (fab_bkl_medios_influencers_recursos.publicidad_id = 2 )";
                        }else{
                            $sql.= " or (fab_bkl_medios_influencers_recursos.publicidad_id = 2 )";
                        }

                    }
                    if ($condicion == 'publicidad_no'){
                        if (empty($sql)){
                            $sql = "  (fab_bkl_medios_influencers_recursos.publicidad_id = 4)";
                        }else{
                            $sql.= " or (fab_bkl_medios_influencers_recursos.publicidad_id = 4)";
                        }

                    }
                    if ($condicion == 'con_follow'){
                        if (empty($sql)){
                            $sql = "  (fab_bkl_medios_recursos.links_follow = 1)";
                        }else{
                            $sql.= " or (fab_bkl_medios_recursos.links_follow = 1)";
                        }

                    }

                 }

                 $query->andWhere($sql);
            }
            else if (count ($this->condiciones)==1){
                if($this->condiciones[0] != ""){
                foreach ($this->condiciones as $condicion){
                    if ($condicion == 'oferta'){
                        //$this->oferta = true;
                        $query->andWhere(['=', 'fValidarDescuentoPaquete(fab_bkl_medios_recursos_paquetes.valor, fab_bkl_medios_recursos_paquetes.descuento,fab_bkl_medios_recursos_paquetes.fecha_desde, fab_bkl_medios_recursos_paquetes.fecha_hasta)', 1]);

                    }
                    if ($condicion == 'no_follow'){
                        $query->andWhere(['=', 'fab_bkl_medios_recursos.links_no_follow',1]);


                    }
                    if ($condicion == 'publicidad_cond'){
                        $query->andWhere(['=', 'fab_bkl_medios_influencers_recursos.publicidad_id', 3]);

                    }
                    if ($condicion == 'publicidad'){
                        $query->andWhere(['=', 'fab_bkl_medios_influencers_recursos.publicidad_id', 2]);


                    }
                    if ($condicion == 'publicidad_no'){
                        $query->andWhere(['=', 'fab_bkl_medios_influencers_recursos.publicidad_id', 4]);

                    }
                    if ($condicion == 'con_follow'){
                        $query->andWhere(['=', 'fab_bkl_medios_recursos.links_follow',1]);


                    }

                }

              }
            }
        }
        if (!empty($this->nro_enlaces)){
            if (count ($this->nro_enlaces)>1){
                $sql = "";
                foreach ($this->nro_enlaces as $enlaces ){
                    if (empty($sql)){
                        $sql = "  (fab_bkl_medios_recursos.max_links = ".$enlaces.")";
                    }else{
                        $sql.= " or (fab_bkl_medios_recursos.max_links = ".$enlaces.")";
                    }

                }
                $query->andWhere($sql);
            }
            else{
                if($this->nro_enlaces[0] != ""){
                    $nro_enlaces = $this->nro_enlaces;
                    $query->andWhere(['>=', 'fab_bkl_medios_recursos.max_links', intval($nro_enlaces[0])]);
                }
            }
        }
        $ids = [];

        if ($this->tipo_propuesta){
            $query->andFilterWhere(['in', 'fab_bkl_tipo_propuestas.id', $this->tipo_propuesta]);
        }
        else{
            if ($this->presupuesto_guardado != "" && isset($this->check_presupuesto) ){
                
                $tipo_paquetes = json_decode($this->tipos_paquetes_id);
                $query->andFilterWhere(['in', 'fab_bkl_tipo_propuestas.id',  $tipo_paquetes]);
    
            }
        }
        if (!empty($this->metrica_dr) and (($this->metrica_dr)!=0) ){

            $query->andFilterWhere([">=", "CAST(JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.dr')) as SIGNED) " , intval($this->metrica_dr)]);
        }
        if (!empty($this->metrica_da) and ($this->metrica_da!=0)){
            $query->andFilterWhere([">=", "CAST(JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.da')) as SIGNED) " , intval($this->metrica_da)]);
        }
      
       /* if (!empty($this->metrica_as) and ($this->metrica_as!=0)){
            $query->andFilterWhere([">=", "CAST(JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.as')) as SIGNED) " , intval($this->metrica_as)]);
        }*/
          if (!empty($this->metrica_dt) and ($this->metrica_dt!=0)){
            $query->andFilterWhere([">=", "CAST(JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.dt')) as SIGNED) " , intval($this->metrica_dt)]);
        }

        if (!empty($this->metrica_ss) and ($this->metrica_ss!=0)){

            $query->andFilterWhere(["<=", "CAST(JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.ss')) as SIGNED) " , intval($this->metrica_ss)]);

        }
        if (!empty($this->metrica_to) and ($this->metrica_to!=0)){
            $query->andFilterWhere([">=", "CAST(JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.to')) as SIGNED) " , intval($this->metrica_to)]);
        }
        if (!empty($this->metrica_tog) and ($this->metrica_tog!=0)){
            $query->andFilterWhere([">=", "CAST(JSON_UNQUOTE(JSON_EXTRACT(fab_bkl_medios_influencers_recursos.seccions, '$.tog')) as SIGNED) " , intval($this->metrica_tog)]);
        }
        //-----------------------------------------------------------
        if (!empty($this->precio_min) and ($this->precio_min!=0)){
            $query->andWhere(['>=', 'fCalcularMontoTotalPaquete('.$porc_wac.','.$comision.','.$retencion.',fab_bkl_medios_recursos_paquetes.valor,fab_bkl_medios_recursos_paquetes.descuento,fab_bkl_medios_recursos_paquetes.fecha_desde,fab_bkl_medios_recursos_paquetes.fecha_hasta)', $this->precio_min]);
        }
        
        if (!empty($this->precio_max) and ($this->precio_max!=0)){
            $query->andWhere(['<=', 'fCalcularMontoTotalPaquete('.$porc_wac.','.$comision.','.$retencion.',fab_bkl_medios_recursos_paquetes.valor,fab_bkl_medios_recursos_paquetes.descuento,fab_bkl_medios_recursos_paquetes.fecha_desde,fab_bkl_medios_recursos_paquetes.fecha_hasta)', $this->precio_max]);
        }
        if($this->enOferta == "oferta"){
            if ((empty($this->metrica_dr) or ($this->metrica_dr) ==0) 
            and (empty($this->precio_max) or ($this->precio_max==0))
            and (empty($this->precio_min) or ($this->precio_min==0))
            and ((empty($this->metrica_da) or ($this->metrica_da) ==0))
            and (empty($this->metrica_ds) or ($this->metrica_ds) ==0)
            and (empty($this->condiciones))
            and (empty($this->paises))
            and (empty($this->idiomas))
            and (empty($this->categorias))
            and (empty($this->busqueda))
            ){
               $query->andWhere(['=', 'fValidarDescuentoPaquete(fab_bkl_medios_recursos_paquetes.valor, fab_bkl_medios_recursos_paquetes.descuento,fab_bkl_medios_recursos_paquetes.fecha_desde, fab_bkl_medios_recursos_paquetes.fecha_hasta)', 1]);
            
            }
            else {
                $query->orWhere(['=', 'fValidarDescuentoPaquete(fab_bkl_medios_recursos_paquetes.valor, fab_bkl_medios_recursos_paquetes.descuento,fab_bkl_medios_recursos_paquetes.fecha_desde, fab_bkl_medios_recursos_paquetes.fecha_hasta)', 1]);
            }
        }
        if ($this->tematicas){
            if (count($this->tematicas)>1){
                
                $query->andWhere('fab_bkl_medios_influencers_recursos.id  not in (select medio_recurso_id 
                 from fab_bkl_medios_recursos_tematicas_no_aceptadas where fab_bkl_medios_recursos_tematicas_no_aceptadas.tematica_no_aceptada_id in ('.implode(',',$this->tematicas).'))');
            }
            else {
                $tematica = $this->tematicas;
                $query->andWhere('fab_bkl_medios_influencers_recursos.id  not in (select medio_recurso_id 
                from fab_bkl_medios_recursos_tematicas_no_aceptadas where  fab_bkl_medios_recursos_tematicas_no_aceptadas.tematica_no_aceptada_id = '.intval($tematica[0]).')');
            }
            
        }
       /* else{
            if ($this->presupuesto_guardado != "" && isset($this->check_presupuesto) ){
                
                $tipo_paquetes = json_decode($this->tipos_paquetes_id);
                $query->andFilterWhere(['in', 'fab_bkl_tipo_propuestas.id',  $tipo_paquetes]);
    
            }
        }*/
        //------------------------------------------------------------------------------------
        $opciones=Yii::$app->user->identity->getProcessLabelAll(Yii::$app->user->identity->id);
        $compraBkl=strpos($opciones, 'Comprar Backlinks')!==false;
        $excluirPqte=0;
        if ( $compraBkl && (\Yii::$app->user->can('MedioInfluencer') && !\Yii::$app->user->can('Comprador')) ) {
            $tProp= new TipoPropuestas();
            $idWac_POST=$tProp->getIdByName(TipoPropuestas::POST_WAC);  
            \Yii::error('tipo prop en filtro FORM L590 '.$idWac_POST);     
            $query->andWhere(['!=', 'fab_bkl_tipo_propuestas.id', $idWac_POST]);
            $excluirPqte=$idWac_POST;
         }
       
      //-----------------------------------------------------------------
       $offset = (($this->page * intval($this->length)) - intval($this->length));
       Yii::warning('Offset: ', $offset);
       //$query->limit($this->length)->offset($offset);
       $query->groupBy(['fab_bkl_medios_influencers_recursos.id']);
       $query->orderBy(['fab_idiomas.idioma'=> 'Español']);
      // $query->orderBy('Rand()');
      // $countQuery = clone $query;

       
       $item = 0;
       $result = array();
       $paquetes_activos = array();
       $inicio = $offset;
       $fin = (intval($this->length) * $this->page) +1;
       //Yii::error('fin es: '.$fin);
       if ($this->page>1){
          $inicio = (intval($this->length) * $this->page) - intval($this->length);
       }
       //Yii::error('inici es: '.$inicio);
       if ($inicio == 0){
           $fin = intval($this->length) + 1;
       }
       //--------------
       //$item = $countQuery->count(); 
       
       // --- si se solicita plan óptimo, se trabaja primero con todos los medios para elaborarlo
       if($this->presupuesto!=''){
        $all_data = $query->asArray()->all();
        $cont_precio= 0; 


       }
      //-----------------
       $item = $query->count(); 
       if(empty($this->presupuesto)) {
        $query->offset($inicio)->limit($this->length);
        if ($this->page ==  1){
            $query->orderBy('rand()');
        }
        
       }
       
        if ($query){
        
            $best_medios = null;
            $cliente_siguiere_plan_optimo = false;


                // si viene del botón filtrar del plan óptimo
             if($this->presupuesto!='' && isset($this->tipo_propuesta) && $item>0){

             $keywords_relacionadas= null;
             
              //  Yii::Warning("Query: ", var_export($all_data, true));


             // ----------------------------------------------------------------- //
             // validar si está en el tipo paquete con id = 7, entonces mostrar medios con artículos afines a las keywords.
             
                if ($this->tipo_propuesta[0] == 7 && $this->keywords_cliente!== ''){

                    $cliente_siguiere_plan_optimo = true;

                    $url_max_sugerida =  Valores::getValorByName("url_max_sugerida");
                    Yii::Warning("url_max_sugerida: ", $url_max_sugerida);

                    Yii::Warning("Keywords del filtro: ", var_export($this->keywords_cliente, true));
                    $keywords_relacionadas = str_replace(",13", "", $this->keywords_cliente);
                    $keywords_relacionadas = explode(",", $keywords_relacionadas);

                    // modificar arreglo de keywords
                     foreach ($keywords_relacionadas as &$keyword) {
                        $keyword = iconv('UTF-8', 'ASCII//TRANSLIT', $keyword); // Eliminar acentos
                        $keyword = strtolower($keyword); // Convertir a minúsculas
                        $keyword = trim($keyword); // eliminar espacios
                    }
                    Yii::Warning("Keywords: ", var_export($keywords_relacionadas, true));

                    // extraer urls de los medios
                    $urls_medios = array_map(function($medio) {
                        return $medio["url"];
                    }, $all_data);
                   # Yii::Warning("Urls de los medios: ", var_export($urls_medios, true));
                 }
                  


             // ------------------------------------------------

               # $precio_paquetes   = array();
                $data_medios = array();
               
                foreach ($all_data as $modelo){
                   
                    if ($modelo['mediosRecursosPaquetes']) {
                        $nro_paquetes = 0;
                        $sum_precio_paquetes= 0;

                        foreach ( $modelo['mediosRecursosPaquetes'] as $paquete ){
                    
                           // Yii::warning("Paquete valor: ". $paquete['valor']);
                            if ($paquete['paquetes']['tipo_propuesta_id']!= $excluirPqte && !empty($paquete['valor']) && $paquete['valor']!= 0  ){        

                                                                                                              // sea el(los) tipo(s) de paquete(s) seleccionado en el form
                                    if (($paquete['paquetes']['tipoPropuestas']['estado'] == 1)  and $this->tipo_propuesta?( in_array($paquete['paquetes']['tipoPropuestas']['id'],$this->tipo_propuesta)) : true ){
                                            //Filtra el paquete por tipo de propuesta
                                            
                                             //Yii::warning($paquete['id']);
                                             $nro_paquetes++;
                                             $calculo = MediosRecursosPaquetes::calcularPrecioPaquete($paquete,$id_cliente);
                                             $precio =  $calculo->precio_cliente_completo;
                                             $sum_precio_paquetes += $precio;          
                                                    
                                     }
                             }
                        }
                        if ($nro_paquetes != 0){

                            $data_medios[] =  array(
                                // estos 3 datos para el algoritmo de optimización
                                'precio'   =>  $sum_precio_paquetes/$nro_paquetes, 
                               // 'nombre'        => $paquete['paquetes']['nombre_cliente'], #tipo de paquete
                                'nombre' => $modelo['nombre'],
                                'prom_metricas' => $modelo['prom_metricas'],
                                'id' => $modelo['id'],
                               // 'url' => $this->tipo_propuesta[0] == 7 ? $modelo['url'] : null,  
                               'url' => $cliente_siguiere_plan_optimo ? $modelo['url'] : null,
                                
                            );

                        }
                       
                        
                   
                    }
                 
               }

              

                 // Yii::warning('********************** DATOS DE LOS MEDIOS : ', var_export($data_medios,true));
                
       
                    // se llama a la función de optimización
                     $best_medios = $this->PlanOptimo($this->presupuesto, $data_medios, $keywords_relacionadas,$cliente_siguiere_plan_optimo, null);
                                       // Yii::warning('BEST MEDIOS! : ', $best_medios);
                     // ordenar los mejores medios por: metricas ASC, precio DESC
                     
                     if ( $cliente_siguiere_plan_optimo){
                        array_multisort(
                            $best_medios ['metricas'], SORT_DESC, SORT_NUMERIC,
                            $best_medios ['precio'], SORT_ASC, SORT_NUMERIC,
                            $best_medios ['medio_nombre'], $best_medios['medio_id'],
                            $best_medios['medio_url' ], $best_medios['articulos_url' ]
                     );
                     } else {
                        array_multisort(
                            $best_medios ['metricas'], SORT_DESC, SORT_NUMERIC,
                            $best_medios ['precio'], SORT_ASC, SORT_NUMERIC,
                            $best_medios ['medio_nombre'], $best_medios['medio_id'],
                            $best_medios['medio_url' ]
                     );
                     }
                     $articulos_medios = $best_medios['articulos_url'];
                     $articulos_medios = array_values($articulos_medios);

                    if (!empty($best_medios['medio_id'])){
                        $query->FilterWhere(['in', 'fab_bkl_medios_influencers_recursos.id', $best_medios['medio_id']]);
                        // ordenar la query 
                        $query->orderBy(new \yii\db\Expression('FIELD (fab_bkl_medios_influencers_recursos.id, '.implode(',', $best_medios['medio_id']).')'));

                        if (count($best_medios['medio_id']) > intval($this->length)){
                            $query_completa = $query; // copia de los mejores medios sin paginación
                            $result_completo = $this->paqueteOptimoCompleto($query_completa,  $excluirPqte,$id_cliente, $best_medios, $articulos_medios, $cliente_siguiere_plan_optimo);
                        }
                      
                        $query->offset($inicio)->limit($this->length);
                    }
                    else{
                        $query->limit(0);
                    }
                    
                   // Yii::warning('********************** BEST MEDIOS LEN : ', var_export(count($best_medios['medio_id']),true));
                   // Yii::warning('********************** Medios del paquete óptimo: ', var_export($best_medios,true));

             }  
             else{
                $query->offset($inicio)->limit($this->length); // para que pueda paginar cuando es draft y plan optimo y se usa las páginas para navegar sin filtrar
             }
                  //  $query->orderBy('rand()');

                    $data = new ActiveDataProvider([
                        'query' => $query->asArray(),
                        'pagination' => false,
                        'sort' => [
                            'defaultOrder' => [
                                'id' => SORT_DESC,
                                
                            ]
                        ],
                    ]);
                //////////////////////////////////////////////////////////////
                     $contador_articulos = 0;
                      foreach ($data->getModels() as $modelo)
                      {
                        //   Yii::warning('********************** Nombre del Medio : ', var_export($modelo['nombre'],true));
                            $idiomas    = array();
                            $categorias = array();
                            $paquetes   = array();
                            $cadena_idiomas = "";
                            $disponible = true;
                            $index_articulos = 0;
                            //-----modo vacaciones
                           
                            $flag = true;
                                     
                          if  ($flag){

                              if ($modelo['idiomasIds']){ 
                                  foreach ($modelo['idiomasIds'] as $idioma){
                                      $idiomas[] = (object)array(
                                          'idIdioma' =>$idioma['idIdioma'],
                                          'idioma'   => $idioma['idioma'],
                                      );
                                      if (empty($cadena_idiomas)){
                                          $cadena_idiomas = $idioma['idioma'];
                                      }
                                      else {
                                          $cadena_idiomas =$cadena_idiomas." / ".$idioma['idioma'];
                                      }
                                  }
          
          
                                }
          
          
                              if ($modelo['mediosInfluencersRecursosCategorias']){
                                  foreach ($modelo['mediosInfluencersRecursosCategorias'] as $categoria){
                                      $categorias[] = (object) array(
                                          'categoria_id' =>$categoria['categoria_id'],
                                          'id'           => $categoria['id'],
                                          'medio_influencer_recurso_id' =>$categoria['medio_influencer_recurso_id'],
                                      );
                                  }
                               }
          
                                  if ($modelo['mediosRecursosPaquetes']) {
                                         
                                          $nro_paquetes = 0;
                                          $nro_rangos  = 0;
                                          $cant_precio_paq = 0;
                                          $cant_precio_rang = 0;

                                          if (empty($this->tipo_propuesta)){
                                            $condicion = true;

                                          }
                                          else{

                                          }
                                      foreach ($modelo['mediosRecursosPaquetes'] as $paquete)
                                      {                                                                       //   modificado
                                          if ($paquete['paquetes']['tipo_propuesta_id']!= $excluirPqte  && !empty($paquete['valor']) && $paquete['valor']!= 0){
                                                                                                           
                                              //                                              if ( ($paquete['paquetes']['tipoPropuestas']['estado'] == 1) and ($this->presupuesto!='') ? ($this->tipo_propuesta!='' ? in_array($paquete['paquetes']['tipoPropuestas']['id'],$this->tipo_propuesta) : true) : true  ){
                                             
                                             
                                              if ($this->presupuesto_guardado != "" && isset($this->check_presupuesto) && !isset($this->tipo_propuesta)){
                                             //   Yii::warning("Tipos paquetes id en modelo: ", $this->tipos_paquetes_id );
                                              //  Yii::warning("Tipos paquetes id var dump : ", var_export($this->tipos_paquetes_id,true) );
                                                $tipo_paquetes = json_decode($this->tipos_paquetes_id);
                                              

                                               
                                             //   Yii::warning("Tipos paquets en BD : ",$paquete['paquetes']['tipoPropuestas']['id'] );
                                                    $condicion = in_array($paquete['paquetes']['tipoPropuestas']['id'], $tipo_paquetes );
                                               
                                                   
                                            }
                                              else{
                                                    $condicion = ($this->presupuesto!='') ? ($this->tipo_propuesta!='' ? in_array($paquete['paquetes']['tipoPropuestas']['id'],$this->tipo_propuesta) : true) : true;
                                              }

                                           // if ($paquete['paquetes']['tipoPropuestas']['estado'] == 1 )          # si es plan optimo, y no eligio tipo de propuesta, trae todas, si no, trae aquellas que escogio
                                             if ( ($paquete['paquetes']['tipoPropuestas']['estado'] == 1) && $condicion  ){
                                                  //Filtra el paquete por tipo de propuesta
          
                                                  
                                                      $nro_paquetes++;
                                                      //calcular ganancia
                                                      $precio = 0;
                                                      $descuento= 0;
                                                      $rangos = array();
                                                      $tiene_rango = "no";
          
                                                          $descuento= $paquete['descuento'];
                                                          $calculo = MediosRecursosPaquetes::calcularPrecioPaquete($paquete,$id_cliente);

          
                                                                  $paquetes[] = (object) array(
                                                                      'id'            => $paquete['id'],
                                                                      'nombre'        => $paquete['paquetes']['nombre_cliente'],
                                                                      'medio_influencer_recurso_id' =>$paquete['medio_influencer_recurso_id'],
                                                                      'fecha_desde'           => $paquete['fecha_desde'],
                                                                      'fecha_hasta'           => $paquete['fecha_hasta'],
                                                                      'tipo_propuesta_id'     => $paquete['paquetes']['tipo_propuesta_id'],
                                                                      'precio_real'           => $paquete['valor'],
                                                                      'monto_ganancia_medio'  => $calculo->precio_proveedor,
                                                                      'monto_total'           => $calculo->precio_cliente,
                                                                      'monto_descuento_medio' => $calculo->monto_descuento_medio,
                                                                      'monto_impuesto'        => $calculo->monto_impuesto,
                                                                      'monto_ganancia_wac'    => $calculo->monto_ganancia_wac,
                                                                      'porcentaje_ganancia_medio'   =>$calculo->porcentaje_ganancia_medio,
                                                                      'porcentaje_descuento_medio'  => $calculo->tiene_descuento?$descuento:0,
                                                                      'porcentaje_ganancia_wac'     => $calculo->porc_ganancia_wac,
                                                                      'propuesta'             => $paquete['paquetes']['tipoPropuestas']['nombre_interno'],
                                                                      'estado_propuesta'      => $paquete['paquetes']['tipoPropuestas']['estado'],
                                                                      'tiene_rango'           => $tiene_rango,
                                                                      'rangos'                => $rangos,
                                                                      'porcentaje_comision'   => $calculo->comision,
                                                                      'porcentaje_impuesto'   => $calculo->retencion,
                                                                      'monto_comision'   => $calculo->monto_comision,
                                                                      'paquete_id'            =>$paquete['paquete_id'],
                                                                      'precio_cliente_completo'   => $calculo->precio_cliente_completo
          
                                                                  );

                                                                
          
          
                                              }
                                          }
                                      }
                                  }

                          
                                  if (count($paquetes)>0){
          
                                      ArrayHelper::multisort($paquetes,'nombre',SORT_ASC);
          
                                            //cargar imagen
                                            $publicacion = "";
                                            if ($modelo['tiempo_publicacion_post']==NULL or $modelo['tiempo_publicacion_post']==0){
                                                $publicacion = "Indefinido";
                                            }
                                            else {
                                                $publicacion = $modelo['tiempo_publicacion_post']." días";
                                            }
                
                                            $tipo_enlaces = [];
          
                                          if ($modelo['mediosRecursos']['links_follow']==1){
                                              $tipo_enlaces[] = "Acepto enlaces Follow";
                                          }
          
                                          if ($modelo['mediosRecursos']['links_no_follow']==1){
                                              $tipo_enlaces[] = "Acepto enlaces No-follow";
                                          }
          
                                      //validar url
                                     // $validateUrl = $this->validateUrl($modelo['url']);
                                     
                                      $result [] = (object) array(
                                          'id'            => $modelo['id'],
                                          'nombre'        => $modelo['nombre'],
                                          'url'           => $modelo['url'],
                                          'descripcion'   => $modelo['descripcion'],
                                          'email_recurso' => $modelo['email_recurso'],
                                          'telefono'      => $modelo['telefono'],
                                          'medio_influencer_id'=> $modelo['medio_influencer_id'],
                                          'metricas'      => ($modelo['metricas']!= NULL)?json_decode($modelo['metricas']):"",
                                          'seccions'      => ($modelo['seccions']!= NULL)?json_decode($modelo['seccions']):"",
                                          'pais_id'       => $modelo['pais_id'],
                                          'publicidad_id' => $modelo['publicidad_id'],
                                          'recurso_id'    => $modelo['recurso_id'],
                                          'verificado'    => $modelo['verificado'],
                                          'tiempo_publicacion_post' => $publicacion,
                                          'tipo_verificacion_id' => $modelo['tipo_verificacion_id'],
                                          'idiomasIds'    => $idiomas,
                                          'cadena_idiomas' => $cadena_idiomas,
                                          'mediosInfluencersRecursosCategorias' => $categorias,
                                          'mediosRecursosPaquetes'    => $paquetes,
                                          'nro_links'    => $modelo['mediosRecursos']['max_links'],
                                          'tipo_enlaces' =>$tipo_enlaces,
                                          'url_imagen' => $modelo['imagen_url'],
                                          'prom_metricas' => $modelo['prom_metricas'],  // nuevo
                                          'code_url' => ($modelo['code_url']==1)?'hide':'',  // 
                                          'urls_articulos' =>($best_medios && $cliente_siguiere_plan_optimo)?$articulos_medios[$contador_articulos]:null,
                                          
                                      );
                                      
                                  }
          
                          }


                          if($this->presupuesto!= ''){
                            //Yii::warning('********************** contador precio: ', $cont_precio);
                            $cont_precio++;
                          }
                          $contador_articulos++;
                      }
                  
                      $_count = ($this->presupuesto!= '' && isset($data_medios))?count($best_medios['medio_id']):$item;  #  count($best_medios[3]); // o count($paquetes)
                      
                       //Yii::warning('********************** Result : ', var_export($result,true));
                       Yii::warning('********************** Count Result : ', count($result));
                       
                       
                      $_pages = ceil($_count / $this->length);
                      
                      if (count($result)>0){
                          return $resultado = (object) array(
                              'total' =>  $_count,
                              'offset' => $offset,
                              'length' => $this->length,
                              'pages' => $_pages,
                              'current' => $this->page,
                              'item' => $_count, // " X sitios web encontrados "
                              'result' => $result,
                              'paquetes_activos' => $paquetes_activos,
                              'enoferta' => $this->enOferta,
                              'result_completo' => isset($result_completo) ?  $result_completo : null,
                              'url_max_sugerida' => isset($url_max_sugerida) ? $url_max_sugerida : null,
                          );
                      }
                      else {
                          return $resultado = (object) array(
                              'total' => 0,
                              'offset' => $offset,
                              'length' => $this->length,
                              'pages' => 1,
                              'current' => 1,
                              'item' => 0,
                              'result' => $result,
                              'paquetes_activos' => $paquetes_activos,
                              'enoferta' => $this->enOferta,
                              'result_completo' => null
                          );
                      }
        
            

                    // ------------------------------//

        }
        else {
           return false;
        }

    }


    
/////////////////////// funcion para calcular plan óptimo de backlinks //////////////////

public function PlanOptimo($presupuesto, $medios, $keywords,$cliente_siguiere_plan_optimo, $mediosGuardados)
{

  /*
    $medios tiene el promedio de métricas del medio,
    el precio del plan escogido por el usuario,
    el nombre y el id
  */

    $itemsCount = count($medios);
    Yii::Warning("Medios restantes para el calculo del plan: ", $itemsCount);
    $K = array();

    for ($i = 0; $i <= $itemsCount; ++$i)
    {
        for ($w = 0; $w <= intval($presupuesto); ++$w)
        {
            if ($i == 0 || $w == 0)
                $K[$i][$w] = 0;
            else if ($medios[$i - 1]['precio'] <= $w)
                $K[$i][$w] = max(intval($medios[$i - 1]['prom_metricas']) + $K[$i - 1][$w - intval($medios[$i - 1]['precio'])], $K[$i - 1][$w]);
            else
                $K[$i][$w] = $K[$i - 1][$w];
        }
    }

    // guardar el resultado 
    $res = $K[$itemsCount][$presupuesto];
    $final_credits = 0;
    $prom_value = $res;

    // arreglos para almacenar los precios y las métricas
    // de los medios escogidos
    $best_medios = [];
    $metrics = [];
    $prices = [];
    $w = intval($presupuesto);
    $medios_escogidos= [];
    $id_medios_escogidos=[];
    $urls_medios_escogidos= [];
    $articulos_url= [];
    //Yii::warning('********************** VALOR $K: ',var_export($K,true));

    for ($i = $itemsCount; $i >= 1 && $w > 0; $i--)
    {
        if ($res <= 0)
        {
            break;
        }

      #  if ($res == $K[$i-1][intval($w)])

        if ($res == $K[$i-1][$w])
        {
            continue;
        }
            
        else  // este medio se incluyó
        {
     
         // se suman los creditos de ese medio al total
            $final_credits+= intval($medios[$i-1]['precio']);

            $res= $res - intval($medios[$i-1]['prom_metricas']);
            $w = $w - intval($medios[$i-1]['precio']);// floatval($medios[$i-1]['precio']);

            // se agregan los datos de ese medio
            $prices[] = intval($medios[$i-1]['precio']);
            $metrics[] = intval($medios[$i-1]['prom_metricas']);
            $medios_escogidos[] = $medios[$i-1]['nombre'];
            $id_medios_escogidos[]= $medios[$i-1]['id'];
            $urls_medios_escogidos[] = $medios[$i-1]['url'];
        } 
    }

   # final_credits, adicional
    $best_medios = [$prices, $metrics, $medios_escogidos, $id_medios_escogidos, $urls_medios_escogidos, $articulos_url];
    $custom_keys = array('precio', 'metricas', 'medio_nombre', 'medio_id', 'medio_url', 'articulos_url');
    $best_medios = array_combine($custom_keys,  $best_medios);
  // --------------------
    // Si es el caso de tipo de paquete 7, analizar las urls en busqueda de articulos relacionados con las kw
    Yii::Warning("Cantidad de Medios en plan optimo inicial: " , count($best_medios['medio_id']));
   // if ($this->tipo_propuesta[0] == 7 && count($best_medios['medio_id'])>0){  // && count($best_medios) mayor a 0
    if ($cliente_siguiere_plan_optimo && count($best_medios['medio_id'])>0){  // && count($best_medios) mayor a 0

        
        Yii::Warning("Entro a recalcular...");
        $datos_medios = array_map(function ($id, $url) {
            return array('id' => $id, 'url' => $url);
        }, $id_medios_escogidos, $urls_medios_escogidos);

        #$urlsAfines = $this->articulosAfines($urls_medios_escogidos, $keywords);
        $urlsAfines = $this->articulosAfines($datos_medios, $keywords);
     #   Yii::Warning("Medios total:", var_export($medios,true));
     #   Yii::Warning("Medios incluidos:", var_export($urlsAfines,true));
       # $prueba = array_merge($best_medios,  $urlsAfines);
       # Yii::Warning("Prueba de arreglo final:", var_export($prueba,true));

        if(is_null($urlsAfines)){

           $mediosRestantes = $this->recalcularMedios($medios, $urls_medios_escogidos);
           # Yii::Warning("Medios restantes: " , var_export($mediosRestantes,true));
           #if (!is_null($mediosGuardados)){
           return $this->PlanOptimo($presupuesto, $mediosRestantes, $keywords, $cliente_siguiere_plan_optimo, $mediosGuardados);

           # }else{
           #     $this->PlanOptimo($presupuesto, $mediosRestantes, $keywords, null);
          #  }
         

        }

        else { // si se hallo al menos 1 medio compatible
           # Yii::Warning("Entro a urls afines Si hay alguno");

            $best_medios = $this->añadirUrlsArticulos($best_medios, $urlsAfines); // añadir urls de articulos
          
              
                // Recalcular paquete optimo con el resto de medios
                $mediosRestantes = $this->recalcularMedios($medios,$urls_medios_escogidos);
                
                $claves_urls_afines = array_keys($urlsAfines);
                $presupuestoRecalculado = $this->recalcularPresupuesto($presupuesto, $medios, $claves_urls_afines);
                $mediosAgregados = $this->guardarMedioRecalculoPlan($urlsAfines, $best_medios);
                if (!is_null($mediosGuardados)){

                    $totalBestMedios = $this->unirMedios($mediosGuardados, $mediosAgregados);
                    
                }else{
                    $totalBestMedios = $mediosAgregados;
                }
                Yii::Warning("Presupuesto nuevo: " , $presupuestoRecalculado);
            #    Yii::Warning("Medios restantes: " , var_export($mediosRestantes,true));
                Yii::Warning("Medios guardados en paquete optimo: " , var_export($totalBestMedios, true));
               return $this->PlanOptimo($presupuestoRecalculado, $mediosRestantes, $keywords, $cliente_siguiere_plan_optimo, $totalBestMedios);
          #  }

        }

     }
     
   /*   Yii::Warning("Medios guardados: " ,  var_export($mediosGuardados,true));
     Yii::Warning("Best medios : " ,  var_export($best_medios, true));

         if (!is_null($mediosGuardados)){
            Yii::Warning("Entro a medios guardados NO NULL");
            return $mediosGuardados;
         }
         else{
            Yii::Warning("Entro a medios guardados NULL");
            return $best_medios;
         } */
 
     // --------------------
 
     #Yii::Warning("Medios guardados: " ,  var_export($mediosGuardados,true));
    # Yii::Warning("Best medios : " ,  var_export($best_medios, true));
    return $mediosGuardados ? $mediosGuardados : $best_medios;
}

function unirMedios($mediosGuardados, $mediosAgregados){

    foreach ($mediosGuardados as $key => $values) {
        foreach ($values as $value) {
            $mediosAgregados[$key][] = $value;
        }
    }
    return $mediosAgregados;
}

function añadirUrlsArticulos($best_medios, $urlsAfines){

    foreach ($urlsAfines as $urlMedio => $articulos) {
        // Buscar la clave del medio en $best_medios['medio_url']
        $claveMedio = array_search($urlMedio, $best_medios['medio_url']);
    
        // Si se encuentra la clave, agregar el contenido de $articulos en "articulos_url"
        if ($claveMedio !== false) {
            $best_medios['articulos_url'][$claveMedio] = $articulos;
        }
    }
    return $best_medios;
}

function recalcularMedios($medios, $mediosExcluir){
 /**
 * Recalcular array de medios usados para generar plan optimo de BKL.
 *
 * @param array $medios medios actuales
 * @param array $mediosExcluir medios a excluir para el recalculo
 * @return array medios excluyendo los que estan en $mediosExcluir
 */
    $mediosRestantes = array_values(array_filter($medios, function($medio) use ($mediosExcluir) {
        return !in_array($medio['url'],  $mediosExcluir);
    }));

    return $mediosRestantes;
}

function recalcularPresupuesto($presupuesto, $medios, $mediosIncluir){

   /**
 * Recalcula el presupuesto restando el precio de los paquetes de medios incluidos
 *
 * @param int $presupuesto El presupuesto total
 * @param array $medios Los medios disponibles
 * @param array $mediosIncluir Los medios que se deben incluir en el cálculo
 * @return int El presupuesto recalculado
 */
    $mediosIncluidos = array_filter($medios, function($medio) use ($mediosIncluir) {
        return in_array($medio['url'], $mediosIncluir);
    });
      
    $preciosMediosIncluidos = array_reduce($mediosIncluidos, function($acumulado, $medio) {
        return $acumulado + $medio['precio'];
    }, 0);

    $presupuestoRecalculado = intval($presupuesto - $preciosMediosIncluidos);

    return  $presupuestoRecalculado;
}
    
function guardarMedioRecalculoPlan($urlsAfines,  $best_medios ){

  #  Yii::Warning("Recalculo de medios guardados...");

    $clavesUrlsAfines = array_keys($urlsAfines);
   # Yii::Warning("Claves de urls afines: ", var_export($clavesUrlsAfines,true));
    
    // Inicializar el arreglo filtrado con la misma estructura que $best_medios pero con valores vacíos
    $mediosFiltrados = array_fill_keys(array_keys($best_medios), array());

    // Iterar sobre cada clave del arreglo de medios incluidos
    foreach ($clavesUrlsAfines as $claveUrl) {
    
        // Obtener las claves que coinciden en ambos arreglos
        $clavesCoincidentes = array_keys($best_medios['medio_url'], $claveUrl, true);
        // Filtrar solo las claves coincidentes
        foreach ($best_medios as $clave => $valores) {
            $mediosFiltrados[$clave] = array_merge($mediosFiltrados[$clave], array_intersect_key($valores, array_flip($clavesCoincidentes)));
        }
    }
  #  Yii::Warning("Medios filtrados: ", var_export($mediosFiltrados,true));
    return $mediosFiltrados;
}

function articulosAfines($urls, $keywords) {

    /**
      * Extraer urls de articulos.
      *
      * Esta función recibe las urls de los medios resultantes del filtro y determina los articulos de los medios que sean afines
      * a las keywords.
      *
      * @param array $keywords keywords que se introducen en el filtro
      * @param array $urls urls de los medios obtenidas luego de haber filtrado.
      * @return int La suma de los dos números.
    */
    $data = array();

    if (intval($this->presupuesto) <= 1){   ## CAMBIAR A 120 O 150 !!!!!!!!!!!!!!!!
        foreach ($urls as $medio){

            $urls_posts= array();
            $resultado = $this->googleSearch($medio['url'], $keywords);
            if ($resultado){
                Yii::Warning("Entro a match con medio:  $medio");
              $urls_posts [] = $resultado;
            }
            usleep(500000); // 0.5 segundos
           
            if($urls_posts){
               
                $urls_posts = array_merge(...$urls_posts);
                $data[$medio['url']] = $urls_posts;
             }
        }
        
        Yii::Warning("RESULTADO : ", var_export($data,true));
        if (!$data){

            $data = $this->analizarUrlsArticulos($urls, $keywords);
        } 
    }
    else{
        $data = $this->analizarUrlsArticulos($urls, $keywords);

    }
    
      
    Yii::Warning("Articulos encontrados: ", var_export($data,true));
    #print_r($data);
    return $data ? $data : null;
}

function analizarUrlsArticulos($urls, $keywords){

  Yii::Warning("MEDIOS CON ID Y URL : ", var_export($urls,true));
//     // leer json con BD de articulos
//     $jsonFile = Yii::getAlias('@backend/web') . '/urls_articulos_prueba.json';
//     $json = file_get_contents($jsonFile);

//     // Decodificar el archivo JSON en un arreglo
//     $bd_urls = json_decode($json, true);
//  #   Yii::Warning("Medios de la BD : ", var_export($bd_urls ,true));
//     $medios_filtrados = array_filter($bd_urls, function($key) use ($urls) {
//         return in_array($key, $urls);
//     }, ARRAY_FILTER_USE_KEY);
//    # Yii::Warning("Medios filtrados : ", var_export($medios_filtrados,true));

    $data = array();
    $n = 1;
    $cont = 0;
    $urls_posts= array();

    // foreach ($medios_filtrados as $medio => $url){

    //     $articulos = array();
    //     Yii::Warning("Medio: ", $medio);
    //     $urls_posts= array();
    //     $max_articulo_por_medio = 10;
    //     $articulos_encontrados = 0;
    //     foreach ($url as $value){
    //     // guardar todos los articulos del medio
    //         $articulos [] = $value;
    //        if ($this->url_con_keywords($value, $keywords)){
    //           $urls_posts [] = $value;
    //           $articulos_encontrados++;

    //           if ($articulos_encontrados >= $max_articulo_por_medio){
    //              break;
    //           }
              
    //        }
    //     }
    //         if($urls_posts){
    //         # $urls_posts = array_merge(...$urls_posts);
    //             $data[$medio] = $urls_posts;
    //             }
    //     }

        // ------------------------
        foreach ($urls as $medio){
            Yii::Warning("MEDIO:  ", $medio['url']);
            Yii::Warning("ID DEL MEDIO ", $medio['id']);
            $model = MediosRecursos::find()->where(['medio_influencer_recurso_id' => $medio['id']])->one();

            if ($model !== null && $model->urls_recurso !== null) {
                $urls_recurso = $model->urls_recurso;
            } else {
                Yii::Warning("No se encontro el medio o no tiene articulos registrados. SITIO: ", $medio['url']);
                continue;
                // Manejar el caso en el que $model es null, es decir, no se encontró ningún registro con el id especificado.
            }
            $articulos_medio = json_decode($urls_recurso, true);  
           // Yii::Warning("Articulos: ", var_export($articulos_medio,true));
            $articulos = array();
            Yii::Warning("Medio: ", $medio['url']);
            $urls_posts= array();
            $max_articulo_por_medio = 10;
            $articulos_encontrados = 0;
            foreach ($articulos_medio as $articulo){
                // guardar todos los articulos del medio
                    
                   if ($this->url_con_keywords($articulo, $keywords)){
                      $urls_posts [] = $articulo;
                      $articulos_encontrados++;
        
                      if ($articulos_encontrados >= $max_articulo_por_medio){
                         break;
                      }
                      
                   }
                }
                if($urls_posts){
                    # $urls_posts = array_merge(...$urls_posts);
                     $data[$medio['url']] = $urls_posts;
                 }

        }
        


        return $data ? $data : null;
}

function reemplazar_caracteres($string, $caracter1, $caracter2, $caracter3 = null, $caracter4 = null) {
    if ($caracter3 === null && $caracter4 === null) {
        $resultado = str_replace($caracter1, $caracter2, $string);
    } else {
        $resultado = str_replace($caracter1, $caracter2, str_replace($caracter3, $caracter4, $string));
    }
    return $resultado;
}


function url_tiene_keyword($url, $keyword) {
    $keyword_url = $this->reemplazar_caracteres($keyword, "ñ", "n", ".", "");

    if (count(explode(' ', $keyword_url)) == 1) {
       # Yii::Warning("Keyword longitud 1: ". $keyword_url ."\n");
        if (strpos(strtolower($url), $keyword_url) !== false) {
            return true;
        } else {
            return false;
        }
    } else {
        $keyword_caso1 = $this->reemplazar_caracteres($keyword, " ", "-", "ñ", "n");
        $keyword_caso2 = $this->reemplazar_caracteres($keyword, " ", "_", "ñ", "n");
        $keyword_caso3 = $this->reemplazar_caracteres($keyword, " ", "", "ñ", "n");

        if (strpos(strtolower($url), $keyword_caso1) !== false || strpos(strtolower($url), $keyword_caso2) !== false || strpos(strtolower($url), $keyword_caso3) !== false) {
            return true;
        } else {
            $palabras = explode(' ', $keyword);
            foreach ($palabras as $palabra) {
                $palabra = str_replace("ñ", "n", $palabra);
                if (strpos($palabra, '.') !== false) {
                    $opc1 = str_replace(".", "-", $palabra);
                    $opc2 = str_replace(".", "_", $palabra);
                    $opc3 = str_replace(".", "", $palabra);
                    if (strpos(strtolower($url), $opc1) === false && strpos(strtolower($url), $opc2) === false && strpos(strtolower($url), $opc3) === false) {
                        return false;
                    }
                } elseif (strpos(strtolower($url), $palabra) === false) {
                    return false;
                }
            }
            return true;
        }
    }
}

function url_con_keywords($url, $keywords) {
    foreach ($keywords as $keyword) {
        if ($this->url_tiene_keyword($url, $keyword) == true) {
            return true;
        }
    }
    return false;
}




function analizarContenido($urls, $keywords){

    // Inicializar multi-cURL
  $mh = curl_multi_init();
  $articulos = array();
  
  // Crear manejadores cURL para cada URL
  $curl_handlers = array();

  # echo "Longitud de posts: " . count($urls) . "\n";
  # echo "Type: " . gettype($urls_posts) . "\n";
  #  print_r($urls_posts);


  foreach ($urls as $url) {
    
    # echo "Creando manejadores para las urls...\n";
    $ch = curl_init($url);
    curl_setopt_array($ch, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0, # 5
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_SSL_VERIFYPEER =>false,
        CURLOPT_SSL_VERIFYHOST =>false,
         
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36',
        CURLOPT_HTTPHEADER =>  array(
            'Content-Type: text/html; charset=UTF-8',
            )
    ));

    curl_multi_add_handle($mh, $ch);
    $curl_handlers[] = $ch;
  }


  // Ejecutar solicitudes cURL simultáneamente
  $running = null;
  do {
    curl_multi_exec($mh, $running);
    curl_multi_select($mh);
    while ($info = curl_multi_info_read($mh)) {
        $match = false;
        $ch = $info['handle'];
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
        $content = curl_multi_getcontent($ch); // Obtiene el contenido de la URL
        $content = preg_replace('/<a[^>]*>(.*?)<\/a>/i', '', $content);
        $content = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $content); // Eliminar acentos
        $content = strtolower($content); // Convertir a minúsculas

        
       # echo "status code :" . $http_code . "\n";
       Yii::Warning("Status code: $http_code");
        if ($http_code == 200 || $http_code <399) {
         #   echo "$url is available\n";
            Yii::Warning("$url is available \n");
           # $articulos[] = $url;
           foreach ($keywords as $keyword) {
            if (strpos($content, $keyword) !== false) {
             #   echo "Keyword '$keyword' found in $url\n";
                Yii::Warning( "Keyword '$keyword' encontrada en $url\n");
                $match = true;
            }
          }
          if ($match){
            $articulos[] = $url;
          }
        } else {
           # echo "$url is not available\n";
            Yii::Warning("$url is not available\n");
        }
        curl_multi_remove_handle($mh, $ch);
        curl_close($ch);
    }
  } while ($running > 0);

  // Limpiar recursos de cURL
  curl_multi_close($mh);
 
  #print_r($articulos);

  return $articulos ? $articulos : null;
}


function googleSearch($medio, $keywords){


    $site = str_replace(['http://', 'https://', 'www.'], '', parse_url($medio, PHP_URL_HOST));
    # echo "Sitio : " . $site . "\n";

    $query = http_build_query([
    "q" => implode(" OR ", $keywords),
    "as_sitesearch" => $site,
    "num" => 5 // 9 numero de resultados
    ]);
    $url = "https://www.google.com/search?" . $query;

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    
    sleep(0.5);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    # echo "status code :" . $http_code . "\n";
    Yii::Warning("Status code: ", $http_code);
    curl_close($ch);
    
    #echo $response;

    $urls = array();
    $dom = new DOMDocument();

    if (!empty($response)){
        @$dom->loadHTML($response);
        $links = $dom->getElementsByTagName('a');
        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            if (strpos($href, '/url?q=') !== false) {
                $url = urldecode(substr($href, strpos($href, '/url?q=')+7, strpos($href, '&')-strpos($href, '/url?q=')-7));

                if( strpos($url, $medio) === 0  // solo articulos del dominio del medio
                 //   &&  strpos($url, ".google") == false  
                    && strpos($url, "tags") == false 
                    && strpos($url, "tag") == false){
                        $urls[] = $url;
                }
            }
        }
    }

   # print_r($urls);
    Yii::Warning("Urls encontradas en la busqueda: ", var_export($urls,true));
    $resultado = $this->analizarContenido($urls, $keywords);

    return $resultado;

}
   

public  function paqueteOptimoCompleto($query_completa, $excluirPqte, $id_cliente, $best_medios, $articulos_medios,$cliente_siguiere_plan_optimo){

    $data = new ActiveDataProvider([
        'query' => $query_completa->asArray(),
        'pagination' => false,
        /* 'sort' => [
            'defaultOrder' => [
                'id' => SORT_DESC,
                
            ]
        ], */
    ]);
    //////////////////////////////////////////////////////////////
      $contador_articulos = 0;
      foreach ($data->getModels() as $modelo)
      {
           $idiomas    = array();
            $categorias = array();
            $paquetes   = array();
            $cadena_idiomas = "";
            $disponible = true;
            //-----modo vacaciones

              if ($modelo['idiomasIds']){ 
                  foreach ($modelo['idiomasIds'] as $idioma){
                      $idiomas[] = (object)array(
                          'idIdioma' =>$idioma['idIdioma'],
                          'idioma'   => $idioma['idioma'],
                      );
                      if (empty($cadena_idiomas)){
                          $cadena_idiomas = $idioma['idioma'];
                      }
                      else {
                          $cadena_idiomas =$cadena_idiomas." / ".$idioma['idioma'];
                      }
                  }


                }


              if ($modelo['mediosInfluencersRecursosCategorias']){
                  foreach ($modelo['mediosInfluencersRecursosCategorias'] as $categoria){
                      $categorias[] = (object) array(
                          'categoria_id' =>$categoria['categoria_id'],
                          'id'           => $categoria['id'],
                          'medio_influencer_recurso_id' =>$categoria['medio_influencer_recurso_id'],
                      );
                  }
               }

                  if ($modelo['mediosRecursosPaquetes']) {
                         
                          $nro_paquetes = 0;
                          $nro_rangos  = 0;
                          $cant_precio_paq = 0;
                          $cant_precio_rang = 0;

                          if (empty($this->tipo_propuesta)){
                            $condicion = true;

                          }
                          else{

                          }
                      foreach ($modelo['mediosRecursosPaquetes'] as $paquete)
                      {                                                                       //   modificado
                          if ($paquete['paquetes']['tipo_propuesta_id']!= $excluirPqte  && !empty($paquete['valor']) && $paquete['valor']!= 0){
                                                                                           
                              
                           // if ($paquete['paquetes']['tipoPropuestas']['estado'] == 1 )          # si es plan optimo, y no eligio tipo de propuesta, trae todas, si no, trae aquellas que escogio
                             if ( ($paquete['paquetes']['tipoPropuestas']['estado'] == 1) and ($this->presupuesto!='') ? ($this->tipo_propuesta!='' ? in_array($paquete['paquetes']['tipoPropuestas']['id'],$this->tipo_propuesta) : true) : true  ){
                                  //Filtra el paquete por tipo de propuesta

                                  
                                      $nro_paquetes++;
                                      //calcular ganancia
                                      $precio = 0;
                                      $descuento= 0;
                                      $rangos = array();
                                      $tiene_rango = "no";

                                          $descuento= $paquete['descuento'];
                                          $calculo = MediosRecursosPaquetes::calcularPrecioPaquete($paquete,$id_cliente);


                                                  $paquetes[] = (object) array(
                                                      'id'            => $paquete['id'],
                                                      'nombre'        => $paquete['paquetes']['nombre_cliente'],
                                                      'medio_influencer_recurso_id' =>$paquete['medio_influencer_recurso_id'],
                                                      'fecha_desde'           => $paquete['fecha_desde'],
                                                      'fecha_hasta'           => $paquete['fecha_hasta'],
                                                      'tipo_propuesta_id'     => $paquete['paquetes']['tipo_propuesta_id'],
                                                      'precio_real'           => $paquete['valor'],
                                                      'monto_ganancia_medio'  => $calculo->precio_proveedor,
                                                      'monto_total'           => $calculo->precio_cliente,
                                                      'monto_descuento_medio' => $calculo->monto_descuento_medio,
                                                      'monto_impuesto'        => $calculo->monto_impuesto,
                                                      'monto_ganancia_wac'    => $calculo->monto_ganancia_wac,
                                                      'porcentaje_ganancia_medio'   =>$calculo->porcentaje_ganancia_medio,
                                                      'porcentaje_descuento_medio'  => $calculo->tiene_descuento?$descuento:0,
                                                      'porcentaje_ganancia_wac'     => $calculo->porc_ganancia_wac,
                                                      'propuesta'             => $paquete['paquetes']['tipoPropuestas']['nombre_interno'],
                                                      'estado_propuesta'      => $paquete['paquetes']['tipoPropuestas']['estado'],
                                                      'tiene_rango'           => $tiene_rango,
                                                      'rangos'                => $rangos,
                                                      'porcentaje_comision'   => $calculo->comision,
                                                      'porcentaje_impuesto'   => $calculo->retencion,
                                                      'monto_comision'   => $calculo->monto_comision,
                                                      'paquete_id'            =>$paquete['paquete_id'],
                                                      'precio_cliente_completo'   => $calculo->precio_cliente_completo

                                                  );

                                                


                              }
                          }
                      }
                  }

          
                  if (count($paquetes)>0){

                      ArrayHelper::multisort($paquetes,'nombre',SORT_ASC);

                            //cargar imagen
                            $publicacion = "";
                            if ($modelo['tiempo_publicacion_post']==NULL or $modelo['tiempo_publicacion_post']==0){
                                $publicacion = "Indefinido";
                            }
                            else {
                                $publicacion = $modelo['tiempo_publicacion_post']." días";
                            }

                            $tipo_enlaces = [];

                          if ($modelo['mediosRecursos']['links_follow']==1){
                              $tipo_enlaces[] = "Acepto enlaces Follow";
                          }

                          if ($modelo['mediosRecursos']['links_no_follow']==1){
                              $tipo_enlaces[] = "Acepto enlaces No-follow";
                          }

                        
                     
                      $result [] = (object) array(
                          'id'            => $modelo['id'],
                          'nombre'        => $modelo['nombre'],
                          'url'           => $modelo['url'],
                          'descripcion'   => $modelo['descripcion'],
                          'email_recurso' => $modelo['email_recurso'],
                          'telefono'      => $modelo['telefono'],
                          'medio_influencer_id'=> $modelo['medio_influencer_id'],
                          'metricas'      => ($modelo['metricas']!= NULL)?json_decode($modelo['metricas']):"",
                          'seccions'      => ($modelo['seccions']!= NULL)?json_decode($modelo['seccions']):"",
                          'pais_id'       => $modelo['pais_id'],
                          'publicidad_id' => $modelo['publicidad_id'],
                          'recurso_id'    => $modelo['recurso_id'],
                          'verificado'    => $modelo['verificado'],
                          'tiempo_publicacion_post' => $publicacion,
                          'tipo_verificacion_id' => $modelo['tipo_verificacion_id'],
                          'idiomasIds'    => $idiomas,
                          'cadena_idiomas' => $cadena_idiomas,
                          'mediosInfluencersRecursosCategorias' => $categorias,
                          'mediosRecursosPaquetes'    => $paquetes,
                          'nro_links'    => $modelo['mediosRecursos']['max_links'],
                          'tipo_enlaces' =>$tipo_enlaces,
                          'url_imagen' => $modelo['imagen_url'],
                          'prom_metricas' => $modelo['prom_metricas'],  // nuevo
                          'code_url' => ($modelo['code_url']==1)?'hide':'',  // 
                          'urls_articulos' =>($best_medios && $cliente_siguiere_plan_optimo)?$articulos_medios[$contador_articulos]:null,
                                          
                         

                      );
                      
                  }

          

                $contador_articulos++;
             }

        Yii::warning('********************** Resultado completo de paquetes: ', var_export($result,true));
        return $result;

}

////////////////////////---------------------------------///////////////////


    public static function calcularCostoRedactorWac($monto){

        $porc_wac = Valores::getValorByName("ganancia_wac");

        $id_cliente =  Yii::$app->user->identity->id;
        $cliente = Clientes::getComisiones($id_cliente);
        $retencion = $cliente->porcentaje_pais;
        $comision = $cliente->comision;

        $monto = ($monto / (1 - ($porc_wac / 100)));

        if ($comision!=0){

            $monto = ($monto /(1 - ($comision / 100)));
        }
        if ($retencion!=0){

            $monto = ($monto /(1 - ($retencion / 100) ));
        }

        Yii::$app->formatter->decimalSeparator = '.';
        Yii::$app->formatter->thousandSeparator = '';

        return Yii::$app->formatter->asDecimal($monto, 2);
    }

    public function checkBalance(){
        
        $paquetes_ids = json_decode($this->paquetes_ids);

        if(!$paquetes_ids) return false;

        $montoTotal = 0;

        foreach ($paquetes_ids as $id) {

            $medios_recursos_paquetes = MediosRecursosPaquetes::findOne(['id'=>$id]);

            if($medios_recursos_paquetes){
                if ($this->cliente){
                    $medios_recursos_paquetes->cliente_paquete = $this->cliente;
                }
                $montoTotal+= $medios_recursos_paquetes->precioPaquete->precio_cliente;
            }
        }
       
        //cliente oc
        //compara contra el monto de odc de backlinks
        //Yii::error("antes de odc");
        if ($this->odc){
           // Yii::error("comparando contra odc ");
           // Yii::error("montoTotal ".$montoTotal);
           // Yii::error("monto_odc ".$this->monto_odc);
            return  Creditos::getSaldoBKL($montoTotal, $this->monto_odc);
        }
        else  {
            //cliente con oc independiente
            Yii::error("cliente con oc independiente");
            $tieneOC = Yii::$app->user->identity->getUsarocc();
            
            if ($tieneOC && !($this->odc)){
                Yii::error("aqui? cliente con oc independiente ".$montoTotal);
                $saldoCliente = Creditos::getSaldoClienteOC($montoTotal,Yii::$app->user->identity->id);
                if ($saldoCliente->disponible == 0){
                    return false;    
                }
                else {
                    return true;
                }
                
            }
            else if (!$tieneOC && $this->cliente){
                Yii::error("allá? cliente con oc independiente");
                return Creditos::checkBalance($montoTotal,$this->cliente);    
            }
            Yii::error("ninguno...? cliente con oc independiente");
            return Creditos::checkBalance($montoTotal);
        }
    }

    public function getBalance(){
        
        $valido = false;
        $saldo = null;
        if ($this->cliente){
            //$saldo = Creditos::getSaldo("CLIENTE",$this->cliente);
            if (!empty($this->monto_odc)){
                Yii::error("comparando contra odc ".$this->monto_odc);
                if (!empty($this->monto_odc) and $this->monto_odc!=0){
                    $valido =  Creditos::getSaldoBKL($this->monto_total, $this->monto_odc);
                    if ($valido === false){
                        $this->saldo_disponible = floatval($this->monto_total) - floatval($this->monto_odc);
                    }
                    else {
                        $valido = true;
                    }
                    
                }
                else{
                    
                    $valido = Creditos::checkBalance($this->monto_total,$this->cliente);
                    if ($valido === false){
                        $saldo = Creditos::getSaldo("CLIENTE",$this->cliente);
                        $this->saldo_disponible = floatval($this->monto_total) - floatval($saldo['disponible']);
                    }
                    else {
                        $valido = true;
                    }
                }
                
            }
            else  {
                
                if(intval(Yii::$app->user->id) == intval($this->cliente)){
                    $tieneOC = Yii::$app->user->identity->getUsarocc();
                    if ($tieneOC){
                        Yii::error("cliente  en getBalance tiene oc??".$this->monto_total);
                        $saldoCliente = Creditos::getSaldoClienteOC($this->monto_total,Yii::$app->user->identity->id);
                        if ($saldoCliente->disponible == 0){
                            $this->saldo_disponible = floatval($this->monto_total) - floatval($saldoCliente->disponible);
                        }
                        else {
                            $this->saldo_disponible = floatval($saldoCliente->disponible) -floatval($this->monto_total) ;
                        }
                        
                        
                    }else {
                        //----
                        Yii::error("cliente sin saldo y sin oc en getBalance??".$this->monto_total);
                        $valido =  Creditos::checkBalance($this->monto_total,$this->cliente);
                        Yii::error("valido es??".$valido);
                        if ($valido === false){

                            $saldo = Creditos::getSaldo("CLIENTE",$this->cliente);
                            $this->saldo_disponible = floatval($this->monto_total) - floatval($saldo['disponible']);
                        }
                        else {
                            $valido = true;
                        }
                    }
                } else {
                    //----
                    Yii::error("cliente sin saldo y sin oc en getBalance??".$this->monto_total);
                    $valido =  Creditos::checkBalance($this->monto_total,$this->cliente);
                    Yii::error("valido es??".$valido);
                    if ($valido === false){

                        $saldo = Creditos::getSaldo("CLIENTE",$this->cliente);
                        $this->saldo_disponible = floatval($this->monto_total) - floatval($saldo['disponible']);
                    }
                    else {
                        $valido = true;
                    }
                }   
                
            }
        }
        else {
           // $saldo = Creditos::getSaldo();
           $tieneOC = Yii::$app->user->identity->getUsarocc();
            if ($tieneOC){
                $saldoCliente = Creditos::getSaldoClienteOC($this->monto_total,Yii::$app->user->identity->id);
                /*if (!$saldoCliente->response){
                    $this->saldo_disponible = $this->monto_total - $saldoCliente->disponible;
                }*/
                if ($saldoCliente->disponible == 0){
                    $this->saldo_disponible = floatval($this->monto_total) - floatval($saldoCliente->disponible);
                }
                else {
                    $this->saldo_disponible = floatval($saldoCliente->disponible) -floatval($this->monto_total) ;
                }
                
                
            }else {
                $valido = Creditos::checkBalance($this->monto_total);
                if ($valido === false){
                    $saldo = Creditos::getSaldo("CLIENTE",$this->cliente);
                    $this->saldo_disponible = floatval($this->monto_total) - floatval($saldo['disponible']);
                }
                else{
                    $valido = true;
                }
                
            }
        }
        

       
        
        return $valido;
    }

    //MV-retomar 

    public function buscar_reservados($paquete_id, $medio_id){
        $sql ="";
        $item = 0;
        $devolucion = 0;
        $now = new \DateTime(null, new \DateTimeZone('America/Bogota'));
        $hoy = $now->format("Y-m-d");
        $fecha_1mes = date('Y-m-d', strtotime('+30 days', strtotime($hoy)));
        $result = array();
        $query = MediosRecursosPaquetes::find();
                   /* ->select('fab_bkl_medios_recursos_paquetes.*');*/
        $query->joinWith(['mediosRecursos']);
        $query->joinWith(['paquetes.tipoPropuestas']);
        $query->joinWith(['mediosRecursos.mediosInfluencers']);
        $query->where(['=', 'fab_bkl_medios_influencers_recursos.estado_medio_influencer_id', 1]);
        $query->andWhere(['=', 'fab_bkl_tipo_propuestas.estado', 1]);
        $query->andWhere(['=', 'fab_bkl_medios_influencers.estado', 1]);
        $query->andWhere(['or',[ 'fab_bkl_medios_influencers_recursos.modo_vaciones' => 0],
                         ['and',[ 'fab_bkl_medios_influencers_recursos.modo_vaciones' => 1],
                         ['>','fecha_inicio_vaciones',$hoy]],
                         ]);
        $query->andWhere(['=', 'fab_bkl_medios_recursos_paquetes.paquete_id', $paquete_id]);
        $query->andWhere(['=', 'fab_bkl_medios_recursos_paquetes.medio_influencer_recurso_id', $medio_id]);

        $opciones=Yii::$app->user->identity->getProcessLabelAll(Yii::$app->user->identity->id);
        $compraBkl=strpos($opciones, 'Comprar Backlinks')!==false;
        if ( $compraBkl && (\Yii::$app->user->can('MedioInfluencer') && !\Yii::$app->user->can('Comprador')) ) {
            $tProp= new TipoPropuestas();
            $idWac_POST=$tProp->getIdByName(TipoPropuestas::POST_WAC);  
            \Yii::error('tipo prop en filtro FORM buscar reservados L1050 '.$idWac_POST);     
            $query->andWhere(['!=', 'fab_bkl_tipo_propuestas.id', $idWac_POST]);
         }

        $query = $query->one();
        $disponible = true;
                //-----modo vacaciones
        if ($query){
            
            return $query;
        }
        else return null;
        
        
    }

    public function getActivePackages(){

       //$result = $this->buscar_filtro();
       $result = $this->buscar_reservados();
       //$activos = $result->paquetes_activos;
       $activos = $result->result;
        $paquetes = [];

        foreach ($activos as $activo) {

            foreach ($activo->mediosRecursosPaquetes as $mediosRecursosPaquete) {
                $paquetes[] = $mediosRecursosPaquete;
            }

        }
        $paquetes = array_column($paquetes,NULL,'id');

        return $paquetes;
    }

    public function saveAll(){

            try {
                $transaction = Yii::$app->db->beginTransaction();
                $rand = Yii::$app->security->generateRandomString(5);
                $listadoMediosCorreos = [];
                $cont = 0;

                $paquetes_ids = json_decode($this->paquetes_ids);
                $objetos_seleccionados = json_decode($this->objetos_seleccionados);

                $post =  Yii::$app->request->post();

                $this->limpiarPropuestas();

                Yii::error ("Proyecto nombre ".$this->proyecto_name);

                $proyecto = Proyectos::findOne(
                    [
                        'nombre'=>$this->proyecto_name,
                        'cliente_id' => ($this->cliente)?intval($this->cliente):Yii::$app->user->identity->id,
                        'user_id_log' => ($this->cliente)?Yii::$app->user->identity->id:NULL,
                    ]);

                if(!$proyecto){
                    Yii::error ("cliente ".$this->cliente);
                    $proyecto = new Proyectos([
                        'nombre' => $this->proyecto_name,
                        'cliente_id' => ($this->cliente)?intval($this->cliente):Yii::$app->user->identity->id,
                        'user_id_log' => ($this->cliente)?Yii::$app->user->identity->id:NULL,
                      
                    ]);
                }

                //----- guardar campos adicionales plan optimo bkl
                $proyecto->presupuesto_guardado = $this->presupuesto;
                $proyecto->keywords_guardadas = $this->keywords_cliente;
                //----------------------------------------------
                $fecha_publicacion = date('Y-m-d', strtotime($this->fecha_publicacion));

                $proyecto->fecha_limite_aceptacion = $this->fecha_aceptacion;
                $proyecto->odc_id = ($this->odc!=NULL)?$this->odc:NULL;
                $proyecto->estado_proyecto_id = EstadosProyectos::getStatusByName(EstadosProyectos::ENCURSO);

            

                $flag = $proyecto->save();

                Yii::error("Flag de salvar proyecto, SALVADO: ".print_r($flag,true));

                $this->propuestas_archivos = UploadedFile::getInstances($this, 'propuestas_archivos');
                $this->propuestas_archivos_cliente = UploadedFile::getInstances($this, 'propuestas_archivos_cliente');
                /*agregado para botones nuevos */
                $this->propuestas_archivos_clientes_adjuntos= UploadedFile::getInstances($this, 'propuestas_archivos_clientes_adjuntos');
                $this->propuestas_archivos_medio_sugiere_url= UploadedFile::getInstances($this, 'propuestas_archivos_medio_sugiere_url');
                $this->propuestas_archivos_wac= UploadedFile::getInstances($this, 'propuestas_archivos_wac');

                Yii::error("Cargo los archivos: ".print_r($flag,true));


                $urlsOpcionales = $post['url_opcional'] ?? null;
                $urlsRequeridas = $post['url_requerida'] ?? null;

                foreach ($objetos_seleccionados as $objeto_seleccionado) {
                    $id = $objeto_seleccionado->package_id;

                    $medios_recursos_paquetes = MediosRecursosPaquetes::findOne(['id'=>$id]);
                    if ($this->cliente){
                        $medios_recursos_paquetes->cliente_paquete = $this->cliente;
                    }
                    if(!$medios_recursos_paquetes){
                    $flag = false;
                    Yii::error('no existe el paquete id: ');
                    Yii::error($id);

                    }

                    $scenario = $medios_recursos_paquetes->paquetes->tipoPropuestas->nombre_interno;
                    $tipo_propuesta = TipoPropuestas::findOne(['nombre_interno'=>$scenario]);
                    $recurso = $medios_recursos_paquetes->mediosRecursos;
                    $max_links = $medios_recursos_paquetes->mediosRecursos->mediosRecursos->max_links;

                    /* @var $propuestas_model Propuestas*/
                    $propuestas_model = new Propuestas();
                    $propuestas_model = $this->getFechas($propuestas_model, $scenario);
                    $propuestas_model->rand = $rand;
                    $propuestas_model->scenario = $scenario;
                    $propuestas_model->precio =  doubleval($medios_recursos_paquetes->precioPaquete->precio_cliente);
                    $propuestas_model->fecha_limite_publicacion = $fecha_publicacion;
                    $propuestas_model->proyecto_id = $proyecto->id;
                    $propuestas_model->estado_propuesta_id = EstadosPropuestas::getStatusByName(EstadosPropuestas::ASIGNADA);
                    $propuestas_model->tipo_propuesta_id = $tipo_propuesta->id;
                    $propuestas_model->medio_influencer_recurso_id = $recurso->id;
                    $propuestas_model->paquete_id = $medios_recursos_paquetes->paquete_id;
                    $propuestas_model->duracion_publicacion = $recurso->tiempo_publicacion_post;
                    $propuestas_model->idioma_id = Idiomas::ESPANOL;
                    $propuestas_model->max_keywords = $max_links;

                    $keywordPrincipal = null;
                    $keywordSecundaria = null;


                    switch ($scenario){
                        case TipoPropuestas::POST_MEDIO:
                            $propuestas_model->propuestas_archivos_array = $this->propuestas_archivos;
                            $keywordPrincipal = $this->keyword_principal;
                            $keywordSecundaria = $this->keyword_secundaria;

                            break;

                        case TipoPropuestas::POST_WAC:
                            $propuestas_model->propuestas_archivos_wac_array = $this->propuestas_archivos_wac;

                            $propuestas_model->pedido_texto_id = $this->pedido_id;
                            $keywordPrincipal = $this->keyword_principal;
                            $keywordSecundaria = $this->keyword_secundaria;
                            break;

                        case TipoPropuestas::POST_CLIENTE:
                            $propuestas_model->propuestas_archivos_cliente_array = $this->propuestas_archivos_cliente;
                            $keywordPrincipal = $this->keyword_principal_cliente;
                            $keywordSecundaria = $this->keyword_secundaria_cliente;
                            /*Para nuevo boton */
                            $propuestas_model->propuestas_archivos_clientes_adjuntos_array = $this->propuestas_archivos_clientes_adjuntos;


                            break;

                        case TipoPropuestas::POST_URL:
                            $keywordPrincipal = null;
                            $keywordSecundaria = null;

                            $urls = [];

                            if($urlsOpcionales){
                                foreach ($urlsOpcionales as $item) {

                                    if(strrpos($item,$medios_recursos_paquetes->mediosRecursos->url) === 0){
                                        $urls[]=[$item,EstadosPropuestas::ASIGNADA];
                                    }
                                }
                                $propuestas_model->propuesta_urls = $urls;
                            }

                            break;

                        case TipoPropuestas::POST_CLIENTE_SUGIERE:
                            
                            $keywordPrincipal = null;
                            $keywordSecundaria = null;

                            $urls = [];

                            if($urlsRequeridas){
                                foreach ($urlsRequeridas as $item) {

                                    if(strrpos($item,$medios_recursos_paquetes->mediosRecursos->url) === 0){
                                        $urls[]=[$item,EstadosPropuestas::ASIGNADA];
                                    }
                                }
                                $propuestas_model->propuesta_urls = $urls;
                            }

                            break;

                        case TipoPropuestas::POST_MEDIO_SUGIERE:
                             /*Para nuevo boton */
                             $propuestas_model->propuestas_archivos_medio_sugiere_url_array = $this->propuestas_archivos_medio_sugiere_url;

                            $keywordPrincipal = null;
                            $keywordSecundaria = null;

                            break;
                    }

                    $propuestas_model->categorias = $recurso->getCategoriasIdArray();
                    $propuestas_model->objeto_seleccionado = $objeto_seleccionado;
                    $propuestas_model->keywords_propuestas = isset($post['keywords_propuestas']) ? $post['keywords_propuestas'] : null;

                    $valores_propuestas = new ValoresPropuestas([
                        'monto_total'=> doubleval($medios_recursos_paquetes->precioPaquete->precio_cliente),
                        'monto_ganancia_medio' => doubleval($medios_recursos_paquetes->precioPaquete->precio_proveedor),
                        'monto_impuesto'=>doubleval($medios_recursos_paquetes->precioPaquete->monto_impuesto),
                        'monto_ganancia_wac'=>doubleval($medios_recursos_paquetes->precioPaquete->monto_ganancia_wac),
                        'porcentaje_ganancia_medio'=>intval($medios_recursos_paquetes->precioPaquete->porcentaje_ganancia_medio),
                        'porcentaje_ganancia_wac'=>intval($medios_recursos_paquetes->precioPaquete->porc_ganancia_wac),
                        'porcentaje_impuesto'=>intval($medios_recursos_paquetes->precioPaquete->retencion),
                        'porcentaje_comision'=>intval($medios_recursos_paquetes->precioPaquete->comision),
                        'monto_comision'=>doubleval($medios_recursos_paquetes->precioPaquete->monto_comision),
                        'monto_corrector'=>0,
                        'monto_descuento_medio'=>doubleval($medios_recursos_paquetes->precioPaquete->monto_descuento_medio),
                        'porcentaje_descuento_medio' =>intval($medios_recursos_paquetes->descuento),
                    ]);

                    $propuestas_model->archivos_temp = $this->archivosTemp;

                    /*Nuevo boton*/
                    $propuestas_model->archivos_temp_wac = $this->archivosTempWac;
                    $propuestas_model->archivos_temp_cliente = $this->archivosTempCliente;
                    $propuestas_model->archivos_temp_medio = $this->archivosTempMedio;


                    $propuestas_model->propuesta_valor = $valores_propuestas;
                    //----------- ODC
                    if ($this->odc){
                        $propuestas_model->odc = true;
                    }
                    //-------------
                    if($flag && $flag = $propuestas_model->save()){
                        if ($this->odc){
                            
                            $odcDetalle = new Ordenesccconsumobkl();
                            $odcDetalle->idOrdenccd = $this->odc_detalle;
                            $odcDetalle->idOrdencc = $this->odc;
                            $odcDetalle->estadoTrans = "PENDIENTE";
                            $odcDetalle->idPropuesta = $propuestas_model->id;
                            $flag = $odcDetalle->save();
                            Yii::error("guardando odc flag 1 ".$flag);
                            if ($flag){
                                //actualizar la odc
                                $ordenDetalle = new Ordenesccdetalle();
                                $ordenDetalle->idOrdenccd = $this->odc_detalle;
                                $ordenDetalle->monto = $propuestas_model->precio;
                                $flag =  $ordenDetalle->updateConsumoBKL();
                                if (!$flag){
                                    Yii::error("guardando odc flag 22 ".$flag);
                                    Yii::error("guardando odc ".print_r($ordenDetalle->getErrors(),true));
                                }
                               
                                
                            }
                            else {
                                Yii::error("ocurrió un error actualizando odc detalle ".$flag);
                            }
                
                        }
                        $propuestas_medios_model = new PropuestasMedios();
                        $propuestas_medios_model->scenario = $scenario;
                        $propuestas_medios_model->load($this->toArray(),'');
                        $propuestas_medios_model->propuesta_id = $propuestas_model->id;

                        // Se quito el keyword
                        //$propuestas_medios_model->keyword_principal = $keywordPrincipal;
                        //$propuestas_medios_model->keyword_secundaria = isset($keywordSecundaria) && !empty($keywordSecundaria) ? implode(',',$keywordSecundaria): null;
                        $propuestas_medios_model->numero_enlace = intval($this->numero_enlace);
                        $propuestas_medios_model->lenguaje_tecnico = boolval($this->lenguaje_tecnico);


                        switch ($scenario){

                            case TipoPropuestas::POST_WAC:

                                $propuestas_medios_model->error_propiedad_intelectual = intval($this->error_propiedad_intelectual_wac);

                                break;

                            case TipoPropuestas::POST_CLIENTE:
                                $propuestas_medios_model->error_propiedad_intelectual = intval($this->error_propiedad_intelectual_cliente);

                                break;

                        }

                        if($flag){
                            Yii::error("ocurrió un error actualizando odc detalle 2 ".$flag);
                            $flag = $propuestas_medios_model->save();
                        }
                    }
                    $listadoMediosCorreos[] = $propuestas_model;

                    $cont++;

                    if(!$flag){
                        break;
                    }
                    
                    /*if ($this->odc){
                        //guarda lo consumido de la odc
                        $save_odc = Ordenesccconsumobkl::saveConsumo();
                    }*/
                }

                if($this->archivosTemp){
                    $propuestas_archivos_id = null;
                    /* @var $archivo Archivos */
                    foreach ($this->archivosTemp as $archivo) {
                        $propuestas_archivos_id = $archivo->propuestas_archivos_id;
                        $archivo->deleteFile();
                    }
                    PropuestasArchivos::deleteAll(['id'=>$propuestas_archivos_id]);
                }

                //Nuevo Boton
                if($this->archivosTempWac){
                    $propuestas_archivos_id = null;
                    /* @var $archivo Archivos */
                    foreach ($this->archivosTempWac as $archivo) {
                        $propuestas_archivos_id = $archivo->propuestas_archivos_id;
                        $archivo->deleteFile();
                    }
                    PropuestasArchivos::deleteAll(['id'=>$propuestas_archivos_id]);
                }

                if($this->archivosTempCliente){
                    $propuestas_archivos_id = null;
                    /* @var $archivo Archivos */
                    foreach ($this->archivosTempCliente as $archivo) {
                        $propuestas_archivos_id = $archivo->propuestas_archivos_id;
                        $archivo->deleteFile();
                    }
                    PropuestasArchivos::deleteAll(['id'=>$propuestas_archivos_id]);
                }
               
               
                if($this->archivosTempMedio){
                    $propuestas_archivos_id = null;
                    /* @var $archivo Archivos */
                    foreach ($this->archivosTempMedio as $archivo) {
                        $propuestas_archivos_id = $archivo->propuestas_archivos_id;
                        $archivo->deleteFile();
                    }
                    PropuestasArchivos::deleteAll(['id'=>$propuestas_archivos_id]);
                }

                if($flag){
                    $transaction->commit();
                    $this->enviarNotificaciones($listadoMediosCorreos);
                    $listadoAdminCorreos = Valores::getValorByName('emails_admin_backl');
                    $this->enviarNotificacionesAdmin($listadoAdminCorreos,$propuestas_model);
                    if ($proyecto->user_id_log){
                        $this->enviarNotificacionesDesdeAdmin($proyecto->cliente->user->id,$propuestas_model);
                    }
                    return $cont;

                }else{
                    $transaction->rollBack();
                    Yii::error("no guardó algo....");
                    Yii::error("error en propuestad: ".print_r($propuestas_model->getErrors(),true));
                    Yii::error("error en proyecto: ".print_r($proyecto->getErrors(),true));
                    Yii::error("error en valores_propuesta: ".print_r($valores_propuestas->getErrors(),true));
                    Yii::error("error en propuesta_medio: ".print_r($propuestas_medios_model->getErrors(),true));
                    return false;
                }
            }
            catch (\Exception $e){

                Yii::error($e->getMessage());
                $transaction->rollBack();
                return false;
            }

    }

    public function saveDraft(){

        try {
            $transaction = Yii::$app->db->beginTransaction();
            $rand = Yii::$app->security->generateRandomString(5);

            $listadoMediosCorreos = [];
            $cont = 0;

            $paquetes_ids = json_decode($this->paquetes_ids);
            $objetos_seleccionados = json_decode($this->objetos_seleccionados);

            $post =  Yii::$app->request->post();

            $this->limpiarPropuestas();
            $this->getBalance();

            Yii::error("nombre proyecto".print_r($this->proyecto_name,true));
            Yii::error("cliente proyecto".print_r($this->cliente,true));
            Yii::error("user_id_log".print_r($this->cliente,true));


            $proyecto = Proyectos::findOne(
                [
                    'nombre'=>$this->proyecto_name,
                    'cliente_id' => ($this->cliente)?intval($this->cliente):Yii::$app->user->identity->id,
                    'user_id_log' => ($this->cliente)?Yii::$app->user->identity->id:NULL,
                    
                ]);

            Yii::error("PROYECTO: ".print_r($proyecto,true));

            if(!$proyecto){
                $proyecto = new Proyectos([
                    'nombre' => $this->proyecto_name,
                    'cliente_id' => ($this->cliente)?intval($this->cliente):Yii::$app->user->identity->id,
                    'user_id_log' => ($this->cliente)?Yii::$app->user->identity->id:NULL,
                  
                ]);
            }

            $fecha_publicacion = date('Y-m-d', strtotime($this->fecha_publicacion));
            Yii::error("odc es: ".$this->odc);
            $proyecto->odc_id = ($this->odc!=NULL)?$this->odc:NULL;
            
            $proyecto->fecha_limite_aceptacion = $this->fecha_aceptacion;

            $proyecto->estado_proyecto_id = EstadosProyectos::getStatusByName(EstadosProyectos::BORRADOR);

            //------------- guardar campos adicionales plan optimo bkl
            $proyecto->presupuesto_guardado = $this->presupuesto;
            $proyecto->keywords_guardadas = $this->keywords_cliente;
            // ----------------------------------------

            $flag = $proyecto->save();

            $this->propuestas_archivos = UploadedFile::getInstances($this, 'propuestas_archivos');
            Yii::error("adjunto medio".print_r($this->propuestas_archivos,true));
            $this->propuestas_archivos_cliente = UploadedFile::getInstances($this, 'propuestas_archivos_cliente');
            Yii::error("adjunto cliente".print_r($this->propuestas_archivos_cliente,true));
            /*Para nuevo boton */
            $this->propuestas_archivos_clientes_adjuntos = UploadedFile::getInstances($this, 'propuestas_archivos_clientes_adjuntos');
            Yii::error("adjuntos adicionales cliente".print_r($this->propuestas_archivos_clientes_adjuntos,true));
            $this->propuestas_archivos_medio_sugiere_url = UploadedFile::getInstances($this, 'propuestas_archivos_medio_sugiere_url');
            $this->propuestas_archivos_wac = UploadedFile::getInstances($this, 'propuestas_archivos_wac');
            Yii::error("adjuntos adicionales wac".print_r($this->propuestas_archivos_wac,true));

            $urlsOpcionales = $post['url_opcional'] ?? null;
            $urlsRequeridas = $post['url_requerida'] ?? null;
    
            foreach ($objetos_seleccionados as $objeto_seleccionado) {
                $id = $objeto_seleccionado->package_id;
                $medios_recursos_paquetes = MediosRecursosPaquetes::findOne(['id'=>$id]);

                if(!$medios_recursos_paquetes){
                    $flag = false;
                    Yii::error('no existe el paquete id: ');
                    Yii::error($id);

                }

                $scenario = $medios_recursos_paquetes->paquetes->tipoPropuestas->nombre_interno;
                $tipo_propuesta = TipoPropuestas::findOne(['nombre_interno'=>$scenario]);
                $recurso = $medios_recursos_paquetes->mediosRecursos;
                $max_links = $medios_recursos_paquetes->mediosRecursos->mediosRecursos->max_links;

                /* @var $propuestas_model Propuestas*/
                $propuestas_model = new Propuestas();
                $propuestas_model = $this->getFechas($propuestas_model, $scenario);
                $propuestas_model->rand = $rand;

                $propuestas_model->scenario = $scenario;
                if ($this->cliente){
                    $medios_recursos_paquetes->cliente_paquete = intval($this->cliente);  
                }
                $propuestas_model->precio =  doubleval($medios_recursos_paquetes->precioPaquete->precio_cliente);
                $propuestas_model->fecha_limite_publicacion = $fecha_publicacion;
                $propuestas_model->proyecto_id = $proyecto->id;
                $propuestas_model->estado_propuesta_id = EstadosPropuestas::getStatusByName(EstadosPropuestas::BORRADOR);
                $propuestas_model->tipo_propuesta_id = $tipo_propuesta->id;
                $propuestas_model->medio_influencer_recurso_id = $recurso->id;
                $propuestas_model->paquete_id = $medios_recursos_paquetes->paquete_id;
                $propuestas_model->duracion_publicacion = $recurso->tiempo_publicacion_post;
                $propuestas_model->idioma_id = Idiomas::ESPANOL;
                $propuestas_model->max_keywords = $max_links;

                $keywordPrincipal = null;
                $keywordSecundaria = null;


                switch ($scenario){
                    case TipoPropuestas::POST_MEDIO:
                        $propuestas_model->propuestas_archivos_array = $this->propuestas_archivos;
                        $keywordPrincipal = $this->keyword_principal;
                        $keywordSecundaria = $this->keyword_secundaria;

                        break;

                    case TipoPropuestas::POST_WAC:

                        $propuestas_model->propuestas_archivos_wac_array = $this->propuestas_archivos_wac;
                        //Yii::error("adjunto adicionales wac ".print_r($this->propuestas_archivos_wac,true));
                        $propuestas_model->pedido_texto_id = $this->pedido_id;

                        $keywordPrincipal = $this->keyword_principal;
                        $keywordSecundaria = $this->keyword_secundaria;
                        break;

                    case TipoPropuestas::POST_CLIENTE:
                       // Yii::error("cliente adjunto ".print_r($this->propuestas_archivos_cliente,true));
                        $propuestas_model->propuestas_archivos_cliente_array = $this->propuestas_archivos_cliente;
                        //Yii::error("cliente adjunto array ".print_r($propuestas_model->propuestas_archivos_cliente_array,true));

                        $keywordPrincipal = $this->keyword_principal_cliente;
                        $keywordSecundaria = $this->keyword_secundaria_cliente;

                         /*Para Nuevo Boton */
                         //Yii::error("cliente adjunto adicionales ".print_r($this->propuestas_archivos_clientes_adjuntos,true));
                         $propuestas_model->propuestas_archivos_clientes_adjuntos_array = $this->propuestas_archivos_clientes_adjuntos;
                         //Yii::error("cliente adjunto adicionales array ".print_r($propuestas_model->propuestas_archivos_clientes_adjuntos_array,true));

                        break;

                    case TipoPropuestas::POST_URL:
                        $keywordPrincipal = null;
                        $keywordSecundaria = null;

                        $urls = [];

                        if($urlsOpcionales){
                          
                            foreach ($urlsOpcionales as $item) {

                                if(strrpos($item,$medios_recursos_paquetes->mediosRecursos->url) === 0){
                                    $urls[]=[$item,EstadosPropuestas::BORRADOR];
                                }
                            }
                            $propuestas_model->propuesta_urls = $urls;
                        }

                        break;

                    case TipoPropuestas::POST_CLIENTE_SUGIERE:
                       
                        $urls = [];

                        if($urlsRequeridas){
                            Yii::Warning('Entro a urls reqeuridas ');
                            Yii::Warning('urlRequerida: ', var_export($urlsRequeridas,true));
                            foreach ($urlsRequeridas as $item) {

                                if(strrpos($item,$medios_recursos_paquetes->mediosRecursos->url) === 0){
                                    Yii::Warning('Entro a añadir url ');
                                    $urls[]=[$item,EstadosPropuestas::BORRADOR];
                                }
                            }

                            $propuestas_model->propuesta_urls = $urls;
                        }

                        break;

                    case TipoPropuestas::POST_MEDIO_SUGIERE:
                        /*Para Nuevo Boton */
                        $propuestas_model->propuestas_archivos_medio_sugiere_url_array = $this->propuestas_archivos_medio_sugiere_url;

                        $keywordPrincipal = null;
                        $keywordSecundaria = null;

                        break;

                }

                $propuestas_model->categorias = $recurso->getCategoriasIdArray();
                $propuestas_model->objeto_seleccionado = $objeto_seleccionado;
                $propuestas_model->keywords_propuestas = isset($post['keywords_propuestas']) ? $post['keywords_propuestas'] : null;
                if ($this->cliente){
                    $medios_recursos_paquetes->cliente_paquete =$this->cliente; 
                }
                $valores_propuestas = new ValoresPropuestas([
                    'monto_total'=> doubleval($medios_recursos_paquetes->precioPaquete->precio_cliente),
                    'monto_ganancia_medio' => doubleval($medios_recursos_paquetes->precioPaquete->precio_proveedor),
                    'monto_impuesto'=>doubleval($medios_recursos_paquetes->precioPaquete->monto_impuesto),
                    'monto_ganancia_wac'=>doubleval($medios_recursos_paquetes->precioPaquete->monto_ganancia_wac),
                    'porcentaje_ganancia_medio'=>intval($medios_recursos_paquetes->precioPaquete->porcentaje_ganancia_medio),
                    'porcentaje_ganancia_wac'=>intval($medios_recursos_paquetes->precioPaquete->porc_ganancia_wac),
                    'porcentaje_impuesto'=>intval($medios_recursos_paquetes->precioPaquete->retencion),
                    'porcentaje_comision'=>intval($medios_recursos_paquetes->precioPaquete->comision),
                    'monto_comision'=>doubleval($medios_recursos_paquetes->precioPaquete->monto_comision),
                    'monto_corrector'=>0,
                    'monto_descuento_medio'=>doubleval($medios_recursos_paquetes->precioPaquete->monto_descuento_medio),
                    'porcentaje_descuento_medio' =>intval($medios_recursos_paquetes->descuento),
                ]);

                $propuestas_model->archivos_temp = $this->archivosTemp;

                //Nuevo Boton
                $propuestas_model->archivos_temp_wac = $this->archivosTempWac;
                $propuestas_model->archivos_temp_cliente = $this->archivosTempCliente;
                $propuestas_model->archivos_temp_medio = $this->archivosTempMedio;

                $propuestas_model->propuesta_valor = $valores_propuestas;

                if($flag && $flag = $propuestas_model->save(false)){

                    $propuestas_medios_model = new PropuestasMedios();
                    $propuestas_medios_model->scenario = $scenario;
                    $propuestas_medios_model->load($this->toArray(),'');
                    $propuestas_medios_model->propuesta_id = $propuestas_model->id;
                    // Se quito el keyword
                    //$propuestas_medios_model->keyword_secundaria = isset($keywordSecundaria) && !empty($keywordSecundaria) ? implode(',',$keywordSecundaria): null;
                    //$propuestas_medios_model->keyword_principal = $keywordPrincipal;
                    $propuestas_medios_model->numero_enlace = intval($this->numero_enlace);
                    $propuestas_medios_model->lenguaje_tecnico = boolval($this->lenguaje_tecnico);

                    switch ($scenario){

                        case TipoPropuestas::POST_WAC:

                            $propuestas_medios_model->error_propiedad_intelectual = intval($this->error_propiedad_intelectual_wac);

                            break;

                        case TipoPropuestas::POST_CLIENTE:
                            $propuestas_medios_model->error_propiedad_intelectual = intval($this->error_propiedad_intelectual_cliente);

                            break;

                    }

                    if($flag){

                        $flag = $propuestas_medios_model->save(false);
                    }
                }
                $listadoMediosCorreos[] = $propuestas_model;
                $cont++;

                if(!$flag){
                    break;
                }
            }

            if($this->archivosTemp){
                $propuestas_archivos_id = null;
                /* @var $archivo Archivos */
                foreach ($this->archivosTemp as $archivo) {
                    $propuestas_archivos_id = $archivo->propuestas_archivos_id;
                    $archivo->deleteFile();
                }
                PropuestasArchivos::deleteAll(['id'=>$propuestas_archivos_id]);
            }

            //Nuevo Boton
            if($this->archivosTempWac){
                $propuestas_archivos_id = null;
                /* @var $archivo Archivos */
                foreach ($this->archivosTempWac as $archivo) {
                    $propuestas_archivos_id = $archivo->propuestas_archivos_id;
                    $archivo->deleteFile();
                }
                PropuestasArchivos::deleteAll(['id'=>$propuestas_archivos_id]);
            }

            if($this->archivosTempCliente){
                $propuestas_archivos_id = null;
                /* @var $archivo Archivos */
                foreach ($this->archivosTempCliente as $archivo) {
                    $propuestas_archivos_id = $archivo->propuestas_archivos_id;
                    $archivo->deleteFile();
                }
                PropuestasArchivos::deleteAll(['id'=>$propuestas_archivos_id]);
            }
           
           
            if($this->archivosTempMedio){
                $propuestas_archivos_id = null;
                /* @var $archivo Archivos */
                foreach ($this->archivosTempMedio as $archivo) {
                    $propuestas_archivos_id = $archivo->propuestas_archivos_id;
                    $archivo->deleteFile();
                }
                PropuestasArchivos::deleteAll(['id'=>$propuestas_archivos_id]);
            }

            if($flag){
                $transaction->commit();
                //$this->enviarNotificaciones($listadoMediosCorreos);
                return $cont;

            }else{
                $transaction->rollBack();
                Yii::error("error guardando borrador: en modelo");
                Yii::error(json_encode($propuestas_model->getErrors()));
                Yii::error(json_encode($proyecto->getErrors()));
                Yii::error(json_encode($valores_propuestas->getErrors()));
                Yii::error(json_encode($propuestas_medios_model->getErrors()));
                return false;
            }
        }
        catch (\Exception $e){
            Yii::error(json_encode($e));
            Yii::error($e->getMessage());
            $transaction->rollBack();
            return false;

        }

    }

    public function saveReasignado($id){

        try {
            $transaction = Yii::$app->db->beginTransaction();
            $rand = Yii::$app->security->generateRandomString(5);

            $listadoMediosCorreos = [];
            $cont = 0;

            $objetos_seleccionados = json_decode($this->objetos_seleccionados);

            $post =  Yii::$app->request->post();

            $this->limpiarPropuesta($id);

            $proyecto = Proyectos::findOne(
                [
                    'nombre'=>$this->proyecto_name,
                    'cliente_id' => Yii::$app->user->identity->id,
                ]);

            if(!$proyecto){
                Yii::error('no existe el proyecto nombre: ');
                Yii::error($this->proyecto_name);
               return false;
            }

            $fecha_publicacion = date('Y-m-d', strtotime($this->fecha_publicacion));

            $proyecto->fecha_limite_aceptacion = $this->fecha_aceptacion;

            $proyecto->estado_proyecto_id = EstadosProyectos::getStatusByName(EstadosProyectos::ENCURSO);

            $flag = $proyecto->save();

            $this->propuestas_archivos = UploadedFile::getInstances($this, 'propuestas_archivos');
            $this->propuestas_archivos_cliente = UploadedFile::getInstances($this, 'propuestas_archivos_cliente');
            /*Para nuevo Boton*/
            $this->propuestas_archivos_clientes_adjuntos = UploadedFile::getInstances($this, 'propuestas_archivos_clientes_adjuntos');
            $this->propuestas_archivos_medio_sugiere_url = UploadedFile::getInstances($this, 'propuestas_archivos_medio_sugiere_url');
            $this->propuestas_archivos_wac = UploadedFile::getInstances($this, 'propuestas_archivos_wac');


            $urlsOpcionales = isset($post['url_opcional']) ? $post['url_opcional'] : null;
            $urlsRequeridas = isset($post['url_requerida']) ? $post['url_requerida'] : null;

            foreach ($objetos_seleccionados as $objeto_seleccionado) {
                $id = $objeto_seleccionado->package_id;
                $medios_recursos_paquetes = MediosRecursosPaquetes::findOne(['id'=>$id]);

                if(!$medios_recursos_paquetes){
                    $flag = false;
                    Yii::error('no existe el paquete id: ');
                    Yii::error($id);

                }

                $scenario = $medios_recursos_paquetes->paquetes->tipoPropuestas->nombre_interno;
                $tipo_propuesta = TipoPropuestas::findOne(['nombre_interno'=>$scenario]);
                $recurso = $medios_recursos_paquetes->mediosRecursos;
                $max_links = $medios_recursos_paquetes->mediosRecursos->mediosRecursos->max_links;

                /* @var $propuestas_model Propuestas*/
                $propuestas_model = new Propuestas();
                $propuestas_model = $this->getFechas($propuestas_model, $scenario);
                $propuestas_model->rand = $rand;

                $propuestas_model->scenario = $scenario;
                $propuestas_model->precio =  doubleval($medios_recursos_paquetes->precioPaquete->precio_cliente);
                $propuestas_model->fecha_limite_publicacion = $fecha_publicacion;
                $propuestas_model->proyecto_id = $proyecto->id;
                $propuestas_model->estado_propuesta_id = EstadosPropuestas::getStatusByName(EstadosPropuestas::ASIGNADA);
                $propuestas_model->tipo_propuesta_id = $tipo_propuesta->id;
                $propuestas_model->medio_influencer_recurso_id = $recurso->id;
                $propuestas_model->paquete_id = $medios_recursos_paquetes->paquete_id;
                $propuestas_model->duracion_publicacion = $recurso->tiempo_publicacion_post;
                $propuestas_model->idioma_id = Idiomas::ESPANOL;
                $propuestas_model->max_keywords = $max_links;


                switch ($scenario){
                    case TipoPropuestas::POST_MEDIO:
                        $propuestas_model->propuestas_archivos_array = $this->propuestas_archivos;
                        break;

                    case TipoPropuestas::POST_WAC:
                        /*para nuevo boton*/
                        $propuestas_model->propuestas_wac_array = $this->propuestas_archivos_wac;
                        $propuestas_model->pedido_texto_id = $this->pedido_id;
                        break;

                    case TipoPropuestas::POST_CLIENTE:
                        $propuestas_model->propuestas_archivos_cliente_array = $this->propuestas_archivos_cliente;
                         /*Para nuevo boton */
                         $propuestas_model->propuestas_archivos_clientes_adjuntos_array = $this->propuestas_archivos_clientes_adjuntos;

                        break;

                    case TipoPropuestas::POST_URL:

                        $urls = [];

                        if($urlsOpcionales){
                            foreach ($urlsOpcionales as $item) {

                                if(strrpos($item,$medios_recursos_paquetes->mediosRecursos->url) === 0){
                                    $urls[]=[$item,EstadosPropuestas::ASIGNADA];
                                }
                            }
                            $propuestas_model->propuesta_urls = $urls;
                        }

                        break;

                    case TipoPropuestas::POST_CLIENTE_SUGIERE:
                       
                        $urls = [];

                        if($urlsRequeridas){
                            foreach ($urlsRequeridas as $item) {

                                if(strrpos($item,$medios_recursos_paquetes->mediosRecursos->url) === 0){
                                    $urls[]=[$item,EstadosPropuestas::ASIGNADA];
                                }
                            }
                            $propuestas_model->propuesta_urls = $urls;
                        }

                        break;
                        /*Mily no estba la opcion */
                    case TipoPropuestas::POST_MEDIO_SUGIERE:
                            /*Para Nuevo Boton */
                            $propuestas_model->propuestas_archivos_medio_sugiere_url_array = $this->propuestas_archivos_medio_sugiere_url;   
    
                            break;
    
                }

                $propuestas_model->categorias = $recurso->getCategoriasIdArray();
                $propuestas_model->objeto_seleccionado = $objeto_seleccionado;
                $propuestas_model->keywords_propuestas = isset($post['keywords_propuestas']) ? $post['keywords_propuestas'] : null;

                $valores_propuestas = new ValoresPropuestas([
                    'monto_total'=> doubleval($medios_recursos_paquetes->precioPaquete->precio_cliente),
                    'monto_ganancia_medio' => doubleval($medios_recursos_paquetes->precioPaquete->precio_proveedor),
                    'monto_impuesto'=>doubleval($medios_recursos_paquetes->precioPaquete->monto_impuesto),
                    'monto_ganancia_wac'=>doubleval($medios_recursos_paquetes->precioPaquete->monto_ganancia_wac),
                    'porcentaje_ganancia_medio'=>intval($medios_recursos_paquetes->precioPaquete->porcentaje_ganancia_medio),
                    'porcentaje_ganancia_wac'=>intval($medios_recursos_paquetes->precioPaquete->porc_ganancia_wac),
                    'porcentaje_impuesto'=>intval($medios_recursos_paquetes->precioPaquete->retencion),
                    'porcentaje_comision'=>intval($medios_recursos_paquetes->precioPaquete->comision),
                    'monto_comision'=>doubleval($medios_recursos_paquetes->precioPaquete->monto_comision),
                    'monto_corrector'=>0,
                    'monto_descuento_medio'=>doubleval($medios_recursos_paquetes->precioPaquete->monto_descuento_medio),
                    'porcentaje_descuento_medio' =>intval($medios_recursos_paquetes->descuento),
                ]);

                $propuestas_model->archivos_temp = $this->archivosTemp;

                 //Nuevo Boton
                 $propuestas_model->archivos_temp_wac = $this->archivosTempWac;
                 $propuestas_model->archivos_temp_cliente = $this->archivosTempCliente;
                 $propuestas_model->archivos_temp_medio = $this->archivosTempMedio;

                $propuestas_model->propuesta_valor = $valores_propuestas;

                if($flag && $flag = $propuestas_model->save(false)){

                    $propuestas_medios_model = new PropuestasMedios();
                    $propuestas_medios_model->scenario = $scenario;
                    $propuestas_medios_model->load($this->toArray(),'');
                    $propuestas_medios_model->propuesta_id = $propuestas_model->id;
                    $propuestas_medios_model->numero_enlace = intval($this->numero_enlace);
                    $propuestas_medios_model->lenguaje_tecnico = boolval($this->lenguaje_tecnico);

                    switch ($scenario){

                        case TipoPropuestas::POST_WAC:

                            $propuestas_medios_model->error_propiedad_intelectual = intval($this->error_propiedad_intelectual_wac);

                            break;

                        case TipoPropuestas::POST_CLIENTE:
                            $propuestas_medios_model->error_propiedad_intelectual = intval($this->error_propiedad_intelectual_cliente);

                            break;

                    }

                    if($flag){

                        $flag = $propuestas_medios_model->save(false);
                    }
                }

                $audit = new PropuestasAudit([
                    'propuesta_id' => $id,
                    'estado_old' => 'reasignada',
                    'estado_new' => $propuestas_model->estadoPropuesta->nombre_interno,
                    'datos' => json_encode($propuestas_model->toArray()),
                    'fechas' =>json_encode($propuestas_model->toArray(['fecha_registro','fecha_publicacion','fecha_modificacion','fecha_limite_entrega','fecha_limite_aceptacion'])),
                    'usuario_evento' => 'wactest01user@%',

                ]);

                if($flag){

                    $flag = $audit->save(false);
                }

                $listadoMediosCorreos[] = $propuestas_model;
                $cont++;

                if(!$flag){
                    break;
                }
            }

            if($this->archivosTemp){
                $propuestas_archivos_id = null;
                /* @var $archivo Archivos */
                foreach ($this->archivosTemp as $archivo) {
                    $propuestas_archivos_id = $archivo->propuestas_archivos_id;
                    $archivo->deleteFile();
                }
                PropuestasArchivos::deleteAll(['id'=>$propuestas_archivos_id]);
            }

             //Nuevo Boton
             if($this->archivosTempWac){
                $propuestas_archivos_id = null;
                /* @var $archivo Archivos */
                foreach ($this->archivosTempWac as $archivo) {
                    $propuestas_archivos_id = $archivo->propuestas_archivos_id;
                    $archivo->deleteFile();
                }
                PropuestasArchivos::deleteAll(['id'=>$propuestas_archivos_id]);
            }

            if($this->archivosTempCliente){
                $propuestas_archivos_id = null;
                /* @var $archivo Archivos */
                foreach ($this->archivosTempCliente as $archivo) {
                    $propuestas_archivos_id = $archivo->propuestas_archivos_id;
                    $archivo->deleteFile();
                }
                PropuestasArchivos::deleteAll(['id'=>$propuestas_archivos_id]);
            }
           
           
            if($this->archivosTempMedio){
                $propuestas_archivos_id = null;
                /* @var $archivo Archivos */
                foreach ($this->archivosTempMedio as $archivo) {
                    $propuestas_archivos_id = $archivo->propuestas_archivos_id;
                    $archivo->deleteFile();
                }
                PropuestasArchivos::deleteAll(['id'=>$propuestas_archivos_id]);
            }



            if($flag){
                $transaction->commit();
                $this->enviarNotificaciones($listadoMediosCorreos);
                return $cont;

            }else{
                $transaction->rollBack();
                Yii::error(json_encode($propuestas_model->getErrors()));
                Yii::error(json_encode($proyecto->getErrors()));
                Yii::error(json_encode($valores_propuestas->getErrors()));
                Yii::error(json_encode($propuestas_medios_model->getErrors()));
                return false;
            }
        }
        catch (\Exception $e){
            Yii::error(json_encode($e));
            Yii::error($e->getMessage());
            $transaction->rollBack();
            return false;

        }
    }

    public function setDataToOnePropuesta($id)
    {
        $this->archivosData = [];
        $tipos_paquetes_id = [];
        /*para nuevo boton*/
        $this->archivosDataClientesAdjuntos = [];
        $this->archivosDataMedioSugiereUrl = [];

        $recursos_ids = [];
        $montoTotal = 0;
        $tipoEnlace = true;
        $urls = [];
        $objetos_seleccionados = [];

        $propuesta = Propuestas::findOne($id);

        Yii::error("propuesta Mily: ".print_r($propuesta,true));

           $modelPaquete = null;

            if($propuesta){

                    $modelPaquete = MediosRecursosPaquetes::findOne([
                        'paquete_id'=>$propuesta->paquete_id,
                        'medio_influencer_recurso_id'=>$propuesta->medio_influencer_recurso_id,
                    ]);

                    $propuestasMedios = PropuestasMedios::find()->where(['propuesta_id'=>$propuesta->id])->one();

                    $enlace_no_follow = $modelPaquete->mediosRecursos->mediosRecursos->links_no_follow;
                    if($enlace_no_follow && $tipoEnlace) $tipoEnlace = true;
                    else $tipoEnlace = false;
                    $tipos_paquetes_id[] = $propuesta->tipo_propuesta_id;
                    if($propuesta->tipoPropuesta->nombre_interno == TipoPropuestas::POST_MEDIO){
                        [$this->archivosData, $this->archivosDataConfig] = $propuesta->archivosBriefLinks;
                        $this->propuestas_archivos = $this->archivosData;
                        
                        $this->keyword_secundaria = $propuestasMedios->keyword_secundaria;
                        $this->lenguaje_tecnico = $propuestasMedios->lenguaje_tecnico;
                        $this->descripcion = $propuestasMedios->descripcion;
                        $this->enfoque_texto = $propuestasMedios->enfoque_texto;
                        $this->temas_incluir = $propuestasMedios->temas_incluir;
                        $this->temas_eliminar = $propuestasMedios->temas_eliminar;
                        $this->keyword_principal = $propuestasMedios->keyword_principal;
                        $this->ejemplo = $propuestasMedios->ejemplo;
                    }

                    if($propuesta->tipoPropuesta->nombre_interno == TipoPropuestas::POST_WAC){
                        /*para nuevo boton */
                        [$this->archivosDataWac, $this->archivosDataConfigWac] = $propuesta->archivosBriefLinksAdicionales;
                        /*para nuevo boton */
                        $this->propuestas_archivos_wac = $this->archivosDataWac;

                        $propuestaPedido = PropuestasPedidos::find()->where(['propuesta_id'=>$propuesta->id])->one();
                        if($propuestaPedido){
                            if($this->validarVigenciaPedido($propuestaPedido->pedido_id, $this->cliente)) {
                                $this->pedido_id = $propuestaPedido->pedido_id;
                                $this->pedido_texto = $propuestaPedido->pedido->tituloDelPedido;
                                $this->error_propiedad_intelectual_wac = $propuestasMedios->error_propiedad_intelectual;
                            }
                        }

                    }



                    if($propuesta->tipoPropuesta->nombre_interno == TipoPropuestas::POST_CLIENTE){
                        [$this->archivosDataCliente, $this->archivosDataConfigCliente] = $propuesta->archivosBriefLinks;
                        
                        $this->propuestas_archivos_cliente = $this->archivosDataCliente ? $this->archivosDataCliente[0] : null;
                        $this->error_propiedad_intelectual_cliente = $propuestasMedios->error_propiedad_intelectual;

                        Yii::error("archivosDataCliente: ".print_r($this->archivosDataCliente,true));

                         /*para nuevo boton */
                         [$this->archivosDataClientesAdjuntos, $this->archivosDataConfigClientesAdjuntos] = $propuesta->archivosBriefLinksAdicionales;
                         /*para nuevo boton */
                          $this->propuestas_archivos_clientes_adjuntos = $this->archivosDataClientesAdjuntos;

                          Yii::error("archivosDataClientesAdjuntos: ".print_r($this->archivosDataClientesAdjuntos,true));

                    }

                if($propuesta->tipoPropuesta->nombre_interno == TipoPropuestas::POST_URL){

                    $propuestasUrl = PropuestaUrl::find()->where(['propuesta_id'=>$propuesta->id])->all();

                    foreach ($propuestasUrl as $propuestaUrl) {
                        $urls[] = $propuestaUrl->url;
                    }

                }

                if($propuesta->tipoPropuesta->nombre_interno == TipoPropuestas::POST_CLIENTE_SUGIERE){
                                      
                    

                                           
                    $propuestasUrl = PropuestaUrl::find()->where(['propuesta_id'=>$propuesta->id])->all();

                    foreach ($propuestasUrl as $propuestaUrl) {
                        $urls[] = $propuestaUrl->url;
                    }

                }

               

                        $this->titulo = $propuestasMedios->titulo;
                        $this->comentario_cliente = $propuestasMedios->comentario_cliente;
                        $this->numero_enlace = $propuestasMedios->numero_enlace;

                        $this->fecha_aceptacion = date('d-m-Y', strtotime($propuesta->fecha_limite_aceptacion));
                        $this->fecha_entrega = date('d-m-Y', strtotime($propuesta->fecha_limite_entrega));
                        $this->fecha_aprobacion = date('d-m-Y', strtotime($propuesta->fecha_limite_aprobacion));
                        $this->fecha_ajustes = date('d-m-Y', strtotime($propuesta->fecha_limite_ajustes));
                        $this->fecha_publicacion = date('d-m-Y', strtotime($propuesta->fecha_limite_publicacion));

                        $this->proyecto_id = $this->proyectoModel->id;
                        $this->keywordsPropuestasData = PropuestasKeywordEnlaces::find()->asArray()->where(['propuesta_id'=>$propuesta->id])->orderBy(['id'=>SORT_ASC])->all();

                $propuestaKeywordEnlace = PropuestasKeywordEnlaces::find()->where(['propuesta_id'=>$propuesta->id])->one();

                $objetos_seleccionados[] = [
                    'id'=>$propuesta->medio_influencer_recurso_id,
                    'package_id' => $modelPaquete->id,
                    'price' => $modelPaquete->precioPaquete->precio_cliente,
                    'package' => $propuesta->tipoPropuesta->nombre_interno,
                    'link_type' => $propuestaKeywordEnlace->tipo_enlace === 'Follow' ? 'Acepto enlaces Follow' : 'Acepto enlaces No-follow',


                ];
                $this->reasignado = $propuesta->medio_influencer_recurso_id;

            }else{
                return new \Exception('Error no existe la propuesta '.$id);
            }



        $max_numero_enlaces = MediosRecursos::find()->where(['in', 'medio_influencer_recurso_id', [$this->reasignado]])->max('max_links');

        foreach (range(1, (int)$max_numero_enlaces) as $numero)
        {
            $this->numeroEnlaceData[$numero] = $numero;
        }



        $this->keywordsPropuestasData = json_encode($this->keywordsPropuestasData);
        $this->monto_total = $montoTotal;
        $this->tipos_paquetes_id = json_encode($tipos_paquetes_id);
        $this->urlsData = json_encode($urls);
        $this->objetos_seleccionados = json_encode($objetos_seleccionados);

    }

    public function setAllData()
    {
        $this->datos = [];
        $this->paquetes_ids = [];
        $this->archivosData = [];
        /*para nuevo boton */
        $this->archivosDataWac = [];
        $this->archivosDataClientesAdjuntos= [];
        $this->archivosDataMedioSugiereUrl = [];
        
        $init = null;
        $recursos_ids = [];
        $errores = [];
        $montoTotal = 0;
        $tipoEnlace = true;
        $id_mayor_numero_enlaces = null;
        $cantidad_mayor = null;
        $urls = [];
        $recurso = array();
        $objetos_seleccionados = [];
        $tipos_paquetes_id = [];

        foreach ($this->proyectoModel->propuestas as $propuesta) {
           $id = $propuesta->id;
           $modelPaquete = null;
            if($id){

                //Si existe el paquete
                $id_activos = $this->buscar_reservados($propuesta->paquete_id, $propuesta->medio_influencer_recurso_id);
                if (isset($id_activos->id)){
                //if(isset($paquetesActivos[$id])){
                    $modelPaquete = MediosRecursosPaquetes::findOne([
                        'paquete_id'=>$id_activos->paquete_id,
                        'medio_influencer_recurso_id'=>$propuesta->medio_influencer_recurso_id,
                    ]);
                    Yii::error("Antes propuestas Medios ".print_r($propuesta->id,true));
                    $propuestasMedios = PropuestasMedios::find()->where(['propuesta_id'=>$propuesta->id])->one();
                    Yii::error("Despues propuestas Medios ".print_r($propuesta->id,true));

                    $tipos_paquetes_id[] = $propuesta->tipo_propuesta_id;
                    $this->paquetes_ids[] = $modelPaquete->id;
                    $this->datos[] = $propuesta->medio_influencer_recurso_id;
                    $montoTotal+= $propuesta->precio;

                    if($propuesta->tipoPropuesta->nombre_interno == TipoPropuestas::POST_MEDIO){
                        [$this->archivosData, $this->archivosDataConfig] = $propuesta->archivosBriefLinks;
                        $this->propuestas_archivos = $this->archivosData;
                        $this->keyword_secundaria = $propuestasMedios->keyword_secundaria;

                        $this->lenguaje_tecnico = $propuestasMedios->lenguaje_tecnico;
                        $this->descripcion = $propuestasMedios->descripcion;
                        $this->enfoque_texto = $propuestasMedios->enfoque_texto;
                        $this->temas_incluir = $propuestasMedios->temas_incluir;
                        $this->temas_eliminar = $propuestasMedios->temas_eliminar;
                        $this->keyword_principal = $propuestasMedios->keyword_principal;
                        $this->ejemplo = $propuestasMedios->ejemplo;
                    }

                    if($propuesta->tipoPropuesta->nombre_interno == TipoPropuestas::POST_WAC){
                        /*para nuevo boton */
                        Yii::error("Traer archivos adicionales wac ".print_r($propuesta->archivosBriefLinksAdicionales,true));
                        [$this->archivosDataWac, $this->archivosDataConfigWac] = $propuesta->archivosBriefLinksAdicionales;
                        /*para nuevo boton */
                        $this->propuestas_archivos_wac = $this->archivosDataWac;

                        $propuestaPedido = PropuestasPedidos::find()->where(['propuesta_id'=>$propuesta->id])->one();
                        if($propuestaPedido){
                            if($this->validarVigenciaPedido($propuestaPedido->pedido_id, $this->cliente)) {
                               $this->pedido_id = $propuestaPedido->pedido_id;
                               $this->pedido_texto = $propuestaPedido->pedido->tituloDelPedido;
                               $this->error_propiedad_intelectual_wac = $propuestasMedios->error_propiedad_intelectual;
                           }
                        }

                    }

                    if($propuesta->tipoPropuesta->nombre_interno == TipoPropuestas::POST_CLIENTE){
                        [$this->archivosDataCliente, $this->archivosDataConfigCliente] = $propuesta->archivosBriefLinks;
                        $this->propuestas_archivos_cliente = $this->archivosDataCliente;
                        $this->error_propiedad_intelectual_cliente = $propuestasMedios->error_propiedad_intelectual;

                        /*para nuevo boton */
                        Yii::error("Traer archivos adicionales del cliente ".print_r($propuesta->archivosBriefLinksAdicionales,true));
                        [$this->archivosDataClientesAdjuntos, $this->archivosDataConfigClientesAdjuntos] = $propuesta->archivosBriefLinksAdicionales;
                        /*para nuevo boton */
                         $this->propuestas_archivos_clientes_adjuntos = $this->archivosDataClientesAdjuntos;

                    }

                    if($propuesta->tipoPropuesta->nombre_interno == TipoPropuestas::POST_URL){

                        $propuestasUrl = PropuestaUrl::find()->where(['propuesta_id'=>$propuesta->id])->all();

                        foreach ($propuestasUrl as $propuestaUrl) {
                            $urls[$propuesta->tipoPropuesta->nombre_interno][] = $propuestaUrl->url;
                        }

                    }

                    if($propuesta->tipoPropuesta->nombre_interno == TipoPropuestas::POST_CLIENTE_SUGIERE){
                       
                        Yii::error("cargando las url...".$propuesta->id);
                        $propuestasUrl = PropuestaUrl::find()->where(['propuesta_id'=>$propuesta->id])->all();
                        Yii::error("cargando las url 2 ...".print_r($propuestasUrl,true));
                        foreach ($propuestasUrl as $propuestaUrl) {
                            $urls[$propuesta->tipoPropuesta->nombre_interno][] = $propuestaUrl->url;
                        }

                    }

                    /*Mily no estba la opcion */
                    if($propuesta->tipoPropuesta->nombre_interno == TipoPropuestas::POST_MEDIO_SUGIERE){
                        /*para nuevo boton */
                        [$this->archivosDataMedioSugiereUrl, $this->archivosDataConfigMedioSugiereUrl] = $propuesta->archivosBriefLinks;
                        /*para nuevo boton */
                        $this->propuestas_archivos_medio_sugiere_url = $this->archivosDataMedioSugiereUrl;
                     }

                    if(!$init){

                        $this->titulo = $propuestasMedios->titulo;
                        $this->fecha_aceptacion = date('d-m-Y', strtotime($propuesta->fecha_limite_aceptacion));
                        $this->fecha_entrega = date('d-m-Y', strtotime($propuesta->fecha_limite_entrega));
                        $this->fecha_aprobacion = date('d-m-Y', strtotime($propuesta->fecha_limite_aprobacion));
                        $this->fecha_ajustes = date('d-m-Y', strtotime($propuesta->fecha_limite_ajustes));
                        $this->fecha_publicacion = date('d-m-Y', strtotime($propuesta->fecha_limite_publicacion));

                        $this->proyecto_id = $this->proyectoModel->id;
                        $this->comentario_cliente = $propuestasMedios->comentario_cliente;

                        $init = true;
                    }

                    if(!$id_mayor_numero_enlaces){

                        $cantidad_mayor = PropuestasKeywordEnlaces::find()->asArray()->where(['propuesta_id'=>$propuesta->id])->count();
                        $id_mayor_numero_enlaces = $propuesta->id;
                    }else{
                        $cant = PropuestasKeywordEnlaces::find()->asArray()->where(['propuesta_id'=>$propuesta->id])->count();

                        if($cantidad_mayor < $cant){
                            $cantidad_mayor = $cant;
                            $id_mayor_numero_enlaces = $propuesta->id;

                        }
                    }

                    $propuestaKeywordEnlace = PropuestasKeywordEnlaces::find()->where(['propuesta_id'=>$propuesta->id])->one();
                    $link_type = $propuestaKeywordEnlace->tipo_enlace === 'Follow' ? 'Acepto enlaces Follow' : 'Acepto enlaces No-follow';

                    if($propuestaKeywordEnlace->tipo_enlace === 'Follow' and $modelPaquete->mediosRecursos->mediosRecursos->links_follow === 0){

                        if (!in_array($propuesta->medio_influencer_recurso_id, $recursos_ids)) {
                            $errores[] = [
                                'mensaje' => 'El sitio '. $modelPaquete->mediosRecursos->nombre .' que habías seleccionado, cambió el tipo de enlace, ya no acepta enlaces Follow. Puedes continuar con este sitio web o seleccionar otro.'
                            ];
                        }

                        $link_type = 'Acepto enlaces No-follow';
                    }

                    if($propuestaKeywordEnlace->tipo_enlace === 'No-follow' and $modelPaquete->mediosRecursos->mediosRecursos->links_no_follow === 0){
                        if (!in_array($propuesta->medio_influencer_recurso_id, $recursos_ids)) {
                            $errores[] = [
                                'mensaje' => 'El sitio ' . $modelPaquete->mediosRecursos->nombre . ' que habías seleccionado, cambió el tipo de enlace, ya no acepta enlaces No-follow. Puedes continuar con este sitio web o seleccionar otro.'
                            ];
                        }
                        $link_type = 'Acepto enlaces Follow';

                    }

                    $recursos_ids[] = $propuesta->medio_influencer_recurso_id;

                    $objetos_seleccionados[] = [
                        'id'=>$propuesta->medio_influencer_recurso_id,
                        'package_id' => $modelPaquete->id,
                        'price' => MediosRecursosPaquetes::calcularPrecioPaquete($modelPaquete,$this->cliente)->precio_cliente,
                        'package' => $propuesta->tipoPropuesta->nombre_interno,
                        'link_type' => $link_type,

                    ];

                }else{
                    $modelPaquete = MediosRecursosPaquetes::findOne([
                            'paquete_id'=>$propuesta->paquete_id,
                            'medio_influencer_recurso_id'=>$propuesta->medio_influencer_recurso_id,
                    ]);
                    $now = new \DateTime(null, new \DateTimeZone('America/Bogota'));
                    $hoy = $now->format("Y-m-d");
                    //if ($hoy>=$modelPaquete->mediosRecursos->fecha_inicio_vaciones and $hoy<=$modelPaquete->mediosRecursos->fecha_fin_vacaciones){
                    if ($modelPaquete){
                        if (!in_array($modelPaquete->mediosRecursos->id, $recurso)) {
                            if (($modelPaquete->mediosRecursos->modo_vaciones==1) and ($hoy>=$modelPaquete->mediosRecursos->fecha_inicio_vaciones and $hoy<=$modelPaquete->mediosRecursos->fecha_fin_vacaciones)){
                                $errores[] = [
                                    'mensaje' => 'El sitio '. $modelPaquete->mediosRecursos->nombre .' que habías seleccionado, no esta disponible por motivo de vacaciones. Te invitamos a seleccionar otro sitio web.'
                                ];
                                array_push($recurso, $modelPaquete->mediosRecursos->id);
                            }else{
                                
                                $errores[] = [
                                    'mensaje' => 'El sitio '. $modelPaquete->mediosRecursos->nombre .' ya no está disponible. Te invitamos a seleccionar otro sitio web.'
                                ];
                                array_push($recurso, $modelPaquete->mediosRecursos->id);
                            }
                        }
                        
                    }
                    else {
                        
                        $errores[] = [
                            'mensaje' => 'Uno de los sitios seleccionados no está disponible. Te invitamos a seleccionar otro sitio web.'
                        ];
                    }
                    

                }

            }else{
                return new \Exception('Error no existe la propuesta '.$id);
            }

        }

        $max_numero_enlaces = MediosRecursos::find()->where(['in', 'medio_influencer_recurso_id', $recursos_ids])->max('max_links');

        foreach (range(1, (int)$max_numero_enlaces) as $numero)
        {
            $this->numeroEnlaceData[$numero] = $numero;
        }

        $this->numero_enlace = $cantidad_mayor;
        $this->keywordsPropuestasData = PropuestasKeywordEnlaces::find()->asArray()->where(['propuesta_id'=>$id_mayor_numero_enlaces])->orderBy(['id'=>SORT_ASC])->all();

        $this->datos = json_encode(array_values(array_unique($this->datos)));
        $this->paquetes_ids = json_encode($this->paquetes_ids);
        $this->keywordsPropuestasData = json_encode($this->keywordsPropuestasData);
        $this->errores = $errores;
        $this->monto_total = $montoTotal;

        $this->urlsData = json_encode($urls);
        Yii::error("urlsData ".print_r($this->urlsData,true));
        $this->tipos_paquetes_id = json_encode(array_unique($tipos_paquetes_id));

        $this->objetos_seleccionados = json_encode($objetos_seleccionados);

    }



    private function formatoRangos($rangos){

        $formato = [];

        foreach($rangos as $rango){
            $formato[$rango->idCosto] = ($rango->hasta - 249).' a '.$rango->hasta;
        }

        return $formato;
    }

    private function limpiarPropuestas(){

        if($this->proyectoModel){
            $i = 1;
            foreach ($this->proyectoModel->propuestas as $propuesta) {

                //Yii::error("Estoy en limpiar propuesta".print_r($propuesta,true));

                PropuestasCategorias::deleteAll(['propuesta_id' => $propuesta->id]);
                PropuestasKeywordEnlaces::deleteAll(['propuesta_id' => $propuesta->id]);
                ValoresPropuestas::deleteAll(['propuesta_id' => $propuesta->id]);
                PropuestasMedios::deleteAll(['propuesta_id' => $propuesta->id]);
                PropuestaUrl::deleteAll(['propuesta_id' => $propuesta->id]);
				HistorialCorreos::deleteAll(['propuesta_id' => $propuesta->id]);
                PropuestasPedidos::deleteAll(['propuesta_id' => $propuesta->id]);

                //Yii::error("propuestas archivos eque se van a limpiar falta wac".print_r($propuesta->propuestasArchivosDesc,true));
                $tipo = $propuesta->tipo_propuesta_id;
               
                foreach ($propuesta->propuestasArchivosDesc as $propuestasArchivo) {


                    if ($tipo == 1){//post_wac

                        foreach ($propuestasArchivo->archivosRel as $archivo) {                       
                        
                            if ($i <= count($propuesta->propuestasArchivosDesc)) {
                                $this->archivosTempWac[] = $archivo;
                                Yii::error("archivo a borrar delete wac".print_r($archivo,true));
                                $archivo->delete();
                                
                            } else {
                                //Yii::error("archivo a borrar delete dos".print_r($archivo,true));
                                $archivo->deleteFile();
                            }
    
                        }

                    }

                    if ($tipo == 2){//post_cliente

                        foreach ($propuestasArchivo->archivosRel as $archivo) {                       
                        
                            if ($i <= count($propuesta->propuestasArchivosDesc)) {
                                $this->archivosTempCliente[] = $archivo;
                                Yii::error("archivo a borrar delete cliente".print_r($archivo,true));
                                $archivo->delete();
                                
                            } else {
                                //Yii::error("archivo a borrar delete dos".print_r($archivo,true));
                                $archivo->deleteFile();
                            }
    
                        }
                    }

                        
                    if ($tipo == 3){//post_medio

                        foreach ($propuestasArchivo->archivosRel as $archivo) {                       
                        
                            if ($i <= count($propuesta->propuestasArchivosDesc)) {
                                $this->archivosTempMedio[] = $archivo;
                                Yii::error("archivo a borrar delete medio".print_r($archivo,true));
                                $archivo->delete();
                                
                            } else {
                                //Yii::error("archivo a borrar delete dos".print_r($archivo,true));
                                $archivo->deleteFile();
                            }
    
                        }
                    }
                  
                }
                PropuestasArchivos::deleteAll(['propuesta_id'=>$propuesta->id]);

                if ($propuesta->delete()){
                    Yii::error("Elimino propuesta id ".print_r( $propuesta->id,true));
                }
                else {        
                        Yii::error("NOOOO elimino propuesta id ".print_r( $propuesta->id,true));
                        Yii::error("Errores ".print_r( $propuesta->getErrors(),true));               
                }

                //$propuesta->delete();       
            
            }               
            $i++;

        }
    }

    public function limpiarPropuesta($id){

            $propuesta = Propuestas::findOne($id);
            $datos = [];

            if($propuesta){
                $datos['propuesta'] = $propuesta->toArray();

                $propuestasCategorias = PropuestasCategorias::find()->where(['propuesta_id' => $propuesta->id])->all();

                foreach ($propuestasCategorias as $propuestasCategoria) {
                    $datos['propuestaCategorias'][] = $propuestasCategoria->toArray();
                }

                $propuestasKeywordEnlaces = PropuestasKeywordEnlaces::find()->where(['propuesta_id' => $propuesta->id])->all();

                foreach ($propuestasKeywordEnlaces as $propuestasKeywordEnlace) {
                    $datos['propuestaKeywordEnlaces'][] = $propuestasKeywordEnlace->toArray();
                }

                $valorPropuesta = ValoresPropuestas::find()->where(['propuesta_id' => $propuesta->id])->one();

                $datos['valoresPropuesta'] = $valorPropuesta->toArray();

                $propuestaMedio = PropuestasMedios::find()->where(['propuesta_id' => $propuesta->id])->one();

                $datos['propuestasMedios'] = $propuestaMedio->toArray();

                $historialCorreos = HistorialCorreos::find()->where(['propuesta_id' => $propuesta->id])->all();

                foreach ($historialCorreos as $historialCorreo) {
                    $datos['historialCorreos'][] = $historialCorreo->toArray();
                }

                $propuestasUrl = PropuestaUrl::find()->where(['propuesta_id' => $propuesta->id])->all();

                foreach ($propuestasUrl as $propuestaUrl) {
                    $datos['propuestaUrl'][] = $propuestaUrl->toArray();
                }


                $propuestaPedido = PropuestasPedidos::find()->where(['propuesta_id'=>$propuesta->id])->one();
                if($propuestaPedido){
                    $datos['propuestaPedido'][] = $propuestaPedido->toArray();
                }

                $audit = new PropuestasAudit([
                    'propuesta_id' => $id,
                    'estado_old' => $propuesta->estadoPropuesta->nombre_interno,
                    'estado_new' => 'reasignada',
                    'datos' => json_encode($datos),
                    'fechas' =>json_encode($propuesta->toArray(['fecha_registro','fecha_publicacion','fecha_modificacion','fecha_limite_entrega','fecha_limite_aceptacion'])),
                    'usuario_evento' => 'wactest01user@%',

                ]);

                $audit->save();

                PropuestasCategorias::deleteAll(['propuesta_id' => $propuesta->id]);
                PropuestasKeywordEnlaces::deleteAll(['propuesta_id' => $propuesta->id]);
                ValoresPropuestas::deleteAll(['propuesta_id' => $propuesta->id]);
                PropuestasMedios::deleteAll(['propuesta_id' => $propuesta->id]);
                HistorialCorreos::deleteAll(['propuesta_id' => $propuesta->id]);
                PropuestaUrl::deleteAll(['propuesta_id' => $propuesta->id]);
                PropuestasPedidos::deleteAll(['propuesta_id' => $propuesta->id]);

                foreach ($propuesta->propuestasArchivosDesc as $propuestasArchivo) {
                    /*OJO REVISAR AQUI */
                    foreach ($propuestasArchivo->archivosRel as $archivo) {
                        $this->archivosTemp[] = $archivo;
                        $archivo->delete();
                    }
                }

                PropuestasArchivos::deleteAll(['propuesta_id'=>$propuesta->id]);

                $propuesta->delete();

            }
    }

    public function enviarNotificaciones(array $models){

        $mailer = \Yii::$container->get(Mailer::className());
        return $mailer->sendPropuestaCreada($models);
    }

    public function enviarNotificacionesAdmin($correos,$models){

        $propuestas = $models->proyecto->propuestas;

        $total_precio = 0;
        $medios_seleccionados = "";
        $total_propuestas = count($propuestas) - 1;
        $i = 0;

        foreach($propuestas as $propuesta){
            $total_precio += $propuesta->precio;
            $medios_seleccionados .= "(". $propuesta->mediosInfluencersRecursos->id .")" . $propuesta->mediosInfluencersRecursos->url;
            $medios_seleccionados .= $total_propuestas == $i ? "" : "<br>";
            $i++;
        }


        $mailer = \Yii::$container->get(Mailer::className());

        return $mailer->sendPropuestaCreadaAdmin($correos,$models,$total_precio,$medios_seleccionados);
    }



    /**
     * @param $propuestas_model Propuestas
     * @param $nombre_interno string
     * @return mixed
     */
    public function getFechas($propuestas_model, $nombre_interno){
        $fechas = null;

        switch ($nombre_interno){
            case TipoPropuestas::POST_MEDIO:
                $fechas = Valores::find()->select(['nombre','valor'])->where(['LIKE','nombre','medio_hace_articulo'])->asArray()->all();
                break;

            case TipoPropuestas::POST_WAC:
                $fechas = Valores::find()->select(['nombre','valor'])->where(['LIKE','nombre','wac_hace_articulo'])->asArray()->all();
                break;

            case TipoPropuestas::POST_CLIENTE:
                $fechas = Valores::find()->select(['nombre','valor'])->where(['LIKE','nombre','cliente_envia_articulo'])->asArray()->all();
                break;

            case TipoPropuestas::POST_URL:
                $fechas = Valores::find()->select(['nombre','valor'])->where(['LIKE','nombre','url_antiguas'])->asArray()->all();
                break;

            case TipoPropuestas::POST_MEDIO_SUGIERE:
                $fechas = Valores::find()->select(['nombre','valor'])->where(['LIKE','nombre','medio_sugiere_url'])->asArray()->all();
                break;
            case TipoPropuestas::POST_CLIENTE_SUGIERE:
                $fechas = Valores::find()->select(['nombre','valor'])->where(['LIKE','nombre','cliente_sugiere_url'])->asArray()->all();
                break;
        }

        $fechas = array_column($fechas, NULL, 'nombre');

        $fechas = array_combine(array_keys($fechas), array_map(function($item) {
            return $item['valor'];
        }, $fechas));

        switch ($nombre_interno){
            case TipoPropuestas::POST_MEDIO:

                $limite_aceptacion = $fechas['medio_hace_articulo_limite_aceptacion'];
                $limite_entrega = $fechas['medio_hace_articulo_limite_entrega'];
                $limite_aprobacion = $fechas['medio_hace_articulo_limite_aprobacion'];
                $limite_ajustes = $fechas['medio_hace_articulo_limite_ajustes'];
                $limite_publicacion = $fechas['medio_hace_articulo_limite_publicacion'];

                $fecha_aceptacion = date('Y-m-d', strtotime(Utilfecha::sumasdiasemana('now', $limite_aceptacion)));
                $fecha_entrega = date('Y-m-d', strtotime('+'.$limite_entrega.' days', strtotime($fecha_aceptacion)));
                $fecha_ajustes = date('Y-m-d', strtotime('+'.$limite_ajustes.' days', strtotime($fecha_entrega)));
                $fecha_aprobacion = date('Y-m-d', strtotime('+'.$limite_aprobacion.' days', strtotime($fecha_ajustes)));
                $fecha_publicacion = date('d-m-Y', strtotime('+'.$limite_publicacion.' days', strtotime($fecha_aprobacion)));

                $propuestas_model->fecha_limite_aceptacion = $fecha_aceptacion;
                $propuestas_model->fecha_limite_entrega = $fecha_entrega;
                $propuestas_model->fecha_limite_aprobacion = $fecha_aprobacion;
                $propuestas_model->fecha_limite_ajustes = $fecha_ajustes;

                break;

            case TipoPropuestas::POST_WAC:

                $limite_aceptacion = $fechas['wac_hace_articulo_limite_aceptacion'];
                $limite_publicacion = $fechas['wac_hace_articulo_limite_publicacion'];

                $fecha_aceptacion = date('Y-m-d', strtotime(Utilfecha::sumasdiasemana('now', $limite_aceptacion)));
                $fecha_publicacion = date('d-m-Y', strtotime('+'.$limite_publicacion.' days', strtotime($fecha_aceptacion)));

                $propuestas_model->fecha_limite_aceptacion = $fecha_aceptacion;

                break;

            case TipoPropuestas::POST_CLIENTE:
                $limite_aceptacion = $fechas['cliente_envia_articulo_limite_aceptacion'];
                $limite_publicacion = $fechas['cliente_envia_articulo_limite_publicacion'];

                $fecha_aceptacion = date('Y-m-d', strtotime(Utilfecha::sumasdiasemana('now', $limite_aceptacion)));
                $fecha_publicacion = date('d-m-Y', strtotime('+'.$limite_publicacion.' days', strtotime($fecha_aceptacion)));

                $propuestas_model->fecha_limite_aceptacion = $fecha_aceptacion;
                break;

            case TipoPropuestas::POST_URL:
                $limite_aceptacion = $fechas['url_antiguas_limite_aceptacion'];
                $limite_aprobacion = $fechas['url_antiguas_limite_aprobacion'];
                $limite_publicacion = $fechas['url_antiguas_limite_publicacion'];

                $fecha_aceptacion = date('Y-m-d', strtotime(Utilfecha::sumasdiasemana('now', $limite_aceptacion)));
                $fecha_aprobacion = date('Y-m-d', strtotime('+'.$limite_aprobacion.' days', strtotime($fecha_aceptacion)));
                $fecha_publicacion = date('d-m-Y', strtotime('+'.$limite_publicacion.' days', strtotime($fecha_aprobacion)));

                $propuestas_model->fecha_limite_aceptacion = $fecha_aceptacion;
                //se quito la fecha de aprobacion
                //$propuestas_model->fecha_limite_aprobacion = $fecha_aprobacion;
                break;

            case TipoPropuestas::POST_MEDIO_SUGIERE:
                $limite_aceptacion = $fechas['medio_sugiere_url_limite_aceptacion'];
                $limite_aprobacion = $fechas['medio_sugiere_url_limite_aprobacion'];
                $limite_publicacion = $fechas['medio_sugiere_url_limite_publicacion'];

                $fecha_aceptacion = date('Y-m-d', strtotime(Utilfecha::sumasdiasemana('now', $limite_aceptacion)));
                $fecha_aprobacion = date('Y-m-d', strtotime('+'.$limite_aprobacion.' days', strtotime($fecha_aceptacion)));
                $fecha_publicacion = date('d-m-Y', strtotime('+'.$limite_publicacion.' days', strtotime($fecha_aprobacion)));

                $propuestas_model->fecha_limite_aceptacion = $fecha_aceptacion;

                break;

            case TipoPropuestas::POST_CLIENTE_SUGIERE:
                $limite_aceptacion = $fechas['cliente_sugiere_url_limite_aceptacion'];
                $limite_aprobacion = $fechas['cliente_sugiere_url_limite_aprobacion'];
                $limite_publicacion = $fechas['cliente_sugiere_url_limite_publicacion'];

                $fecha_aceptacion = date('Y-m-d', strtotime(Utilfecha::sumasdiasemana('now', $limite_aceptacion)));
                $fecha_aprobacion = date('Y-m-d', strtotime('+'.$limite_aprobacion.' days', strtotime($fecha_aceptacion)));
                $fecha_publicacion = date('d-m-Y', strtotime('+'.$limite_publicacion.' days', strtotime($fecha_aprobacion)));

                $propuestas_model->fecha_limite_aceptacion = $fecha_aceptacion;

                break;
        }

        return $propuestas_model;
    }


    /**
     * verifica si un pedido de premium esta vigente segun la variable dias_vigencia_pedidos_texto para ser retomado o reasignado en algun pedido de bkl
     * @param $id
     * @return bool
     */
    public function validarVigenciaPedido($id, $cliente = null){


        $days = Valores::getValorByName('dias_vigencia_pedidos_texto');

        $fechaActual = date("Y-m-d 23:59:59");

        $fechaAnterior = date("Y-m-d 00:00:00", strtotime("-$days day"));

        $query = Pedidos::find()
            ->joinWith(['estHistorialPedidosComprado'])
            ->where(
                [
                    Pedidos::tableName().'.user_id' => $cliente ?: Yii::$app->user->id,
                    'numeroDeNotas'=>1,
                ])
            ->andWhere(['IN','tipo',['normal','especial']])
            ->andWhere(['IS NOT',EstHistorialPedidos::tableName().'.id', new Expression('NULL')])
            ->andWhere(['REGEXP ','archivoRuta','\\|{1}'])
            ->andWhere(['between', EstHistorialPedidos::tableName() . '.fechaEvent', $fechaAnterior, $fechaActual]);

        $queryPedidos = $query->asArray()->all();

        $resultados = array_filter(array_map(function ($item){

            $archivo = substr($item['archivoRuta'],0,-1);

            $rutaArchivos = Yii::getAlias("@archivosPlataforma");
            $url = "$rutaArchivos/$archivo";

            $data = false;

            if(is_file($url)){
                $data = [
                    'idPedido'=>$item['idPedido'],
                    'tituloDelPedido'=>$item['tituloDelPedido'],
                    'fechaCompra'=>$item['estHistorialPedidosComprado']['fechaEvent'],

                ];

            }

            return $data;

        },$queryPedidos), function ($item) use ($id) {
            return (is_array($item) && array_search($id, $item));
        });

        return count($resultados)>0;

    }

     public function enviarNotificacionesDesdeAdmin($cliente_id,$models){

        $propuestas = $models->proyecto->propuestas;

        $total_precio = 0;
        $medios_seleccionados = "";
        $total_propuestas = count($propuestas) - 1;
        $i = 0;

        foreach($propuestas as $propuesta){
            $total_precio += $propuesta->precio;
            $medios_seleccionados .= "(". $propuesta->mediosInfluencersRecursos->id .")" . $propuesta->mediosInfluencersRecursos->url;
            $medios_seleccionados .= $total_propuestas == $i ? "" : "<br>";
            $i++;
        }


        $mailer = \Yii::$container->get(Mailer::className());

        return $mailer->sendPropuestaCreadaDesdeAdmin($cliente_id,$models,$total_precio,$medios_seleccionados);
    }

    public function getMediosActivos($limit=0){
        $now = new \DateTime(null, new \DateTimeZone('America/Bogota'));
        $hoy = $now->format("Y-m-d");
        $result = array();
        $cadena_categorias ="";
        $cadena_idiomas ="";
        $cadena_temas ="";
        $count = 0;
        $query = MediosInfluencersRecursos::find()
                    ->select(['fab_bkl_medios_influencers_recursos.*, 
                    fValidarDescuentoPaquete(fab_bkl_medios_recursos_paquetes.valor, fab_bkl_medios_recursos_paquetes.descuento,fab_bkl_medios_recursos_paquetes.fecha_desde, fab_bkl_medios_recursos_paquetes.fecha_hasta) as tiene_descuento,
                    (SELECT fCalcularMontoTotalPaquete(30,0,0,fab_bkl_medios_recursos_paquetes.valor,fab_bkl_medios_recursos_paquetes.descuento,fab_bkl_medios_recursos_paquetes.fecha_desde,fab_bkl_medios_recursos_paquetes.fecha_hasta) from 
                        fab_bkl_medios_recursos_paquetes
                        where fab_bkl_medios_recursos_paquetes.paquete_id = 2
                        and fab_bkl_medios_recursos_paquetes.medio_influencer_recurso_id = fab_bkl_medios_influencers_recursos.id) as costo,
                        (select fab_retenciones_pais.pais from fab_retenciones_pais where id = fab_bkl_medios_influencers_recursos.pais_id) as pais,
                        (select fab_bkl_publicidad.nombre from fab_bkl_publicidad where id = fab_bkl_medios_influencers_recursos.publicidad_id) as publicidad
                    '
                    ]);
        $query->joinWith(['mediosRecursos']);
        $query->joinWith(['mediosRecursosPaquetes']);
        $query->joinWith(['mediosRecursosPaquetes.paquetes.tipoPropuestas']);
        $query->joinWith(['mediosInfluencersRecursosCategorias']);
        $query->joinWith(['mediosInfluencersRecursosCategorias.categoria']);
        $query->joinWith(['tematicasNoAceptadasIds']);
        $query->joinWith(['estadosMediosInfluencers']);
        $query->joinWith(['influencersRecursos']);
        $query->joinWith(['mediosInfluencers']);
        $query->joinWith(['mediosInfluencers.user']);
        $query->joinWith(['idiomasIds']);
        $query->where(['=', 'fab_bkl_medios_influencers_recursos.estado_medio_influencer_id', 1]);
        $query->andWhere(['=', 'fab_bkl_tipo_propuestas.estado', 1]);
        $query->andWhere(['=', 'fab_bkl_tipo_propuestas.id', 3]);
        $query->andWhere(['=', 'fab_bkl_medios_recursos_paquetes.paquete_id', 2]);
        $query->andWhere(['=', 'fab_bkl_medios_influencers.estado', 1]);
        $query->andWhere(['or',[ 'fab_bkl_medios_recursos.estado' => 0],
                        [ 'fab_bkl_medios_recursos.estado' => 1],
                        ]);
        $query->andWhere(['or',[ 'fab_bkl_medios_influencers_recursos.modo_vaciones' => 0],
                         ['and',[ 'fab_bkl_medios_influencers_recursos.modo_vaciones' => 1],
                         ['>','fecha_inicio_vaciones',$hoy]],
                          ]);
        $query->orWhere(['and', ['fab_bkl_medios_influencers_recursos.modo_vaciones' => 1],
                          ['and', ['<', 'fab_bkl_medios_influencers_recursos.fecha_inicio_vaciones', $hoy],
                              ['<', 'fab_bkl_medios_influencers_recursos.fecha_fin_vacaciones', $hoy]]]);
        
        $query->groupBy(['fab_bkl_medios_influencers_recursos.id']);
        if ($limit>0){
            $query->limit($limit);
        }
        
        if ($query){
            $count = $query->count(); 
            $data = new ActiveDataProvider([
                'query' => $query->asArray(),
                'pagination' => false,
                'sort' => [
                    'defaultOrder' => [
                        'id' => SORT_DESC,
                        
                    ]
                ],
            ]);
            foreach ($data->getModels() as $modelo){
                $idiomas    = array();
                $categorias = array();
                $paquetes   = array();
                $cadena_idiomas = "";
                $disponible = true;
               
                //-------------------
                if ($modelo['idiomasIds']){
                    foreach ($modelo['idiomasIds'] as $idioma){
                        $idiomas[] = (object)array(
                            'idIdioma' =>$idioma['idIdioma'],
                            'idioma'   => $idioma['idioma'],
                        );
                        if (empty($cadena_idiomas)){
                            $cadena_idiomas = $idioma['idioma'];
                        }
                        else {
                            $cadena_idiomas =$cadena_idiomas." / ".$idioma['idioma'];
                        }
                    }


                }


                if ($modelo['mediosInfluencersRecursosCategorias']){
                    foreach ($modelo['mediosInfluencersRecursosCategorias'] as $categoria){
                        if (empty($cadena_categorias)){
                            $cadena_categorias = $categoria['categoria'][0]['nombre'];
                        }
                        else {
                            $cadena_categorias = $cadena_categorias.", ".$categoria['categoria'][0]['nombre'];;
                        }
                        
                    }
                }

                if ($modelo['tematicasNoAceptadasIds']){
                    foreach ($modelo['tematicasNoAceptadasIds'] as $tematicas){
                        if (empty($cadena_temas)){
                            $cadena_temas = $tematicas['nombre'];
                        }
                        else {
                            $cadena_temas = $cadena_temas.", ".$tematicas['nombre'];
                        }
                        
                    }
                }

                    
                    
                        $publicacion = "";
                        if ($modelo['tiempo_publicacion_post']==NULL or $modelo['tiempo_publicacion_post']==0){
                            $publicacion = "Indefinido";
                        }
                        else {
                            $publicacion = $modelo['tiempo_publicacion_post']." días";
                        }

                        $tipo_enlaces = [];

                            if ($modelo['mediosRecursos']['links_follow']==1){
                                $tipo_enlaces[] = "Acepto enlaces Follow";
                            }

                            if ($modelo['mediosRecursos']['links_no_follow']==1){
                                $tipo_enlaces[] = "Acepto enlaces No-follow";
                            }

                        
                        $result [] = (object) array(
                            'url'           => $modelo['url'],
                            //'email' => $modelo['email_recurso'],
                            'costo' => $modelo['costo'],
                            'niche' => $cadena_categorias,
                            'type' =>$tipo_enlaces,
                            'country'=>$modelo['pais'],
                            'notes' => array(
                                'en_oferta' => ($modelo['tiene_descuento']==1)?'si':'no',
                                'metricas'      => ($modelo['seccions']!= NULL)?json_decode($modelo['seccions']):"",
                                'nro_links'    => $modelo['mediosRecursos']['max_links'],
                                'tiempo_publicacion_post' => $publicacion,
                                'publicidad' => $modelo['publicidad'],
                                'idiomas' => $cadena_idiomas,
                                'tematicas_no_aceptadas' => $cadena_temas,
                                
                            ),
                        
                        );
                    



            }

            return (object)array(
                'data' => $result,
                'count' =>  $count
            );

        }
        else {
           return false;
        }

    }




}

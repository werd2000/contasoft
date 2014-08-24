<?php
ob_start();
require_once 'configuration.php';
require_once 'Zend/Json.php';
require_once 'class/Gasto.php';
require_once 'class/Proveedor.php';
require_once 'class/Usuario.php';
require_once 'class/Ingresos.php';
require_once 'class/Cuenta.php';
require_once 'class/GrupoCuentas.php';
require_once 'class/Profesionales/Profesionales.php';
require_once 'class/Turnos/Turnos.php';
require_once 'class/Sueldo.php';

if ($_GET) {
    $responce = '';
    $tarea = $_GET["option"];
    $sub = $_GET["sub"];
    /* Me fijo si vino sidx. Si no vino ordeno por "id" */
    if ($_GET['sidx']) {
        $ordenar_por = $_GET['sidx']; // get index row - i.e. user click to sort
    } else {
        $ordenar_por = 'id';
    }
    /* Me fijo si vino sord. Si no vino ordeno en forma "ASC" */
    if ($_GET['sord']) {
        $ordenar_enforma = $_GET['sord']; // get the direction
    } else {
        $ordenar_enforma = 'ASC';
    }
    /* Me fijo si vino agrupar. Si no vino no agrupa, es decir = '' */
    if ($_GET['agrupar']) {
        $agrupar_por = $_GET['agrupar']; // get the direction
    } else {
        $agrupar_por = '';
    }
//    /* Me fijo si vino searchField. Si no vino no busca nada, es decir = '' */
//    if ($_GET['searchField']) {
//        //        $where = 'WHERE ' . $_GET['searchField'] . " " . $_GET['searchOper'] . " " . $_GET['searchString']; // get the direction
//        $where = 'conta_gastos.' . $_GET['searchField'] . " = " . $_GET['searchString']; // get the direction
////        echo $where;
//    } else {
//        $where = '';
//    }
    /* Me fijo si vino page. Si no vino no la pagina es la primera = 0 */
    if ($_GET['page']) {
        $page = $_GET['page']; // get the page
    } else {
        $page = 1;
    }
    /* Me fijo si vino rows. Si no vino el límite es de 20 */
    if ($_GET['rows']) {
        $limit = $_GET['rows']; // get the direction
    } else {
        $limit = 20;
    }
    $inicio = $page * $limit - $limit;

    /* Controlo si es para gastos o proveedores */
    switch ($tarea) {
        /* Tareas para gastos */
        case 'gastos':
            if ($_GET['sub']) { //Me fijo si vine con sub
                switch ($sub) {
                    /* Es para el análisis de los datos */
                    case 'analisismensual':
                        $fecha_inicio = '2009-11-01';//date('Y-m-') . '01';
                        $fecha_fin = date('Y-m-t');
                        $opciones['SELECT'] = "conta_proveedores.id, conta_proveedores.razon_social,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_gastos.fecha_comprobante) = '11', conta_gastos.total, 0 )) AS noviembre,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_gastos.fecha_comprobante) = '12', conta_gastos.total, 0 )) AS diciembre,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_gastos.fecha_comprobante)= '1', conta_gastos.total , 0 )) AS enero,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_gastos.fecha_comprobante)= '02', conta_gastos.total , 0 )) AS febrero,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_gastos.fecha_comprobante)= '03', conta_gastos.total , 0 )) AS marzo,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_gastos.fecha_comprobante)= '04', conta_gastos.total , 0 )) AS abril,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_gastos.fecha_comprobante)= '05', conta_gastos.total , 0 )) AS mayo,";
                        $opciones['SELECT'] .= "SUM( total ) AS Total_total ";
                        $opciones['SELECT'] .= "FROM conta_gastos LEFT JOIN conta_proveedores ON conta_gastos.proveedor=conta_proveedores.id";
                        $opciones['WHERE']= ''; //"WHERE fecha_comprobante BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "' ";
                        $opciones['GROUPBY']='razon_social WITH ROLLUP';
                        $cant_reg = count(Gasto::listarDatosJSON($opciones));
                        $opciones['INICIO']=$inicio;
                        $opciones['LIMIT']= $limit;
                        $gastos = Gasto::listarDatosJSON($opciones);
                        $count = count($gastos);
                        if ($cant_reg > 0 && $limit > 0) {
                            $total_pages = ceil($cant_reg / $limit);
                        } else {
                            $total_pages = 0;
                        }
                        if ($page > $total_pages)
                            $page = $total_pages;
                        if ($page == $total_pages)
                        {
                            $hasta = $count-1;
                        }else{
                            $hasta = $count;
                        }
                        $responce->page = $page;
                        $responce->total = $total_pages;
                        $responce->records = $cant_reg;
                        for ($i = 0; $i < $hasta; $i ++) {
                            $responce->rows[$i]['id'] = $gastos[$i]['id'];
                            $responce->rows[$i]['cell'] = array($gastos[$i]['razon_social'], $gastos[$i]['noviembre']  , $gastos[$i]['diciembre'], $gastos[$i]['enero']  ,$gastos[$i]['febrero'] , $gastos[$i]['marzo'], $gastos[$i]['abril'],$gastos[$i]['mayo'] ,$gastos[$i]['Total_total']);// , $gastos[$i]['total_iva_inscripto'] , $gastos[$i]['total_iva_diferencial'] , $gastos[$i]['total_percepcion'] , $gastos[$i]['total_total']);
                        }
                        $responce->rows[$i]['id'] = $gastos[$i]['id'];
                        $responce->rows[$i]['cell'] = array('TOTAL:' , $gastos[$i]['noviembre'], $gastos[$i]['diciembre'] , $gastos[$i]['enero'] , $gastos[$i]['febrero'], $gastos[$i]['marzo'], $gastos[$i]['abril'], $gastos[$i]['mayo'], $gastos[$i]['Total_total'] );//, $gastos[$i]['total_percepcion'] , $gastos[$i]['total_total']);
                        break;
                    case 'analisiscuenta':
                        $fecha_inicio = '2009-11-01';//date('Y-m-') . '01';
                        $fecha_fin = date('Y-m-t');
                        $opciones['SELECT'] = "conta_cuentas.id, conta_cuentas.cuenta,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_gastos.fecha_comprobante) = '11', conta_gastos.total, 0 )) AS noviembre,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_gastos.fecha_comprobante) = '12', conta_gastos.total, 0 )) AS diciembre,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_gastos.fecha_comprobante)= '1', conta_gastos.total , 0 )) AS enero,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_gastos.fecha_comprobante)= '02', conta_gastos.total , 0 )) AS febrero,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_gastos.fecha_comprobante)= '03', conta_gastos.total , 0 )) AS marzo,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_gastos.fecha_comprobante)= '04', conta_gastos.total , 0 )) AS abril,";
                        $opciones['SELECT'] .= "SUM( total ) AS Total_total ";
                        $opciones['SELECT'] .= "FROM conta_gastos LEFT JOIN conta_cuentas ON conta_gastos.cuenta=conta_cuentas.id";
                        $opciones['WHERE']= ''; //"WHERE fecha_comprobante BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "' ";
                        $opciones['GROUPBY']='cuenta WITH ROLLUP';
                        $cant_reg = count(Gasto::listarDatosJSON($opciones));
                        $opciones['INICIO']=$inicio;
                        $opciones['LIMIT']= $limit;
                        $gastos = Gasto::listarDatosJSON($opciones);
                        $count = count($gastos);
                        if ($cant_reg > 0 && $limit > 0) {
                            $total_pages = ceil($cant_reg / $limit);
                        } else {
                            $total_pages = 0;
                        }
                        if ($page > $total_pages)
                            $page = $total_pages;
                        if ($page == $total_pages)
                        {
                            $hasta = $count-1;
                        }else{
                            $hasta = $count;
                        }
                        $responce->page = $page;
                        $responce->total = $total_pages;
                        $responce->records = $cant_reg;
                            
                        for ($i = 0; $i < $hasta; $i ++) {
                            $responce->rows[$i]['id'] = $gastos[$i]['id'];
                            $responce->rows[$i]['cell'] = array($gastos[$i]['cuenta'], $gastos[$i]['noviembre']  , $gastos[$i]['diciembre'], $gastos[$i]['enero']  ,$gastos[$i]['febrero'] , $gastos[$i]['marzo'], $gastos[$i]['abril'] ,$gastos[$i]['Total_total']); 
                        }
                        $responce->rows[$i]['id'] = $gastos[$i]['id'];
                        $responce->rows[$i]['cell'] = array('TOTAL:' , $gastos[$i]['noviembre'], $gastos[$i]['diciembre'] , $gastos[$i]['enero'] , $gastos[$i]['febrero'], $gastos[$i]['marzo'], $gastos[$i]['abril'], $gastos[$i]['Total_total'] );//, $gastos[$i]['total_percepcion'] , $gastos[$i]['total_total']);
                        break;
                    case 'lista':
                        $cant_reg = Gasto::cant_reg('conta_gastos');
                        if ($cant_reg > 0 && $limit > 0) {
                            $total_pages = ceil($cant_reg / $limit);
                        } else {
                            $total_pages = 0;
                        }
                        if ($page > $total_pages)
                            $page = $total_pages;
                        $responce->page = $page;
                        $responce->total = $total_pages;
                        $responce->records = $cant_reg;
                        require_once 'persistencia/Sql.php';
                        $sql = new Sql();
                        $sql->addSelect('conta_gastos.*');
                        $sql->addSelect('conta_proveedores.razon_social');
                        $sql->addTable('conta_gastos LEFT JOIN conta_proveedores ON conta_gastos.proveedor=conta_proveedores.id');
//                        $opciones['SELECT']='conta_gastos.*, conta_proveedores.razon_social FROM conta_gastos LEFT JOIN conta_proveedores ON conta_gastos.proveedor=conta_proveedores.id';
//                        $opciones['WHERE']=condicion('conta_gastos.');
                        if ($ordenar_por == 'razon_social') {
                            $sql->addOrder("conta_proveedores " . $ordenar_por);
//                            $opciones['ORDERBY']='conta_proveedores.' . $ordenar_por;
                        }else{
//                            $opciones['ORDERBY']='conta_gastos.' . $ordenar_por;
                            $sql->addOrder("conta_gastos " . $ordenar_por);
                        }
//                        $opciones['ORDERTYPE']=$ordenar_enforma;
//                        $opciones['GROUPBY']='';
                        $sql->addLimit($inicio,$limit);
                        $gastos = Gasto::listarDatosJSON($sql);
                        $count = count($gastos);
                        for ($i = 0; $i < $count; $i ++) {
                            $tig += $gastos[$i]->importe_gravado;
                            $tivai += $gastos[$i]->iva_inscripto;
                            $responce->rows[$i]['id'] = $gastos[$i]->id;
                            $responce->rows[$i]['cell'] = array($gastos[$i]->id, $gastos[$i]->cuenta , $gastos[$i]->razon_social , $gastos[$i]->fecha_comprobante , $gastos[$i]->comprobante , $gastos[$i]->tipo_comprobante , $gastos[$i]->nro_comprobante , $gastos[$i]->importe_gravado , $gastos[$i]->importe_nogravado , $gastos[$i]->iva_inscripto , $gastos[$i]->iva_diferencial , $gastos[$i]->percepcion , $gastos[$i]->total);
                        }
                        $responce->userdata['razon_social'] = 'Total:';
                        $responce->userdata['importe_gravado'] = $tig;
                        $responce->userdata['iva_inscripto'] = $tivai;
                        break;
                }
            }
            break;
        case 'ingresos':
            if ($_GET['sub']) { //Me fijo si vine con sub
                switch ($sub) {
                    /* Es para el análisis de los datos */
                    case 'analisis':
                        $fecha_inicio = '2009-11-01';//date('Y-m-') . '01';
                        $fecha_fin = date('Y-m-t');
                        $opciones['SELECT'] = "conta_clientes.id, conta_clientes.razon_social,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_ingresos.fecha_comprobante) = '11', conta_ingresos.total, 0 )) AS noviembre,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_ingresos.fecha_comprobante) = '12', conta_ingresos.total, 0 )) AS diciembre,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_ingresos.fecha_comprobante)= '1', conta_ingresos.total , 0 )) AS enero,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_ingresos.fecha_comprobante)= '02', conta_ingresos.total , 0 )) AS febrero,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_ingresos.fecha_comprobante)= '03', conta_ingresos.total , 0 )) AS marzo,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_ingresos.fecha_comprobante)= '04', conta_ingresos.total , 0 )) AS abril,";
                        $opciones['SELECT'] .= "SUM( total ) AS Total_total ";
                        $opciones['SELECT'] .= "FROM conta_ingresos LEFT JOIN conta_clientes ON conta_ingresos.cliente=conta_clientes.id";
                        $opciones['WHERE']= ''; //"WHERE fecha_comprobante BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "' ";
                        $opciones['GROUPBY']='razon_social WITH ROLLUP';
                        $cant_reg = count(Ingresos::listarDatosJSON($opciones));
                        $opciones['INICIO']=$inicio;
                        $opciones['LIMIT']= $limit;
                        $ingresos = Ingresos::listarDatosJSON($opciones);
                        $count = count($ingresos);
                        if ($cant_reg > 0 && $limit > 0) {
                            $total_pages = ceil($cant_reg / $limit);
                        } else {
                            $total_pages = 0;
                        }
                        if ($page > $total_pages)
                            $page = $total_pages;
                        if ($page == $total_pages)
                        {
                            $hasta = $count-1;
                        }else{
                            $hasta = $count;
                        }
                        $responce->page = $page;
                        $responce->total = $total_pages;
                        $responce->records = $cant_reg;
                            
                        for ($i = 0; $i < $hasta; $i ++) {
                            $responce->rows[$i]['id'] = $ingresos[$i]['id'];
                            $responce->rows[$i]['cell'] = array($ingresos[$i]['razon_social'], $ingresos[$i]['noviembre']  , $ingresos[$i]['diciembre'], $ingresos[$i]['enero']  ,$ingresos[$i]['febrero'] , $ingresos[$i]['marzo'], $ingresos[$i]['abril'] ,$ingresos[$i]['Total_total']);// , $gastos[$i]['total_iva_inscripto'] , $gastos[$i]['total_iva_diferencial'] , $gastos[$i]['total_percepcion'] , $gastos[$i]['total_total']);
                        }
                        $responce->rows[$i]['id'] = $ingresos[$i]['id'];
                        $responce->rows[$i]['cell'] = array('TOTAL:' , $ingresos[$i]['noviembre'], $ingresos[$i]['diciembre'] , $ingresos[$i]['enero'] , $ingresos[$i]['febrero'], $ingresos[$i]['marzo'], $ingresos[$i]['abril'], $ingresos[$i]['Total_total'] );//, $gastos[$i]['total_percepcion'] , $gastos[$i]['total_total']);
                        break;
                    case 'analisiscuenta':
                        $fecha_inicio = '2009-11-01';//date('Y-m-') . '01';
                        $fecha_fin = date('Y-m-t');
                        
                        $opciones['SELECT'] = "conta_cuentas.id, conta_cuentas.cuenta,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_ingresos.fecha_comprobante) = '11', conta_ingresos.total, 0 )) AS noviembre,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_ingresos.fecha_comprobante) = '12', conta_ingresos.total, 0 )) AS diciembre,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_ingresos.fecha_comprobante)= '1', conta_ingresos.total , 0 )) AS enero,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_ingresos.fecha_comprobante)= '02', conta_ingresos.total , 0 )) AS febrero,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_ingresos.fecha_comprobante)= '03', conta_ingresos.total , 0 )) AS marzo,";
                        $opciones['SELECT'] .= "SUM( if (MONTH(conta_ingresos.fecha_comprobante)= '04', conta_ingresos.total , 0 )) AS abril,";
                        $opciones['SELECT'] .= "SUM( total ) AS Total_total ";
                        $opciones['SELECT'] .= "FROM conta_ingresos LEFT JOIN conta_cuentas ON conta_ingresos.cuenta=conta_cuentas.id";
                        $opciones['WHERE']= ''; //"WHERE fecha_comprobante BETWEEN '" . $fecha_inicio . "' AND '" . $fecha_fin . "' ";
                        $opciones['GROUPBY']='cuenta WITH ROLLUP';
                        $cant_reg = count(Ingresos::listarDatosJSON($opciones));
                        $opciones['INICIO']=$inicio;
                        $opciones['LIMIT']= $limit;
                        $ingresos = Ingresos::listarDatosJSON($opciones);
                        $count = count($ingresos);
                        if ($cant_reg > 0 && $limit > 0) {
                            $total_pages = ceil($cant_reg / $limit);
//                        echo $page;
                        } else {
                            $total_pages = 0;
                        }
                        if ($page > $total_pages)
                            $page = $total_pages;
                        if ($page == $total_pages)
                        {
                            $hasta = $count-1;
                        }else{
                            $hasta = $count;
                        }
                        $responce->page = $page;
                        $responce->total = $total_pages;
                        $responce->records = $cant_reg;
                        for ($i = 0; $i < $hasta; $i ++) {
                            $responce->rows[$i]['id'] = $ingresos[$i]['id'];
                            $responce->rows[$i]['cell'] = array($ingresos[$i]['cuenta'], $ingresos[$i]['noviembre']  , $ingresos[$i]['diciembre'], $ingresos[$i]['enero']  ,$ingresos[$i]['febrero'] , $ingresos[$i]['marzo'], $ingresos[$i]['abril'] ,$ingresos[$i]['Total_total']);// , $gastos[$i]['total_iva_inscripto'] , $gastos[$i]['total_iva_diferencial'] , $gastos[$i]['total_percepcion'] , $gastos[$i]['total_total']);
                        }
                        $responce->rows[$i]['id'] = $ingresos[$i]['id'];
                        $responce->rows[$i]['cell'] = array('TOTAL:' , $ingresos[$i]['noviembre'], $ingresos[$i]['diciembre'] , $ingresos[$i]['enero'] , $ingresos[$i]['febrero'], $ingresos[$i]['marzo'], $ingresos[$i]['abril'], $ingresos[$i]['Total_total'] );//, $gastos[$i]['total_percepcion'] , $gastos[$i]['total_total']);
                        break;
                    case 'lista':
                        $cant_reg = Ingresos::cant_reg('conta_ingresos');
                        if ($cant_reg > 0 && $limit > 0) {
                            $total_pages = ceil($cant_reg / $limit);
                        } else {
                            $total_pages = 0;
                        }
                        if ($page > $total_pages)
                            $page = $total_pages;
                        $responce->page = $page;
                        $responce->total = $total_pages;
                        $responce->records = $cant_reg;
//                        $opciones['SELECT']=' conta_ingresos.*, conta_clientes.razon_social FROM conta_ingresos LEFT JOIN conta_clientes ON conta_ingresos.cliente=conta_clientes.id';
                        $sql = new Sql('conta_ingresos LEFT JOIN conta_clientes ON conta_ingresos.cliente=conta_clientes.id');
                        $sql->addSelect('conta_ingresos.*');
                        $sql->addSelect('conta_clientes.razon_social');
                        $sql->addTable('conta_ingresos LEFT JOIN conta_clientes ON conta_ingresos.cliente=conta_clientes.id');
                        if ($ordenar_por == 'razon_social') $ordenar_por = 'cliente';
                        $opciones['ORDERBY']='conta_ingresos.' . $ordenar_por;
                        $sql->addOrder('conta_ingresos.' . $ordenar_por);
                        $sql->addLimit($inicio,$limit);
                        $ingresos = Ingresos::listarDatosJSON($sql);
                        $count = count($ingresos);
                        for ($i = 0; $i < $count; $i ++) {
                            $tig += $ingresos[$i]->importe_gravado;
                            $tivai += $ingresos[$i]->iva_inscripto;
                            $responce->rows[$i]['id'] = $ingresos[$i]->id;
                            $responce->rows[$i]['cell'] = array($ingresos[$i]->id, $ingresos[$i]->cuenta , $ingresos[$i]->razon_social, $ingresos[$i]->fecha_comprobante, $ingresos[$i]->comprobante, $ingresos[$i]->tipo_comprobante, $ingresos[$i]->nro_comprobante, $ingresos[$i]->importe_gravado, $ingresos[$i]->importe_nogravado, $ingresos[$i]->iva_inscripto, $ingresos[$i]->iva_diferencial, $ingresos[$i]->percepcion, $ingresos[$i]->total);
                        }
                        $responce->userdata['razon_social'] = 'Total:';
                        $responce->userdata['importe_gravado'] = $tig;
                        $responce->userdata['iva_inscripto'] = $tivai;
                        break;
                }
            }
            break;
        case 'proveedores':
            $opciones['SELECT'] = '* FROM conta_proveedores';
            $opciones['WHERE'] = '';
            $opciones['ORDERBY'] = $ordenar_por;
            $opciones['ORDERTYPE'] = $ordenar_enforma;
            $opciones['GROUPBY'] = '';
            $opciones['INICIO'] = $inicio;
            $opciones['LIMIT'] = $limit;
            $proveedor = Proveedor::listarDatosJSON($opciones);
            $count = count($proveedor);
            for ($i = $inicio; $i < $count; $i ++) {
                $responce->rows[$i]['id'] = $proveedor[$i]['id'];
                $responce->rows[$i]['cell'] = array($proveedor[$i]['id'] , $proveedor[$i]['razon_social'] , $proveedor[$i]['domicilio'] , $proveedor[$i]['condicion_iva'] , $proveedor[$i]['cuit'] , $proveedor[$i]['tel'] , $proveedor[$i]['cel'] , $proveedor[$i]['email']);
            }
            break;
        case 'clientes':
            $opciones['SELECT']=' * FROM conta_clientes ';
            $opciones['WHERE']='';
            $opciones['ORDERBY']=$ordenar_por;
            $opciones['ORDERTYPE']=$ordenar_enforma;
            $opciones['GROUPBY']='';
            $opciones['INICIO']=$inicio;
            $opciones['LIMIT']= $limit;
            $cliente = Cliente::listarDatosJSON($opciones);
            $count = count($cliente);
            for ($i = $inicio; $i < $count; $i ++) {
                $responce->rows[$i]['id'] = $cliente[$i]['id'];
                $responce->rows[$i]['cell'] = array($cliente[$i]['id'] , $cliente[$i]['razon_social'] , $cliente[$i]['domicilio'] , $cliente[$i]['condicion_iva'] , $cliente[$i]['cuit'] , $cliente[$i]['tel'] , $cliente[$i]['cel'] , $cliente[$i]['email']);
            }
            break;
        case 'usuarios':
            if ($_GET['sub']) { //Me fijo si vine con sub
                switch ($sub) {
                    case 'lista':
			            $opciones['SELECT']=' * FROM conta_usuarios ';
			            $opciones['WHERE']='';
			            $opciones['ORDERBY']=$ordenar_por;
			            $opciones['ORDERTYPE']=$ordenar_enforma;
			            $opciones['GROUPBY']='';
			            $opciones['INICIO']=$inicio;
			            $opciones['LIMIT']= $limit;
			            $usuarios = Usuario::listarDatosJSON($opciones);
			            $count = count($usuarios);
			            for ($i = $inicio; $i < $count; $i ++) {
			                $responce->rows[$i]['id'] = $usuarios[$i]['id'];
			                $responce->rows[$i]['cell'] = array($usuarios[$i]['id'] , $usuarios[$i]['nombre'] , $usuarios[$i]['username'] , $usuarios[$i]['categoria'] , $usuarios[$i]['fechaRegistro'] , $usuarios[$i]['ultimaVisita'] , $usuarios[$i]['email'] , $usuarios[$i]['activo']);
			            }
			            break;
                }
            }
            break;
            
        case 'cuentas':
            if ($_GET['sub']) { //Me fijo si vine con sub
                switch ($sub) {
                    case 'lista':
                        $opciones['SELECT']=' conta_cuentas.*, conta_grupo_cuentas.grupo_cuenta as G_C FROM conta_cuentas LEFT JOIN conta_grupo_cuentas ON conta_cuentas.grupo_cuenta=conta_grupo_cuentas.id';
                        $opciones['WHERE']='';
                        $opciones['ORDERBY']='conta_cuentas.' . $ordenar_por;
                        $opciones['ORDERTYPE']=$ordenar_enforma;
                        $opciones['GROUPBY']='';
                        $opciones['INICIO']=$inicio;
                        $opciones['LIMIT']= $limit;
                        $cuentas = Cuenta::listarDatosJSON($opciones);
                        $count = count($cuentas);
                        for ($i = $inicio; $i < $count; $i ++) {
                            $responce->rows[$i]['id'] = $cuentas[$i]['id'];
                            $responce->rows[$i]['cell'] = array($cuentas[$i]['id'] , $cuentas[$i]['cuenta'] , $cuentas[$i]['G_C']);
                        }
                        break;
                }
            }
            break;
            
        case'grupocuentas':
            if ($_GET['sub']) { //Me fijo si vine con sub
                switch ($sub) {
                    case 'lista':
                        $opciones['SELECT']=' * FROM conta_grupo_cuentas ';
                        $opciones['WHERE']='';
                        $opciones['ORDERBY']='' . $ordenar_por;
                        $opciones['ORDERTYPE']=$ordenar_enforma;
                        $opciones['GROUPBY']='';
                        $opciones['INICIO']=$inicio;
                        $opciones['LIMIT']= $limit;
                        $grupocuentas = GrupoCuentas::listarDatosJSON($opciones);
                        $count = count($grupocuentas);
                        for ($i = $inicio; $i < $count; $i ++) {
                            $responce->rows[$i]['id'] = $grupocuentas[$i]['id'];
                            $responce->rows[$i]['cell'] = array($grupocuentas[$i]['id'] , $grupocuentas[$i]['grupo_cuenta']);
                        }
                        break;
                }
            }
            break;
            
        case 'horariosdisponibles':
            if ($_GET['sub']) { //Me fijo si vine con sub
                switch ($sub) {
                    case 'lista':
                        $profesional = $_GET['profesional'];
                        $cant_reg = 11;//Ingresos::cant_reg('conta_ingresos');
                        if ($cant_reg > 0 && $limit > 0) {
                            $total_pages = ceil($cant_reg / $limit);
                        } else {
                            $total_pages = 0;
                        }
                        if ($page > $total_pages)
                            $page = $total_pages;
                        $responce->page = $page;
                        $responce->total = $total_pages;
                        $responce->records = $cant_reg;
                        $opciones['SELECT']=' * FROM conta_disponibilidadhoraria';
                        $opciones['WHERE']="profesional = $profesional";
                        $opciones['ORDERBY']='profesional';
                        $opciones['ORDERTYPE']=$ordenar_enforma;
                        $opciones['GROUPBY']='';
                        $opciones['INICIO']=$inicio;
                        $opciones['LIMIT']= $limit;
                        $profesionales = Profesionales::listarHorariosDisponiblessJSON($opciones);
                        $count = count($profesionales);
                        for ($i = 0; $i < $count; $i ++) {
                            $responce->rows[$i]['id'] = $i;
                            $responce->rows[$i]['cell'] = array($profesionales[$i]['hora'], $profesionales[$i]['lunes'],$profesionales[$i]['martes'],$profesionales[$i]['miercoles'],$profesionales[$i]['jueves'],$profesionales[$i]['viernes'] );
                        }
                        break;
                }
            }
            break;
        case 'horariodia':
            if ($_GET['sub']) { //Me fijo si vine con sub
                switch ($sub) {
                    case 'lista':
                        $opciones['SELECT'] = "conta_horarios.*,";
                        $opciones['SELECT'] .= "GROUP_CONCAT(if(terapia = 'PSICOLOGIA', conta_pacientes.paciente, NULL )) AS 'PSICOLOGIA' ,";
                        $opciones['SELECT'] .= "GROUP_CONCAT(if(terapia = 'APOYO ESCOLAR', conta_pacientes.paciente, NULL )) AS 'APOYO ESCOLAR' ,";
                        $opciones['SELECT'] .= "GROUP_CONCAT(if(terapia = 'MUSICO TERAPIA', conta_pacientes.paciente, NULL )) AS 'MUSICO TERAPIA' ,";
                        $opciones['SELECT'] .= "GROUP_CONCAT(if(terapia = 'FONOAUDIOLOGIA', conta_pacientes.paciente, NULL )) AS 'FONOAUDIOLOGIA ' ";
                        $opciones['SELECT'] .= "FROM conta_horarios LEFT JOIN conta_pacientes ON conta_horarios.paciente = conta_pacientes.id";
                        $opciones['WHERE']= 'conta_horarios.dia = 1'; 
                        $opciones['GROUPBY']='hora';
                        $cant_reg = count(Turnos::listarHorariosJSON($opciones));
                        $opciones['INICIO']=$inicio;
                        $opciones['LIMIT']= $limit;
                        $turnos = Turnos::listarHorariosJSON($opciones);
                        $count = count($turnos);
                        if ($cant_reg > 0 && $limit > 0) {
                            $total_pages = ceil($cant_reg / $limit);
//                        echo $page;
                        } else {
                            $total_pages = 0;
                        }
                        if ($page > $total_pages)
                            $page = $total_pages;
                            $hasta = $count;
                        $responce->page = $page;
                        $responce->total = $total_pages;
                        $responce->records = $cant_reg;
                        for ($i = 0; $i < $hasta; $i ++) {
                            $responce->rows[$i]['id'] = $i;
                            $responce->rows[$i]['cell'] = array($turnos[$i]['hora'],$turnos[$i]['PSICOLOGIA'],$turnos[$i]['APOYO ESCOLAR'],$turnos[$i]['MUSICO TERAPIA'],$turnos[$i]['FONOAUDIOLOGIA']);//, $ingresos[$i]['noviembre']  , $ingresos[$i]['diciembre'], $ingresos[$i]['enero']  ,$ingresos[$i]['febrero'] , $ingresos[$i]['marzo'], $ingresos[$i]['abril'] ,$ingresos[$i]['Total_total']);// , $gastos[$i]['total_iva_inscripto'] , $gastos[$i]['total_iva_diferencial'] , $gastos[$i]['total_percepcion'] , $gastos[$i]['total_total']);
                        }
                        break;
                                        }
            }
            break;
            
        case 'exportar':
            if ($_GET['sub']) { //Me fijo si vine con sub
                switch ($sub) {
                    /* Es para el análisis de los datos */
                    case 'gastos':
//			            $exp = new Export2Excel('gastos.xls');
			            break;
//			            $opciones['SELECT'] = 'conta_gastos.*, conta_proveedores.razon_social FROM conta_gastos LEFT JOIN conta_proveedores ON conta_gastos.proveedor=conta_proveedores.id';
//			            $opciones['WHERE'] = '';
//			            IF ($ordenar_por == 'razon_social')
//			                $ordenar_por = 'proveedor';
//			            $opciones['ORDERBY'] = 'conta_gastos.' . $ordenar_por;
//			            $opciones['ORDERTYPE'] = $ordenar_enforma;
//			            $opciones['GROUPBY'] = '';
//			            $opciones['INICIO'] = 0;
//			            $opciones['LIMIT'] = 0;
			//            $gastos = Gasto::listarDatosJSON($opciones);
//			            $exp->exportWithQuery($opciones,"gastos.xls",'GASTOS'); 
                }
                break;
            }
            break;
            
        case 'sueldos':
            if ($_GET['sub']) { //Me fijo si vine con sub
                switch ($sub) {
                    case 'lista':
                        $opciones['SELECT']=" conta_sueldos.*, CONCAT_WS(', ' , conta_empleados.apellido, conta_empleados.nombre) AS NyA FROM conta_sueldos LEFT JOIN conta_empleados ON conta_sueldos.empleado=conta_empleados.id";
                        $opciones['WHERE']='';
                        $opciones['ORDERBY']='' . $ordenar_por;
                        $opciones['ORDERTYPE']=$ordenar_enforma;
                        $opciones['GROUPBY']='';
                        $cant_reg = count(Sueldo::listarDatosJSON($opciones));
                        $opciones['INICIO']=$inicio;
                        $opciones['LIMIT']= $limit;
                        $sueldos = Sueldo::listarDatosJSON($opciones);
                        $count = count($sueldos);
                        if ($cant_reg > 0 && $limit > 0) {
                            $total_pages = ceil($cant_reg / $limit);
                        } else {
                            $total_pages = 0;
                        }
                        if ($page > $total_pages)
                            $page = $total_pages;
                        if ($page == $total_pages)
                        {
                            $hasta = $count-1;
                        }else{
                            $hasta = $count;
                        }

                        $responce->page = $page;
                        $responce->total = $total_pages;
                        $responce->records = $cant_reg;
                        for ($i = 0; $i < $count; $i ++) {
                            $responce->rows[$i]['id'] = $sueldos[$i]['id'];
                            $responce->rows[$i]['cell'] = array(
                                $sueldos[$i]['id'],
                                $sueldos[$i]['NyA'],
                                $sueldos[$i]['periodo_pago'],
                                $sueldos[$i]['nro_recibo'],
                                $sueldos[$i]['remuneracion_gravada'],
                                $sueldos[$i]['remuneracion_nogravada'],
                                $sueldos[$i]['descuentos'],
                                $sueldos[$i]['total']);
//                             print_r($responce->rows[$i]['cell']);
                        }
   			            break;
                }
                break;
            }
            break;
    case 'controlar_nro_comprobante':
            $retorno = 0;
            $nro_factura = $_GET['nro_comprobante'];
            $proveedor = $_GET['proveedor'];
            $comprobante = $_GET['comprobante'];
            $tipo_comprobante = $_GET['tipoComprobante'];
            $rpta = Gasto::controlar_nro_factura($nro_factura, $proveedor, $comprobante, $tipo_comprobante) ;
            if ($rpta=='ok')
            {
                $retorno = true;
            }
            echo $retorno;
            return $retorno;
    }
    echo Zend_Json::encode($responce);
//echo $retorno;
}

if ($_POST){
    $tarea = $_POST["option"];
    switch ($tarea) {
        case 'controlar_nro_comprobante':
            $nro_factura = $_POST['nro_comprobante'];
            $proveedor = $_POST['proveedor'];
            $comprobante = $_POST['comprobante'];
            $tipo_comprobante = $_POST['tipoComprobante'];
            echo Gasto::controlar_nro_factura($nro_factura, $proveedor, $comprobante, $tipo_comprobante) ;
            return ;
    }
    
}

function condicion($tabla){
    /* Me fijo si vino searchField. Si no vino no busca nada, es decir = '' */
    if ($_GET['searchField']) {
        $operador = buscarOperador($_GET['searchOper']);
        if ($_GET['searchField']=='fecha_comprobante'){
            $where = $tabla . $_GET['searchField'] . $operador . "'" . implode( '/', array_reverse( explode( '/', $_GET['searchString'] ) ) ) . "'"; // get the direction
        }else{
            $where = $tabla . $_GET['searchField'] . $operador . $_GET['searchString']; // get the direction
        }
//        echo $where;
    } else {
        $where = '';
    }
    return $where;
    
}

function buscarOperador($op){
    switch ($op){
        case 'eq':
            $operador = '=';
            break;
        case 'ne':
            $operador = '<>';
            break;
        case 'lt':
            $operador = '<';
            break;
        case 'le':
            $operador = '<=';
            break;
        case 'gt':
            $operador = '>';
            break;
        case 'ge':
            $operador = '>=';
            break;
    }
    return $operador;
}
ob_end_flush();

?>
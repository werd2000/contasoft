<?php
ob_start();
require_once 'configuration.php';
require_once 'class/ExportToExcel.php';
require_once 'persistencia/Sql.php';
require_once 'persistencia/ContasoftPersistencia.php';
if ($_GET) {
    $tarea = $_GET["option"];
    switch ($tarea) {
        case 'iva_compras':
            //            $exp = new ExportToExcel();
            $exp = new ExportToExcel();
            $exp->setTitulo('Listado de Gastos');
            $exp->setEncabezadoPagina('&L&G&C&HPequeno Hogar S.R.L.');
            $exp->setPiePagina('&RPag &P de &N');
            $encCol = "Cuenta,Proveedor,Iva Prov.,Cuit Prov.,Fecha,Comprobante,Tipo,Nro,Imp. Grav,Imp. No Grav.,Iva,Iva Dif.,Percep.,Total";
            $encBD = "cuenta,razon_social,condicion_iva,cuit,fecha_comprobante,comprobante,tipo_comprobante,nro_comprobante,importe_gravado,importe_nogravado,iva_inscripto,iva_diferencial,percepcion,total";
            $exp->setEncBD($encBD);
            $exp->setEncabezados($encCol);
            $exp->setIfTotales(true);
            $sql = new Sql();
            $sql->addSelect('conta_gastos.*, conta_proveedores.razon_social, conta_proveedores.condicion_iva, conta_proveedores.cuit');
            $sql->addTable('conta_gastos LEFT JOIN conta_proveedores ON conta_gastos.proveedor=conta_proveedores.id');
            $sql->addOrder("conta_proveedores.razon_social ASC");
                $desde = "";
                $hasta = "";
                if (isset($_GET['fd'])){
                    $fecha_desde = implode('-', array_reverse(explode('/', $_GET["fd"])));
                    $desde = sprintf(" fecha_comprobante >= '%s'", $fecha_desde);
                }
                if (isset($_GET['fh'])){
                    $fecha_hasta = implode('-', array_reverse(explode('/', $_GET['fh'])));
                    $hasta = sprintf(" fecha_comprobante <= '%s'", $fecha_hasta);
                }
            
            if ($desde != "") {
                $sql->addWhere($desde);
            }
            if ($hasta != "") {
                $sql->addWhere($hasta);
            }
            
            $exp->setConsulta($sql);
            $exp->exportar('gastos');
            break;
        case 'honorarios':
            $exp = new ExportToExcel();
            $exp->setTitulo('Listado de Honorarios');
            $exp->setEncabezadoPagina('&L&G&C&HPequeno Hogar S.R.L.');
            $exp->setPiePagina('&RPag &P de &N');
            $encCol = "Id,Apellido,Nombre,IVA,CUIT,Fecha,Comprobante,Tipo,Nro,Imp. Grav,Imp. No Grav.,Iva,Iva Dif.,Percep.,Total";
            $encBD = "id,cuenta,profesional,fecha_comprobante,comprobante,tipo_comprobante,nro_comprobante,importe_gravado,importe_nogravado,iva_inscripto,iva_diferencial,percepcion,total,apellido,nombre, condicion_iva,cuit";
            $exp->setEncBD($encBD);
            $exp->setEncabezados($encCol);
            $exp->setIfTotales(true);
            $sql = new Sql();
            $sql->addSelect('conta_honorarios.*, conta_profesionales.apellido,conta_profesionales.nombre, conta_profesionales.condicion_iva, conta_profesionales.cuit');
            $sql->addTable('conta_honorarios LEFT JOIN conta_profesionales ON conta_honorarios.profesional=conta_profesionales.id');
            $sql->addOrder("conta_profesionales.apellido ASC");
                $desde = "";
                $hasta = "";
                if (isset($_GET['fd'])){
                    $fecha_desde = implode('-', array_reverse(explode('/', $_GET["fd"])));
                    $desde = sprintf(" fecha_comprobante >= '%s'", $fecha_desde);
                }
                if (isset($_GET['fh'])){
                    $fecha_hasta = implode('-', array_reverse(explode('/', $_GET['fh'])));
                    $hasta = sprintf(" fecha_comprobante <= '%s'", $fecha_hasta);
                }
            
            if ($desde != "") {
                $sql->addWhere($desde);
            }
            if ($hasta != "") {
                $sql->addWhere($hasta);
            }
            
            $exp->setConsulta($sql);
            $exp->exportar('honorarios');
            break;
      case 'sueldos':
            $exp = new ExportToExcel();
            $exp->setTitulo('Listado de Sueldos');
            $exp->setEncabezadoPagina('&L&G&C&HPequeno Hogar S.R.L.');
            $exp->setPiePagina('&RPag &P de &N');
            $encCol = "Id,Empleado,Periodo,Rem Grav.,Rem No Grav.,Descuentos,Total";
            $exp->setEncabezados($encCol);
            $encBD = "id,empleado,periodo_pago,nro_recibo,remuneracion_gravada,remuneracion_nogravada,descuentos,total";
            $exp->setEncBD($encBD);
            $exp->setIfTotales(true);
            $sql = new Sql();
            $sql->addSelect( "conta_sueldos.*, CONCAT_WS(', ' , conta_empleados.apellido, conta_empleados.nombre) AS apellido ");
            $sql->addTable(' conta_sueldos LEFT JOIN conta_empleados ON conta_sueldos.empleado=conta_empleados.id');
            $sql->addOrder("conta_empleados.apellido ASC");
                $desde = "";
                $hasta = "";
                if (isset($_GET['fd'])){
                    $fecha_desde = implode('-', array_reverse(explode('/', $_GET["fd"])));
                    $desde = sprintf(" periodo_pago >= '%s'", $fecha_desde);
                }
                if (isset($_GET['fh'])){
                    $fecha_hasta = implode('-', array_reverse(explode('/', $_GET['fh'])));
                    $hasta = sprintf(" periodo_pago <= '%s'", $fecha_hasta);
                }
            
            if ($desde != "") {
                $sql->addWhere($desde);
            }
            if ($hasta != "") {
                $sql->addWhere($hasta);
            }
            
            $exp->setConsulta($sql);
            $exp->exportar('sueldos');
            break;
    }
}
ob_end_flush();
?>
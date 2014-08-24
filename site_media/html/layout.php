<?php
echo $this->doctype();
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <?php
    echo $this->headMeta();
    echo $this->headTitle();
    echo $this->headScript();
    echo $this->headStyle();
    ?>
</head>
<body>
    <?php
    // view helper propio para crear el header del sitio
//    echo $this->siteHeader();
    ?>
 
    <div id="content">
    <?php
    // mostrando el contenido de la vista
    echo $this->layout()->content;
    ?>
   </div>
 <?php
// view helper propio para crear el footer
//echo $this->siteFooter();
?>
</body>
</html>

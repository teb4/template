<?php
    ini_set( 'display_startup_errors', 1 );
    ini_set( 'display_errors', 1 );
    error_reporting( -1 );
    error_reporting( E_ALL & ~E_STRICT );
    
    require_once( $_SERVER[ "DOCUMENT_ROOT" ] . "/TemplateWithList.class.php" );
    use template\TemplateWithList;
    
    $templateFileName = 'index.tpl';
    $templateMap = array(
        '%DATE_NOW%' => date( 'd.m.Y' ),
        '%TIME_NOW%' => date( 'H:i:s' ),
        '%SAMPLE_LIST%' => array(
            '%SAMPLE_LIST_ITEM%' => 'item',
            '%ITEM_MODE%' => 'mode',
            array(
                array( 'item' => 45.15, 'mode' =>  '' ),
                array( 'item' => 50.05, 'mode' =>  'selected' ),
                array( 'item' => 115.20, 'mode' =>  '' ),
            )
        )
    );
    
    print TemplateWithList::toHtml( $templateFileName, $templateMap );
?>
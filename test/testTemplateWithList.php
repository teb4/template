<?php
    require_once( $_SERVER[ "DOCUMENT_ROOT" ] . "/simpletest/autorun.php" );
    require_once( $_SERVER[ "DOCUMENT_ROOT" ] . "/simpletest/web_tester.php" );
    require_once( $_SERVER[ "DOCUMENT_ROOT" ] . "/TemplateWithList.class.php" );

    use template\TemplateWithList;
    
    class testTemplateWithList extends WebTestCase{
        function testSelf(){
	    $this->assertTrue( true );
        }
        function testToHtml(){
            $templateFileName = '';
            $templateMap = array(
                '%VALUE%' => '122',
                '%LIST%' => array(
                    '%LIST_ITEM%' => 'list_item',
                    array(
                        array( 'list_item' => '1' ),
                        array( 'list_item' => '2' ),
                        array( 'list_item' => '3' ),
                    )
                )
            );
            $template = 'test = %VALUE%, list =%LIST% %LIST_ITEM%%LIST%';
            $html = TemplateWithList::toHtml( $templateFileName, $templateMap, $template );
            $this->assertEqual( $html, 'test = 122, list = 1 2 3' );
        }
    }
?>
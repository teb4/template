<?php
    require_once( $_SERVER[ "DOCUMENT_ROOT" ] . "/simpletest/autorun.php" );
    require_once( $_SERVER[ "DOCUMENT_ROOT" ] . "/simpletest/web_tester.php" );

    class testDefault extends WebTestCase{
        function testSelf(){
	    $this->assertTrue( true );
        }
    }
?>
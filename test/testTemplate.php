<?php
    require_once( $_SERVER[ "DOCUMENT_ROOT" ] . "/simpletest/autorun.php" );
    require_once( $_SERVER[ "DOCUMENT_ROOT" ] . "/simpletest/web_tester.php" );
    require_once( $_SERVER[ "DOCUMENT_ROOT" ] . "/Template.class.php" );

    use template\Template;
    
    class testTemplate extends WebTestCase{
        function testSelf(){
	    $this->assertTrue( true );
        }
        function testToHtml_empty_file_name_exception(){
            $fire = false;
            $file = '';
            $map = array();
            try{
                Template::toHtml( $file, $map );                
            }
            catch( \template\EmptyFileNameException $e ){
                $fire = true;
            }
            $this->assertTrue( $fire );
        }
        function testToHtml_template_file_not_exist_exception(){
            $fire = false;
            $file = 'aaa.tpl';
            $map = array();
            try{
                Template::toHtml( $file, $map );                
            }
            catch( \template\TemplateFileNotExistException $e ){
                $fire = true;
            }
            $this->assertTrue( $fire );
        }
        function testToHtml_empty_map_exception(){
            $fire = false;
            $file = $_SERVER[ "DOCUMENT_ROOT" ] . '/real.tpl';
            $map = array();
            try{
                Template::toHtml( $file, $map );                
            }
            catch( \template\EmptyMapException $e ){
                $fire = true;
            }
            $this->assertTrue( $fire );
        }
        function testToHtml_template_from_var(){
            $file = '';
            $map = array(
                '%VALUE%' => 55
            );
            $template = 'template in var: %VALUE%';
            $this->assertEqual( Template::toHtml( $file, $map, $template ), 'template in var: 55' );
        }
        function testToHtml_template_from_var_2(){
            $file = '';
            $map = array(
                '%VALUE%' => 'ASDF'
            );
            $template = 'template in var: %VALUE%';
            $this->assertEqual( Template::toHtml( $file, $map, $template ), 'template in var: ASDF' );
        }        
        function testToHtml_template_from_var_3(){
            $file = '';
            $map = array(
                '%VALUE1%' => 15,
                '%VALUE2%' => 'ASDF'
            );
            $template = 'template in var: (a) %VALUE1%, (b) %VALUE2%';
            $this->assertEqual( Template::toHtml( $file, $map, $template ), 'template in var: (a) 15, (b) ASDF' );
        }                
        function testToHtml_template_from_var_4(){
            $file = '';
            $map = array(
                '%VALUE%' => 'ASDF'
            );
            $template = 'template in var: %VALUEx%';
            $this->assertEqual( Template::toHtml( $file, $map, $template ), 'template in var: %VALUEx%' );
        }                
        function testToHtml_template_from_file(){
            $file = $_SERVER[ "DOCUMENT_ROOT" ] . '/real.tpl';
            $map = array(
                '%VALUE1%' => 55
            );
            $this->assertEqual( Template::toHtml( $file, $map ), 'test template file: (a) var = 55' );
        }        
    }
?>
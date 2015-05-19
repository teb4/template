<?php
    namespace template;
    
    require_once( $_SERVER[ "DOCUMENT_ROOT" ] . "/Template.class.php" );
    
    class TemplateWithList extends Template{  
	public static function toHtml( $templateFileName, $templateMap, $template = "" ){
            $result = parent::getTemplate( $templateFileName, $template );
	    foreach( $templateMap as $key => $mapElement ){    
                $result = self::elementProcess( $mapElement, $key, $result );
	    }
	    return $result;
	}
        protected static function elementProcess( $mapElement, $key, $result ){
            if( is_array( $mapElement ) ){
                $result = self::listToHtml( $mapElement, $key, $result );
            }
            else{                
                $result = self::singleToHtml( $mapElement, $key, $result );
            }
            return $result;
        }
        protected static function listToHtml( array $mapElement, $key, $template ){
            $result = null;
	    $list = $mapElement[ 0 ];
	    $templateSection = self::getTemplateSection( $key, $template );
	    $keywordsList = self::getKeywordsList( $mapElement );
	    $dataList = self::getDataList( $list, $templateSection, $keywordsList );
	    foreach( $dataList as $element ){
		$result .= $element;
	    }
	    $result = str_replace( $key . $templateSection . $key, $result, $template );
	    return $result;
	}
	protected static function singleToHtml( $mapElement, $key, $template ){
	    $result = parent::toHtml( '', array( $key => $mapElement ), $template );
	    return $result;		
	}        
	protected static function getKeywordsList( $mapElement ){
	    $map = $mapElement;
	    unset( $map[ 0 ] );
	    foreach( $map as $key => $entry ){
		$keywordsList[] = array( $key, $entry );
	    }      
	    return $keywordsList;
	}
	protected static function getDataList( $list, $templateSection, $keywordsList ){
	    $dataList = array();
	    foreach( $list as $element ){
                $map = self::getMap( $element, $keywordsList );
                $dataList[] = parent::toHtml( '', $map, $templateSection );
	    }
	    return $dataList;
	}
	protected static function getMap( $element, $keywordsList ){
	    foreach( $keywordsList as $keywords ){
                $map[ $keywords[ 0 ] ] = $element[ $keywords[ 1 ] ];                
	    }
	    return $map;
	}
	protected static function getTemplateSection( $word, $mainTemplate ){
	    $l = strpos( $mainTemplate, $word ) + strlen( $word );
	    $r = strpos( substr( $mainTemplate, $l ), $word );
	    $result = substr( $mainTemplate, $l, $r );
	    return $result;
	}
    }
    class MapElementEmptyException extends \Exception{}
?>
<?php
    namespace template;
    
    class Template{
	public static function toHtml( $file, array $map, $template = "" ){
            $result = NULL;
            $template = self::getTemplate( $file, $template );
	    if( count( $map ) == 0 ){
                throw new EmptyMapException();
            }
            else{
                $result = self::mapping( $map, $template );
	    }
	    return $result;
	}
        protected static function mapping( array $map, $template ){
            foreach( $map as $name => $value ){
                $template = self::itemMapping( $name, $value, $template );
            }           
            return $template;
        }
        protected static function itemMapping( $name, $value, $template ){
            if( is_object( $value ) ){
                throw new TemplateValueIsObject( get_class( $value ) );
            }
            else{
                $template = str_replace( $name, $value, $template );		
            }            
            return $template;
        }
        protected static function getTemplate( $file, $template ){
            $result = NULL;
	    if( trim( $template ) == '' ){
                if( trim( $file ) == '' ){
                    throw new EmptyFileNameException();
                }
                else{
                    if( file_exists( $file ) ){
                        $result = file_get_contents( $file );
                    }
                    else{
                        throw new TemplateFileNotExistException( $file );
                    }
                }
	    } 
            else{
                $result = $template;
            }
            return $result;
        }
    }
    class EmptyFileNameException extends \Exception{}
    class TemplateFileNotExistException extends \Exception{}
    class EmptyMapException extends \Exception{}
    class TemplateValueIsObject extends \Exception{}
/* eof */
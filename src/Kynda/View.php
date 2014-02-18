<?php
/**
 * @version 1.0.0
 * @package Genus
 * @author Joe Hallenbeck
 * 
 */

namespace Kynda;

class View {
    
    protected $templates;
    
    protected $header;
    
    protected $body;
    
    protected $data;
    
    protected $headJavascript;
    
    protected $postJavascript;
    
    protected $styles;
    
    public function __construct( $app )
    {
        $this->templates = $app['view.options']['templates'];
        
        $this->data = array();
        
        $this->header = isset( $app['view.options']['header'] ) ? $app['view.options']['header'] : null;
        
        $this->body = isset( $app['view.options']['body'] ) ? $app['view.options']['body'] : null;
        
        $this->headJavascript = isset( $app['view.headJavascript'] ) ? $app['view.headJavascript'] : array();
        
        $this->postJavascript = isset( $app['view.postJavascript'] ) ? $app['view.postJavascript'] : array();
        
        $this->styles = isset( $app['view.styles'] ) ? $app['view.styles'] : array();
    }

    public function __set($index, $value) {
        $this->data[$index] = $value;
    }
    
    public function add( $obj )
    {
        if( is_array( $obj) || $obj instanceof \Iterator )
        {
            foreach( $obj as $key => $item )
            {
                $this->data[$key] = $item;
            }
            return;
        }                
    }
    
    public function addHeaderJavascript( $path ) {
        $this->headJavascript[] = $path;
    }
    
    public function addPostJavascript( $path ) {
        $this->postJavascript[] = $path;
    }
    
    public function addStyle( $path ) {
        $this->postJavascript[] = $path;
    }
    
    public function page( array $templates, $flush=false )
    {        
        $panel = '';
        foreach( $templates as $template )
        {
            $panel .= $this->show( $template );
        }
        
        $this->__set( 'panel', $panel );
                
        $head = isset( $this->header ) ? $this->show( $this->header, $flush ) : null;        
        $body = isset( $this->body ) ? $this->show( $this->body, $flush ) : null;
        
        return $head . $body;
    }
    
    public function show( $template, $flush=false ) {                       
        
        $path = __DIR__ . '/' . $this->templates . $template . '.php';
        
        if( file_exists( $path ) == false ) {
            throw new \RuntimeException( $template . ' Not Found. Using Path: ' . $path );            
        }
        
        foreach( $this->data as $key => $value ) {
            $$key = $value;
        }
        
        if( $flush )
        {
            include $path;
            return;
        }
        
        ob_start();
        
        include $path;
        
        return ob_get_clean();
    }
}

?>
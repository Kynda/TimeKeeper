<?php
/**
 * View processes html documents
 *
 * @copyright 2014 Joseph Hallenbeck
 */

namespace Kynda;

/**
 * View Processor of html documents
 */
class View {

    /**
     * Path to template directory
     *
     * @var string
     */
    protected $templates;

    /**
     * Path to header template
     *
     * @var string
     */
    protected $header;

    /**
     *  Path to body template
     *
     * @var string
     */
    protected $body;

    /**
     * Data to to be displayed in view
     *
     * @var array
     */
    protected $data;

    /**
     * Array of javascript file paths to display in document head
     *
     * @var array
     */
    protected $headJavascript;

    /**
     * Array of javascript file paths to display at end of document
     *
     * @var array
     */
    protected $postJavascript;

    /**
     * Array of css files to include in document.
     *
     * @var array
     */
    protected $styles;

    /**
     * Instantiates View from teh configuration options in $app
     *
     * @param Silex\Application $app
     */
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

    /**
     * Allows us to set data to be passed to the view by setting properties on
     * the View object.
     *
     * @param string $index Key to retrieve value
     * @param mixed $value Value to set
     */
    public function __set($index, $value) {
        $this->data[$index] = $value;
    }

    /**
     * Allows us to add an Iterator object  to the View.
     *
     * @param \Kynda\Iterator $obj
     * @return null
     */
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

    /**
     * Add a javascript file to the document head
     *
     * @param string $path
     */
    public function addHeaderJavascript( $path ) {
        $this->headJavascript[] = $path;
    }

    /**
     * Add a javascript file to end of the document.
     *
     * @param string $path
     */
    public function addPostJavascript( $path ) {
        $this->postJavascript[] = $path;
    }

    /**
     * Add a stylesheet to the document
     *
     * @param string $path
     */
    public function addStyle( $path ) {
        $this->postJavascript[] = $path;
    }

    /**
     * Generates and returns the document as a string.
     *
     * If flush is true the view is immediately generated and output. If flush
     * is false the view is generated in a buffer and returned as a string.
     *
     * @param array $templates
     * @param bool $flush
     * @return string
     */
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

    /**
     * Generates and returns a single template as a string
     *
     * If flush is true the view is immediately generated and output. If flush
     * is false the view is generated ina buffer and returned as string.
     *
     * @param string $template
     * @param bool $flush
     * @return string
     * @throws \RuntimeException
     */
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

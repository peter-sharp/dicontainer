<?php
namespace WeddingTools;

class DepInjectContainer {
    private $_deps = array();
    
    // store singleton contructors until needed
    private $_singletons = array();

    public function __get( $name ){
        

        if( $this->is_dep( $name ) && $this->is_closure( $this->_deps[$name] ) ) {
            return $this->_deps[$name]($this);
        }

        if( $this->is_singleton( $name ) ) {
            $this->_deps[$name] = $this->_singletons[$name]($this);
            unset( $this->_singletons[$name]);
        }

        return ( $this->is_dep( $name ) )? $this->_deps[$name] : FALSE;
    }

    public function __set( $name, $value ){
        $this->_deps[ $name ] = $value;
    }

    public function singleton( $name, $value ) {
        $this->_singletons[ $name ] = $value;
    }

    protected function is_closure( $dep ){
        return is_object( $dep ) && ($dep instanceof \Closure);
    }

    protected function is_singleton( $name ){
        return array_key_exists($name, $this->_singletons);
    }

    protected function is_dep( $name ){
        return array_key_exists($name, $this->_deps);
    }
}
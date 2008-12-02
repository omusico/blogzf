<?php
/**
 * Libreria para la configuracion del sistema
 *
 */
class Blogzf_Generic_Config
{
    private $_config_sys;
    private $_configName = '';
    public function load ($configName = 'config.default.ini')
    {
        $this->_configName = $configName;
        $this->_configSystem();
        $this->_dataBaseConfig();
    }
    /**
     * Configuración Base de Datos
     */
    private function _dataBaseConfig ()
    {
        $dataBase = Zend_Db::factory(
            $this->_config_sys->database->db->adapter, 
            $this->_config_sys->database->db->config->toArray());
        Zend_Db_Table::setDefaultAdapter( $dataBase );
        Zend_Registry::set( 'dbAdapter', $dataBase);
    }
    /**
     * Configuración del sistema que será leída del config.ini y mergeada con 
     * el archvo config.local.ini, este archivo no entra en el repositorio
     * 
     * @return config_sys 
     */
    private function _configSystem ()
    {
        $localConfig = new Zend_Config_Ini(self::LOCAL_FILE, NULL, true);
        $this->_config_sys = new Zend_Config_Ini('../config/' . $this->_configName, NULL, true);
        $this->_config_sys->merge( $localConfig )->setReadOnly();
        /**
         * Permite registra de forma pública las instancias de estas variables
         * de configuración
         */
        Zend_Registry::set( 'config_sys', $this->_config_sys );
        Zend_Registry::set( 'base_path', realpath('.') );
    }
}
<?php
/**
 * Libreria para la configuracion del sistema
 *
 */
class Blogzf_Generic_Config
{
    private $_config_sys;
    private $_configName = '';
    public $models;
    const LOCAL_FILE = '../config/config.local.ini';
    /**
     * Retorna el valor de alguna variable seteada en el archivo de configuracion
     * 
     * @param string $module Modulo al que pertenece la variable
     * @param string $section Secccion de la varialbe
     * @param string $variable Nombre de la variable
     * @return string Valor de la variable
     */
    public function getValue ($module = null, $section = null, $variable = null)
    {
        if ($module === null) {
            return $this->_config_sys->toArray();
        }
        if ($section === null) {
            if ($this->_config_sys->$module === null) {
                return null;
            }
            return $this->_config_sys->$module->toArray();
        }
        if ($variable === null) {
            if ($this->_config_sys->$module === null || $this->_config_sys->$module->$section === null) {
                return null;
            }
            return $this->_config_sys->$module->$section->toArray();
        }
        if ($this->_config_sys->$module === null || $this->_config_sys->$module->$section === null) {
            return null;
        }
        return $this->_config_sys->$module->$section->$variable;
    }
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
        Zend_Db_Table::setDefaultAdapter($dataBase);
        Zend_Registry::set('dbAdapter', $dataBase);
    }
    /** Devuelve una conexion a la base */
    public function _getDataBaseConfig ()
    {
        $configName = 'config.default.ini';
        $this->_configName = $configName;
        $this->_configSystem();
        return Zend_Db::factory($config->_config_sys->database->db->adapter, $config->_config_sys->database->db->config->toArray());
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
        $this->_config_sys->merge($localConfig)->setReadOnly();
        /**
         * Permite registra de forma pública las instancias de estas variables
         * de configuración
         */
        $registry = Zend_Registry::getInstance();
        $registry->set('config_sys', $this->_config_sys);
        $registry->set('base_path', realpath('.'));
    }
}
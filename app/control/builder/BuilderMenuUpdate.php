<?php

/**
 * BuilderMenuUpdate
 *
 * @version    1.0
 * @package    control
 * @subpackage admin
 * @author     Pablo Dall'Oglio
 * @copyright  Copyright (c) 2006 Adianti Solutions Ltd. (http://www.adianti.com.br)
 * @license    http://www.adianti.com.br/framework-license
 */
class BuilderMenuUpdate extends TPage
{
    /**
     * Constructor method
     */
    public function __construct()
    {
        parent::__construct();

        BuilderPermissionService::checkPermission();
    }

    /**
     * Ask for Update menu
     */
    public function onAskUpdate($param = null)
    {
        try 
        {
            BuilderPermissionService::checkPermission();
            
            $action = new TAction([$this, 'onUpdateMenu']);
            
            new TQuestion(_t('Update menu overwriting existing file?'), $action);
        }
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());
        }
    }

    /**
     * Update menu
     */
    public static function onUpdateMenu($param)
    {
        try 
        {
            BuilderPermissionService::checkPermission();
            
            $menus = BuilderPageService::getMenus();
            
            file_put_contents('top_menu.xml', base64_decode($menus->topMenu));
            file_put_contents('menu.xml', base64_decode($menus->leftMenu));
            file_put_contents('menu-public.xml', base64_decode($menus->publicMenu));
                        
            new TMessage('info', _t('Menu updated successfully'));
            TScript::create('setTimeout(function(){location.href = "index.php"}, 200);');
        } 
        catch (Exception $e) 
        {
            new TMessage('error', $e->getMessage());
        }
    }
}
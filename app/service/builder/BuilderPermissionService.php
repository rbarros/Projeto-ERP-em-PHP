<?php

class BuilderPermissionService{
    
    public static function checkPermission()
    {
        if (TSession::getValue('login') !== 'admin')
        {
            throw new Exception(_t('Permission denied'));
        }
    }
}
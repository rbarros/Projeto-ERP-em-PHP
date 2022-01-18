<?php

class BuilderTemplateParser
{
    /**
     * Parse template and replace basic system variables
     * @param $content raw template
     */
    public static function parse($content)
    {
        $ini = AdiantiApplicationConfig::get();
        $theme = $ini['general']['theme'];
        $has_left_menu = false;
        $has_top_menu = false;
        $top_menu_var = 'false';
        $top_menu = '';

        if ( (!isset($ini['general']['left_menu']) || $ini['general']['left_menu'] == '0') && (!isset($ini['general']['top_menu']) || $ini['general']['top_menu'] == '0') )
        {
            $content = str_replace(['<!--[IF-LEFT-MENU]-->', '<!--[/IF-LEFT-MENU]-->'], ['', ''], $content);
            $has_left_menu = true;
        }
        elseif (!isset($ini['general']['left_menu']) || $ini['general']['left_menu'] == '0')
        {
            $content = str_replace(['<!--[IF-LEFT-MENU]-->', '<!--[/IF-LEFT-MENU]-->'], ['<!--', '-->'], $content);
        }
        elseif(isset($ini['general']['left_menu']) && $ini['general']['left_menu'] == '1')
        {
            $content = str_replace(['<!--[IF-LEFT-MENU]-->', '<!--[/IF-LEFT-MENU]-->'], ['', ''], $content);
            $has_left_menu = true;
        }

        if (isset($ini['general']['top_menu']) && $ini['general']['top_menu'] == '1')
        {
            $content = str_replace(['<!--[IF-TOP-MENU]-->', '<!--[/IF-TOP-MENU]-->'], ['', ''], $content);
            $content = str_replace(['<!--[IF-NOT-TOP-MENU]-->', '<!--[/IF-NOT-TOP-MENU]-->'], ['/*', '*/'], $content);
            $has_top_menu = true;
            $top_menu_var = 'true';
        }
        else
        {
            $content = str_replace(['<!--[IF-TOP-MENU]-->', '<!--[/IF-TOP-MENU]-->'], ['<!--', '-->'], $content);
            $content = str_replace(['<!--[IF-NOT-TOP-MENU]-->', '<!--[/IF-NOT-TOP-MENU]-->'], ['', ''], $content);
        }

        if(!$has_left_menu)
        {
            $content = str_replace('{builder_top_menu}', 'top-menu-only', $content);
            $content = str_replace('{top_menu_only}', 'true', $content);
        }
        else
        {
            $content = str_replace('{builder_top_menu}', '', $content);
            $content = str_replace('{top_menu_only}', 'false', $content);
        }
        
        if ($has_top_menu && !TSession::getValue('logged') && isset($ini['general']['public_view']) && $ini['general']['public_view'] == '1')
        {
            $top_menu = BuilderMenuService::parse('menu-public.xml', 'top-'.$theme);
        }
        elseif($has_top_menu)
        {
            $top_menu = BuilderMenuService::parse('top_menu.xml', 'top-'.$theme);
        }

        $libraries_user = file_get_contents("app/templates/{$theme}/libraries_user.html");
        $libraries_builder = file_get_contents("app/templates/{$theme}/libraries_builder.html");
        $libraries_theme = file_get_contents("app/templates/{$theme}/libraries_theme.html");
        
        $content = str_replace('{LIBRARIES_USER}', $libraries_user, $content);
        $content = str_replace('{LIBRARIES_BUILDER}', $libraries_builder, $content);
        $content = str_replace('{LIBRARIES_THEME}', $libraries_theme, $content);
        $content = str_replace('{template}', $theme, $content);
        $content = str_replace('{TOP-MENU-BUILDER}', $top_menu, $content);
        $content = str_replace('{top_menu_var}', $top_menu_var, $content);
        $content = str_replace('{lang}', AdiantiCoreTranslator::getLanguage(), $content);
        $content = str_replace('{debug}', isset($ini['general']['debug']) ? $ini['general']['debug'] : '1', $content);
        $content = str_replace('{verify_messages_menu}', isset($ini['general']['verify_messages_menu']) ? $ini['general']['verify_messages_menu'] : 'false', $content);
        $content = str_replace('{verify_notifications_menu}', isset($ini['general']['verify_notifications_menu']) ? $ini['general']['verify_notifications_menu'] : 'false', $content);

        // Remove all comments
        $content = preg_replace('/<!--.*?-->/s', '', $content);
        
        return $content;
    }
}

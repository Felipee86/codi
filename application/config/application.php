<?php

return [
    /**
     * The setting of the enviroment that application run in.
     */
    'enviroment' => 'development',

    /**
     * If layout isn't set then take this information from here.
     */
    'layout'     => [
        'css' => [
            'main.css',
            'component.css'
        ]
    ],

    /**
     * The default mixed settings for application.
     */
    'default' => [

        /**
         * If title isn't set then take it from here.
         */
        'title'    => 'CoDI Test',

        /**
         * If application don't support the browser language then take it from here.
         */
        'language' => 'pl',
    ],

    /**
     * Settings for logs.
     */
    'logs' => [

        /**
         * Does the debug info should be written to logs. It is highlyrecommended to turn this option off in the
         * prodaction enviroment.
         */
        'debug' => false
    ],

    'doctype-header' => '<!DOCTYPE html>'
];

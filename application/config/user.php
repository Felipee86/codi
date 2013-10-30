<?php
return [
    /**
     * Settings for CoDI Autheticator.
     */
    'authenticator' => [

        /**
         * Keep user in cookies.
         */
        'cookie' => true,

        /**
         * Does the session id is required for session idetification.
         */
        'session_id_require' => true,

        /**
         * Does the user ip is required for session authentication.
         */
        'session_ip_require' => true,
    ]
];
/**
 * Admin JavaScript
 * 
 * @package Mida
 */

(function($) {
    'use strict';

    $(document).ready(function() {
        // Initialize your admin JavaScript here
        console.log('Mida admin loaded');

        // Example: Handle admin actions
        $('.mida-admin-action').on('click', function(e) {
            e.preventDefault();
            // Your code here
        });
    });

})(jQuery);

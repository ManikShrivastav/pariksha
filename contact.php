<?php 

define('WP_USE_THEMES', false);

function my_plugin_contact_section() {
    ?>
    <div class="contact-section" style="background-color: #f9f9f9; padding: 30px; border-radius: 8px; margin-top: 30px; box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);">
        <h2 style="margin-top: 0; font-size: 24px; color: #333;">Get in Touch</h2>
        <p style="margin-bottom: 20px; font-size: 16px; color: #666;">If you have any questions, feel free to contact us:</p>
        <ul style="list-style: none; padding-left: 0;">
            <li style="margin-bottom: 10px;"><strong>Email:</strong> <a href="mailto:vaikartangroup@gmail.com" style="text-decoration: none; color: #007bff;">vaikartangroup@gmail.com</a></li>
            <li style="margin-bottom: 10px;"><strong>Whatsapp:</strong> <span style="color: #007bff;">+9779746815627</span></li>
        </ul>
        <p style="margin-top: 20px; font-size: 16px; color: #666;">For more information, visit our website:</p>
        <a href="https://vaikartan.com" target="_blank" style="display: inline-block; background-color: #007bff; color: #fff; text-decoration: none; padding: 10px 20px; border-radius: 5px; transition: background-color 0.3s;">Visit Website</a>
    </div>
    <?php
}

my_plugin_contact_section();
?>
<?php
/**
 * Plugin Name: Blood Glucose Meter
 * Description: A WordPress plugin that adds a blood glucose level indicator using a shortcode.
 * Version: 1.0
 * Author: Abhishek_Ghosh
 */

// Enqueue scripts and styles
function blood_glucose_slider_enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_style('blood-glucose-slider-style', plugins_url('style.css', __FILE__));
    wp_enqueue_script('blood-glucose-slider-script', plugins_url('script.js', __FILE__), array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'blood_glucose_slider_enqueue_scripts');

// Shortcode function
function blood_glucose_slider_shortcode() {
    ob_start(); ?>
    <div class="blood-glucose-container">
        <h2>Blood Glucose Level Indicator</h2>
        <label for="glucose">Enter Blood Glucose Level (mg/dL):</label>
        <input type="number" id="glucose" min="0" max="600">
        <button onclick="updateNeedle()">Check</button>
        
        <div class="gradations">
            <span>0</span>
            <span>70</span>
            <span>140</span>
            <span>180</span>
            <span>600</span>
        </div>
        <div class="slider-container">
            <div class="needle" id="needle"></div>
        </div>
        <div class="status" id="status"></div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('blood_glucose_slider', 'blood_glucose_slider_shortcode');

// Create CSS file dynamically
function blood_glucose_slider_styles() {
    $css = "
    .blood-glucose-container {
        text-align: center;
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
    }
    .slider-container {
        position: relative;
        width: 80%;
        height: 20px;
        background: linear-gradient(to right, red, green, yellow, orange, red);
        margin: 20px auto;
        border-radius: 10px;
    }
    .needle {
        position: absolute;
        width: 10px;
        height: 50px;
        background: linear-gradient(to bottom, silver, gray, black);
        border-radius: 5px;
        top: -15px;
        left: 0;
        transition: left 0.5s ease;
    }
    .gradations {
        display: flex;
        justify-content: space-between;
        width: 80%;
        margin: 10px auto;
        font-size: 14px;
        color: #333;
    }
    .status {
        margin-top: 20px;
        font-size: 22px;
        font-weight: bold;
        color: black;
    }
    ";
    file_put_contents(plugin_dir_path(__FILE__) . 'style.css', $css);
}
add_action('init', 'blood_glucose_slider_styles');

// Create JavaScript file dynamically
function blood_glucose_slider_script() {
    $js = "
    function updateNeedle() {
        let glucose = parseInt(document.getElementById('glucose').value);
        if (isNaN(glucose) || glucose < 0 || glucose > 600) {
            alert('Please enter a valid glucose level between 0 and 600 mg/dL.');
            return;
        }
        let position = ((glucose - 0) / 600) * 100;
        document.getElementById('needle').style.left = position + '%';
        let statusText = '';
        let statusColor = 'black';
        if (glucose <= 70) {
            statusText = 'Dangerous (Low)';
            statusColor = 'red';
        } else if (glucose >= 180) {
            statusText = 'Increasingly Dangerous (High)';
            statusColor = 'red';
        } else if (glucose >= 141 && glucose <= 179) {
            statusText = 'Risky';
            statusColor = 'orange';
        } else {
            statusText = 'Safe';
            statusColor = 'green';
        }
        document.getElementById('status').textContent = statusText;
        document.getElementById('status').style.color = statusColor;
    }
    ";
    file_put_contents(plugin_dir_path(__FILE__) . 'script.js', $js);
}
add_action('init', 'blood_glucose_slider_script');

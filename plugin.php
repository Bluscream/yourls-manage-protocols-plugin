<?php
/*
Plugin Name: Manage Protocols
Plugin URI: https://github.com/yourls/yourls
Description: Add, view, toggle, and delete allowed URL protocols directly in the YOURLS administration interface.
Version: 1.1
Author: Bluscream, Antigravity.AI
Author URI: https://github.com/yourls/yourls
*/

// Hook into YOURLS allowed protocols filter
yourls_add_filter('yourls_allowed_protocols', 'manage_protocols_filter');
function manage_protocols_filter($protocols) {
    $custom = yourls_get_option('manage_protocols_custom', null);
    
    // First run: Initialize defaults into database option
    if ($custom === null) {
        $custom = manage_protocols_get_initial_defaults();
        yourls_update_option('manage_protocols_custom', $custom);
    }
    
    $allowed = array();
    if (is_array($custom)) {
        foreach ($custom as $proto => $data) {
            $enabled = is_array($data) ? $data['enabled'] : $data;
            if ($enabled) {
                $allowed[] = $proto;
            }
        }
    }
    return array_values(array_unique($allowed));
}

// Register settings page in admin panel
yourls_add_action('plugins_loaded', 'manage_protocols_init');
function manage_protocols_init() {
    yourls_register_plugin_page('manage_protocols', 'Manage Protocols', 'manage_protocols_display_page');
}

// Helper to get initial default protocols structure
function manage_protocols_get_initial_defaults() {
    $defaults = array(
        'http://', 'https://', 'ftp://', 'ftps://', 'mailto:', 'news:',
        'irc:', 'gopher:', 'nntp:', 'feed:', 'telnet:', 'mms:', 'rtsp:',
        'svn:', 'tel:', 'sms:', 'callto:'
    );
    $custom = array();
    foreach ($defaults as $def) {
        $custom[$def] = array('enabled' => true, 'is_default' => true);
    }
    return $custom;
}

// Display the settings page
function manage_protocols_display_page() {
    // Handle form submissions
    if (isset($_POST['action']) && $_POST['action'] == 'update_protocols') {
        // Check nonce for security
        yourls_verify_nonce('manage_protocols_settings');

        $custom = yourls_get_option('manage_protocols_custom', manage_protocols_get_initial_defaults());

        // 1. Reset to Defaults
        if (isset($_POST['reset_defaults'])) {
            $custom = manage_protocols_get_initial_defaults();
            yourls_update_option('manage_protocols_custom', $custom);
            echo '<div class="alert alert-success" style="padding: 10px; margin: 15px 0; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px;">Reset to defaults successfully!</div>';
        } else {
            // 2. Add new protocol
            if (!empty($_POST['new_protocol'])) {
                $new = trim($_POST['new_protocol']);
                // Ensure it ends with :// or : if appropriate
                if (strpos($new, ':') === false) {
                    $new .= '://';
                }
                $custom[$new] = array('enabled' => true, 'is_default' => false);
            }

            // 3. Toggle existing protocols
            if (isset($_POST['toggle_proto'])) {
                foreach ($_POST['toggle_proto'] as $proto => $val) {
                    if (isset($custom[$proto])) {
                        $custom[$proto]['enabled'] = ($val == '1');
                    }
                }
            }

            // 4. Delete existing protocols
            if (isset($_POST['delete_proto'])) {
                foreach ($_POST['delete_proto'] as $proto) {
                    unset($custom[$proto]);
                }
            }

            yourls_update_option('manage_protocols_custom', $custom);
            echo '<div class="alert alert-success" style="padding: 10px; margin: 15px 0; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 4px;">Settings saved successfully!</div>';
        }
    }

    $custom = yourls_get_option('manage_protocols_custom', manage_protocols_get_initial_defaults());
    $nonce = yourls_create_nonce('manage_protocols_settings');

    echo <<<HTML
    <div style="margin: 20px; max-width: 850px; font-family: sans-serif;">
        <h2>Manage Allowed Protocols</h2>
        <p>YOURLS filters shortened URLs to block unauthorized protocols. On this page, you can enable/disable default protocols or add your own custom schemes (like <code>vrchat://</code>, <code>steam://</code>, <code>magnet:</code>).</p>

        <form method="post">
            <input type="hidden" name="action" value="update_protocols" />
            <input type="hidden" name="nonce" value="{$nonce}" />

            <!-- Protocols Table -->
            <table class="tblTheme" cellpadding="5" cellspacing="0" style="width: 100%; border: 1px solid #ddd; margin-bottom: 20px; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8f9fa; border-bottom: 2px solid #ddd;">
                        <th style="text-align: left; padding: 10px;">Protocol</th>
                        <th style="text-align: left; padding: 10px; width: 120px;">Type</th>
                        <th style="text-align: center; padding: 10px; width: 100px;">Status</th>
                        <th style="text-align: center; padding: 10px; width: 180px;">Action</th>
                    </tr>
                </thead>
                <tbody>
HTML;

    if (empty($custom)) {
        echo '<tr><td colspan="4" style="padding: 15px; text-align: center; color: #777;">No protocols whitelisted. Everything is currently blocked!</td></tr>';
    } else {
        // Sort: defaults first, then custom
        uksort($custom, function($a, $b) use ($custom) {
            $a_def = isset($custom[$a]['is_default']) && $custom[$a]['is_default'];
            $b_def = isset($custom[$b]['is_default']) && $custom[$b]['is_default'];
            if ($a_def != $b_def) {
                return $a_def ? -1 : 1;
            }
            return strcmp($a, $b);
        });

        foreach ($custom as $proto => $data) {
            $enabled = is_array($data) ? $data['enabled'] : $data;
            $is_default = is_array($data) ? (isset($data['is_default']) && $data['is_default']) : true;

            $status_val = $enabled ? '1' : '0';
            $btn_text = $enabled ? 'Disable' : 'Enable';
            $toggle_to = $enabled ? '0' : '1';
            $proto_html = htmlspecialchars($proto);
            
            $bg_color = $enabled ? '#28a745' : '#6c757d';
            $status_text = $enabled ? 'Active' : 'Inactive';
            
            $type_text = $is_default ? 'Built-in' : 'Custom';
            $type_bg = $is_default ? '#e9ecef' : '#fff3cd';
            $type_color = $is_default ? '#495057' : '#856404';

            echo <<<HTML
            <tr style="border-bottom: 1px solid #ddd;">
                <td style="padding: 10px; font-weight: bold; font-family: monospace; font-size: 14px;">{$proto_html}</td>
                <td style="padding: 10px;">
                    <span style="padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; color: {$type_color}; background-color: {$type_bg};">
                        {$type_text}
                    </span>
                </td>
                <td style="padding: 10px; text-align: center;">
                    <span style="padding: 3px 8px; border-radius: 3px; font-size: 11px; font-weight: bold; color: white; background-color: {$bg_color};">
                        {$status_text}
                    </span>
                    <input type="hidden" name="toggle_proto[{$proto_html}]" value="1" />
                </td>
                <td style="padding: 10px; text-align: center;">
                    <button type="submit" name="toggle_proto[{$proto_html}]" value="{$toggle_to}" style="cursor: pointer; padding: 4px 8px; border: 1px solid #ccc; background: #fff; border-radius: 3px;">{$btn_text}</button>
                    <button type="submit" name="delete_proto[]" value="{$proto_html}" style="cursor: pointer; padding: 4px 8px; border: 1px solid #dc3545; background: #dc3545; color: #fff; border-radius: 3px; margin-left: 5px;" onclick="return confirm('Are you sure you want to delete this protocol?');">Delete</button>
                </td>
            </tr>
HTML;
        }
    }

    echo <<<HTML
                </tbody>
            </table>

            <!-- Add & Reset Controls -->
            <div style="display: flex; justify-content: space-between; align-items: center; background: #f8f9fa; padding: 15px; border-radius: 5px;">
                <div>
                    <h4 style="margin: 0 0 8px 0;">Add Custom Protocol</h4>
                    <input type="text" name="new_protocol" placeholder="e.g. steam://" style="padding: 6px 12px; width: 250px; border: 1px solid #ccc; border-radius: 4px; font-family: monospace;" />
                    <button type="submit" class="button" style="padding: 6px 15px; margin-left: 10px; cursor: pointer;">Add Protocol</button>
                </div>
                <div>
                    <button type="submit" name="reset_defaults" value="1" style="cursor: pointer; padding: 8px 16px; border: 1px solid #0056b3; background: #007bff; color: #fff; border-radius: 4px; font-weight: bold;" onclick="return confirm('Are you sure you want to reset all protocols to factory defaults? This will erase custom additions.');">Reset to Defaults</button>
                </div>
            </div>
        </form>
    </div>
HTML;
}

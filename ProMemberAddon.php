<?php
/**
 * Plugin Name: WP Pro Membership Addon
 * Plugin URI: https://www.wpdownloadmanager.com/download/wp-pro-membership/
 * Description: User Subscription & Membership Management Plugin for WordPress Download Manager
 * Version: 3.3.5
 * Author: WordPress Download Manager
 * Text Domain: wppromembership
 * Author URI: https://www.wpdownloadmanager.com/
 */

namespace ProMemberAddon;
if (!defined('ABSPATH')) exit;

use WP_Error;
use WP_User;
use WPDM\Email;
use function Sodium\add;

define('WPPM_TD', 'wppromembership');
define("PMA_BASE_URL", plugin_dir_url(__FILE__));
define("PMA_BASE_PATH", plugin_dir_path(__FILE__));

class ProMemberAddon
{
    function __construct()
    {
//        add_filter('registration_errors', [$this, 'registration_errors'], 10, 3);
//        add_filter('wpdm_user_profile_menu', [$this, 'public_profile'], 10, 3);
//        add_action('user_register', [$this, 'user_register']);
        add_action('show_user_profile', [$this, 'show_extra_profile_fields']);
        add_action('edit_user_profile', [$this, 'show_extra_profile_fields']);
        add_action('wpdm_edit_profile_form', [$this, 'show_extra_profile_fields']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_script']);

        add_action('wp_ajax_user_status', [$this, 'update_user_status']);
        add_filter('manage_users_columns', [$this, 'modify_user_table']);
        add_filter('wppm_free_sub_button_label', [$this, 'change_button_label']);
        add_filter('wppm_stripe_sub_button_label', [$this, 'change_button_label']);
        add_filter('manage_users_custom_column', [$this, 'modify_user_table_row'], 10, 3);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_custom_admin_style']);

        add_action('wp_ajax_nopriv_pma_signup', array($this, 'actionProcessSignup'));
//        add_filter('wp_authenticate_user', [$this, 'check_login'], 9, 1);
        add_filter('wpdm_email_templates', array($this, 'addEmailTemplates'));

        add_action('profile_update', [$this, 'update_profile'], 10, 2);
        add_action('wppm_before_signup_fields', [$this, 'before_signup_fields']);
        add_action('wppm_before_start_download', [$this, 'before_start_download']);
    }

    function change_button_label($label)
    {
        return 'Complete Signup';
    }

    public function addEmailTemplates($templates)
    {
        $templates['pending-subscription'] = [
            'label' => __("New Pending Membership Approval", WPPM_TEXTDOMAIN),
            'for' => 'admin',
            'plugin' => 'Pro Membership',
            'default' => array(
                'to_email' => get_option('admin_email'),
                'subject' => __("New Pending Membership Approval", WPPM_TEXTDOMAIN),
                'from_name' => get_option('blogname'),
                'from_email' => get_option('admin_email'),
                'message' => 'Hello, [#display_name#] just registered in your website. Click <a href="[#approve_url#]">here</a> to approve them, ignore this email otherwise.
                                <strong>User Info:</strong><br>
                                 Display Name => [#display_name#],
                                 Email => [#user_email#],
                                 Country => [#country#],
                                 State => [#state#],
                                 Bio => [#bio#]
                                 [#dj_type#]')];

        $templates['on-user-register'] = [
            'label' => __("New User Signup", WPPM_TEXTDOMAIN),
            'for' => 'user',
            'plugin' => 'Pro Membership',
            'default' => array(
                'subject' => __("Welcome to RiddimStream", WPPM_TEXTDOMAIN),
                'from_name' => get_option('blogname'),
                'from_email' => get_option('admin_email'),
                'message' => 'Dear [#display_name#],<br>Welcome to RiddimStream. 
                          <strong>Your account has not been approved yet.</strong><br/><strong>FREE account</strong> – review time is a min 0-56 hours <br/><strong>PRO account</strong> - review time is 0-24 hours.'

            )];

        $templates['on-user-approved'] = [
            'label' => __("User Approved", WPPM_TEXTDOMAIN),
            'for' => 'user',
            'plugin' => 'Pro Membership',
            'default' => array(
                'subject' => __("Approval Status Updated", WPPM_TEXTDOMAIN),
                'from_name' => get_option('blogname'),
                'from_email' => get_option('admin_email'),
                'message' => 'Dear [#display_name#],<br>
                      Congratulations, your pending account has been approved.')];

        $templates['on-user-disapproved'] = [
            'label' => __("User Disapproved", WPPM_TEXTDOMAIN),
            'for' => 'user',
            'plugin' => 'Pro Membership',
            'default' => array(
                'subject' => __("Approval Status Updated", WPPM_TEXTDOMAIN),
                'from_name' => get_option('blogname'),
                'from_email' => get_option('admin_email'),
                'message' => 'Dear [#display_name#],<br>
                      Sorry, your pending account approval has been declined.
                      If you thing this shouldn\'t  be the case contact [#admin_email#]')];

        return $templates;
    }

    function check_login($user)
    {
        if ($user instanceof WP_User) {
            $approved = get_user_meta($user->data->ID, 'user_status', true);

            if ($approved === 0 || $approved === '0') {
                $user = new WP_Error('authentication_failed', __('<strong>ERROR</strong>: User has not been approved. <br/>  FREE account – review time is a min 0-56 hours <br/>PRO account - review time is 0-24 hours.'));
            }
        }

        return $user;
    }

    function public_profile($menu_items)
    {
        if (!is_user_logged_in()) {
            return $menu_items;
        }
        $user = wp_get_current_user()->data;
        $menu_items = ['info' => ['icon' => 'fas fa-heart', 'name' => __("Profile", "download-manager"), 'content' => [$this, 'show_extra_profile_fields', $user]]] + $menu_items;
        return $menu_items;
    }

    function profile()
    {
        echo "OK";
    }


    function registration_errors($errors, $sanitized_user_login, $user_email)
    {

        if (empty($_POST['year_of_birth'])) {
            $errors->add('year_of_birth_error', __('<strong>ERROR</strong>: Please enter your year of birth.', WPPM_TD));
        }

        if (!empty($_POST['year_of_birth']) && intval($_POST['year_of_birth']) < 1900) {
            $errors->add('year_of_birth_error', __('<strong>ERROR</strong>: You must be born after 1900.', WPPM_TD));
        }

        return $errors;
    }

    function user_register($user_id)
    {
        update_user_meta($user_id, 'user_status', 0);
        $user = get_user_by('id', $user_id);
        $params = array(
            'subject' => sprintf(__("[%s] Subscription Reminder"), get_bloginfo('name'), WPPM_TEXTDOMAIN),
            'display_name' => $user->display_name,
            'approve_url' => admin_url('/users.php?s=' . $user_id),
            'user_email' => $user->user_email,
            'country' => get_user_meta($user_id, 'pma_country', true),
            'bio' => get_user_meta($user_id, 'pma_bio', true),

        ); //[#approve_url#]
        Email::send("pending-subscription", $params);

        $params = array(
            'display_name' => $user->display_name,
            'to_email' => $user->user_email,
        );
        Email::send("on-user-register", $params);

    }

    function sendEmailAfterUserRegistered($user_id, $data)
    {
        $user = get_user_by('id', $user_id);
        $params = array(
            'subject' => sprintf(__("[%s] Subscription Reminder"), get_bloginfo('name'), WPPM_TEXTDOMAIN),
            'display_name' => $user->display_name,
            'approve_url' => admin_url('/users.php?s=' . $user_id),
            'user_email' => $user->user_email,
            'country' => $data['pma_country'],
            'state' => $data['pma_state'],
            'bio' => $data['pma_bio'],

        ); //[#approve_url#]
        if (!$this->isCurator($user_id)) {
            $dj_types = get_user_meta($user->ID, 'pma_dj_type', true);
            if (!is_array($dj_types)) $dj_types = [];
            $params['dj_type'] = 'DJ type => ' . implode(',', $dj_types);
        } else {
            $params['dj_type'] = ' ';
        }
        Email::send("pending-subscription", $params);

        $params = array(
            'subject' => __("Welcome to RiddimStream", WPPM_TEXTDOMAIN),
            'display_name' => $user->display_name,
            'to_email' => $user->user_email,
            'from_name' => get_option('blogname'),
            'from_email' => get_option('admin_email'),
        );
        Email::send("on-user-register", $params);

    }

    function show_extra_profile_fields($user)
    {
        if (!isset($user) || $user === '') {
            $user = wp_get_current_user();
        }
        include __DIR__ . '/extra-profile-fields.php';
    }

    function enqueue_script()
    {
        wp_enqueue_media();
        wp_enqueue_style('pma-style', PMA_BASE_URL . 'style.css');
        wp_enqueue_script('pma-script', PMA_BASE_URL . 'script.js', array('jquery'));
        wp_localize_script("pma-script", "pma_localize", array(
            'ajax_url' => admin_url('admin-ajax.php'),
        ));
    }

    function modify_user_table($column)
    {
        $column['user_status'] = 'User Status';
        return $column;
    }

    function modify_user_table_row($val, $column_name, $user_id)
    {
        switch ($column_name) {
            case 'user_status' :
                $val = get_user_meta($user_id, 'user_status', true);
                $btnLabel = ($val === "" || $val === '0') ? 'Approve' : 'Disapprove';
                return '<button class="user_status btn btn-xs btn-primary" data-user-id="' . $user_id . '" type="button">' . $btnLabel . '</button>';
            default:
        }
        return $val;
    }

    function enqueue_custom_admin_style($hook)
    {
        if ('users.php' != $hook) {
            return;
        }
        wp_register_script('pma_admin', PMA_BASE_URL . 'admin-script.js', false, '1.0.0');
        wp_enqueue_script('pma_admin');
    }

    function update_user_status()
    {
        update_user_meta($_REQUEST['user_id'], 'user_status', $_REQUEST['user_status']);

        $user = get_user_by('id', $_REQUEST['user_id']);

        if (($_REQUEST['user_status'] === '0' || $_REQUEST['user_status'] === 0)) {
            $template_name = 'on-user-disapproved';
        } else {
            $template_name = 'on-user-approved';
        }

        $params = array(
            'subject' => __("Approval Status Updated", WPPM_TEXTDOMAIN),
            'display_name' => $user->display_name,
            'to_email' => $user->user_email,
            'from_name' => get_option('blogname'),
            'from_email' => get_option('admin_email'),
            'admin_email' => get_option('admin_email'),
        );
        Email::send($template_name, $params);

        wp_send_json($_REQUEST['user_status']);

    }

    public function actionProcessSignup()
    {
        $plan = get_post($_REQUEST['plan_id']);
        $signup_data = \wpdm_query_var(WPPM_SIGNUP_FORM_DATA_KEY);

        if (!empty($signup_data)) {
            //signup request
            $result = $this->trySignup($signup_data);

            if (is_int($result)) {
                //successful signup; now login
                $user = $this->login($signup_data['user_login'], $signup_data['user_pass']);
                $user_id = $result;
                if ($user_id) {
                    update_user_meta($user_id, 'user_status', 0);
                    update_user_meta($user_id, 'pma_social', $_REQUEST['pma_social']);
                    update_user_meta($user_id, 'pma_bio', $_REQUEST['pma_bio']);
                    update_user_meta($user_id, 'pma_country', $_REQUEST['pma_country']);
                    update_user_meta($user_id, 'pma_state', $_REQUEST['pma_state']);
                    update_user_meta($user_id, 'pma_found_via', $_REQUEST['pma_found_via']);
                    update_user_meta($user_id, 'pma_marketing_consent', $_REQUEST['pma_marketing_consent']);
                    update_user_meta($user_id, 'pma_tnc', $_REQUEST['pma_tnc']);
                    if (strtolower($plan->post_title) === 'curator') {
                        update_user_meta($user_id, 'pma_label_name', $_REQUEST['pma_label_name']);
                        update_user_meta($user_id, 'pma_city', $_REQUEST['pma_city']);
                        update_user_meta($user_id, 'pma_zip', $_REQUEST['pma_zip']);
                        update_user_meta($user_id, 'pma_street_address', $_REQUEST['pma_street_address']);
                        update_user_meta($user_id, 'pma_phone1', $_REQUEST['pma_phone1']);
                        update_user_meta($user_id, 'pma_phone2', $_REQUEST['pma_phone2']);
                        $user_id = wp_update_user(array('ID' => $user_id, 'display_name' => $_REQUEST['pma_label_name']));

                        if (isset($_FILES)) {
                            $this->handleFileUploads($_FILES, $user_id);
                        }
                    } else {
                        update_user_meta($user_id, 'pma_dj_type', $_REQUEST['pma_dj_type']);
                        update_user_meta($user_id, 'pma_dj_yoe', $_REQUEST['pma_dj_yoe']);
                        update_user_meta($user_id, 'pma_dj_name', $_REQUEST['pma_dj_name']);
                        // update display name
                        $user_id = wp_update_user(array('ID' => $user_id, 'display_name' => $_REQUEST['pma_dj_name']));

                    }
                    $this->sendEmailAfterUserRegistered($user_id, $_REQUEST);
                }
                wp_send_json($user);
            } else {
                //errors
                wp_send_json(['errors' => $result], 400);
            };
        }
    }

    function handleFileUploads($_FILE, $user_id)
    {
        if (!function_exists('wp_handle_upload')) {
            require_once(ABSPATH . 'wp-admin/includes/file.php');
        }

        $uploadedfile = $_FILES['pma_label_logo'];

        $upload_overrides = array(
            'test_form' => false
        );

        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
        if (isset($movefile['error'])) {
            wp_send_json(['errors' => ['File upload error.']], 400);
        } else {
            update_user_meta($user_id, 'pma_label_logo', $movefile['url']);
        }
    }

    /**
     *
     * @return true|array[errors]
     */
    private function validateSignupData($data)
    {
        // first_name , last_name, user_email, user_login, user_pass
        extract($data);

        $errors = [];

        // validate email
        if (!is_email($user_email)) {
            $errors[] = __("Invalid Email address.", "wppromembership");
        } elseif (email_exists($user_email)) {
            $errors[] = __("Email already exists.", "wppromembership");
        }

        //validate username
        if (empty($user_login)) {
            $errors[] = __("Invalid username.", "wppromembership");
        } elseif (username_exists($user_login)) {
            $errors[] = __("Username already exists.", "wppromembership");
        }

        //validate password
        if (empty($user_pass)) {
            $errors[] = __("Invalid password.", "wppromembership");
        }


        if (count($errors) > 0) {
            return $errors;
        }

        return true;
    }


    /**
     * first validate the data then try signup
     *
     * @return int(user_id)| array errors
     */
    private function trySignup($data)
    {
        extract($data);
        $errors = [];

        $validation = $this->validateSignupData($data);

        if ($validation === true) {
            // do signup
            return $this->signup($data);
        } else {
            // send validation errors
            $errors = $validation;
        }

        return $errors;
    }


    /**
     * use this function after successfull validation
     *
     * @return user_id|false
     */
    private function signup($data)
    {
        // user_email, user_login, user_pass, first_name , last_name,
        extract($data);

        //create new wp user
        $user_id = wp_create_user($user_login, $user_pass, $user_email);
        if ($user_id) {

            $first_name = isset($first_name) ? $first_name : '';
            $last_name = isset($last_name) ? $last_name : '';
            $display_name = ($first_name && $last_name) ? $first_name . " " . $last_name : $user_login;

            $user_data = array(
                'ID' => $user_id,
                'display_name' => $display_name,
                'first_name' => $first_name,
                'last_name' => $last_name
            );
            wp_update_user($user_data);

            $data = wp_parse_args($data, $user_data);

            // signup complete hook

            return $user_id;
        }

        return false;
    }


    /**
     *
     * @return WP_User|WP_Error
     */
    public function login($user_login, $user_pass)
    {
        $creds = array();
        $creds['user_login'] = $user_login;
        $creds['user_password'] = $user_pass;
        $creds['remember'] = "forever";
        return wp_signon($creds, false);
    }

    function isCurator($user_id)
    {
        $is_curator = false;
        $user_meta = get_userdata($user_id);
        $user_roles = $user_meta->roles;
        foreach ($user_roles as $role) {
            if (strpos($role, 'wppm') !== false) {
                $post_id = explode('_', $role);
                $post = get_post($post_id[1]);
                if (strtolower($post->post_title) === 'curator') {
                    $is_curator = true;
                }
            }
        }
        return $is_curator;
    }

    function update_profile($user_id, $old_user_data)
    {
        $is_curator = $this->isCurator($user_id);

        if ($user_id) {
            update_user_meta($user_id, 'pma_social', $_REQUEST['pma_social']);
            update_user_meta($user_id, 'pma_bio', $_REQUEST['pma_bio']);
            update_user_meta($user_id, 'pma_dj_type', $_REQUEST['pma_dj_type']);
            update_user_meta($user_id, 'pma_dj_yoe', $_REQUEST['pma_dj_yoe']);
            update_user_meta($user_id, 'pma_dj_name', $_REQUEST['pma_dj_name']);
            update_user_meta($user_id, 'pma_country', $_REQUEST['pma_country']);
            update_user_meta($user_id, 'pma_state', $_REQUEST['pma_state']);
            update_user_meta($user_id, 'pma_found_via', $_REQUEST['pma_found_via']);
            update_user_meta($user_id, 'pma_marketing_consent', $_REQUEST['pma_marketing_consent']);
        }
        if ($is_curator) {
            update_user_meta($user_id, 'pma_label_name', $_REQUEST['pma_label_name']);
            update_user_meta($user_id, 'pma_label_logo', $_REQUEST['pma_label_logo']);
            update_user_meta($user_id, 'pma_city', $_REQUEST['pma_city']);
            update_user_meta($user_id, 'pma_zip', $_REQUEST['pma_zip']);
            update_user_meta($user_id, 'pma_street_address', $_REQUEST['pma_street_address']);
            update_user_meta($user_id, 'pma_phone1', $_REQUEST['pma_phone1']);
            update_user_meta($user_id, 'pma_phone2', $_REQUEST['pma_phone2']);
        }
    }

    function before_signup_fields()
    {
        if (!is_user_logged_in()) {
            return;
        }
        $approved = get_user_meta(get_current_user_id(), 'user_status', true);
        if ($approved === 0 || $approved === "0")
            echo '<div class="alert alert-warning" role="alert">Complete the Registration by pressing the <strong>Complete Signup</strong> or relevant payment method button of your choice bellow.<br>
  <strong>Warning</strong> - Your account has not been approved yet.<br/><strong>FREE account</strong> – review time is a min 0-56 hours <br/><strong>PRO account</strong> - review time is 0-24 hours.
</div>';
    }

    function before_start_download()
    {
        if (!is_user_logged_in()) {
            return;
        }

        $user_status = get_user_meta(get_current_user_id(), 'user_status', true);
        if ($user_status !== "1" || $user_status !== 1) {
            die("Your account has not been approved.");
        }
    }

}

new ProMemberAddon();
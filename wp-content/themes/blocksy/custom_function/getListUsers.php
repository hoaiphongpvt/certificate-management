<?php 

    function handle_get_list_users() {
        global $wpdb;
        $user_table = $wpdb->prefix . 'USER_FORM';
        $certificate_table = $wpdb->prefix . 'CERTIFICATE';

        $page = isset($_GET['paged']) ? intval($_GET['paged']) : 1;
        $per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 5;
        $offset = ($page - 1) * $per_page;

        $total_users = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}user_form");

        $query = $wpdb->prepare("
            SELECT u.Id, u.Name, u.Phone, u.Email, u.CertificateId, u.isCertified, u.isDeleted, u.submittedAt, c.Name as certificate_name
            FROM $user_table u
            LEFT JOIN $certificate_table c ON u.CertificateId = c.Id"
        );

        $results = $wpdb->get_results($query);

        if ($results === false) {
            error_log('Error: ' . $wpdb->last_error);
            wp_send_json_error('Có lỗi xảy ra khi lấy danh sách người dùng.');
        }

        wp_send_json_success([
           'users' => $results,
            'total_users' => $total_users,
            'page' => $page,
            'per_page' => $per_page,
        ]);

        wp_die();
    }
?>
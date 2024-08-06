<?php 
function handle_get_users_by_name() {
    $name = isset($_POST['name']) ? $_POST['name'] : '';

    global $wpdb;
    $table_user = $wpdb->prefix . 'user_form';
    $table_certificate = $wpdb->prefix . 'certificate';

    $query = $wpdb->prepare(
        "SELECT @rownum := @rownum + 1 AS rownum, u.ID, u.Name, u.Phone, u.Email, u.CertificateId, u.isCertified, u.isDeleted, u.submittedAt, c.Name as certificate_name
        FROM $table_user u
        CROSS JOIN (SELECT @rownum := 0) AS r
        LEFT JOIN $table_certificate c ON u.CertificateId = c.Id
        WHERE u.Name LIKE %s",
        '%' . $wpdb->esc_like($name) . '%'
    );

    $results = $wpdb->get_results($query);

    if ($results === false) {
        error_log('Error: ' . $wpdb->last_error);
        wp_send_json_error('Có lỗi xảy ra khi lấy danh sách người dùng.');
    }

    wp_send_json_success($results);

    wp_die();
}
?>
<?php 
function handle_get_users_by_date_submit() {
    $date = isset($_POST['date']) ? sanitize_text_field($_POST['date']) : '';

    global $wpdb;
    $table_user = $wpdb->prefix . 'user_form';
    $table_certificate = $wpdb->prefix . 'certificate';

    $query = $wpdb->prepare(
        "SELECT @rownum := @rownum + 1 AS rownum, u.Id, u.Name, u.Phone, u.Email, u.CertificateId, u.isCertified, u.isDeleted, u.submittedAt, a.Name as certificate_name
        FROM $table_user u
        INNER JOIN $table_certificate a ON u.CertificateId = a.Id
        CROSS JOIN (SELECT @rownum := 0) r
        WHERE DATE(u.submittedAt) = %s",
        $date
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
<?php 
// Hàm hiển thị nội dung của trang quản lý người dùng
function my_custom_user_management_page_html() {
    // Kiểm tra quyền truy cập
    if (!current_user_can('manage_options')) {
        return;
    }

    // Define the number of items per page and the current page number
    $items_per_page = 5; // Number of items per page
    $current_page = isset($_GET['paged']) ? (int)$_GET['paged'] : 1; // Current page number, default is 1

    // Calculate the OFFSET
    $offset = ($current_page - 1) * $items_per_page;

    // Lấy danh sách người dùng từ bảng USER_FORM
    global $wpdb;
    $user_table = $wpdb->prefix . 'USER_FORM';
	$certificate_table = $wpdb->prefix . 'CERTIFICATE';

// 	$users = $wpdb->get_results("
// 	SELECT @rownum := @rownum + 1 AS rownum, u.Id, u.Name, u.Phone, u.Email, u.CertificateId, u.isCertified, u.isDeleted, u.submittedAt, c.Name as certificate_name
// 	FROM $user_table u
// 	LEFT JOIN $certificate_table c ON u.CertificateId = c.Id
//     CROSS JOIN (SELECT @rownum := 0) AS r
// ");

    $users = $wpdb->get_results("
        SELECT u.Id, u.Name, u.Phone, u.Email, u.CertificateId, u.isCertified, u.isDeleted, u.submittedAt, c.Name as certificate_name
        FROM $user_table u
        LEFT JOIN $certificate_table c ON u.CertificateId = c.Id
        LIMIT $items_per_page OFFSET $offset
    ");

    //Lấy tổng số users
    $total_users = $wpdb->get_var("SELECT COUNT(*) FROM $user_table");

    // Calculate the total number of pages
    $total_pages = ceil($total_users / $items_per_page);

    //Tính số thứ tự
    $stt_start = $offset + 1;

    // Hiển thị danh sách người dùng
    ?>
    <div class="wrap">
        <h1>Quản lý người dùng</h1>
        <table class="wp-list-table widefat fixed striped users">
            <thead>
                <tr>
                    <th>STT</th>
                    <th>Tên</th>
                    <th>SĐT</th>
                    <th>Email</th>
                    <th>Chứng chỉ</th>
                    <th>Ngày gửi</th>
                    <th>Trạng thái</th>
                    <th>Hoạt động</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $index => $user) { ?>
                <tr>
                    <td><?php echo esc_html($stt_start + $index); ?></td>
                    <td><?php echo esc_html($user->Name); ?></td>
                    <td><?php echo esc_html($user->Phone); ?></td>
                    <td><?php echo esc_html($user->Email); ?></td>
                    <td><?php echo esc_html($user->certificate_name); ?></td>
                    <td><?php echo (new DateTime($user->submittedAt))->format('d/m/Y'); ?></td>
                    <td><?php echo $user->isCertified ? 'Đã cấp' : 'Chưa cấp'; ?></td>
                    <td><?php echo $user->isDeleted ? 'Đã xóa' : 'Đang hoạt động'; ?></td>
                    <td>
                        <select name="action" class="user-action" data-id="<?php echo esc_html($user->CertificateId); ?>" data-user_id="<?php echo esc_html($user->Id); ?>" data-user_name="<?php echo esc_html($user->Name); ?>">
                            <option>Tùy chọn</option>
                            <option value="delete">Xóa</option>
                            <option value="certification">Cấp chứng chỉ</option>
                            <option value="cancelCertificate">Hủy chứng chỉ</option>
                        </select>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <nav style="text-align: center; margin-top: 30px">
            <ul class="pagination" style="display: flex; justify-content: center; gap: 10px;">
                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                <li><a style="text-decoration: none; padding: 8px; background-color: #DDD; border-radius: 4px; <?php if($i == $current_page) echo 'color: #00df98;'; else echo 'color: #000;'; ?>" href="?page=custom-user-management&paged=<?php echo $i; ?>"><?php echo $i; ?></a></li>
                <?php endfor; ?>
            </ul>
        </nav>

        <div id="certificationPopup" style="display:none;">
            <h2>Thông tin chứng chỉ</h2>
            <div id="certificateContent" style="width: 50%"></div>
            <button style="color: #212529; background-color: #f8f9fa; padding: 6px 12px; border: none; cursor: pointer; border-radius: .25rem" id="closePopup">Đóng</button>
            <button style="color: #fff; background-color: #007bff; border: none; padding: 6px 12px; cursor: pointer; border-radius: .25rem; margin-left: 6px" id="saveCertificate">Lưu</button>
        </div>

        <script>
            jQuery(document).ready(function($) {

                let currentUserId = null;

                $('.user-action').change(function() {
                    const action = $(this).val();
                    const certificateId = $(this).data('id');
                    currentUserId = $(this).data('user_id');
                    const userName = $(this).data('user_name')
                    const date = new Date().toLocaleDateString('en-GB')

                    if (action === 'certification') {
                        $.ajax({
                            url: ajaxurl, // URL cho yêu cầu AJAX
                            type: 'POST',
                            data: {
                                action: 'get_certificate', // Tên của action PHP để xử lý yêu cầu
                                id: certificateId // ID Chứng chỉ
                            },
                            success: function(response) {
                                
                                const replacedName = response.data.TemplateSVG.replace("{name}", userName);
                                const newResult = replacedName.replace("{date}", date)
                               
                                $('#certificateContent').html(newResult); // Hiển thị chứng chỉ trong popup
                                $('#certificationPopup').css('display', 'block'); // Hiển thị popup
                            },
                            error: function() {
                                alert('Có lỗi xảy ra khi lấy chứng chỉ.');
                            }
                        });
                    }

                    if (action === 'delete') {
                        if (confirm("Bạn có chắc chắn muốn xóa người dùng này không?")) {
                            $.ajax({
                            url: ajaxurl, // URL cho yêu cầu AJAX, WordPress cung cấp ajaxurl sẵn
                            type: 'POST',
                            data: {
                                action: 'delete_user', // Tên của action PHP để xử lý yêu cầu
                                id: currentUserId // ID Chứng chỉ
                            },
                            success: function(response) {
                                alert("Đã xóa thành công.")
                                location.reload();
                            },
                            error: function() {
                                alert('Có lỗi xảy ra khi xóa người dùng.');
                            }
                        });
                        } 
                    }

                    if (action === 'cancelCertificate') {
                        $.ajax({
                            url: ajaxurl, // URL cho yêu cầu AJAX, WordPress cung cấp ajaxurl sẵn
                            type: 'POST',
                            data: {
                                action: 'cancel_certificate', // Tên của action PHP để xử lý yêu cầu
                                id: currentUserId // ID Chứng chỉ
                            },
                            success: function(response) {
                                alert("Đã hủy chứng chỉ thành công.")
                                location.reload();
                            },
                            error: function() {
                                alert('Có lỗi xảy ra khi hủy chứng chỉ.');
                            }
                        });
                    }
                });

                $('#saveCertificate').click(function() {
                    const certificate = $('#certificateContent').html()

                    $.ajax({
                        url: ajaxurl, // URL cho yêu cầu AJAX, WordPress cung cấp ajaxurl sẵn
                        type: 'POST',
                        data: {
                            action: 'save_certificate', // Tên của action PHP để xử lý yêu cầu
                            certificate: certificate, // Chứng chỉ
                            userId: currentUserId
                        },
                        success: function(response) {
                            alert('Đã lưu chứng chỉ thành công.')
                            // Reload trang sau khi lưu thành công
                            location.reload();
                        },
                        error: function() {
                            alert('Có lỗi xảy ra khi lấy chứng chỉ.');
                        }
                    });
                })

                $('#closePopup').click(function() {
                    $('#certificationPopup').css('display', 'none'); // Ẩn chứng chỉ
                });
            });

        </script>
    </div>
    <?php
}

// Thêm action để xử lý yêu cầu AJAX
add_action('wp_ajax_get_certificate', 'handle_get_certificate');
add_action('wp_ajax_save_certificate', 'handle_save_certificate');
add_action('wp_ajax_delete_user', 'handle_delete_user');
add_action('wp_ajax_cancel_certificate', 'handle_cancel_certificate');
?>
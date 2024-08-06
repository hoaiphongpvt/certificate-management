<?php 
// Hàm hiển thị nội dung của trang quản lý người dùng
function my_custom_user_management_page_html() {
    // Kiểm tra quyền truy cập
    if (!current_user_can('manage_options')) {
        return;
    }

    ?>
    <div class="wrap">
        <h1>Quản lý người dùng</h1>
        <div style="margin: 20px 0;">
            <div style="display: flex; align-items: center; gap: 50px">
                <div style="display: flex; align-items: center; gap: 12px">
                    <label style="font-size: 18px">Tìm kiếm người dùng:</label>
                    <input style="width: 300px" type="text" id="txtUsername" placeholder="Nhập tên người dùng...">
                    <button style="color: #fff; background-color: #007bff; border: none; padding: 6px 12px; cursor: pointer; border-radius: .25rem;" id="search">Tìm</button>
                </div>
                <div style="display: flex; align-items: center; gap: 12px">
                    <label style="font-size: 18px">Lọc người dùng:</label>
                    <select id="filter">
                        <option value="">Lựa chọn phương thức</option>
                        <option value="date">Lọc theo ngày cấp chứng chỉ</option>
                        <option value="dateSubmit">Lọc theo ngày gửi</option>
                        <option value="certificate">Lọc theo chứng chỉ</option>
                    </select>
                    <div id="formSelectDate" style="display: none">
                        <label>Chọn ngày</label>
                        <input type="date" id="datePicker">
                    </div>
                    <div id="formSelectCertificate" style="display: none">
                        <label>Chọn chứng chỉ</label>
                        <select id="certificateType">
                            <option value="">Chọn</option>
                            <option value="1">Tình nguyện viên</option>
                            <option value="2">Nhà tài trợ</option>
                            <option value="3">Thành viên</option>
                        </select>
                    </div>
                    <button id="btnFilter" style="color: #fff; background-color: #007bff; border: none; padding: 6px 12px; cursor: pointer; border-radius: .25rem;">Lọc</button>
                </div>
            </div>
        </div>
        <div id="loading-container">
            <img src="images/loading.gif" alt="Loading..." class="loading-gif"/>
        </div>
        <div id="result"></div>
        <div id="pagination"></div>

        <div id="certificationPopup" style="display:none;">
            <h2>Thông tin chứng chỉ</h2>
            <div id="certificateContent" style="width: 50%"></div>
            <button style="color: #212529; background-color: #f8f9fa; padding: 6px 12px; border: none; cursor: pointer; border-radius: .25rem" id="closePopup">Đóng</button>
            <button style="color: #fff; background-color: #007bff; border: none; padding: 6px 12px; cursor: pointer; border-radius: .25rem; margin-left: 6px" id="saveCertificate">Lưu</button>
        </div>

        <style>
            #loading-container {
                width: 100%;
                height: 70vh;
                display: flex;
                justify-content: center;
                align-items: center;
                background-color: #f0f0f0;
            }

            .loading-gif {
                width: 25px;
                height: 25px;
            }

            #restore {
                width: 123px;
                height: 30px
            }
        </style>

        <script>
            jQuery(document).ready(function($) {

                let currentPage = 1;
                const perPage = 5;

                window.onload = function() {
                   getListUsers(currentPage);
                };

                function getListUsers(page) {
                    showLoading()
                    $.ajax({
                        url: ajaxurl, 
                        type: 'GET',
                        data: {
                            action: 'get_list_users', 
                            paged: page,
                            per_page: perPage
                        },
                        success: function(response) {
                            const users = response.data.users;
                            const totalUsers = response.data.total_users;
                            const per_page = response.data.per_page;
                            const totalPages = Math.ceil(totalUsers / perPage);
                            if (users.length !== 0) {
                                showResult(users)
                            }
                        },
                        error: function() {
                            alert('Có lỗi xảy ra khi lấy danh sách người dùng.');
                        },
                        complete: function() {
                            hideLoading();
                        }
                    });
                }

                function showResult(users) {
                    let tableHtml = '<table class="wp-list-table widefat fixed striped users" style="margin-top: 10px"><tr><th>STT</th><th>Họ tên</th><th>Email</th><th>SĐT</th><th>Chứng chỉ</th><th>Ngày gửi</th><th>Trạng thái</th><th>Hoạt động</th><th>Hành động</th></tr>';
                    users.forEach((user, index) => {
                        //const stt = (currentPage - 1) * perPage + index + 1; // Calculate STT
                        tableHtml += `<tr>
                                        <td>${index + 1}</td>
                                        <td>${user.Name}</td>
                                        <td>${user.Email}</td>
                                        <td>${user.Phone}</td>
                                        <td>${user.certificate_name}</td>
                                        <td>${formatDate(user.submittedAt)}</td>
                                        <td>${user.isCertified === "0" ? "Chưa cấp" : "Đã cấp"}</td>
                                        <td>${user.isDeleted === "0" ? "Đang hoạt động" : "Đã xóa"}</td>
                                        <td>
                                            ${user.isDeleted === "0" ? `
                                                <select name="action" class="user-action" data-id="${user.CertificateId}" data-user_id="${user.Id}" data-user_name="${user.Name}">
                                                    <option>Tùy chọn</option>
                                                    <option value="delete">Xóa</option>
                                                    ${user.isCertified !== "0" ? `<option value="cancelCertificate">Hủy chứng chỉ</option>` : `<option value="certification">Cấp chứng chỉ</option>`}
                                                </select>` : 
                                                `<button id="restore" data-user_id="${user.Id}">Khôi phục</button>`
                                            }
                                        </td>
                                    </tr>`;
                    });

                    tableHtml += '</table>';
                    $('#result').html(tableHtml);
                }
            

                //Tra cứu thông tin
                $('#search').click(function() {
                    const name = $('#txtUsername').val()
                    if (!name) {
                        alert('Vui lòng nhập tên người dùng!')
                        return
                    }
                    showLoading()
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'get_users_by_name',
                            name
                        },
                        success: function(response) {
                            $('#txtUsername').val('')
                            const users = response.data;
                            if (users.length !== 0) {
                                showResult(users)
                            } else {
                                $('#result').html('<p>Không tồn tại người dùng với tên này</p>');
                            }
                        },
                        error: function() {
                            alert('Có lỗi xảy ra khi lấy danh sách người dùng.');
                        },
                        complete: function() {
                            hideLoading()
                        }
                    });
                })

                $('#filter').change(function() {
                    const action = $(this).val();
                    if (action === "date" || action === "dateSubmit") {
                        $("#formSelectDate").css('display', 'block');
                    } else {
                        $("#formSelectDate").css('display', 'none');
                    }

                    if (action === "certificate") {
                        $('#formSelectCertificate').css('display', 'block');
                    } else {
                        $('#formSelectCertificate').css('display', 'none');
                    }
                })

                $("#btnFilter").click(function() {
                    const method = $("#filter").val()
                    const date = $("#datePicker").val()
                    const certificateType = $('#certificateType').val()

                    if (!method) {
                        alert("Vui lòng chọn phương thức cần lọc!")
                        return
                    }

                    if (method === "date" && !date) {
                        alert("Vui lòng chọn ngày!")
                        return
                    }

                    if (method === "certificate" && !certificateType) {
                        alert("Vui lòng chọn loại chứng chỉ!")
                        return
                    }

                    if (method === "dateSubmit" && !date) {
                        alert("Vui lòng chọn ngày gửi!")
                        return
                    }

                    if (method === "dateSubmit" && date) {
                        showLoading()
                        $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'get_users_by_date_submit',
                            date: date
                        },
                        success: function(response) {
                            const users = response.data;
                            if (users.length !== 0) {
                                showResult(users)
                            } else {
                                $('#result').html('<p>Không có người dùng nộp yêu cầu trong ngày này.</p>');
                            }

                        },
                        error: function() {
                            alert('Có lỗi xảy ra khi lấy danh sách người dùng.');
                        },
                        complete: function() {
                            hideLoading()
                        }
                        });
                    }

                    if (method === "date" && date) {
                        showLoading()
                        $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'get_users_by_date',
                            date: date
                        },
                        success: function(response) {
                            const users = response.data;
                            if (users.length !== 0) {
                                showResult(users)
                            } else {
                                $('#result').html('<p>Không có người dùng được cấp chứng chỉ trong ngày này.</p>');
                            }
                            

                        },
                        error: function() {
                            alert('Có lỗi xảy ra khi lấy danh sách người dùng.');
                        },
                        complete: function() {
                            hideLoading()
                        }
                        });
                    }

                    if (method === "certificate" && certificateType) {
                        showLoading()
                        $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'get_users_by_certificate',
                            idCertificate: certificateType
                        },
                        success: function(response) {
                            const users = response.data;
                            if (users.length !== 0) {
                                showResult(users)
                            } else {
                                $('#result').html('<p>Không tồn tại người dùng với chứng chỉ này</p>');
                            }
                        },
                        error: function() {
                            alert('Có lỗi xảy ra khi lấy danh sách người dùng.');
                        },
                        complete: function() {
                            hideLoading()
                        }
                        });
                    }
                })
                
                let currentUserId = null;
                $('#result').on('change', '.user-action', function() {
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
                        if (confirm("Bạn có chắc chắn muốn hủy chứng chỉ của người dùng này không?")) {
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
                    }
                });

                $('#result').on('click', '#restore', function() {
                    const userId = $(this).data('user_id');
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'restore_user',
                            userId
                        },
                        success: function(response) {
                            alert('Đã khôi phục người dùng thành công.')
                            location.reload();
                        },
                        error: function() {
                            alert('Có lỗi xảy ra khi khôi phục người dùng.');
                        }
                    });
                })

                $('#saveCertificate').click(function() {
                    const certificate = $('#certificateContent').html()

                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'save_certificate',
                            certificate: certificate,
                            userId: currentUserId
                        },
                        success: function(response) {
                            alert('Đã lưu chứng chỉ thành công.')
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

                function showLoading() {
                    $('#result').hide()
                    $('#loading-container').show();
                }

                function hideLoading() {
                    $('#loading-container').hide();
                    $('#result').show()
                }
            });

            function formatDate(dateString) {
                if (dateString === "0000-00-00 00:00:00") return "-"
                const date = new Date(dateString);
                const day = String(date.getDate()).padStart(2, '0');
                const month = String(date.getMonth() + 1).padStart(2, '0'); // Tháng bắt đầu từ 0
                const year = date.getFullYear();
                return `${day}/${month}/${year}`;
            }
            
        </script>
    </div>
    <?php
}

// Thêm action để xử lý yêu cầu AJAX
//Quản lý người dùng
add_action('wp_ajax_get_list_users', 'handle_get_list_users');
add_action('wp_ajax_get_certificate', 'handle_get_certificate');
add_action('wp_ajax_save_certificate', 'handle_save_certificate');
add_action('wp_ajax_delete_user', 'handle_delete_user');
add_action('wp_ajax_restore_user', 'handle_restore_user');
add_action('wp_ajax_cancel_certificate', 'handle_cancel_certificate');
//Tra cứu
add_action('wp_ajax_get_users_by_name', 'handle_get_users_by_name');
add_action('wp_ajax_get_users_by_certificate', 'handle_get_users_by_certificate');
add_action('wp_ajax_get_users_by_date', 'handle_get_users_by_date');
add_action('wp_ajax_get_users_by_date_submit', 'handle_get_users_by_date_submit');
?>
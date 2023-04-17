<?php
/**
 * Plugin Name: Câu đố
 * Plugin URI: https://yourwebsite.com
 * Description: Plugin để tạo và quản lý các câu đố.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * License: GPL2
 */



// Thêm ô nhập liệu vào trang đăng bài mới
function cau_do_add_meta_box() {
    add_meta_box('cau_do_meta_box','Đáp án câu đố','cau_do_render_meta_box','post');
}
add_action( 'add_meta_boxes', 'cau_do_add_meta_box' );

// Hiển thị ô nhập liệu
function cau_do_render_meta_box( $post ) {
    echo '<label for="cau_do_dap_an">Đáp án:</label>';
    echo '<input type="text" id="cau_do_dap_an" name="cau_do_dap_an" value="' . esc_attr( $value ) . '" />';
}

// Lưu trữ dữ liệu nhập liệu
function cau_do_save_meta_box_data( $post_id ) {
        $cau_do_dap_an = sanitize_text_field( $_POST['cau_do_dap_an'] );
        update_post_meta( $post_id, 'cau_do_dap_an', $cau_do_dap_an );
}
add_action( 'save_post', 'cau_do_save_meta_box_data' );

function add_answer_button( $content ) {
    global $post;
    $url = get_permalink( $post->ID );
    $button_html = '<div><a href="' . $url . '?dap-an=true" class="button">Đáp án</a></div>';
    $content .= $button_html;
    return $content;
}
add_filter( 'the_content', 'add_answer_button' );

// Hiển thị phần đáp án của câu đố
function cau_do_get_answer() {
    $answer = '';

    if ( isset( $_GET['dap-an'] ) && $_GET['dap-an'] == 'true' ) {
        $value = get_post_meta( get_the_ID(), 'cau_do_dap_an', true );
        if ( $value ) {
            $answer = '<div class="cau-do-dap-an"><strong>Đáp án:</strong> ' . esc_html( $value ) . '</div>';
        }
    }

    return $answer;
}

// Hiển thị nội dung bài post
function cau_do_show_content( $content ) {
    if ( is_singular( 'post' ) ) {
        $answer = cau_do_get_answer();
        $content .= $answer;
    }
    return $content;
}
add_filter( 'the_content', 'cau_do_show_content' );

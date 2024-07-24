<?php


include_once TEMPLATEPATH.'/login_ui.php';//后台美化
include_once TEMPLATEPATH.'/customize.php';//自定义设置



//wordpress优化开始

//隐藏前端的wordpress版本号.
remove_action('wp_head', 'wp_generator');
function remove_wp_version_strings($src) {
    global $wp_version;
    $parsed_url = parse_url($src, PHP_URL_QUERY);
    if ($parsed_url) {
        parse_str($parsed_url, $query);
        if (!empty($query['ver']) && $query['ver'] === $wp_version) {
            $src = remove_query_arg('ver', $src);
        }
    }
    return $src;
}
add_filter('script_loader_src', 'remove_wp_version_strings');
add_filter('style_loader_src', 'remove_wp_version_strings');

//移除wordpress后台logo,创作,版本
function remove_wp_admin_bar_logo_and_footer() {
    global $wp_admin_bar;
    $wp_admin_bar->remove_menu('wp-logo');
    add_filter('admin_footer_text', '__return_empty_string', 9999);
    add_filter('update_footer', '__return_empty_string', 9999);
}
add_action('wp_before_admin_bar_render', 'remove_wp_admin_bar_logo_and_footer', 0);


//防止网页被iframe调用
header('X-Frame-Options:Deny');

//彻底屏蔽 XML-RPC，防止 xmlrpc.php 被扫描，也不需要用客户端发文章
add_filter('xmlrpc_enabled','__return_false');
add_filter('xmlrpc_methods', '__return_empty_array');

// 禁用文章修订版本
add_filter( 'wp_revisions_to_keep', 'disable_post_revisions', 10, 2 );

function disable_post_revisions( $num, $post ) {
    return 0; // 这里0表示不保留任何修订版本
}

// 禁用自动保存
add_action( 'admin_print_scripts', function() {
    wp_deregister_script('autosave');
});


//禁止pingback，防止垃圾评论
add_filter('xmlrpc_methods',function($methods){
	$methods['pingback.ping'] = '__return_false';
	$methods['pingback.extensions.getPingbacks'] = '__return_false';
	return $methods;
});

//禁止pingbacks,enclosures,trackbacks，防止垃圾评论
remove_action('do_pings','do_all_pings', 10 );

//去掉 _encloseme 和 do_ping 操作，防止垃圾评论
remove_action('publish_post','_publish_post_hook',5);

//禁止Emoji转换为图片存储
remove_action('admin_print_scripts','print_emoji_detection_script');
remove_action('admin_print_styles','print_emoji_styles');
remove_action('wp_head','print_emoji_detection_script',7);
remove_action('wp_print_styles','print_emoji_styles');
remove_action('embed_head','print_emoji_detection_script');
remove_filter('the_content_feed','wp_staticize_emoji');
remove_filter('comment_text_rss','wp_staticize_emoji');
remove_filter('wp_mail','wp_staticize_emoji_for_email');
remove_filter('the_content','capital_P_dangit');
remove_filter('the_title','capital_P_dangit');
remove_filter('comment_text','capital_P_dangit');
add_filter('emoji_svg_url','__return_false');

//禁止后台加载Google字体
add_action('admin_print_styles', function(){
	wp_deregister_style('wp-editor-font');
	wp_register_style('wp-editor-font', '');
});

//禁止字符转码，加快页面显示，中文博客不需要它来处理
add_filter('run_wptexturize','__return_false');

//禁止代码标点转换
remove_filter('the_content','wptexturize');

//禁止Feed，防止RSS采集
function disable_feed() {
	wp_die(__('<h1>本站不提供Feed，浏览本站请访问本站<a href="'.get_bloginfo('url').'">首页</a>！</h1>'));
}
add_action('do_feed','disable_feed',1);
add_action('do_feed_rdf','disable_feed',1);
add_action('do_feed_rss','disable_feed',1);
add_action('do_feed_rss2','disable_feed',1);
add_action('do_feed_atom','disable_feed',1);

// 移除头部 wp-json 标签和 HTTP header 中的 link
remove_action('wp_head','rest_output_link_wp_head',10);
remove_action('template_redirect','rest_output_link_header',11);

//移除不必要的存档页面
function remove_wp_archives(){
    if( is_attachment() || is_feed() || is_date() || is_author() ) {
        global $wp_query;
        $wp_query->set_404();
    }
  }
add_action('template_redirect','remove_wp_archives');


//禁止加载古登堡样式
function remove_block_library_css(){
    wp_dequeue_style('wp-block-library');
    wp_deregister_style('global-styles');
    wp_dequeue_style('global-styles');
    wp_dequeue_style('wp-block-library-theme');
    wp_dequeue_style('wc-block-style');
    wp_dequeue_style('wc-blocks-vendors-style');
    wp_dequeue_style('wc-blocks-style');
    wp_dequeue_style('bp-member-block');
    wp_dequeue_style('bp-members-block');
    wp_dequeue_style('classic-theme-styles');
}
add_action('wp_enqueue_scripts','remove_block_library_css',100);

//移除wordpress冗余
remove_action('template_redirect','wp_shortlink_header',11,0);
remove_action('wp_head','feed_links_extra',3);
remove_action('wp_head','feed_links',2);
remove_action('wp_head','rsd_link');
remove_action('wp_head','wlwmanifest_link');
remove_action('wp_head','index_rel_link');
remove_action('wp_head','parent_post_rel_link',10,0);
remove_action('wp_head','start_post_rel_link',10,0);
remove_action('wp_head','adjacent_posts_rel_link',10,0);
remove_action('wp_head','wp_resource_hints',2);
remove_action('wp_head','wp_shortlink_wp_head',10,0);
remove_filter('rest_pre_serve_request','_oembed_rest_pre_serve_request',10,4);
remove_filter('oembed_dataparse','wp_filter_oembed_result',10);
remove_filter('oembed_response_data','get_oembed_response_data_rich',10,4);
remove_action('wp_head','wp_oembed_add_discovery_links');
remove_action('wp_head','wp_oembed_add_host_js');

//禁止每6个月的管理员邮箱验证
add_filter('admin_email_check_interval','__return_false');

//移除后台隐私相关页面.
add_action('admin_menu',function(){
	remove_submenu_page('options-general.php','options-privacy.php');
	remove_submenu_page('tools.php','export-personal-data.php');
	remove_submenu_page('tools.php','erase-personal-data.php');
},11);
add_action('admin_init',function(){
	remove_action('admin_init',['WP_Privacy_Policy_Content','text_change_check'],100);
	remove_action('edit_form_after_title',['WP_Privacy_Policy_Content','notice']);
	remove_action('admin_init',['WP_Privacy_Policy_Content','add_suggested_content'],1);
	remove_action('post_updated',['WP_Privacy_Policy_Content','_policy_page_updated']);
	remove_filter('list_pages','_wp_privacy_settings_filter_draft_page_titles',10,2);
},1);

//禁止Embed，防止被他人嵌入文章
remove_action('wp_head','wp_oembed_add_discovery_links');
remove_action('wp_head','wp_oembed_add_host_js');

//禁用未登录用户访问REST API，如果你有小程序什么的需要，请注释或删除
function rest_only_for_authorized_users($wp_rest_server){
    if(!is_user_logged_in()) {
        wp_die('本站不支持REST API');
    }
}
add_filter('rest_api_init','rest_only_for_authorized_users',99);

//移除默认的max-image-preview指令，已手动添加.
remove_filter('wp_robots','wp_robots_max_image_preview_large');

//禁止前台展示工具条
add_filter('show_admin_bar', '__return_false');

//屏蔽找回密码，好好记牢密码，真的忘记了再来注释掉
add_filter('allow_password_reset','__return_false');

//禁止访问作者页面报错站点地图禁止收录搜索只可以搜索文章内容等，防止暴露用户名
function disableAuthorUrl(){
    if (is_author()) {
      header("HTTP/1.1 404 Not Found");
      exit();
    }
}
add_action('template_redirect','disableAuthorUrl');
add_filter('wp_sitemaps_add_provider',function ($provider,$name){
  return ( $name == 'users' ) ? false : $provider;
},10,2);
add_filter('locale', function($locale){
    $locale = ( is_admin() ) ? $locale : 'en_US';
    return $locale;
});
add_filter('pre_get_posts',function($query){
    if ($query->is_search && !$query->is_admin) {
       $query->set('post_type', 'post');
     }
     return $query;
});

//禁用自动生成的图片尺寸
function shapeSpace_disable_image_sizes($sizes) {
	unset($sizes['thumbnail']);
	unset($sizes['medium']);
	unset($sizes['large']);
	unset($sizes['medium_large']);
	unset($sizes['1536x1536']);
	unset($sizes['2048x2048']);
	unset($sizes['60x75']);
	return $sizes;
}
add_action('intermediate_image_sizes_advanced','shapeSpace_disable_image_sizes');

//禁用缩放尺寸
add_filter('big_image_size_threshold','__return_false');

//禁用其他图片尺寸
function shapeSpace_disable_other_image_sizes(){
	remove_image_size('post-thumbnail');
	remove_image_size('another-size');
}
add_action('init','shapeSpace_disable_other_image_sizes');
function disable_srcset( $sources ) {
	return false;
}
add_filter('wp_calculate_image_srcset','disable_srcset');

//使用MD5对附件重命名，应该不会有附件重名，有助于减少上传附件时的sql查询
function custom_upload_filter( $file ){
    $info = pathinfo($file['name']);
    $ext = $info['extension'];
    $filedate = date('YmdHis').rand(0,99).$file['name'];
    $filedate = md5($filedate);
    $file['name'] = $filedate.'.'.$ext;
    return $file;
}
add_filter('wp_handle_upload_prefilter','custom_upload_filter');
add_filter('wp_img_tag_add_srcset_and_sizes_attr','__return_false');
add_filter('use_default_gallery_style','__return_false');
add_theme_support("post-thumbnails");

//WordPress的优化结束




//以下为主题功能

//载入JS,CSS
add_action('wp_enqueue_scripts', function(){
    if (!is_admin()){
        // 注册和加载全局样式和脚本
        wp_enqueue_script('global', get_template_directory_uri().'/style/js/main.js','',false,true);	
        wp_enqueue_style('global', get_template_directory_uri().'/style/css/main.css');
        wp_enqueue_style('iconfont', get_template_directory_uri().'/style/css/iconfont.css');

        // 对于单个文章和页面，注册和加载特定的样式和脚本
        if(is_single()||is_page()){
            wp_enqueue_style('single', get_template_directory_uri().'/style/css/single.css', array('global'));
            wp_enqueue_style('comments-css', get_template_directory_uri() . '/style/css/comments.css');
            wp_enqueue_style('highlight', get_template_directory_uri().'/style/css/monokai-sublime.min.css');
            wp_enqueue_script('copy', get_template_directory_uri().'/style/js/customize.js');
            wp_enqueue_script('highlight', get_template_directory_uri().'/style/js/highlight.min.js');
            wp_enqueue_script('highlight-line', get_template_directory_uri().'/style/js/highlightjs-line-numbers-2.8.0.min.js', array('highlight'));
        }
       global $wp_query; 
    }
});

//设置古登堡编辑器的宽度,CSS样式里调整
function custom_gutenberg_editor_width() {
    wp_enqueue_style(
        'custom-gutenberg-width',
        get_stylesheet_directory_uri() . '/style/css/gutenberg.css'
    );
}
add_action( 'enqueue_block_editor_assets', 'custom_gutenberg_editor_width' );



//搜索关键词为空比如空格，返回主页
add_filter('request',function ($query_variables){
	if (isset($_GET['s']) && !is_admin()) {
		if(empty($_GET['s']) || ctype_space($_GET['s'])){
			wp_redirect( home_url());
			exit;
		}
	}
	return $query_variables;
});

//搜索结果只有一篇时直接跳转到文章页面
function redirect_single_post(){
    if(is_search()){
        global $wp_query;
        if($wp_query->post_count == 1){
            wp_redirect(get_permalink($wp_query->posts['0']->ID));
        }
    }
}
add_action('template_redirect','redirect_single_post');



<?php


//输出顶部通知区域

function custom_top_notice_area() {
    $notice_text = '&#10024 获取更多博主的动态，欢迎加入我的频道:&nbsp&nbsp<a href="https://t.me/s/qichiyuu" target="_blank" rel="noopener noreferrer" style="font-weight: bold; text-decoration: underline;">Telegram  </a>';
    $notice_style = 'background-color: rgba(100, 100, 100, 0.03); text-align: center; font-size: 14px; padding: 8px;';

    $notice_html = '<div class="custom-top-notice" style="' . esc_attr($notice_style) . '">' . $notice_text . '</div>';

    echo $notice_html;
}

//SEO优化开始

//自定义通用SEO关键字和通用SEO描述.
function key_Desc($type){
    switch ($type) {
        case 'k':
            $con = '电脑知识,电脑软件,手机软件,实用软件,精品软件,网络资源,免费教程,网络技术,技术分享';
            break;
        case 'd':
        default:
            $con = '七尺宇的个人博客,博主踩坑笔记,以及经验分享';
            break;
    }
    return $con;
}

//文章分类页面添加SEO输入框
$category_meta = array( 
    array("name" => "categorykws","std" => "","title" => __('SEO关键词', 'haoui').'：'));
    function add_category_field(){
        global $category_meta;
        foreach($category_meta as $meta_box) {
            echo '<div class="form-field"><label for="'.$meta_box['name'].'">'.$meta_box['title'].'</label><input name="'.$meta_box['name'].'" id="'.$meta_box['name'].'" type="text" value="" size="40">'.'</div>';
        } 
    }
    function edit_category_field($tag){
        global $category_meta;
        foreach($category_meta as $meta_box) {
            echo '<tr class="form-field"><th scope="row"><label for="'.$meta_box['name'].'">'.$meta_box['title'].'</label></th><td><input name="'.$meta_box['name'].'" id="'.$meta_box['name'].'" type="text" value="'; 
            echo get_option(''.$meta_box['name'].'-'.$tag->term_id).'" size="40"/>'.'</td></tr>';
        }
    }
    function category_save($term_id){
        global $category_meta;
        foreach($category_meta as $meta_box) {
            $data = $_POST[$meta_box['name']];
            if(isset($data)){
                if(!current_user_can('manage_categories')){
                    return $term_id;
                }
                $key = $meta_box['name'].'-'.$term_id;
                update_option( $key, $data );
            }
        }
    }
add_action('category_add_form_fields','add_category_field',10,2);
add_action('category_edit_form_fields','edit_category_field',10,2);
add_action('created_category','category_save',10,1);
add_action('edited_category','category_save',10,1);

//写一个用来调用SEO标题的函数，首页格式：站点标题——副标题，请在WordPress后台【设置——常规】中填写。
function seo_title(){
    $title = '';
	if(is_singular()){
		$title = wp_get_document_title();
	}elseif(is_category()){
		$title = single_cat_title().'-'.get_bloginfo( 'name' );
	}elseif(is_tag()){
	    $title = single_tag_title('',false).'-'.get_bloginfo( 'name' );
	}elseif(is_404()){
	    $title = '页面不存在 - '.get_bloginfo( 'name' );
	}else{
		$title = get_bloginfo( 'name' ).' - '.get_bloginfo( 'description' );
	}
	return $title;
}

//图片自动添加ALT和TITLE，把SEO做好
function image_alt_tag($content){
    global $post;preg_match_all('/<img (.*?)\/>/', $content, $images);
    if(!is_null($images)) {foreach($images[1] as $index => $value)
    {
        $new_img = str_replace('<img', '<img title="'.get_the_title().'-'.get_bloginfo('name').'" alt="'.get_the_title().'-'.get_bloginfo('name').'"', $images[0][$index]);
        $content = str_replace($images[0][$index], $new_img, $content);}}
        return $content;
}
add_filter('the_content','image_alt_tag',99999);

//设定摘要的长度,以及屏蔽描述后面自带的[...]，200个字符无论是中英文都很够了
function new_excerpt_length($length) {
    return 200;
}
add_filter('excerpt_length','new_excerpt_length');
function new_excerpt_more(){
    return '';
}
add_filter('excerpt_more','new_excerpt_more');

//SEO关键字，文章的关键字取自文章标签，分类的关键字取自分类中自定义
function seo_keywords(){
    $keywords = '';
    if(is_singular()){
        global $post, $posts;
    	$gettags = get_the_tags($post->ID);
    	if ($gettags) {
    		foreach ($gettags as $tag) {
    			$posttag[] = $tag->name;
    		}
    		$keywords = implode( ',', $posttag );
    	}
    }elseif(is_category()){
        $keywords = get_option('categorykws-'.get_query_var('cat'));
    }elseif(is_tag()){
        $keywords = single_tag_title('',false);
    }
    if(empty($keywords)){
        $keywords = key_Desc('k');
    }
    return $keywords;
}

//SEO描述，文章描述自动截取文章前200个字符，分类的描述取自分类描述
function seo_description(){
    $category_id = '';
    $description = '';
    if(is_singular()){
        $description = get_the_excerpt ($post=null);
    }elseif(is_category()){
        $description = str_replace(array("<p>","","</p>", "\r", "\n"),"",category_description($category_id));
    }
    if(empty($description)){
        $description = key_Desc('d');
    }
    return $description;
}

//SEO优化结束!!




//以下为其他设置项目

//隐藏wordpress后台登录地址
 add_action('login_enqueue_scripts','login_protection');  
 function login_protection(){  
    if($_GET['Long'] != '320117'){
        wp_redirect(home_url());
        exit();
    }
 }

//文章密码登录界面提示文字自定义,美化在CSS部分
function custom_password_form() {
    global $post;
    $label = '请输入密码'; // 提示用户输入密码
    $img_path = get_stylesheet_directory_uri() . '/style/img/passwd.svg'; // 获取矢量图片路径

    $output = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" method="post" class="post-password-form">';
    $output .= '<img src="' . $img_path . '" alt="Password Protected" class="password-img">'; // 添加图片
    $output .= '<p>' . '此内容受保护，请输入密码以访问。' . '</p>'; // 直接写入中文提示
    $output .= '<p><label for="pwbox-' . $post->ID . '">' . "密码：" . ' <input name="post_password" id="pwbox-' . $post->ID . '" type="password" size="20" /></label> <input type="submit" name="Submit" value="' . "提交" . '" /></p>';
    $output .= '</form>';
    return $output;
}
add_filter( 'the_password_form', 'custom_password_form' );

//自动设置特色图
function list_the_post_thumbnail($postID, $imageMogr2 = '') {
    $thumb = '';
    $default_img_dir = get_template_directory_uri() . '/style/img/default/';
    clean_post_cache($postID);
    $first_img_url = get_first_image_url_from_content($postID);
    if ($first_img_url) {
        update_post_meta($postID, '_thumbnail_id', -1); // 设置一个无效的 ID,以防止特色图像显示为默认图像
        update_post_meta($postID, '_thumbnail_override', $first_img_url);
        $thumb = $first_img_url;
    } else {
        if (has_post_thumbnail($postID)) {
            $thumb_id = get_post_thumbnail_id($postID);
            $thumb_src = wp_get_attachment_image_src($thumb_id, 'full');
            $thumb = $thumb_src[0];
        } else {
            $default_img_files = array_filter(
                array_map(
                    function ($file) use ($default_img_dir) {
                        $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                        return in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']) ? $default_img_dir . $file : false;
                    },
                    array_diff(scandir(get_template_directory() . '/style/img/default/'), ['.', '..'])
                )
            );
            $thumb = $default_img_files ? $default_img_files[array_rand($default_img_files)] : '';
        }
    }
    if (filter_var($thumb, FILTER_VALIDATE_URL) === false) {
        $thumb = '';
    }
    if (!empty($imageMogr2)) {
        $thumb = $thumb . $imageMogr2;
    }
    return $thumb;
}

// 从文章内容中获取第一张图片的URL
function get_first_image_url_from_content($post_id) {
    $post = get_post($post_id);
    $content = $post->post_content;
    $image = '';

    // 匹配所有图片标签
    preg_match_all('/<img.+src=([\'"])(?<src>.+?)\1.*>/i', $content, $matches);

    if (isset($matches['src'][0])) {
        $image = $matches['src'][0];
    }

    return $image;
}


//面包屑导航
function custom_breadcrumbs() {
    global $post;
    $homeLink = home_url('/');
    $breadcrumb = array(
        'separator' => ' / ',
        'before' => '<span class="current">现在位置: </span>',
        'home' => '首页'
    );
    $breadcrumb_trail = '<nav class="breadcrumbs" aria-label="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">';
    $breadcrumb_trail .= $breadcrumb['before'];
    $trail = array();
    $trail[] = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a href="' . $homeLink . '" rel="home" itemprop="item"><span itemprop="name">' . $breadcrumb['home'] . '</span></a>';
    $trail[] = '<meta itemprop="position" content="1" /></span>';
    $trail[] = $breadcrumb['separator'];
    if (is_singular()) {
        $categories = get_the_category($post->ID);
        if ($categories) {
            $position = 1;
            foreach ($categories as $category) {
                $parents = get_category_parents($category->term_id, true, $breadcrumb['separator']);
                $trail[] = $parents;
                $trail[] = '<meta itemprop="position" content="' . ++$position . '" />';
                $trail[] = $breadcrumb['separator'];
            }
            array_pop($trail);
            $trail[] = '<span itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span itemprop="name">正文</span>';
            $trail[] = '<meta itemprop="position" content="' . ++$position . '" /></span>';
        }
    }
    $breadcrumb_trail .= implode('', $trail);
    $breadcrumb_trail .= '</nav>';
    
    echo $breadcrumb_trail;
}

//菜单设置
function register_my_menus(){
      register_nav_menus([
            'header-menu' => __('顶部菜单'),
            'footer-menu' => __('底部菜单')
        ]);
   }
add_action('init','register_my_menus');

//开启友情链接
add_filter('pre_option_link_manager_enabled','__return_true');

//添加翻页
function list_paginate_links(){
	$pagination_links = paginate_links( array(
		'mid_size'     => 1,
		'prev_text'    => __('上一页'),
		'next_text'    => __('下一页'),
	) );
	if($pagination_links){
	    $pagination_links = '<div class="pagenavi flex">'.$pagination_links.'</div>';
	}
	return $pagination_links;
}

//文章标签，文章里面可以调用它
function post_the_tags(){
    $post_tag = the_tags( '<div class="post-tag">','','</div>');
	if($post_tag){return $post_tag;}
}

//头像使用国内镜像
if (!function_exists( 'get_cravatar_url' )) {
    function get_cravatar_url( $url ) {
        $sources = array(
            'www.gravatar.com',
            '0.gravatar.com',
            '1.gravatar.com',
            '2.gravatar.com',
            'secure.gravatar.com',
            'cn.gravatar.com',
            'gravatar.com',
        );
        return str_replace( $sources, 'cravatar.cn', $url );
    }
    add_filter( 'um_user_avatar_url_filter', 'get_cravatar_url', 1 );
    add_filter( 'bp_gravatar_url', 'get_cravatar_url', 1 );
    add_filter( 'get_avatar_url', 'get_cravatar_url', 1 );
}
if ( ! function_exists( 'set_defaults_for_cravatar' ) ) {
    function set_defaults_for_cravatar( $avatar_defaults ) {
        $avatar_defaults['gravatar_default'] = 'Cravatar 标志';
        return $avatar_defaults;
    }
    add_filter( 'avatar_defaults', 'set_defaults_for_cravatar', 1 );
}



//评论相关优化
//屏蔽纯英文评论和纯日文
function refused_english_comments($incoming_comment) {
    $pattern = '/[一-龥]/u';
    // 禁止全英文评论
    if(!preg_match($pattern, $incoming_comment['comment_content'])) {
        die( "您的评论中必须包含汉字!");
    }
    $pattern = '/[あ-んア-ン]/u';
    // 禁止日文评论
    if(preg_match($pattern, $incoming_comment['comment_content'])) {
        die( "评论禁止包含日文!" );
    }
    return( $incoming_comment );
}
add_filter('preprocess_comment', 'refused_english_comments');

// 评论添加@，好让人知道你在回复谁！！！
function ds_comment_add_at( $comment_text, $comment = '') {
    if( $comment->comment_parent > 0) {
      $comment_text = '@<a href="#comment-' . $comment->comment_parent . '">'.get_comment_author( $comment->comment_parent ) . '</a> ' . $comment_text;
    }
    return $comment_text;
  }
add_filter( 'comment_text' , 'ds_comment_add_at', 20, 2);

//新窗口打开评论者网站链接
add_filter('get_comment_author_link', function ($return, $author, $id) {
    return str_replace('<a ', '<a target="_blank" ', $return);
},0,3 );

//删除掉函数 comment_class() 和 body_class() 中输出的 "comment-author-" 和 "author-"，防止暴露管理员用户名
function remove_comment_body_class($content){ 
    $pattern = "/(.*?)([^>]*)author-([^>]*)(.*?)/i";
    $content = preg_replace_callback($pattern, function(){return '';}, $content);
    return $content;
}
add_filter('comment_class', 'remove_comment_body_class');
add_filter('body_class', 'remove_comment_body_class');
//评论相关的定义到此结束.


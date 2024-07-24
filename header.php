<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="renderer" content="webkit">
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="color-scheme" content="light dark">
    <meta name="robots" content="noarchive, max-image-preview:large"/>
    <meta content="yes" name="apple-mobile-web-app-capable"/>
    <meta name="format-detection" content="telephone=no, email=no, address=no"/>
    <title><?php if(is_search()){$title = $s.' - '.get_bloginfo( 'name' );}else{$title = seo_title();}echo $title;?></title>
    <meta name='keywords' content='<?php if(is_search()){$keywords = $s;}else{$keywords = seo_keywords();}echo $keywords;?>'/> 
    <meta name='description' content='<?php if(is_search()){$description = $s;}else{$description = seo_description();}echo $description;?>'/> 
    <?php wp_head();?>
</head>
<body id="body">
    <header class="site-header flex">
        <div class="header-container container flex">
            <div class="openMenu iconButton mobile"><i class="iconfont icon-menu-2-line"></i></div>
                 <div class="logo-container">
                   <a href="<?php echo home_url(); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/style/img/logo.svg" alt="whiteLogo" class="logo light-logo" height="30px" width="90px">
                    <img src="<?php echo get_template_directory_uri(); ?>/style/img/blacklogo.svg" alt="blackLogo" class="logo dark-logo" height="30px" width="90px" style="display:none;" loading="lazy">
                   </a>
                 </div>
             <nav class="header-menu">
                <div class="goDarkm iconButton menuButton mobile" onclick="switchDarkMode()"><i class="iconfont icon-lightbulb-flash-line"></i></div>
                <div class="closeMenu iconButton menuButton mobile"><i class="iconfont icon-close-line"></i></div>
                <ul class="gore flex">
                    <?php wp_nav_menu(['container' => true, 'items_wrap' => '%3$s', 'theme_location' => 'header-menu', 'fallback_cb' => false]); ?>
                </ul>
            </nav>
            <div class="site-search none">
                <form method="get" class="site-form flex" action="<?php bloginfo('url');?>">
                    <input type="search" class="field" placeholder="输入关键词进行搜索…" maxlength="2048" autocomplete="off" value="" name="s" required="true">
                    <div class="closeFind iconButton"><i class="iconfont icon-close-line"></i></div>
                </form>
            </div>
            <div class="goDark iconButton" onclick="switchDarkMode()"><i class="iconfont icon-lightbulb-flash-line"></i></div>
            <div class="goFind iconButton"><i class="iconfont icon-search-2-line"></i></div>
        </div>
    </header>

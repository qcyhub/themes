<?php

// WordPress后台登录页面美化
function custom_login_style() {
    echo "<style>
            body {
                background-position: 50%;
                background-size: cover;
            }
            #login {
                width: 400px;
                padding: 12% 0 0;
            }
            .login form {
                margin-top: 0;
                padding: 26px 44px 34px;
                border: none;
                border-radius: 10px;
                box-shadow: 0 0 18px 0 rgba(0, 0, 0, 0.04);
            }
            .wp-core-ui .button-primary {
                background: #333;
                border-color: #333;
                width: 100%;
                height: 40px;
                line-height: 40px;
                border-radius: 20px;
            }
            .login #login_error, .login .message, .login .success {
                border-radius: 10px;
            }
            .login #backtoblog a, .login #nav a {
                color: #fff;
            }
            .login h1, .login .message, .login .success, 
            .login #backtoblog, .login #nav, .language-switcher,
            .login .dashicons-visibility:before {
                display: none;
            }
        </style>";
}
add_action('login_head', 'custom_login_style');

// 自定义登录页面的标题
add_filter('login_title', function() {
    return get_bloginfo('name') . ' ‹ 登录';
}, 10, 2);

// 移除登录页面logo的链接
add_filter('login_headerurl', 'home_url');
?>

//网站黑白模式切换(跟随系统切换最高优先级,用户选择和系统不一致是记住配置,并提醒)
// 在全局作用域中声明变量以存储用户的选择
window.userPrefersDark = localStorage.getItem('userPrefersDark');

function updateLogoDisplay() {
  const darkLogo = document.querySelector('.dark-logo');
  const lightLogo = document.querySelector('.light-logo');
  const isDarkMode = document.documentElement.classList.contains('dark');
  darkLogo.style.display = isDarkMode ? 'block' : 'none';
  lightLogo.style.display = isDarkMode ? 'none' : 'block';
}

function applyThemeBasedOnSystemPreference() {
  const isSystemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  document.documentElement.classList.toggle('dark', isSystemDark);
  document.documentElement.classList.toggle('light-mode', !isSystemDark);
  updateLogoDisplay(); // 更新logo显示
}

function applyUserThemeChoice() {
  const userChoice = localStorage.getItem('userPrefersDark');
  const isDark = userChoice === '1';
  document.documentElement.classList.toggle('dark', isDark);
  document.documentElement.classList.toggle('light-mode', !isDark);
  updateLogoDisplay(); // 更新logo显示
}

// 用户手动切换模式
function switchDarkMode() {
  const isDarkModeCurrentlyEnabled = document.documentElement.classList.contains('dark');
  const isSystemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  const isDark = !isDarkModeCurrentlyEnabled;

  // 确认用户是否真的想要切换
  if (isDark !== isSystemDark) {
    const userWantsToSwitch = confirm('您当前的选择与系统色彩不一致,可能会影响浏览体验。您确定要切换吗？');
    if (userWantsToSwitch) {
      document.documentElement.classList.toggle('dark', isDark);
      document.documentElement.classList.toggle('light-mode', !isDark);
      localStorage.setItem('userPrefersDark', isDark ? '1' : '0');
      updateLogoDisplay(); // 更新logo显示
    }
  } else {
    document.documentElement.classList.toggle('dark', isDark);
    document.documentElement.classList.toggle('light-mode', !isDark);
    localStorage.removeItem('userPrefersDark');
    updateLogoDisplay(); // 更新logo显示
  }
}

// 监听系统主题变化并应用系统偏好
window.matchMedia('(prefers-color-scheme: dark)').addListener(applyThemeBasedOnSystemPreference);

// 在页面加载时根据系统偏好或用户选择应用主题
document.addEventListener('DOMContentLoaded', function() {
  const userChoiceExists = localStorage.getItem('userPrefersDark') !== null;
  if (userChoiceExists) {
    applyUserThemeChoice();
  } else {
    applyThemeBasedOnSystemPreference();
  }
});




// 全站通用js
  function $(name) {
    return document.querySelector(name);
  }
  
  function addloadEvent(func) {
    let oldonload = window.onload;
    "function" != typeof window.onload
      ? (window.onload = func)
      : (window.onload = function () {
          oldonload && oldonload(), func();
        });
  }
  function goTopbtn() {
    let gotop = $("goTop"),timer,tf = !0;
    (window.onscroll = function () {
      let ostop = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop,ch = document.documentElement.clientHeight || document.body.clientHeight;
      (goTop.style.display = ostop >= 100 ? "block" : "none"),tf || clearInterval(timer),(tf = !1);
    }),(goTop.onclick = function () {document.body.scrollIntoView();
    });
  }
  addloadEvent(goTopbtn)
  let has_children=document.getElementsByClassName("menu-item-has-children");
	for(i=0;i<has_children.length;i++){
    has_children[i].getElementsByTagName("a")[0].insertAdjacentHTML('afterend','<span class="menu-sign"></span>');
  }
  $(".goFind").onclick = function(){
    $(".site-search").classList.toggle("none"),
    $(".field").focus()
  },$(".closeFind").onclick = function(){
    $(".site-search").classList.toggle("none")
  },$(".field").onblur = function(){
    $(".site-search").classList.toggle("none")
  },$(".openMenu").onclick = function(){
    $(".header-menu").classList.toggle("open-header-menu"),
    $(".mask").style.display = "block";
  },$(".closeMenu").onclick = function(){
    $(".header-menu").classList.toggle("open-header-menu"),
    $(".mask").style.display = "none";
  },$(".mask").onclick = function(){
    $(".header-menu").classList.toggle("open-header-menu"),
    $(".mask").style.display = "none";
  }
  !$(".post-navigation") || ($(".nav_box_previous") && $(".nav_box_next")) || ($(".nav-box").style.width = "100%");

  console.log("如果代码是诗，您已经发现了我们的藏诗。如果代码是海洋，那么您已经准备好和我们一起航行。抛出您的锚，让我们共同启航，开启这段友谊的旅程吧!");



  //移动端打开或关闭二级菜单
  if(document.body.scrollWidth<767){
    var oldIndex = '',classIndex = '',menu_has_children = document.getElementsByClassName('menu-item-has-children'),menu_sign = document.getElementsByClassName('menu-sign');
    for(var i=0;i<menu_sign.length;i++){
      menu_sign[i].setAttribute("index",i),
      menu_sign[i].onclick = function(){
        classIndex = this.getAttribute("index");
        if(oldIndex!=''&&oldIndex!=classIndex){
          menu_sign[oldIndex].classList.remove("open-menu-sign"),
          menu_has_children[oldIndex].classList.remove("open-menu-item-has-children");
        }
        menu_sign[classIndex].classList.toggle("open-menu-sign"),
        menu_has_children[classIndex].classList.toggle("open-menu-item-has-children");
        oldIndex = classIndex;
      }
    };
    document.addEventListener('click', function(event) {
        if(oldIndex!=''){
          if (event.target !== menu_sign[oldIndex]) {
            menu_sign[oldIndex].classList.remove("open-menu-sign"),
            menu_has_children[oldIndex].classList.remove("open-menu-item-has-children");
            oldIndex='';
          }
        }
    });
  }



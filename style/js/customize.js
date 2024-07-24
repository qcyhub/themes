// 配置项
const config = {
  headingLevels: [2, 3, 4], // 需要在目录中显示的标题级别
  indentSize: 20, // 缩进距离
  scrollOffset: 20, // 滚动高亮偏移量
};
let headings;
let tocItems;
function throttle(fn) {
  let waiting = false;
  return function (...args) {
    if (!waiting) {
      requestAnimationFrame(() => {
        fn.apply(this, args);
        waiting = false;
      });
      waiting = true;
    }
  };
}
function generateTocHtml() {
  const counters = new Map();
  config.headingLevels.forEach((level) => counters.set(level, 0));
  let html = '';
  headings.forEach((heading, index) => {
    const level = parseInt(heading.tagName.slice(1));
    if (config.headingLevels.includes(level)) {
      const counter = counters.get(level);
      counters.set(level, counter + 1);
      config.headingLevels.filter((l) => l > level).forEach((l) => counters.set(l, 0));
      const number = config.headingLevels
        .filter((l) => l <= level)
        .map((l) => counters.get(l))
        .join('.');
      heading.id = `heading-${level}-${index}`;
      html += `<li class="toc-level-${level}" style="margin-left: ${(level - 2) * config.indentSize}px">
        <a href="#${heading.id}">${number} ${heading.textContent}</a>
      </li>`;
    }
  });
  return html ? `<h3 class="toc-title">文章目录</h3><ul class="toc">${html}</ul>` : '';
}
function updateTocHighlightingOptimized(scrollPosition, headingOffsets) {
  let currentActiveIndex;
  for (let i = 0; i < headingOffsets.length; i++) {
    const offset = headingOffsets[i];
    const nextOffset = headingOffsets[i + 1] || Infinity;
    if (scrollPosition >= offset - config.scrollOffset && scrollPosition < nextOffset - config.scrollOffset) {
      currentActiveIndex = i;
      break;
    }
  }
  if (currentActiveIndex !== undefined) {
    tocItems.forEach((item, index) => {
      item.classList.toggle('current', index === currentActiveIndex);
    });
  }
}
document.addEventListener('DOMContentLoaded', () => {
  headings = Array.from(document.querySelectorAll('.post-main h2, .post-main h3, .post-main h4'));
  const tocContainer = document.getElementById('toc-container');
  const tocContent = document.getElementById('toc-content');

  if (!tocContainer || !tocContent) {
    console.error('目录容器或内容元素不存在');
    return;
  }
  window.addEventListener('load', () => {
    const headingOffsets = headings.map((heading) => heading.getBoundingClientRect().top + window.scrollY);
    const tocHtml = generateTocHtml();
    if (tocHtml) {
      tocContent.innerHTML = tocHtml;
      tocItems = Array.from(tocContent.querySelectorAll('.toc li'));
      let tocOffsetTop = tocContent.getBoundingClientRect().top + window.scrollY;
      const updateTocPositionAndHighlightingOptimized = throttle(() => {
        const scrollTop = window.scrollY || window.pageYOffset;
        if (scrollTop > tocOffsetTop) {
          tocContainer.classList.add('floating');
        } else {
          tocContainer.classList.remove('floating');
        }
        updateTocHighlightingOptimized(scrollTop, headingOffsets);
      }, 100);
      window.addEventListener('scroll', updateTocPositionAndHighlightingOptimized);
    } else {
      tocContainer.style.display = 'none';
    }
  });
});

//代码复制按钮JS
document.addEventListener('DOMContentLoaded', (event) => {
  document.querySelectorAll('pre code').forEach((block) => {
    var button = document.createElement('button');
    button.className = 'copy-button';
    button.type = 'button';
    button.innerHTML = 'Copy';
    button.addEventListener('click', () => {
      navigator.clipboard.writeText(block.innerText).then(() => {
        /* 复制成功后的操作 */
        button.innerHTML = '<span class="copied">已复制</span>';
        setTimeout(() => {
          button.innerHTML = 'Copy';
          button.classList.remove('copied');
        }, 2000);
      });
    });
    block.parentNode.insertBefore(button, block);
  });
});


//markdown各种警告快JS
document.addEventListener('DOMContentLoaded', (event) => {
  // 获取所有引用块
  const blockquotes = document.querySelectorAll('blockquote');

  // 遍历每个引用块
  blockquotes.forEach(blockquote => {
	  // 检查并处理WARNING标记
    if (blockquote.innerText.includes('[!WARNING]')) {
      blockquote.classList.add('warning-border');
      blockquote.innerHTML = blockquote.innerHTML.replace(/\[!WARNING\][^\n]*\n?/g, '');
    }
    // 检查并处理NOTE标记
    if (blockquote.innerText.includes('[!NOTE]')) {
      blockquote.classList.add('note-border');
      blockquote.innerHTML = blockquote.innerHTML.replace(/\[!NOTE\][^\n]*\n?/g, '');
    }
    // 检查并处理TIP标记
    else if (blockquote.innerText.includes('[!TIP]')) {
      blockquote.classList.add('tip-border');
      blockquote.innerHTML = blockquote.innerHTML.replace(/\[!TIP\][^\n]*\n?/g, '');
    }
    // 检查并处理CAUTION标记
    else if (blockquote.innerText.includes('[!CAUTION]')) {
      blockquote.classList.add('caution-border');
      blockquote.innerHTML = blockquote.innerHTML.replace(/\[!CAUTION\][^\n]*\n?/g, '');
    }
    // 检查并处理IMPORTANT标记
    else if (blockquote.innerText.includes('[!IMPORTANT]')) {
      blockquote.classList.add('important-border');
      blockquote.innerHTML = blockquote.innerHTML.replace(/\[!IMPORTANT\][^\n]*\n?/g, '');
    }
  });
});



// 分享按钮
document.addEventListener('DOMContentLoaded', function() {
    var copyButton = document.getElementById('copyButton');
    copyButton.addEventListener('click', function() {
        // 从data属性读取文章链接
        var copyText = this.closest('.article-share').getAttribute('data-article-link');
        navigator.clipboard.writeText(copyText).then(function() {
            copyButton.textContent = '链接已复制';
            copyButton.classList.add('copied');
            setTimeout(function() {
                copyButton.textContent = '分享链接';
                copyButton.classList.remove('copied');
            }, 3000); // 3秒后恢复
        }).catch(function(err) {
            console.error('无法复制链接: ', err);
        });
    });
});

// 代码块顶部语言类型显示
window.addEventListener('load', () => {
  const preElements = document.querySelectorAll('pre');

  preElements.forEach(pre => {
    const codeElement = pre.querySelector('code');

    if (codeElement) {
      const languageClass = Array.from(codeElement.classList).find(className => className.startsWith('language-'));

      if (languageClass) {
        const languageType = languageClass.replace('language-', '');
        const languageTag = document.createElement('div');
        languageTag.className = 'language-tag';
        languageTag.textContent = languageType;
        pre.insertAdjacentElement('afterbegin', languageTag);
      }
    }
  });
});
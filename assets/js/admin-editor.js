// assets/js/admin-editor.js
// محرر نصوص غني (TinyMCE 6) - عربي RTL - مع رفع صور مدمج
(function () {
  if (typeof tinymce === 'undefined') {
    console.warn('[admin-editor] TinyMCE not loaded.');
    return;
  }

  var BASE = window.APP_BASE || '';
  var CSRF = document.querySelector('meta[name="csrf-token"]')?.content || '';

  tinymce.init({
    selector: 'textarea.rich-editor',
    language: 'ar',
    directionality: 'rtl',
    height: 520,
    menubar: false,
    branding: false,
    promotion: false,
    resize: true,
    plugins: [
      'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
      'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
      'insertdatetime', 'media', 'table', 'help', 'wordcount', 'emoticons'
    ],
    toolbar:
      'undo redo | blocks | ' +
      'bold italic underline strikethrough forecolor backcolor | ' +
      'alignright aligncenter alignleft alignjustify | ' +
      'bullist numlist outdent indent | link image media table | ' +
      'removeformat code fullscreen',
    content_style: [
      "@import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap');",
      "body { font-family: 'Cairo', sans-serif; font-size: 16px; line-height: 1.9; color: #1e293b; padding: 12px; }",
      "h1,h2,h3,h4 { color: #061B52; font-weight: 800; margin: 12px 0; }",
      "a { color: #092A9E; text-decoration: underline; }",
      "img { max-width: 100%; height: auto; border-radius: 8px; }",
      "blockquote { border-right: 4px solid #F4C978; background: #FDF3E1; padding: 12px 16px; border-radius: 8px; margin: 12px 0; color: #061B52; }",
      "table { border-collapse: collapse; width: 100%; margin: 12px 0; }",
      "th, td { border: 1px solid #e2e8f0; padding: 8px 12px; }",
      "th { background: #f8fafc; font-weight: 800; }",
      "code { background: #f1f5f9; padding: 2px 6px; border-radius: 4px; font-family: monospace; }",
    ].join('\n'),

    // رفع الصور إلى الخادم (نقطة مخصصة للمحرر)
    images_upload_handler: function (blobInfo, progress) {
      return new Promise(function (resolve, reject) {
        var fd = new FormData();
        fd.append('file', blobInfo.blob(), blobInfo.filename());
        fd.append('_csrf', CSRF);

        fetch(BASE + '/admin/editor/upload', {
          method: 'POST',
          body: fd,
          credentials: 'same-origin',
          headers: { 'X-CSRF-Token': CSRF }
        })
          .then(function (r) { return r.json().then(function (j) { return { status: r.status, data: j }; }); })
          .then(function (res) {
            if (res.data && res.data.ok && res.data.url) {
              resolve(res.data.url);
            } else {
              reject('فشل رفع الصورة: ' + (res.data && res.data.error ? res.data.error : 'خطأ غير معروف'));
            }
          })
          .catch(function (err) {
            reject('خطأ شبكة: ' + (err && err.message ? err.message : err));
          });
      });
    },

    // قبول صور فقط من المحرر
    file_picker_types: 'image',
    image_uploadtab: true,

    setup: function (editor) {
      editor.on('init', function () {
        // إزالة شريط العلامة التجارية (لو ظهر)
        document.querySelectorAll('.tox-statusbar__branding').forEach(function (el) { el.remove(); });
      });
    }
  });

  // حفظ المحتوى قبل إرسال أي فورم (مهم جداً)
  document.querySelectorAll('form').forEach(function (form) {
    form.addEventListener('submit', function () {
      if (typeof tinymce !== 'undefined' && tinymce.triggerSave) {
        tinymce.triggerSave();
      }
    });
  });
})();
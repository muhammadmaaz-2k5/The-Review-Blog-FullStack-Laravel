<script src="https://cdn.tiny.cloud/1/gjrk0daltn9k2pl7fi56fv57llh54c1xzigkyyya8clpwaoc/tinymce/8/tinymce.min.js" referrerpolicy="origin" crossorigin="anonymous"></script>

<script>
  tinymce.init({
    selector: 'textarea.tinymce-editor', // Use this class on any textarea to enable TinyMCE
    
    // Free/Standard plugins only (removed premium plugins)
    plugins: [
      'anchor',
      'autolink',
      'autoresize',
      'charmap',
      'code',
      'codesample',
      'directionality',
      'emoticons',
      'fullscreen',
      'help',
      'image',
      'importcss',
      'insertdatetime',
      'link',
      'lists',
      'media',
      'nonbreaking',
      'pagebreak',
      'preview',
      'quickbars',
      'save',
      'searchreplace',
      'table',
      'template',
      'visualblocks',
      'visualchars',
      'wordcount',
      'advlist',
      'align',
      'autosave',
      'rtl'
    ],
    
    // Complete toolbar configuration
    toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist | outdent indent | link image media table | code codesample | charmap emoticons | fullscreen preview | searchreplace | visualblocks visualchars | wordcount | help',
    
    // Menubar configuration
    menubar: 'file edit view insert format tools table help',
    
    // Menu items
    menu: {
      file: { title: 'File', items: 'newdocument restoredraft | preview | print ' },
      edit: { title: 'Edit', items: 'undo redo | cut copy paste | selectall | searchreplace' },
      view: { title: 'View', items: 'code | visualaid visualchars visualblocks | preview fullscreen' },
      insert: { title: 'Insert', items: 'image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor | insertdatetime' },
      format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align lineheight | forecolor backcolor | removeformat' },
      tools: { title: 'Tools', items: 'code wordcount' },
      table: { title: 'Table', items: 'inserttable | cell row column | tableprops deletetable' },
      help: { title: 'Help', items: 'help' }
    },
    
    // Height configuration
    height: 600,
    min_height: 400,
    max_height: 800,
    
    // Content CSS for styling
    content_css: [
      'default',
      '//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap'
    ],
    
    // Content style
    content_style: `
      body {
        font-family: 'Poppins', sans-serif;
        font-size: 14px;
        line-height: 1.6;
        color: #333;
      }
      h1, h2, h3, h4, h5, h6 {
        font-family: 'Poppins', sans-serif;
        font-weight: 700;
        margin-top: 1em;
        margin-bottom: 0.5em;
      }
      p {
        margin-bottom: 0.75em;
      }
      img {
        max-width: 100%;
        height: auto;
      }
      table {
        border-collapse: collapse;
        width: 100%;
        margin: 1em 0;
      }
      table td, table th {
        border: 1px solid #ddd;
        padding: 8px;
      }
      table th {
        background-color: #f2f2f2;
        font-weight: 600;
      }
      code {
        background-color: #f4f4f4;
        padding: 2px 6px;
        border-radius: 3px;
        font-family: 'Fira Code', 'Courier New', 'Consolas', monospace;
        font-size: 0.9em;
        color: #e83e8c;
      }
      pre {
        background-color: #2d2d2d;
        color: #f8f8f2;
        padding: 0.75em 1em;
        border-radius: 8px;
        overflow-x: auto;
        margin: 1em 0;
        border: 1px solid #3d3d3d;
        font-family: 'Fira Code', 'Courier New', 'Consolas', monospace;
        font-size: 0.9em;
        line-height: 1.6;
      }
      pre code {
        background-color: transparent;
        padding: 0;
        color: inherit;
        font-size: inherit;
        border-radius: 0;
      }
      /* Code samples from codesample plugin */
      pre[class*="language-"],
      code[class*="language-"] {
        font-family: 'Fira Code', 'Courier New', 'Consolas', monospace;
        font-size: 0.9em;
        line-height: 1.6;
        direction: ltr;
        text-align: left;
        white-space: pre;
        word-spacing: normal;
        word-break: normal;
        tab-size: 4;
        hyphens: none;
      }
      pre[class*="language-"] {
        background-color: #2d2d2d;
        color: #f8f8f2;
        padding: 0.75em 1em;
        margin: 1em 0;
        overflow: auto;
        border-radius: 8px;
        border: 1px solid #3d3d3d;
      }
      pre[class*="language-"] code[class*="language-"] {
        display: block;
        color: #f8f8f2;
        background: transparent;
        padding: 0;
      }
      blockquote {
        border-left: 4px solid #ccc;
        margin: 1em 0;
        padding-left: 1em;
        color: #666;
      }
      ul, ol {
        margin: 1em 0;
        padding-left: 2em;
      }
      img {
        margin: 1em 0;
      }
      a {
        color: #E50914;
        text-decoration: none;
      }
      a:hover {
        text-decoration: underline;
      }
    `,
    
    // Additional configuration options
    branding: false,
    promotion: false,
    resize: true,
    elementpath: true,
    statusbar: true,
    paste_as_text: false,
    paste_data_images: true,
    automatic_uploads: true,
    file_picker_types: 'image',
    images_upload_url: '/admin/articles/upload-image', // You'll need to create this endpoint
    
    // Image handling
    image_advtab: true,
    image_caption: true,
    image_title: true,
    image_description: true,
    
    // Table options
    table_toolbar: 'tableprops tabledelete | tableinsertrowbefore tableinsertrowafter tabledeleterow | tableinsertcolbefore tableinsertcolafter tabledeletecol',
    table_resize_bars: true,
    table_default_attributes: {
      border: '1'
    },
    table_default_styles: {
      'border-collapse': 'collapse',
      'width': '100%'
    },
    
    // Link options
    link_assume_external_targets: true,
    link_context_toolbar: true,
    link_title: true,
    
    // Code options
    codesample_languages: [
      { text: 'HTML/XML', value: 'markup' },
      { text: 'JavaScript', value: 'javascript' },
      { text: 'CSS', value: 'css' },
      { text: 'PHP', value: 'php' },
      { text: 'Ruby', value: 'ruby' },
      { text: 'Python', value: 'python' },
      { text: 'Java', value: 'java' },
      { text: 'C', value: 'c' },
      { text: 'C#', value: 'csharp' },
      { text: 'C++', value: 'cpp' },
      { text: 'SQL', value: 'sql' },
      { text: 'JSON', value: 'json' },
      { text: 'Bash', value: 'bash' },
      { text: 'Go', value: 'go' },
      { text: 'TypeScript', value: 'typescript' }
    ],
    
    // Code sample plugin options
    codesample_global_prismjs: false, // Use TinyMCE's built-in highlighting
    codesample_dialog_width: 800,
    codesample_dialog_height: 600,
    
    // Auto-resize
    autoresize_bottom_margin: 50,
    autoresize_on_init: true,
    
    // Quickbars
    quickbars_selection_toolbar: 'bold italic | quicklink quickimage quicktable',
    quickbars_insert_toolbar: 'quickimage quicktable',
    
    // Spell checker
    browser_spellcheck: true,
    
    // Language
    language: 'en',
    
    // Setup callback
    setup: function(editor) {
      editor.on('init', function() {
        console.log('TinyMCE initialized successfully');
      });
    },
    
    // Custom formats
    block_formats: 'Paragraph=p; Heading 1=h1; Heading 2=h2; Heading 3=h3; Heading 4=h4; Heading 5=h5; Heading 6=h6; Preformatted=pre',
    
    // Font family
    font_family_formats: 'Arial=arial,helvetica,sans-serif; Courier New=courier new,courier; Georgia=georgia,palatino; Helvetica=helvetica; Impact=impact,chicago; Poppins=Poppins, sans-serif; Tahoma=tahoma,arial,helvetica,sans-serif; Times New Roman=times new roman,times; Trebuchet MS=trebuchet ms,geneva; Verdana=verdana,geneva',
    
    // Font size
    font_size_formats: '8pt 10pt 12pt 14pt 16pt 18pt 24pt 36pt 48pt',
    
    // Line height
    line_height_formats: '1 1.2 1.4 1.6 1.8 2 2.5 3',
    
    // Remove formatting
    remove_formats: 'b,strong,i,em,u,ins,s,strike,del,code,kbd,samp,var,mark,small,sub,sup',
    
    // Convert URLs
    convert_urls: false,
    
    // Relative URLs
    relative_urls: false,
    
    // Remove script tags
    remove_script_host: true,
    
    // Document base URL
    document_base_url: '/',
    
    // End container
    end_container_on_empty_block: true,
    
    // Forced root block
    forced_root_block: 'p',

    // Permissive valid elements for all kinds of iframes (Doodstream, YouTube, etc)
    extended_valid_elements: 'iframe[src|title|width|height|frameborder|allow|allowfullscreen|webkitallowfullscreen|mozallowfullscreen|style|class|scrolling|loading|marginwidth|marginheight|align|sandbox]',

    
    // Ensure iframes are preserved in various parent tags
    valid_children: '+body[iframe],+div[iframe],+p[iframe],+span[iframe]',
    
    // Media live embeds 
    media_live_embeds: true,
    media_alt_source: false,
    media_filter_html: false,


    // Force BR newlines
    force_br_newlines: false,
    
    // Force P newlines
    force_p_newlines: true,
    
    // Convert newlines
    convert_newlines_to_brs: false,
    
    // Non-breaking space
    nonbreaking_force_tab: true,
    nonbreaking_wrap: false,
    
    // Visual blocks
    visual_blocks: false,
    visual_chars: false,
    
    // Word count
    wordcount_countregex: /[\w\u2019\'-]+/g,
    wordcount_countregex_ascii: /[\w\'-]+/g,
    
    // Template
    templates: [
      {
        title: 'Article Template',
        description: 'A basic article template',
        content: '<h1>Article Title</h1><p>Introduction paragraph...</p><h2>Section Title</h2><p>Content...</p>'
      }
    ],
    
    // Custom CSS for editor
    body_class: 'tinymce-content',
    body_id: 'tinymce-body',
    
    // Skin
    skin: 'oxide',
    
    // Content direction
    directionality: 'ltr',
    
    // RTL
    rtl_ui: false,
    
    // Encoding
    encoding: 'xml',
    
    // Entity encoding
    entity_encoding: 'named',
    
    // Entities
    entities: '160,nbsp,38,amp,60,lt,62,gt',
    
    // Entity list
    entity_list: '160 nbsp,38 amp,60 lt,62 gt',
    
    // Custom buttons (if needed)
    custom_ui_selector: '.custom-tinymce-toolbar',
    
    // Auto focus
    auto_focus: false,
    
    // Save on cancel
    save_on_cancel: false,
    
    // Save button
    save_onsavecallback: null,
    
    // Cancel button
    save_oncancelcallback: null
  });
</script>

<style>
  /* Additional styles for TinyMCE editor */
  .tox-tinymce {
    border: 1px solid #ddd !important;
    border-radius: 8px !important;
  }
  
  .tox .tox-edit-area__iframe {
    background-color: #fff !important;
  }
  
  /* Dark mode support */
  @media (prefers-color-scheme: dark) {
    .tox-tinymce {
      border-color: #4a5568 !important;
    }
    
    .tox .tox-edit-area__iframe {
      background-color: #1a202c !important;
    }
  }
  
  /* Code sample styling in editor */
  .tox .tox-edit-area__iframe body pre[class*="language-"],
  .tox .tox-edit-area__iframe body pre {
    background-color: #2d2d2d !important;
    color: #f8f8f2 !important;
    border: 1px solid #3d3d3d !important;
    padding: 0.75em 1em !important;
    border-radius: 8px !important;
    margin: 1em 0 !important;
    font-family: 'Fira Code', 'Courier New', 'Consolas', monospace !important;
    font-size: 0.9em !important;
    line-height: 1.6 !important;
    overflow-x: auto !important;
  }
  
  .tox .tox-edit-area__iframe body code[class*="language-"],
  .tox .tox-edit-area__iframe body pre code {
    background-color: transparent !important;
    color: #f8f8f2 !important;
    padding: 0 !important;
    font-family: 'Fira Code', 'Courier New', 'Consolas', monospace !important;
  }
  
  .tox .tox-edit-area__iframe body code:not(pre code) {
    background-color: #f4f4f4 !important;
    color: #e83e8c !important;
    padding: 2px 6px !important;
    border-radius: 3px !important;
  }
  
  /* Dark mode code samples in editor */
  html.dark .tox .tox-edit-area__iframe body pre[class*="language-"],
  html.dark .tox .tox-edit-area__iframe body pre {
    background-color: #1e1e1e !important;
    color: #d4d4d4 !important;
    border-color: #3d3d3d !important;
  }
  
  html.dark .tox .tox-edit-area__iframe body code:not(pre code) {
    background-color: #374151 !important;
    color: #f472b6 !important;
  }
</style>
<?php /**PATH C:\Users\asdfq\Desktop\Nazaarabox\resources\views/components/head/tinymce-config.blade.php ENDPATH**/ ?>
tinymce.init({
  selector: "textarea",
  plugins: ['advlist autolink lists link image charmap print preview anchor',
              'searchreplace visualblocks code fullscreen',
              'insertdatetime media table paste imagetools wordcount'
            ],
  toolbar_mode: "floating",
  height: 500,
  preformatted: true,
  verify_html: true,
  a11y_advanced_options: true,
  image_caption: true,
  image_title: true,
  image_uploadtab: false,
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
  image_list: [
    { title: 'My image 1', value: 'https://picsum.photos/200/300' },
    { title: 'My image 2', value: 'https://picsum.photos/200' },
    { title: 'source', value: '/src/img/default_poster.jpg' }
  ]
});

/* tinymce.init({
  selector: 'textarea',
  height: 500,
  plugins: [
    'advlist autolink lists link image charmap print preview anchor',
    'searchreplace visualblocks code fullscreen',
    'insertdatetime media table paste imagetools wordcount'
  ],
  verify_html: false,
  toolbar: 'insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
  content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
}); */
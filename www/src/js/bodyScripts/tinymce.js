let images = [];
$.ajax({
  type: "POST",
  url: callRoute("medias_user_data"),
  dataType: "json",
  async: false,
  success: function (response) {
    images = response;
    console.log(response);
    console.log(images);
  },
});
tinymce.init({
  selector: "#articleContent",
  height: 500,
  plugins: [
    "advlist autolink lists link image charmap print preview anchor",
    "searchreplace visualblocks code fullscreen",
    "insertdatetime media table paste wordcount",
  ],
  toolbar:
    "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | help",
  content_style:
    "body { font-family:Helvetica,Arial,sans-serif; font-size:14px }",

  // IMAGE
  image_list: images,
  paste_block_drop: false, // disable drag and drop
  paste_data_images: false, // disable paste image
  smart_paste: false, // disable from url to embedded image
  paste_preprocess: function (plugin, args) {
    console.log(args);
    console.log(plugin);
    console.log(args.content);
  },
});

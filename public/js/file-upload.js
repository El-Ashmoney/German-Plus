$(".image-box").click(function(event) {
	var previewImg = $(this).children("img");
	$(this)
		.siblings()
		.children("input")
		.trigger("click");
    var data_type = $(this).data('type');
	$(this)
		.siblings()
		.children("input")
		.change(function() {
			var reader = new FileReader();

			reader.onload = function(e) {
			    if(data_type!== 'file'){
                    var urll = e.target.result;
                    $(previewImg).attr("src", urll);
                    previewImg.parent().css("background", "transparent");
                    previewImg.show();
                    previewImg.siblings("p").hide();
                }else{
                    $(previewImg).attr("src", static_images_path+'/file_image.png');
                    previewImg.parent().css("background", "transparent");
                    previewImg.show();
                    previewImg.siblings("p").hide();
                }

			};
			reader.readAsDataURL(this.files[0]);
		});
});

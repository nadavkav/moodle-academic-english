$(function(){
	$( "table" ).wrap(function(){
		var ctab_obj = $(this);
		if(ctab_obj.parent('div').hasClass('no-overflow'))
		{

		}else{
			return "<div class='no-overflow'></div>";
		}

	});

	var wh = '_white';
	var si = setInterval(function(){

          $(".block").each(function() {
					var ctab_obj = $(this);
						var dock_tar = ctab_obj.find(".block_action input[type='image']");
						var dock_imgsrc = dock_tar.attr("src") || '';
						if (dock_imgsrc != '') {
							var  isunique = dock_imgsrc.indexOf(wh);
							if (isunique == -1) {
								dock_tar.attr('src', dock_imgsrc + "_white");
							 }
						}

						var tg = ctab_obj.find(".block_action img").length;
						if (tg > 0) {
							ctab_obj.find(".block_action img").each(function(){
							  var cimg = $(this);
							  var cimg_src = cimg.attr("src");
							  var  isunique = cimg_src.indexOf(wh);
							  if (isunique == -1) {
								  cimg.attr("src", cimg_src + "_white");
							   }

							 });
						}

						var ac_mu = ctab_obj.find(".block-control-actions").length;
						if (ac_mu > 0) {
							var li_img = ctab_obj.find(".block-control-actions ul.menubar li img").length;
							if (li_img > 0) {
								ctab_obj.find(".block-control-actions ul.menubar li img").each(function(){
										 var cimg = $(this);
										 var cimg_src = cimg.attr("src");
										 var  isunique = cimg_src.indexOf(wh);
										   if (isunique == -1) {
										   	  cimg.attr("src",cimg_src + "_white");
										   }
								 });
							}
						}

		      });

			$("table.flexible thead tr th, .generaltable thead tr th").each(function() {

				$cobj = $(this);
				var img_cnt = $cobj.find("img").length;
				if(img_cnt > 0) {
					var cimg = $cobj.find("img");
					var cimg_src = cimg.attr("src");
					var isunique = cimg_src.indexOf(wh);
					if (isunique == -1) {
						cimg.attr("src",cimg_src + "_white");
					}
				}

            });

				  }, 1000);

	  setTimeout(function( ) { clearInterval( si ); }, 60000);

});
;(function($, scope){
	scope['manage-photo-clone-admin'] = {
		Libs : {
			JLUserAlbum : function(id) {
				this.items = {};
				this.photos = [];
				this.itemCount = 0;
				
				this.instance = $('#user-album-' + id);
				
				this.parse = function() {
					var album = this;
					this.instance.find('li').each(function() {
						var photo = new JLUserPhoto(album);
						photo.parse($(this));
					});
				}
				
				this.remove = function(photo) {
					photo.container.fadeOut('slow').remove();
					this.itemCount--;
					
					if (this.itemCount == 0) {
						$('.no-photo').removeClass('hidden');
					}
				}
				
				this.makePrimary = function(photo) {
					this.instance.prepend(photo.container);
					this.instance.find('li').removeClass('primary');
					photo.container.addClass('primary');
					alert('/admin_manage/manageUser/makePhotoUser/binIDUser/' + photo.container.attr('user_id') + '/binIDPhoto/' + photo.container.attr('photo_id'));
					$.ajax({
						url : '/admin_manage/manageUser/makePhotoUser/binIDUser/' + photo.container.attr('user_id') + '/binIDPhoto/' + photo.container.attr('photo_id'),
						success : function(res) {
							if (!res.error) {
								var notifyOptions = {
									message	: res.message,
									autoHide : true,
									timeOut : 2
								}
								jlbd.dialog.notify(notifyOptions);
							}
						}
					});
					
				}
				
				this.addPhoto = function(options) {
					var photo = new JLUserPhoto(this);
					photo.create(options);
					return photo;
				};
				
				JLUserPhoto = function(parent) {
					this.parent = parent;
					
					// init
					this.parse = function(jLIInstance) {
						this.container = jLIInstance;
						this.instance = this.container.find('img');
						this.btnDelete = this.container.find('a.wd-delete-image');
						this.link = this.container.find('a.view');
						
						this.bindButtonEvent();
						this.parent.itemCount++;
					}
					
					this.create = function(options) {
						this.container = $("<li></li>");
						this.container.addClass('wd-current');
						this.container.attr('photo_id', options.photoID);
						
						this.link = $("<a href='#' class='view make-as-primary'></a>");
						this.container.append(this.link);
						
						this.instance = $("<img src='"+options.src+"' alt='"+options.alt+"' />");
						this.link.append(this.instance);
						
						this.btnDelete = $("<a href='"+options.deleteUrl+"' class='wd-delete-image'>remove</a>");
						this.container.append(this.btnDelete);
						
						this.parent.instance.append(this.container);
						
						this.bindButtonEvent();
						this.parent.itemCount++;
					}
					
					
					var photo = this;
					
					this.bindButtonEvent = function() {
						var album = this.parent;
						
						// bind delete button
						this.btnDelete.click(function() {
							if (confirm("Are you sure to delete this photo?")) {
								$.ajax({
									url : $(this).attr('href'),
									dataType : 'json',
									success : function(res) {
										if (!res.error) {
											album.remove(photo);
											
										} else {
											jlbd.dialog.alert("JustLook Message", res.message);
										}
									}
								});
							}
							return false;
						});
						
						// make as primary
						this.link.click(function() {
							if (!$(this).parent().hasClass('primary')) {
								jlbd.dialog.confirm("Justlook Confirmation", "You are about to make this photo as primary. Are you sure?", function(r) {
									if (r) {
										album.makePrimary(photo);
									}
								});
							}
							
							return false;
						});
					}
				}
			},
		}
	}
})(jQuery, jlbd);

$(document).ready(function() {
	$("a.btnAddPhoto_admin").fancybox({
		'width'				: 660,
		'height'			: 390,
		'autoScale'			: false,
	});	
//	var userAlbum = new jlbd['manage-photo'].Libs.JLUserAlbum('userphoto');
//	userAlbum.parse();
	
	// adding event to fileuploader
/*	$('#photoUploader').bind('fileuploaddone', function (e, data) {
		$('.no-photo').addClass('hidden');
		for (var i = 0; i < data.result.length; i++) {
			var photo = userAlbum.addPhoto({
				src : data.result[i]['200-200-thumbnail_url'],
				alt : data.result[i].filename,
				deleteUrl : data.result[i].delete_url,
				photoID : data.result[i].photo_id
			});
			new xii.gallery.Libs.XIIImage(photo.instance);
		}
	});
	
	// close button
	$('#upload_photo_form a.btnClose').click(function() {
		$.fancybox.close();
	});*/
});


function registerMediaClick(){
	$('.media_src').on('click', function(event){ 
		event.preventDefault();
		var item_url = $(this).attr('href');
		document.getElementById(mediaBrowser.editorInput).value = item_url;
		$('#mediaModal').modal('hide');
	});
}


$(document).ready(function(){
	
	if($('#batchMediaDelete').length){
		$('#batchMediaDelete').on('click',function(event) {
			event.preventDefault();
			if(confirm('Are you sure you want to delete these items?')){
			    var keys = $('#mediaGrid').yiiGridView('getSelectedRows'); // returns an array of pkeys, and #grid is your grid element id
			    $.post({
			       url: 'index.php?r=media/batch-delete', // your controller action
			       dataType: 'json',
			       data: {keys: keys},
			       success: function(data) {
			          if (data.status === 'success') {
				          $.pjax.reload({container:'#mediaGridPjax'});
			          } else if(data.status === 'error') {
			              alert('Selected media was not deleted!');
			          }
			       },
			    });
			} else {
				return false;
			}
		});
	}
	
	if($('#mediaUploadContainer').length){
				
		$('#mediaUploadButton').on('click', function(event){
			event.preventDefault();
			$('#mediaUploadContainer').toggle();
		});
				
		$('#mediaUploadForm').on('beforeSubmit', function(event){
			event.preventDefault();
			var form = $(this);
			var mediaInput = document.getElementById('mediaInput');
			var files = mediaInput.files;
			var formData = new FormData(form[0]);
			$.each(files, function(key, file){
				formData.append('files[]', file, file.name);
			});
			if(!form.find('.has-error').length){
				$.post({
					url: 'index.php?r=media/upload',
					data: formData,
					cache: false,
					processData: false,
					contentType: false,
					success: function(data){
						if(data.status === 'success'){
							var mediaIndexUrl = 'index.php?r=media/index' + (typeof mediaUrlPostfix !== 'undefined' ? mediaUrlPostfix : '');
							$.pjax({url: mediaIndexUrl, container: '#mediaGridPjax', push: false});
							mediaInput.value = '';
						} else {
						}
												
					},
					error: function(jqXHR, textStatus, errorThrown){console.log('ERRORS: ' + errorThrown);}
				});
			}
			return false;
		});
	}

	if($('#mediaModal').length){
		var mediaUrlPostfix = '-modal';
		loadMediaBrowser();
		$(document).on('pjax:success', function() {	
			registerMediaClick();
		});
	}
	
	
});

function loadMediaBrowser(){
		var csrfToken = $('meta[name="csrf-token"]').attr("content");
		$.post( "index.php?r=media/index-modal", {_csrf: csrfToken}, function( data ) {
			$('#mediaGridPjax').replaceWith(data);
			$('#mediaModal').css('z-index', 65537);
			registerMediaClick();
		});
}

var mediaBrowser = {
	init: function(fieldName){
		mediaBrowser.editorInput = fieldName;		
		$('#mediaModal').modal('show');
		return false;
	}
}


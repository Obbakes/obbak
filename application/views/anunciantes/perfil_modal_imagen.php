<style>

.img-container img {
  width: 100%;
}
</style>
	<script>
    window.addEventListener('DOMContentLoaded', function () {
      var avatar = document.getElementById('avatar');
      var image = document.getElementById('image');
      var input = document.getElementById('input');
      var $modal = $('#modal-imagen-usuario');
      var cropper;

      input.addEventListener('change', function (e) {
        var files = e.target.files;
        var done = function (url) {
          input.value = '';
          image.src = url;
          $modal.modal('show');
        };
        var reader;
        var file;
        var url;

        if (files && files.length > 0) {
          file = files[0];
          if (URL) {
            done(URL.createObjectURL(file));
          } else if (FileReader) {
            reader = new FileReader();
            reader.onload = function (e) {
              done(reader.result);
            };
            reader.readAsDataURL(file);
          }
        }
      });

      $modal.on('shown.bs.modal', function () {
        cropper = new Cropper(image, {
          aspectRatio: 1,
          viewMode: 3,
        });
      }).on('hidden.bs.modal', function () {
        cropper.destroy();
        cropper = null;
      });

      document.getElementById('crop').addEventListener('click', function () {
        var initialAvatarURL;
        var canvas;

        $modal.modal('hide');

        if (cropper) {
          canvas = cropper.getCroppedCanvas({
            width: 100,
            height: 100,
          });
          initialAvatarURL = avatar.src;
          avatar.src = canvas.toDataURL();
          canvas.toBlob(function (blob) {
            var formData = new FormData();
            formData.append('avatar', blob, 'avatar.png');
            $.ajax('<?php echo base_url();?>anunciantes/imagenUsuario', {
              method: 'POST',
              data: formData,
              processData: false,
              contentType: false,
              success: function (response) {
	 	       	  $('#btn-borrar-imagen').show(); 
	 	          $('#btn-subir-imagen').hide();
                  var boxTitle = document.getElementsByClassName('ibox-title');
                  boxTitle[0].insertAdjacentHTML('beforebegin', response)
              },
              error: function () {
                avatar.src = initialAvatarURL;
              },
              complete: function () {
              },
            });
          });
        }
      });
    });
  </script>
	
	
	<div class="modal fade" id="modal-imagen-usuario" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalLabel">Seleccionar imagen</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="img-container">
              <img id="image">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
            <button type="button" class="btn btn-primary" id="crop">Seleccionar</button>
          </div>
        </div>
      </div>
    </div>
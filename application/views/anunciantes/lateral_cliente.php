<link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
<link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet"/>
<link href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css" rel="stylesheet"/>

<div class="user-info">
    <a class="image" href="<?php echo base_url();?>anunciantes/perfil">
        <?php if (empty ($cliente->imagen)){?>
            <div class="upload">
                <input type="file"
                       class="filepond"
                       name="filepond"
                       accept="image/png, image/jpeg, image/gif"/>
            </div>
        <?php } else {?>
            <img alt="imagen" class="img-circle" src="<?php echo base_url().$cliente->imagen;?>" />
        <?php } ?>

    </a>
    <div class="detail">
        <h4><?php echo $cliente->nombre;?></h4>
        <small class="text-uppercase"><?php echo $cliente->nombre_contacto.' '.$cliente->apellidos_contacto;?></small>
    </div>

</div>
<script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-crop/dist/filepond-plugin-image-crop.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-resize/dist/filepond-plugin-image-resize.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-transform/dist/filepond-plugin-image-transform.js"></script>
<script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>


<script src="https://unpkg.com/filepond/dist/filepond.js"></script>

<script>
    // Get a reference to the file input element
    const inputElement = document.querySelector('input[type="file"]');

    FilePond.registerPlugin(
        FilePondPluginFileValidateType,
        FilePondPluginImageExifOrientation,
        FilePondPluginImagePreview,
        FilePondPluginImageCrop,
        FilePondPluginImageResize,
        FilePondPluginImageTransform,
        FilePondPluginImageEdit
    );

    // Create a FilePond instance
    const pond = FilePond.create(inputElement,
        {
            labelIdle: `Drag & Drop your picture or <span class="filepond--label-action">Browse</span>`,
            imagePreviewHeight: 170,
            imageCropAspectRatio: '1:1',
            imageResizeTargetWidth: 100,
            imageResizeTargetHeight: 100,
            stylePanelLayout: 'compact circle',
            styleLoadIndicatorPosition: 'center bottom',
            styleProgressIndicatorPosition: 'right bottom',
            styleButtonRemoveItemPosition: 'left bottom',
            styleButtonProcessItemPosition: 'right bottom',
        });
    FilePond.setOptions({
        server: './upload.php',
        instantUpload: false,
    });
</script>
<style>
    .upload .filepond--root {

    }

</style>



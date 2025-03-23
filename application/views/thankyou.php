
<section id="features" class="container services">
    <div class="row"> </div>
</section>

<div class="thankYou" >
    <div class="correct">
        <h1>
            ¡Muchas gracias por enviar tu solicitud!
        </h1>

        <!--<p>Ya estamos trabajando para procesar tu solicitud</p>-->
        <!--<p>Es importante que antes de validar tu acceso a nuestra plataforma, confirmemos el visto bueno de los medios colaboradores. Nos pondremos en contacto contigo en un plazo máximo de 48 horas para comunicarte la resolución de tu solicitud.</p>-->

        <!--<p>
            En caso de no recibir nuestra respuesta, verifica por favor en tu correo electrónico la carpeta de “No deseados”
            o “Spam” o márcanos como correo seguro.
        </p>-->
        <p>Recuerda completar tu perfil para tener acceso a todos los medios colaboradores.</p>
        <a class="boton-acceso" href="<?php echo base_url();?>/inicio/login">Accede a tu cuenta</a>
        <div class="hr_mod">
            <p style="text-align: right">Equipo Técnico de <b>bimads</b></p>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        var alto = document.body.clientHeight;
        var footer= $('inicio/footer_principal').height();
        var header= $('.inicio/default_login').height();
        $('.thankYou').height(alto-footer-header-100);
    });
</script>
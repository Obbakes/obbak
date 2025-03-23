<footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <span class="copyright">Copyright &copy; bimads 2016</span>
                </div>
                <div class="col-md-4">
                    <span><img src="<?php echo base_url();?>images/e-certificada_HORIZONTAL.jpg"></span>
                    <!--<ul class="list-inline social-buttons">
                        <li><a href="#"><i class="fa fa-twitter"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-facebook"></i></a>
                        </li>
                        <li><a href="#"><i class="fa fa-linkedin"></i></a>
                        </li>
                    </ul>-->
                </div>
                <div class="col-md-4">
                    <ul class="list-inline quicklinks">
                        <li><a href="<?php echo base_url();?>inicio/condiciones">Política de Privacidad</a>
                        </li>
                        <li><a href="<?php echo base_url();?>inicio/condiciones#terminos_uso">Términos de Uso</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <script>
        
            $(document).ready(function(){
                $('[data-toggle="tooltip"]').tooltip();   

            });


            var popupStatus = 1;
            //Aligning our box in the middle
            var windowWidth = document.documentElement.clientWidth;
            var windowHeight = document.documentElement.clientHeight;
            var popupHeight = $("#popupProduct").height();
            var popupWidth = $("#popupProduct").width();

            $(document).ready(function() {
                    checkCookie();

                    //centering
                    $("#popupProduct").css({
                            "position": "fixed",
                            //  "top": windowHeight / 4 - popupHeight / 2,
                            //	"top": '3%',
                            //	"left": windowWidth / 2 - popupWidth / 2
                    });

                    //aligning our full bg
                    /*$("#ProductBackgroundPopup").css({
                            "height": windowHeight
                    });*/

                    // Pop up the div and Bg
                    if (popupStatus == 0) {
                            $("#ProductBackgroundPopup").css({
                                    "opacity": "0.7"
                            });
                            $("#popupProduct").fadeIn("slow");
                            popupStatus = 1;
                    }

                    //Close Them
                    $("#popupProductClose").click(function() {
                            if (popupStatus == 1) {
                                    $("#popupProduct").fadeOut("slow");
                                    popupStatus = 0;
                            }

                            aceptar_cookies();
                    });

                    //Close Them
                    $("#popupProductAccept").click(function() {
                            if (popupStatus == 1) {
                                    $("#popupProduct").fadeOut("slow");
                                    popupStatus = 0;
                            }

                            aceptar_cookies();
                    });
            });

            function aceptar_cookies(){
                    setCookie("acepta_cookies_nolimitsmedia", "ok", 365);
            }

            function getCookie(cname){
                    var name = cname + "=";
                    var ca = document.cookie.split(';');

                    for(var i=0; i<ca.length; i++){
                            var c = ca[i].trim();

                            if (c.indexOf(name) == 0)
                                    return c.substring(name.length,c.length);
                    }

                    return "";
            }

            function setCookie(cname, cvalue, exdays){
                    var d = new Date();

                    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));

                    var expires = "expires=" + d.toGMTString();

                    document.cookie = cname + "=" + cvalue + "; " + expires;
            }

            function checkCookie(){
                    var value = getCookie("acepta_cookies_nolimitsmedia");

                    if (value == ""){
                            popupStatus = 0;
                    }
            }

            function cerrarcookie(){
                    if (popupStatus == 1) {
                            $("#popupProduct").fadeOut("slow");
                            popupStatus = 0;
                    }
                    aceptar_cookies();

            }
    </script>
    <!-- jQuery -->
    <script src="<?php echo base_url();?>vendor/jquery/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="<?php echo base_url();?>vendor/bootstrap/js/bootstrap.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js"></script>

    <!-- Contact Form JavaScript -->
    <script src="<?php echo base_url();?>js/jqBootstrapValidation.js"></script>
    <script src="<?php echo base_url();?>js/contact_me.js"></script>

    <!-- Theme JavaScript -->
    <script src="<?php echo base_url();?>js/agency.min.js"></script>

<html>

  <head>
    <meta charset="utf-8">
  <link rel="stylesheet" type="text/css" href="http://localhost:8000/parabellum/app/images/slider_2/slick/slick.css"/>
  <link rel="stylesheet" type="text/css" href="http://localhost:8000/parabellum/app/images/slider_2/slick/slick-theme.css"/>

  <link href="http://localhost:8000/parabellum/app/images/slider_2/twentytwenty-master/css/foundation.css" rel="stylesheet" type="text/css" />
  <link href="http://localhost:8000/parabellum/app/images/slider_2/twentytwenty-master/css/twentytwenty.css" rel="stylesheet" type="text/css" />

  <script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
  <script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
  <script type="text/javascript" src="http://localhost:8000/parabellum/app/images/slider_2/slick/slick.min.js"></script>
  <script src="http://localhost:8000/parabellum/app/images/slider_2/twentytwenty-master/js/jquery.event.move.js"></script>
  <script src="http://localhost:8000/parabellum/app/images/slider_2/twentytwenty-master/js/jquery.twentytwenty.js"></script>
  
  
  <style>
    .slick-prev::before, .slick-next::before {
  font-family: 'slick';
  font-size: 20px;
  line-height: 1;
  opacity: .75;
  color: black;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
    #padre {
    position: relative;
    justify-content: center;
    height: 600px;
    width:100%;
    background-color:white;
    }
    .hijo {
    height:550px;
    width:850px;
    margin:0px auto;
}
  </style>
   </head>
   <body>
   <script>
    let objetos3D = [];
  </script>
   <div id="padre">
      <div class="hijo">

      <?php

  
        //$fichero='./file/slider.csv';
       // $file = fopen( './file/slider.csv', "r");
        
       // while (!feof($file)) {
       //$data = fgetcsv($file,null,';');
       //print_r($data);
       //if(!empty($data)){
            /* REVISAMOS QUE LA LINEA SE DE IMAGEN
            if( file_exists(__DIR__."/".$_REQUEST["r"]."/after.jpg") &&  file_exists(__DIR__."/".$_REQUEST["r"]."/before.jpg")) {
                echo '<div id="container" class="twentytwenty-container">';
                echo '<img src="http://localhost:8000/parabellum/app/images/sonrisa/'.$_REQUEST["r"].'/after.jpg" width="850" height="550"/>';
                echo '<img src="http://localhost:8000/parabellum/app/images/sonrisa/'.$_REQUEST["r"].'/before.jpg" width="850" height="550"/>';
                echo '</div>';
            }*/
            if( file_exists(__DIR__."/".$_REQUEST["r"]."/video_1.mp4")) {
              echo'<div><iframe width="850" height="550" src="http://localhost:8000/parabellum/app/images/sonrisa/'.$_REQUEST["r"].'/video_1.mp4" title="YouTube video player" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
            }
            if( file_exists(__DIR__."/".$_REQUEST["r"]."/imagen_1.png")) {
                echo '<div><img src="http://localhost:8000/parabellum/app/images/sonrisa/'.$_REQUEST["r"].'/imagen_1.png"></div>';
            }
            if( file_exists(__DIR__."/".$_REQUEST["r"]."/imagen_2.png")) {
              echo '<div><img src="http://localhost:8000/parabellum/app/images/sonrisa/'.$_REQUEST["r"].'/imagen_2.png"></div>';
            }
            
            $files = scandir(__DIR__."/".$_REQUEST["r"]);
          
       
            $index3D = 0;
            foreach($files as $file){
              $len = strlen(".ply");
              if( substr($file, -$len) === ".ply"){
                  echo "<div><div id='container".$index3D."'></div>";
                  echo "<script>objetos3D.push({'contenedor':'container".$index3D."','formato': 'ply', 'objeto':'http://localhost:8000/parabellum/app/images/sonrisa/".$_REQUEST["r"]."/".$file."' })</script></div>";            
                  $index3D  ++;
              }
            }

          
          /*
                // REVISAMOS QUE LA LINEA SEA DE VIDEO
                if($data[0]=='video'){
                   // Segun el formato del video generamos el elemento
                    if($data[1]=='youtube'){
                        echo'<div><iframe width="850" height="550" src="'.$data[2].'" title="YouTube video player" frameborder="0" allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>';
                    }
                    if($data[1]=='mp4'){
                        echo'<div><video src="'.$data[2].'"></div>';
                    }
                }
           
            /*
                //REVISAMOS QUE LA LINEA SEA UN OBJETO 3D 
                if($data[0]==='3d'){
                   // Revisamos el formato del objeto 3d
                    $index = 0;
                    if($data[1]=='ply'){
                        echo "<div><div id='container".$index."'></div>";
                        echo "<script>objetos3D.push({'contenedor':'container".$index."','formato': '".$data[1]."', 'objeto':'".$data[2]."' })</script></div>";
                    }
                }
   */
       //   }
       // }
       // fclose($file);
    ?>
      </div>
    </div>

  
  <script type="text/javascript">
      $(document).ready(function(){
          $("#container").twentytwenty();

          $('.hijo').slick({
              dots: true,
              speed: 300,
              slidesToShow: 1,
              draggable: false,
          });

      })
  </script>
  <script async src="https://unpkg.com/es-module-shims@1.3.6/dist/es-module-shims.js"></script>
  <script type="importmap">
    {
      "imports": {
        "three": "http://localhost:8000/parabellum/app/images/slider_2/three/build/three.module.js"
      }
    }
  </script>
  <script type="module">

    import * as THREE from 'three';

    import { OrbitControls } from 'http://localhost:8000/parabellum/app/images/slider_2/three/examples/jsm/controls/OrbitControls.js'

    import { PLYLoader } from 'http://localhost:8000/parabellum/app/images/slider_2/three/examples/jsm/loaders/PLYLoader.js'

    let camera, scene, renderer;

    objetos3D.forEach((item)=>{
      console.log(item['contenedor'])
        init(item)
        render()
    })


    function init(item) {

        let container = document.getElementById( ''+item.contenedor );

        scene = new THREE.Scene();
        scene.background = new THREE.Color( 0xa0a0a0 );


        var hemiLight = new THREE.HemisphereLight( 0xffffff, 0xffffff, 0.6 );
        hemiLight.position.set( 0, 70, 0 );
        hemiLight.castShadow = true
        scene.add( hemiLight );

        var dirLight = new THREE.DirectionalLight( 0xffffff, 0.8 );
        dirLight.position.set( 0, 1, 1 );
        dirLight.castShadow = true;
        dirLight.shadow.camera.top = 0.2;
        dirLight.shadow.camera.bottom = - 0.2;
        dirLight.shadow.camera.left = - 0.2;
        dirLight.shadow.camera.right = 0.2;
        dirLight.shadow.camera.near = 0.1;
        dirLight.shadow.camera.far = 2;
        dirLight.shadow.mapSize.set( 1024, 1024 );
        scene.add( dirLight );

        camera = new THREE.PerspectiveCamera( 20, window.innerWidth / window.innerHeight, 1, 100 );
        camera.position.set( 0,20, 25);
        camera.rotation.set(0, 0,0);

        renderer = new THREE.WebGLRenderer( { antialias: true, alpha: true } );
        renderer.setPixelRatio( window.devicePixelRatio );
        renderer.setSize( 850, 550);
        renderer.toneMapping = THREE.ACESFilmicToneMapping;
        renderer.toneMappingExposure = 1;
        renderer.outputEncoding = THREE.sRGBEncoding;
        renderer.shadowMap.enabled = true;

        container.appendChild( renderer.domElement );

        let controls = new OrbitControls( camera, renderer.domElement );
        controls.addEventListener( 'change', render ); // use if there is no animation loop
        controls.minDistance = 1;
        controls.maxDistance = 200;
        controls.target.set( 0, 0, 1 );
        controls.update();

        let axesHelper = new THREE.AxesHelper( 5 );
        scene.add( axesHelper );

        if(item.formato ==='ply'){

            let loader = new PLYLoader();
            loader.load(  item.objeto, function ( geometry ) {

                geometry.computeVertexNormals();

                let material = new THREE.MeshPhongMaterial({flatShading: true, vertexColors: true} );
                let mesh = new THREE.Mesh( geometry, material );

                mesh.scale.set(0.2,0.2,0.2)
                mesh.rotation.set(90 *(Math.PI/180),180 *(Math.PI/180), 0 *(Math.PI/180))

                mesh.castShadow = true;
                mesh.receiveShadow = true;


                scene.add( mesh );
                render()

            } ,function ( xhr ) {

                console.log( ( xhr.loaded / xhr.total * 100 ) + '% loaded' );

            }, );
        }
      }

        function animate() {
          requestAnimationFrame( animate );
          render();
          //stats.update();
        }

        function render() {
          renderer.render( scene, camera );
        }

    </script>
  
  </body>
</html>

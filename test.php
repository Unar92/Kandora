<style>
  .Select,
  #toolbar,
  #viewHelper,
  #sidebar,
  #menubar,
  #info,
  .lil-gui.allow-touch-styles {
    display: none;
  }

  .panel-3d div {
    width: 100%;
    top: 0 !important;
  }

  .panel-3d {
    position: relative;
  }

  @media screen and (max-width:767px) {
    .panel-3d {
      margin-bottom: 20px;
    }
  }



  .custom-radio span i {
    width: 30px;
    height: 100%;
    margin-right: 15px;
    border: 1px solid #777;
  }
</style>

<link rel="stylesheet" href="css/main.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>

<script src="./examples/jsm/libs/draco/draco_encoder.js"></script>

<script type="importmap">
      {
        "imports": {
          "three": "./build/three.module.js",
          "three/addons/": "./jsm/"
        }
      }
    </script>

<script type="module">

  import * as THREE from 'three';
  import { GUI } from 'three/addons/libs/lil-gui.module.min.js';
  import { OrbitControls } from 'three/addons/controls/OrbitControls.js';
  import { EffectComposer } from 'three/addons/postprocessing/EffectComposer.js';
  import { RenderPass } from 'three/addons/postprocessing/RenderPass.js';
  import { TexturePass } from 'three/addons/postprocessing/TexturePass.js';
  import { GLTFLoader } from 'three/addons/loaders/GLTFLoader.js';
  import { RGBELoader } from 'three/addons/loaders/RGBELoader.js';

  let camera, scene, renderer;
  let gui;

  const state = { variant: 'midnight' };

  init();
  render();

  function init() {
    const container = document.querySelector('.panel-3d');

    camera = new THREE.PerspectiveCamera(25, container.clientWidth / container.clientHeight, 0.25, 100);
    camera.position.set(1, 2,  8);
    //camera zoom
    camera.zoom = 1;
    camera.updateProjectionMatrix();

    //console camera position
    console.log(camera.position);

    scene = new THREE.Scene();

    new RGBELoader()
      .setPath('models/')
      .load('studio_small_08_1k.hdr', function (texture) {


        // texture.mapping = THREE.EquirectangularReflectionMapping;

        // scene.background = texture;
        // scene.environment = texture;

        scene.background = new THREE.Color(0xf1f1f1);
        scene.environment = null;
        // renderer.toneMapping = THREE.LinearToneMapping;
        // renderer.toneMappingExposure = 1;

        //punctual light
        const Plight = new THREE.PointLight(0xffffff, 0);

        
        
     


       
        //ambient intensity 0
        scene.add(new THREE.AmbientLight(0x000000, 0));
        //direct intensity 0
        const light = new THREE.DirectionalLight(0x000000, 0);

        render();

        const loader = new GLTFLoader().setPath('models/');
        loader.load('7 11 24 (2).glb', function (glb) {
          glb.scene.scale.set(1.7, 1.7, 1.7);
          glb.scene.position.set(0, -1.1, 0);
          scene.add(glb.scene);

          //remove embroidery_plane from scene
          // scene.traverse((child) => {
          //   if (child.isMesh && child.name === 'Embriodery_plane005') {
              
          //     child.visible = false;
          //   }
          //   if (child.isMesh && child.name === 'Embriodery_plane002') {
          //     child.visible = false;
              
          //   }
          //   if (child.isMesh && child.name === 'tarboosh_tongue') {
          //     child.visible = false;
          //   }
          //   if (child.isMesh && child.name === 'tarboosh_1') {
          //     child.visible = false;
          //   }
          // });

          gui = new GUI();
          const parser = glb.parser;

          render();
        });
      });


    //color picker event
    const colorPickers = document.getElementsByClassName('colorPicker');
    Array.from(colorPickers).forEach(picker => {
      picker.addEventListener('input', function (event) {
        const colorValue = event.target.value;
        updateColorProperty(colorValue);
      });
    });



    // Function to update color property
    function updateColorProperty(value) {
      // consoogle.l("Color change");
      //change object color
      scene.traverse((child) => {
        if (child.isMesh) {
          child.material.color.set(value);
          //emisive color set
          child.material.emissive.set(value);
          //update texture change
          child.material.needsUpdate = true;
          //update scene
          render();
        }
      });

      // Ensure the texture update is visible when the model is moved

    }





   
    function updateEmbroideryTexture(textureUrl) {
    // Preload the image
    var img = new Image();
    img.src = textureUrl;

    img.onload = function () {
      // Image is preloaded, proceed with updating the texture
      console.log('Embroidery texture change');
      const loader = new THREE.TextureLoader();
      loader.load(textureUrl, function (texture) {
        texture.wrapS = THREE.RepeatWrapping;
        texture.wrapT = THREE.RepeatWrapping;
        texture.needsUpdate = true; // Ensure the texture is updated

      scene.traverse((child) => {
        // Embriodery_plane005 use for new model and for old model use Embriodery_plane
        if (child.isMesh && child.name === 'Embriodery_plane005') { // Ensure you have a way to identify the embroidery mesh
          
            //add specular map with jpeg
            // child.material.specularMap = texture;

            
            

            //console log child name
            console.log("c name",child.name);
            
            if (child.name === 'Embriodery_plane005') {
              //remove old texture
              //dispose old texture
              child.material.map.dispose();
              child.material.map = null;
              
              child.material.needsUpdate = true; // Ensure the material updates immediately
              child.material.map = texture;
              //use emission map
              child.material.emissiveMap = texture;
              child.material.needsUpdate = true; // Ensure the material updates immediately

              //get current base color code and apply for emisive 
              child.material.emissive.set(child.material.color);
             
              
             

              
              child.material.emissiveIntensity = 0.5; // Set emissive intensity to 0
              child.material.roughness = 0.5; // Adjust roughness
              child.material.metalness = 0.5; // Adjust metalness

              child.material.transparent = true;
              child.material.opacity = 0;
              gsap.to(child.material, {
              duration: 1,
              opacity: 1,
              onUpdate: function () {
                child.material.needsUpdate = true;
              }
              });
            }

          // Move the camera to view the embroidery
          const embroideryPosition = child.position.clone();
          const cameraTarget = new THREE.Vector3(embroideryPosition.x, embroideryPosition.y, embroideryPosition.z);

          // Capture the current camera position, zoom level, and controls target
          const currentCameraPosition = camera.position.clone();
          const currentZoom = camera.zoom;
          const currentTarget = controls.target.clone();

          // Log current camera and controls state
          console.log('Current Camera Position:', currentCameraPosition);
          console.log('Current Zoom:', currentZoom);
          console.log('Current Controls Target:', currentTarget);

          // Desired camera position, zoom level, and controls target
          const desiredZoom = 6; // Adjust this value to zoom in
          const desiredPosition = {
            x: embroideryPosition.x + -3, // Adjust these values as needed
            y: embroideryPosition.y + 0,
            z: embroideryPosition.z + 5
          };
          const desiredTarget = embroideryPosition.clone();

          // Log desired camera and controls state
          console.log('Desired Camera Position:', desiredPosition);
          console.log('Desired Zoom:', desiredZoom);
          console.log('Desired Controls Target:', desiredTarget);

          // Check if the camera is already at the desired zoom level and position
if (currentZoom !== desiredZoom || !currentCameraPosition.equals(new THREE.Vector3(desiredPosition.x, desiredPosition.y, desiredPosition.z))) {
  const timeline = gsap.timeline();

  var minZoom = 0.5;
var maxZoom = 5;
// controls.minDistance = minZoom; // Minimum zoom distance
// controls.maxDistance = maxZoom; // Maximum zoom distance

timeline.to(camera, {
    duration: 1,
    zoom: Math.max(minZoom, Math.min(maxZoom, desiredZoom)), // Ensure desiredZoom is within range
    ease: "power2.inOut", // Use easing function for smooth animation
    onUpdate: function () {
      camera.zoom = Math.max(minZoom, Math.min(maxZoom, camera.zoom)); // Clamp the zoom level
      camera.updateProjectionMatrix(); // Ensure the camera's projection matrix is updated
    },
});

  timeline.to(camera.position, {
    duration: 1,
    x: desiredPosition.x,
    y: desiredPosition.y,
    z: desiredPosition.z,
    onUpdate: function () {
      //camera zoom
      camera.lookAt(cameraTarget);
      controls.update(); // Ensure controls are updated
      render();
    }
  }, 0); // Start at the same time as the zoom animation

  timeline.to(controls.target, {
    duration: 1,
    x: desiredTarget.x,
    y: desiredTarget.y,
    z: desiredTarget.z,
    onStart: function () {
      controls.minDistance = 1; // Ensure min zoom distance is set before animation
      controls.maxDistance = 20; // Ensure max zoom distance is set before animation
    },
    onUpdate: function () {
      controls.update();
      render();
    },
    onComplete: function () {
      controls.update();
      controls.minDistance = 1; // Ensure min zoom distance is set after animation
      controls.maxDistance = 20; // Ensure max zoom distance is set after animation
    }
  }, 0); // Start at the same time as the zoom animation
}
          render();
        }
      });
      render();
    });
  };
}

// Make updateEmbroideryTexture function available globally
window.updateEmbroideryTexture = updateEmbroideryTexture;
    // Make updateEmbroideryTexture function available globally

    




    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1;
    container.appendChild(renderer.domElement);

    const controls = new OrbitControls(camera, renderer.domElement);
    controls.addEventListener('change', render);
    controls.minDistance = 1; // Minimum zoom distance
    controls.maxDistance = 15; // Maximum zoom distance
    controls.target.set(0, 0.5, -0.2);
    controls.update();

    window.addEventListener('resize', onWindowResize);

    function selectVariant(scene, parser, extension, variantName) {
      const variantIndex = extension.variants.findIndex((v) => v.name.includes(variantName));
      scene.traverse(async (object) => {
        if (!object.isMesh || !object.userData.gltfExtensions) return;
        const meshVariantDef = object.userData.gltfExtensions['KHR_materials_variants'];
        if (!meshVariantDef) return;
        if (!object.userData.originalMaterial) {
          object.userData.originalMaterial = object.material;
        }
        const mapping = meshVariantDef.mappings.find((mapping) => mapping.variants.includes(variantIndex));
        if (mapping) {
          object.material = await parser.getDependency('material', mapping.material);
          parser.assignFinalMaterial(object);
        } else {
          object.material = object.userData.originalMaterial;
        }
      });
    }
  }

  function onWindowResize() {
    const container = document.querySelector('.panel-3d');
    camera.aspect = container.clientWidth / container.clientHeight;
    camera.updateProjectionMatrix();
    renderer.setSize(container.clientWidth, container.clientHeight);
    render();
  }

  function render() {
    renderer.render(scene, camera);
  }

</script>

<?php include 'header.php' ?>
<section class="pages-navigation bg-FAFAFA">
  <div class="container-1820">
    <div class="navigation-items">
      <ul>
        <li>
          <a href="#">Home</a>
        </li>
        <li>
          <a href="#">Customize Your Kandora</a>
        </li>
      </ul>
    </div>
  </div>
</section>
<section class="heading-name-pages bg-F7E0E1">
  <div class="container-1820">
    <h2 class="color-red heading-pages-name text-center font-weight-bold">Customize Your Kandora</h2>
  </div>
</section>

<section class="my-60-175">
  <div class="container-1550">
    <div class="customize-your-kandora-main">
      <div class="kandora-image-section" id="customize-scene">
        <h3 class="image-heading">Choose Your Kandora Style</h3>
        <!-- <div class="kandora-style-slider sticky-portion">
          <div class="kandora-style-items">
            <img src="assets/images/customizeyourkandora/select-emirati-style-img1.png" alt="" />
            <button class="btn-reg select-style-btn" data-value="Emirati Style">Select Emirati Style</button>
          </div>
          <div class="kandora-style-items">
            <img src="assets/images/customizeyourkandora/select-emirati-style-img1.png" alt="" />
            <button class="btn-reg select-style-btn" data-value="Fancy Style">Select Fancy Style</button>
          </div>
        </div> -->
        <!-- <iframe style="width:100%;height:500px;"  src="editor/" class="kandora-customizer-frame">

</iframe> -->

        <div class="panel-3d" style="width:100%;height:calc(75vh);">

        </div>
        <div class="kandora-image-select">
          <img src="assets/images/customizeyourkandora/select-img.png" alt="">
        </div>
      </div>
      <div class="kandora-customize-section">
        <!-- <div class="customize-section-box customization-summary d-none desktop sticky-portion my-0">
          <h3>Customization Summary</h3>
          <div class="table-responsive table-sliding-on-click">
            <div class="table-heding">
              <div class="">Options</div>
              <div class="">Selected</div>
              <div class="checkmark-settings-sticky"><img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" /></div>
            </div>
            <div class="open-summary"  style="display:none">
            <table class="table">
              <tbody>
                <tr>
                  <td>Kandora Type</td>
                  <td class="kandora-type"></td>
                  <td class="kandora-type-checkmark"><img src="assets/images/customizeyourkandora/checkmark.svg" alt=""
                      class="d-none" /></td>
                </tr>
                <tr>
                  <td>Fabric</td>
                  <td class="fabric_radio"></td>
                  <td><img src="assets/images/customizeyourkandora/checkmark.svg" alt="" class="d-none" /></td>
                </tr>
                <tr>
                  <td>Size</td>
                  <td class="size_radio"></td>
                  <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
                </tr>
                <tr>
                  <td>Front Style</td>
                  <td class="front_style"></td>
                  <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
                </tr>
                <tr>
                  <td>Front Pleat Style</td>
                  <td class="front_pleat_style"></td>
                  <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
                </tr>
                <tr>
                  <td>Side Lines</td>
                  <td class="side_lines"></td>
                  <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
                </tr>
                <tr>
                  <td>Stitch Style</td>
                  <td class="stitch_style"></td>
                  <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
                </tr>
                <tr>
                  <td>Embroidery Style</td>
                  <td class="embroidery_style"></td>
                  <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
                </tr>
                <tr>
                  <td>Farukha Type</td>
                  <td class="farukha_type"></td>
                  <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
                </tr>
                <tr>
                  <td>Tarboosh Style</td>
                  <td class="tarboosh_style"></td>
                  <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
                </tr>
                <tr>
                  <td>Collar Style</td>
                  <td class="collar_style"></td>
                  <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
                </tr>
                <tr>
                  <td>Collar Thickness</td>
                  <td class="collar_thickness"></td>
                  <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
                </tr>
                <tr>
                  <td>Cuffs</td>
                  <td class="cuffs"></td>
                  <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
                </tr>
                <tr>
                  <td>Back Style</td>
                  <td class="back_style"></td>
                  <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
                </tr>
                <tr>
                  <td>Pockets</td>
                  <td class="pockets"></td>
                  <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
                </tr>
                <tr>
                  <td>Pocket Edging</td>
                  <td class="pocket_edging"></td>
                  <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
                </tr>
              </tbody>
            </table>
            </div>
          </div>
        </div> -->
        <div class="customize-section-box">
          <div class="heading">
            <h3>Choose Color</h3>
          </div>
          <div class="customize-option">





            <div class="custom-radio-group">
              <label class="custom-radio">
                <input type="radio" name="colorPicker" value="#9e8b54" class="colorPicker">

                <span><i style="background-color: #9e8b54; "></i> Moss Green</span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="colorPicker" value="#b6a38f" class="colorPicker">
                <span><i style="background-color: #b6a38f; "></i> Brown </span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="colorPicker" value="#05356d" class="colorPicker">
                <span><i style="background-color: #05356d;"></i> Blue </span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="colorPicker" value="#d1d1d1" class="colorPicker" checked>
                <span><i style="background-color: #d1d1d1; "></i> Grey White </span>
              </label>
            </div>




          </div>

        </div>


        <!-- <div class="customize-section-box" >
          <div class="heading" >
            <h3 >Choose Side Lines</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border">
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/side-lines-plain.png" alt="" />
                  </div>
                  <h4 class="side_lines">Plain</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/side-lines-plain.png" alt="" />
                  </div>
                  <h4 class="side_lines">Hijazi</h4>
                </li>
                
              </ul>
            </div>
          </div>
        </div> -->
        <!-- <div class="customize-section-box">
          <div class="heading">
            <h3>Choose Stitch Style</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border">
                <li>
                  <div class="img"   onclick="changeTextureByName('Stiches_plane', 'assets/images/customizeyourkandora/stitches/Stiches1.png')">
                    <img src="assets/images/customizeyourkandora/8-stitches.png" alt="" />
                  </div>
                  <h4 class="stitch_style">1 Stitche</h4>
                </li>
                <li>
                  <div class="img" onclick="changeTextureByName('Stiches_plane', 'assets/images/customizeyourkandora/stitches/stiches2.png')">
                    <img src="assets/images/customizeyourkandora/12-stitches.png" alt="" />
                  </div>
                  <h4 class="stitch_style">4 Stitches</h4>
                </li>
                <li>
                  <div class="img" onclick="changeTextureByName('Stiches_plane', 'assets/images/customizeyourkandora/stitches/stiches3.png')">
                    <img src="assets/images/customizeyourkandora/4-stitches-with-embroidery.png" alt="" />
                  </div>
                  <h4 class="stitch_style">6 Stitches with Embroidery</h4>
                </li>
                <li>
                  <div class="img" onclick="changeTextureByName('Stiches_plane', 'assets/images/customizeyourkandora/stitches/stiches4.png')">
                    <img src="assets/images/customizeyourkandora/12-stitches.png" alt="" />
                  </div>
                  <h4 class="stitch_style">8 Stitches</h4>
                </li>
              </ul>
            </div>
          </div>
        </div> -->
        <div class="customize-section-box">
          <div class="heading">
            <h3>Choose Embroidery Style</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border">
                <li>

                  <div class="img"
                    onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/new/E3.png')">
                    <img src="models/embroidery/_KA_4417.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 1</h4>
                </li>
                <li>
                  <div class="img"
                    onclick="updateEmbroideryTexture( 'assets/images/customizeyourkandora/embroidery/new/E2.png')">
                    <img src="models/embroidery/_KA_4427.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 2</h4>
                </li>
                <li>
                  <div class="img"
                    onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/new/E4Final.png')">
                    <img src="models/embroidery/_KA_4440.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 3</h4>
                </li>
                <li>
                  <div class="img"
                    onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/tarboosh04.png')">
                    <img src="models/embroidery/_KA_4446.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 4</h4>
                </li>
                <li>
                  <div class="img"
                    onclick="updateEmbroideryTexture( 'assets/images/customizeyourkandora/embroidery/tarboosh05.png')">
                    <img src="models/embroidery/_KA_4451.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 5</h4>
                </li>
                <li>
                  <div class="img"
                    onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/tarboosh06.png')">
                    <img src="models/embroidery/_KA_4456.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 6</h4>
                </li>
                <li>
                  <div class="img"
                    onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/tarboosh07.png')">
                    <img src="models/embroidery/_KA_4457.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 7</h4>
                </li>
                <li>
                  <div class="img"
                    onclick="updateEmbroideryTexture('models/embroidery/tarboosh08.png')">
                    <img src="models/embroidery/_KA_4460.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 8</h4>
                </li>
                <li>
                  <div class="img"
                    onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/new/em1.png')">
                    <img src="models/embroidery/_KA_4461.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 9</h4>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- <div class="customize-section-box">
          <div class="heading">
            <h3>Choose Tarboosh Style</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border">
                <li>
                  <div class="img">
                    <img onclick="changeTextureById(13, 'assets/images/customizeyourkandora/4-stitches-with-embroidery.png')" src="assets/images/customizeyourkandora/tarbosh1.png" alt="" />
                  </div>
                  <h4 class="tarboosh_style">Style 1</h4>
                </li>
                <li>
                  <div class="img">
                    <img onclick="changeObjectColorByName(editor.scene, 'kandora_arms', 0xff0000)" src="assets/images/customizeyourkandora/tarbosh2.png" alt="" />
                    

                  </div>
                  <h4 class="tarboosh_style">Style 2</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/tarbosh3.png" alt="" />
                  </div>
                  <h4 class="tarboosh_style">Style 3</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/tarbosh4.png" alt="" />
                  </div>
                  <h4 class="tarboosh_style">Style 4</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/tarbosh1.png" alt="" />
                  </div>
                  <h4 class="tarboosh_style">Style 5</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/tarbosh2.png" alt="" />
                  </div>
                  <h4 class="tarboosh_style">Style 6</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/tarbosh3.png" alt="" />
                  </div>
                  <h4 class="tarboosh_style">Style 7</h4>
                </li>
               
              </ul>
            </div>
          </div>
        </div> -->


        <div class="specific-notes">
          <div class="form-group formZn textarea">
            <div class="placeholder">
              <label for="street">Specific Notes or Instructions for Tailor</label>
            </div>
            <textarea class="form-control" rows="3"></textarea>
          </div>
          <div class="btn-group">
            <button class="btn-reg fw-600 customize-kandora-reset">Reset</button>
            <button class="btn-reg fw-600">Save Kandora Style</button>
          </div>
        </div>
      </div>

      <div class="customization-summary d-none mobile">
        <h3>Customization Summary</h3>
        <div class="table-responsive">
          <div class="table-heding">
            <div class="">Options</div>
            <div class="">Selected</div>
          </div>
          <table class="table">
            <tbody>
              <tr>
                <td>Kandora Type</td>
                <td class="kandora-type"></td>
                <td class="kandora-type-checkmark"><img src="assets/images/customizeyourkandora/checkmark.svg" alt=""
                    class="d-none" /></td>
              </tr>
              <tr>
                <td>Fabric</td>
                <td class="fabric_radio"></td>
                <td><img src="assets/images/customizeyourkandora/checkmark.svg" alt="" class="d-none" /></td>
              </tr>
              <tr>
                <td>Size</td>
                <td class="size_radio"></td>
                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
              </tr>
              <tr>
                <td>Front Style</td>
                <td class="front_style"></td>
                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
              </tr>
              <tr>
                <td>Front Pleat Style</td>
                <td class="front_pleat_style"></td>
                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
              </tr>
              <tr>
                <td>Side Lines</td>
                <td class="side_lines"></td>
                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
              </tr>
              <tr>
                <td>Stitch Style</td>
                <td class="stitch_style"></td>
                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
              </tr>
              <tr>
                <td>Embroidery Style</td>
                <td class="embroidery_style"></td>
                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
              </tr>
              <tr>
                <td>Farukha Type</td>
                <td class="farukha_type"></td>
                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
              </tr>
              <tr>
                <td>Tarboosh Style</td>
                <td class="tarboosh_style"></td>
                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
              </tr>
              <tr>
                <td>Collar Style</td>
                <td class="collar_style"></td>
                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
              </tr>
              <tr>
                <td>Collar Thickness</td>
                <td class="collar_thickness"></td>
                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
              </tr>
              <tr>
                <td>Cuffs</td>
                <td class="cuffs"></td>
                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
              </tr>
              <tr>
                <td>Back Style</td>
                <td class="back_style"></td>
                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
              </tr>
              <tr>
                <td>Pockets</td>
                <td class="pockets"></td>
                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
              </tr>
              <tr>
                <td>Pocket Edging</td>
                <td class="pocket_edging"></td>
                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" /></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- <script type="module">
    import * as THREE from 'https://cdn.jsdelivr.net/npm/three@0.132.2/build/three.module.js';
    import { GLTFLoader } from 'https://cdn.jsdelivr.net/npm/three@0.132.2/examples/jsm/loaders/GLTFLoader.js';

    let scene, camera, renderer;

    function init() {
      scene = new THREE.Scene();
      camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
      renderer = new THREE.WebGLRenderer({ antialias: true });
      renderer.setSize(window.innerWidth, window.innerHeight);
      document.querySelector('.kandora-image-section').appendChild(renderer.domElement);
      
      const light = new THREE.HemisphereLight(0xffffff, 0x444444);
      light.position.set(0, 200, 0);
      scene.add(light);

      const loader = new GLTFLoader();
      console.log(loader);
      loader.load('editor/assets/kandrora example1.glb', function (gltf) {
        scene.add(gltf.scene);
        animate();
      }, undefined, function (error) {
        console.error('An error happened', error);
      });

      camera.position.z = 5;
    }

    function animate() {
      requestAnimationFrame(animate);
      renderer.render(scene, camera);
    }

    window.addEventListener('resize', () => {
      camera.aspect = window.innerWidth / window.innerHeight;
      camera.updateProjectionMatrix();
      renderer.setSize(window.innerWidth, window.innerHeight);
    });

    init();
  </script> -->
<?php include 'footer.php' ?>
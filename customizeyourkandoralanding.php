
<style>
		.Select,
		#toolbar,
		#viewHelper,
		#sidebar,
		#menubar, #info,    .lil-gui.allow-touch-styles 
     {
			display: none;
		}
		.panel-3d div {
			width: 100%;
			top: 0 !important;
		}
    .panel-3d
    {
      position: relative;
    }

    @media screen and (max-width:767px) {
      .panel-3d
    {
      margin-bottom: 20px;
    }
    }



    .custom-radio span i
    {
      width:30px;height:100%;margin-right:15px;
      border: 1px solid #777;
    }
	</style>

<link rel="stylesheet" href="css/main.css" />

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

    camera = new THREE.PerspectiveCamera(25, container.clientWidth / container.clientHeight, 0.25, 20);
    camera.position.set(1, 2, 8);
    //camera zoom
    camera.zoom = 5;

    scene = new THREE.Scene();

    new RGBELoader()
        .setPath('models/')
        .load('studio_small_08_1k.hdr', function (texture) {


						texture.mapping = THREE.EquirectangularReflectionMapping;

						scene.background = texture;
						scene.environment = texture;

            //  scene.background = new THREE.Color(0x000000);
            // //  scene.environment = null;
            //  renderer.toneMapping = THREE.LinearToneMapping;
            //  renderer.toneMappingExposure = 1;
            
  

            //ambient intensity 0
						// scene.add(new THREE.AmbientLight(0xffffff, 0));
						//direct intensity 0
						// const light = new THREE.DirectionalLight(0xffffff, 0);

            render();

            const loader = new GLTFLoader().setPath('models/');
            loader.load('updatedmodel.glb', function (glb) {
                glb.scene.scale.set(2, 2, 2);
                glb.scene.position.set(0, -2, 0);
                scene.add(glb.scene);

               //remove embroidery_plane from scene
                scene.traverse((child) => {
                    if (child.isMesh && child.name === 'Embriodery_plane') {
                        child.visible = false;
                    }
                    if (child.isMesh && child.name === 'Embriodery_plane') {
                        child.visible = false;
                    }
                });

                gui = new GUI();
                const parser = glb.parser;

                render();
            });
        });


    //color picker event
    const colorPickers = document.getElementsByClassName('colorPicker');
    Array.from(colorPickers).forEach(picker => {
        picker.addEventListener('input', function(event) {
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

    img.onload = function() {
        // Image is preloaded, proceed with updating the texture
        console.log('Embroidery texture change');
        const loader = new THREE.TextureLoader();
        loader.load(textureUrl, function(texture) {
            texture.wrapS = THREE.RepeatWrapping;
            texture.wrapT = THREE.RepeatWrapping;
            texture.needsUpdate = true; // Ensure the texture is updated

            scene.traverse((child) => {
                // Embriodery_plane005 use for new model and for old model use Embriodery_plane
                if (child.isMesh && child.name === 'Embriodery_plane') { // Ensure you have a way to identify the embroidery mesh
                 

                  //use texture as overlay on mesh so it can blend with mesh color
                  child.visible = true;
                  
                    child.material.map = texture;
                  //   child.material.blending = THREE.MultiplyBlending;
                  // child.material.transparent = true;
                  // child.material.alphaTest = 0.9; // Ensure alpha channel is respected

                  // child.material.color.set(0x05356d);
                    child.material.needsUpdate = true;
                    //blend texture color with mesh color
                    // child.material.blending = THREE.MultiplyBlending;
                    // child.material.transparent = true;

                    // // Set the color to blend with the texture
                 

                    
                    
                    // Update the texture on mesh
                    render();
                }
            });
            renderer.render(scene, camera);
        });
    };

    img.onerror = function() {
        console.error('Failed to preload image: ' + textureUrl);
    };
}

   //make updateEmbroideryTexture function available globally
    window.updateEmbroideryTexture = updateEmbroideryTexture;




    renderer = new THREE.WebGLRenderer({ antialias: true });
    renderer.setPixelRatio(window.devicePixelRatio);
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.toneMapping = THREE.ACESFilmicToneMapping;
    renderer.toneMappingExposure = 1;
    container.appendChild(renderer.domElement);

    const controls = new OrbitControls(camera, renderer.domElement);
    controls.addEventListener('change', render);
    controls.minDistance = 2;
    controls.maxDistance = 10;
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
      <div class="kandora-image-section">
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

        <div class="panel-3d"  style="width:100%;height:calc(75vh);">
          
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
                      <input type="radio" name="colorPicker" value="#9e8b54" class="colorPicker" >
                     
                      <span><i  style="background-color: #9e8b54; "></i> Moss Green</span>
                    </label>
                    <label class="custom-radio">
                      <input type="radio" name="colorPicker" value="#b6a38f" class="colorPicker"  >
                      <span><i style="background-color: #b6a38f; "></i> Brown </span>
                    </label>
                    <label class="custom-radio">
                      <input type="radio" name="colorPicker" value="#05356d" class="colorPicker"  >
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
          <div class="heading" >
            <h3>Choose Embroidery Style</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border">
                <li>
              
                  <div class="img" onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/tarboosh01.png')">
                    <img  src="models/embroidery/s1.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 1</h4>
                </li>
                <li>
                  <div class="img" onclick="updateEmbroideryTexture( 'assets/images/customizeyourkandora/embroidery/tarboosh02.png')">
                    <img src="models/embroidery/s2.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 2</h4>
                </li>
                <li>
                  <div class="img" onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/tarboosh03.png')">
                    <img src="models/embroidery/s3.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 3</h4>
                </li>
                <li>
                  <div class="img" onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/tarboosh04.png')">
                    <img src="models/embroidery/s4.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 4</h4>
                </li>
                <li>
                  <div class="img" onclick="updateEmbroideryTexture( 'assets/images/customizeyourkandora/embroidery/tarboosh05.png')">
                    <img src="models/embroidery/s5.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 5</h4>
                </li>
                <li>
                  <div class="img" onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/tarboosh06.png')">
                    <img src="models/embroidery/s6.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 6</h4>
                </li>
                <li>
                  <div class="img" onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/tarboosh07.png')">
                    <img src="models/embroidery/s7.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 7</h4>
                </li>
                <li>
                  <div class="img" onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/tarboosh08.png')">
                    <img src="models/embroidery/s8.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 8</h4>
                </li>
                <li>
                  <div class="img" onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/tarboosh09.png')">
                    <img src="models/embroidery/s9.png" alt="" />
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
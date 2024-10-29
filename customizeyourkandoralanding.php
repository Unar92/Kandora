
<style>
		.Select,
		#toolbar,
		#viewHelper,
		#sidebar,
		#menubar, #info
     {
			display: none;
		}
		.Panel {
			width: 100%;
			top: 0 !important;
		}
    .kandora-3d-customizer
    {
      position: relative;
    }
	</style>

<link rel="stylesheet" href="css/main.css" />

<script src="./examples/jsm/libs/draco/draco_encoder.js"></script>

<link rel="stylesheet" href="js/libs/codemirror/codemirror.css" />
<link rel="stylesheet" href="js/libs/codemirror/theme/monokai.css" />
<script src="js/libs/codemirror/codemirror.js"></script>
<script src="js/libs/codemirror/mode/javascript.js"></script>
<script src="js/libs/codemirror/mode/glsl.js"></script>

<script src="js/libs/esprima.js"></script>
<script src="js/libs/jsonlint.js"></script>

<script src="https://cdn.jsdelivr.net/npm/@ffmpeg/ffmpeg@0.11.6/dist/ffmpeg.min.js"></script>

<link rel="stylesheet" href="js/libs/codemirror/addon/dialog.css" />
<link rel="stylesheet" href="js/libs/codemirror/addon/show-hint.css" />
<link rel="stylesheet" href="js/libs/codemirror/addon/tern.css" />

<script src="js/libs/codemirror/addon/dialog.js"></script>
<script src="js/libs/codemirror/addon/show-hint.js"></script>
<script src="js/libs/codemirror/addon/tern.js"></script>
<script src="js/libs/acorn/acorn.js"></script>
<script src="js/libs/acorn/acorn_loose.js"></script>
<script src="js/libs/acorn/walk.js"></script>
<script src="js/libs/ternjs/polyfill.js"></script>
<script src="js/libs/ternjs/signal.js"></script>
<script src="js/libs/ternjs/tern.js"></script>
<script src="js/libs/ternjs/def.js"></script>
<script src="js/libs/ternjs/comment.js"></script>
<script src="js/libs/ternjs/infer.js"></script>
<script src="js/libs/ternjs/doc_comment.js"></script>
<script src="js/libs/tern-threejs/threejs.js"></script>
<script src="js/libs/signals.min.js"></script>

<script type="importmap">
  {
    "imports": {
      "three": "./build/three.module.js",
      "three/addons/": "./examples/jsm/",
      "three/examples/": "./examples/",
      "three-gpu-pathtracer": "https://cdn.jsdelivr.net/npm/three-gpu-pathtracer@0.0.23/build/index.module.js",
      "three-mesh-bvh": "https://cdn.jsdelivr.net/npm/three-mesh-bvh@0.7.4/build/index.module.js"
    }
  }
</script>

<script type="module">
  import * as THREE from 'three';
  import { GLTFLoader } from './examples/jsm/loaders/GLTFLoader.js';
  import { Editor } from './js/Editor.js';
  import { Viewport } from './js/Viewport.js';
  import { Toolbar } from './js/Toolbar.js';
  import { Script } from './js/Script.js';
  import { Player } from './js/Player.js';
  import { Sidebar } from './js/Sidebar.js';
  import { Menubar } from './js/Menubar.js';
  import { Resizer } from './js/Resizer.js';





  window.URL = window.URL || window.webkitURL;
  window.BlobBuilder = window.BlobBuilder || window.WebKitBlobBuilder || window.MozBlobBuilder;

  const editor = new Editor();
  window.editor = editor;
  window.THREE = THREE;

  const viewport = new Viewport(editor);
  document.querySelector('.kandora-3d-customizer').appendChild(viewport.dom);

  // Initialize renderer
  const renderer = new THREE.WebGLRenderer({ antialias: true });
  renderer.setSize(window.innerWidth, window.innerHeight);

  //clear three js cache
  THREE.Cache.enabled = false;

  //lights config
  const ambientLight = new THREE.AmbientLight(0xffffff, 0.5);
  editor.scene.add(ambientLight);
  // console.log('ambientLight 0xffffff, 1', ambientLight);

  // const directionalLight = new THREE.DirectionalLight(0xffffff, 0.2);
  // directionalLight.position.set(10, 10, 10);
  // editor.scene.add(directionalLight);

  // zoom to 2
  // Initialize the camera
  // const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
  // camera.position.z = 5;
  editor.camera.zoom = 2;
  // camera.updateProjectionMatrix();
// Remove AxesHelper and GridHelper by type
editor.scene.children.forEach(child => {
    if (child instanceof THREE.AxesHelper || child instanceof THREE.GridHelper) {
        editor.scene.remove(child);
        console.log(`${child.constructor.name} removed from the scene.`);
    }
});
  const toolbar = new Toolbar(editor);
  document.querySelector('.kandora-3d-customizer').appendChild(toolbar.dom);

  const script = new Script(editor);
  document.querySelector('.kandora-3d-customizer').appendChild(script.dom);

  const player = new Player(editor);
  document.querySelector('.kandora-3d-customizer').appendChild(player.dom);

  const sidebar = new Sidebar(editor);
  document.querySelector('.kandora-3d-customizer').appendChild(sidebar.dom);

  const menubar = new Menubar(editor);
  document.querySelector('.kandora-3d-customizer').appendChild(menubar.dom);

  const resizer = new Resizer(editor);
  document.querySelector('.kandora-3d-customizer').appendChild(resizer.dom);

  // Function to change the texture of an object by its name
  function changeTextureByName(objectName, texturePath, newColor) {
      console.log('Changing texture of object with name:', objectName);
      const textureLoader = new THREE.TextureLoader();
      textureLoader.load(texturePath, function (newTexture) {
          // Set the wrapping mode of the texture
          newTexture.wrapS = THREE.RepeatWrapping;
          newTexture.wrapT = THREE.RepeatWrapping;
          newTexture.needsUpdate = true;
          
          //change color code to setHex
        if (newColor) {
            newColor = newColor.replace('#', '0x');
            newColor = parseInt(newColor, 16);
        }
          // Traverse the model to find the texture with the specified ID
          const kandora = editor.scene.getObjectByName('kandrora_model');
          if (kandora) {
    const objectsToUpdate = ['tarboosh_1', 'tarboosh_tongue', objectName];

    kandora.traverse(function (child) {
        console.log('Child: texture', child);
        if (child.isMesh && objectsToUpdate.includes(child.name)) {
            console.log('Found object texture:', child);
            if (Array.isArray(child.material)) {
                child.material.forEach(mat => {
                    // Remove old texture
                    if (mat.map) {
                        mat.map.dispose(); // Remove old texture
                        mat.map = null; // Ensure the texture is removed
                    }
                    console.log('Found object texture:', mat);

                    // Set new color to the material
                    console.log('color updated for object with name:', child.name);

                    if (newColor) {
                        // Blend texture with color
                        mat.color.setHex(newColor);
                        mat.emissive = new THREE.Color(newColor); // Set emissive color to black
                        mat.emissiveIntensity = 1; // Set emissive intensity to 0
                        console.log('color updated for object with name:', child.name);
                    } else {
                        mat.map = newTexture;
                    }
                    mat.needsUpdate = true;
                });
            } else {
                console.log('Found object texture:', child);
                if (child.material.map) {
                    child.material.map.dispose(); // Remove old texture
                    child.material.map = null; // Ensure the texture is removed
                }

                if (newColor) {
                    child.material.color.setHex(newColor);
                    child.material.emissive = new THREE.Color(newColor); // Set emissive color to black
                    child.material.emissiveIntensity = 1; // Set emissive intensity to 0
                    console.log('color updated for object with name:', child.name);
                } else {
                    child.material.map = newTexture;
                }

                console.log('color updated for object with name:', child.name);
                child.material.needsUpdate = true;
            }

            // Move the camera to the affected object and update the scene
            if (!newColor && child.name === objectName) {
                moveCameraToObject(child);
            }
            editor.signals.sceneGraphChanged.dispatch();

            console.log('Texture updated for object with name:', child.name);
        }
    });
} else {
    console.error('Model "kandrora_model" not found in the scene.');
}
      }, undefined, function (error) {
          console.error('An error happened while loading the new texture:', error);
      });
  }

  // Attach the function to the window object to make it globally accessible
  window.changeTextureByName = changeTextureByName;

  // Function to move the camera to the affected object
  function moveCameraToObject(object) {
      const box = new THREE.Box3().setFromObject(object);
      const center = box.getCenter(new THREE.Vector3());
      const size = box.getSize(new THREE.Vector3());

      const maxDim = Math.max(size.x, size.y, size.z);
      const fov = editor.camera.fov * (Math.PI / 180);
      let cameraZ = Math.abs(maxDim / 4 * Math.tan(fov * 2));

      cameraZ *= 1.5; // Add some distance to the camera

      editor.camera.position.set(center.x, center.y, center.z + cameraZ);
      editor.camera.lookAt(center);
      editor.camera.updateProjectionMatrix();
  }

  // Auto save state function
  editor.storage.init(function () {
      editor.storage.get(async function (state) {
          // if (isLoadingFromHash) return;

          if (state !== undefined) {
              await editor.fromJSON(state);
          }

          // de-select
          editor.select(null);

          // Check if the model is already present
          const existingModel = editor.scene.getObjectByName('kandrora_model');
          if (!existingModel) {
              // Load .glb file (Kandora) only if it's not already in the scene
              const loader = new GLTFLoader();
              loader.load('assets/kandora-model.glb?noCache=' + (new Date()).getTime(), function (gltf) {
                  const kandora = gltf.scene;
                  kandora.name = 'kandrora_model'; // Assign a unique name
                  kandora.position.set(0, -1.3, 0); // Set position as needed
                  editor.addObject(kandora);

                  // Assuming the texture is part of the loaded model
                  kandora.traverse(function (child) {
                      // get detail of the name and model
                      console.log('Name:', child.name); // Log the name
                      console.log('Type:', child.type); // Log the type

                      if (child.isMesh) {
                          console.log('Mesh found:', child); // Log the mesh
                          const material = child.material;
                          // if (Array.isArray(material)) {
                          //     material.forEach(mat => {
                          //         const texture = mat.map; // Access the texture
                          //         if (texture) {
                          //             console.log('Texture ID:', texture.id); // Log the texture ID
                          //         } else {
                          //             console.log('No texture found in material:', mat); // Log if no texture is found
                          //         }
                          //     });
                          // } else {
                          //     const texture = material.map; // Access the texture
                          //     if (texture) {
                          //         console.log('Texture ID:', texture.id); // Log the texture ID
                          //     } else {
                          //         console.log('No texture found in material:', material); // Log if no texture is found
                          //     }
                          // }
                      } else {
                          console.log('Non-mesh child found:', child); // Log non-mesh children
                      }
                  });

              }, undefined, function (error) {
                  console.error('An error happened while loading the model:', error);
              });
          }
      });

      let timeout;
      function saveState() {
          if (editor.config.getKey('autosave') === false) {
              return;
          }

          clearTimeout(timeout);

          timeout = setTimeout(function () {
              editor.signals.savingStarted.dispatch();

              timeout = setTimeout(function () {
                  editor.storage.set(editor.toJSON());
                  editor.signals.savingFinished.dispatch();
              }, 100);
          }, 1000);
      }

      const signals = editor.signals;
      signals.geometryChanged.add(saveState);
      signals.objectAdded.add(saveState);
      signals.objectChanged.add(saveState);
      signals.objectRemoved.add(saveState);
      signals.materialChanged.add(saveState);
      signals.sceneBackgroundChanged.add(saveState);
      signals.sceneEnvironmentChanged.add(saveState);
      signals.sceneFogChanged.add(saveState);
      signals.sceneGraphChanged.add(saveState);
      signals.scriptChanged.add(saveState);
      signals.historyChanged.add(saveState);
  });

  // Resize
  function onWindowResize() {
      editor.signals.windowResize.dispatch();
  }

  window.addEventListener('resize', onWindowResize);
  onWindowResize();

  // ServiceWorker
  if ('serviceWorker' in navigator) {
      try {
          navigator.serviceWorker.register('sw.js');
      } catch (error) {
          console.error('Service Worker registration failed:', error);
      }
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

        <div class="kandora-3d-customizer"  style="width:100%;height:calc(75vh);">
          
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
                      <input type="radio" name="colorPicker" value="#ff0000" onchange="changeTextureByName('kandora_arms', 'assets/images/customizeyourkandora/stitches/Stiches1.png', this.value)">
                      <span>Red</span>
                    </label>
                    <label class="custom-radio">
                      <input type="radio" name="colorPicker" value="#8B4513" onchange="changeTextureByName('kandora_arms', 'assets/images/customizeyourkandora/stitches/Stiches1.png', this.value)">
                      <span>Brown</span>
                    </label>
                    <label class="custom-radio">
                      <input type="radio" name="colorPicker" value="#0000ff" onchange="changeTextureByName('kandora_arms', 'assets/images/customizeyourkandora/stitches/Stiches1.png', this.value)">
                      <span> Blue</span>
                    </label>
                    <label class="custom-radio">
                      <input type="radio" name="colorPicker" value="#ffffff" onchange="changeTextureByName('kandora_arms', 'assets/images/customizeyourkandora/stitches/Stiches1.png', this.value)">
                      <span>White</span>
                    </label>
                  </div> 
          
               
                
             
          </div>
               
          </div>
       
    
        <div class="customize-section-box" >
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
        </div>
        <div class="customize-section-box">
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
        </div>
        <div class="customize-section-box">
          <div class="heading" >
            <h3>Choose Embroidery Style</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border">
                <li>
              
                  <div class="img" onclick="changeTextureByName('Embriodery_plane', 'assets/images/customizeyourkandora/embroidery/tarboosh01.png')">
                    <img  src="assets/images/customizeyourkandora/embroidery-style-1.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 1</h4>
                </li>
                <li>
                  <div class="img" onclick="changeTextureByName('Embriodery_plane', 'assets/images/customizeyourkandora/embroidery/tarboosh02.png')">
                    <img src="assets/images/customizeyourkandora/embroidery-style-2.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 2</h4>
                </li>
                <li>
                  <div class="img" onclick="changeTextureByName('Embriodery_plane', 'assets/images/customizeyourkandora/embroidery/tarboosh03.png')">
                    <img src="assets/images/customizeyourkandora/embroidery-style-3.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 3</h4>
                </li>
                <li>
                  <div class="img" onclick="changeTextureByName('Embriodery_plane', 'assets/images/customizeyourkandora/embroidery/tarboosh04.png')">
                    <img src="assets/images/customizeyourkandora/embroidery-style-4.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 4</h4>
                </li>
                <li>
                  <div class="img" onclick="changeTextureByName('Embriodery_plane', 'assets/images/customizeyourkandora/embroidery/tarboosh05.png')">
                    <img src="assets/images/customizeyourkandora/embroidery-style-1.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 5</h4>
                </li>
                <li>
                  <div class="img" onclick="changeTextureByName('Embriodery_plane', 'assets/images/customizeyourkandora/embroidery/tarboosh06.png')">
                    <img src="assets/images/customizeyourkandora/embroidery-style-2.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 6</h4>
                </li>
                <li>
                  <div class="img" onclick="changeTextureByName('Embriodery_plane', 'assets/images/customizeyourkandora/embroidery/tarboosh07.png')">
                    <img src="assets/images/customizeyourkandora/embroidery-style-3.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 7</h4>
                </li>
                <li>
                  <div class="img" onclick="changeTextureByName('Embriodery_plane', 'assets/images/customizeyourkandora/embroidery/tarboosh08.png')">
                    <img src="assets/images/customizeyourkandora/embroidery-style-4.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 8</h4>
                </li>
                <li>
                  <div class="img" onclick="changeTextureByName('Embriodery_plane', 'assets/images/customizeyourkandora/embroidery/tarboosh09.png')">
                    <img src="assets/images/customizeyourkandora/embroidery-style-4.png" alt="" />
                  </div>
                  <h4 class="embroidery_style">Style 9</h4>
                </li>
              </ul>
            </div>
          </div>
        </div>
      
        <div class="customize-section-box">
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
        </div>

    
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
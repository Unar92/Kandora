
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

  // zoom to 2
  editor.camera.zoom = 2;

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

  // Auto save state function
  editor.storage.init(function () {
    editor.storage.get(async function (state) {
      // if (isLoadingFromHash) return;

      if (state !== undefined) {
        await editor.fromJSON(state);
      }

//   de-select
  editor.select(null);

    //   const selected = editor.config.getKey('selected');
    //   if (selected !== undefined) {
    //     // editor.selectByUuid(selected);
    //   }

      // Check if the model is already present
    
      const existingModel = editor.scene.getObjectByName('kandrora_model');
      if (!existingModel) {
        // Load .glb file (Kandora) only if it's not already in the scene
        const loader = new GLTFLoader();
        loader.load('assets/5.glb?noCache=' + (new Date()).getTime(), function (gltf) {
          const kandora = gltf.scene;
          kandora.name = 'kandrora_model'; // Assign a unique name
          kandora.position.set(0, -1.3, 0); // Set position as needed
          editor.addObject(kandora);

         
      
          // Assuming the texture is part of the loaded model
          kandora.traverse(function (child) {

            //get detail of the name and model
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

<script>
function changeTextureById(textureId, newTextureUrl) {
    console.log('Changing texture with ID:', textureId);
    // Load the new texture
    const textureLoader = new THREE.TextureLoader();
    textureLoader.load(newTextureUrl, function (newTexture) {
        // Traverse the model to find the texture with the specified ID
        const kandora = editor.scene.getObjectByName('kandrora_model');
        if (kandora) {
            kandora.traverse(function (child) {
              
                if (child.isMesh) {
                    const material = child.material;
                    if (Array.isArray(material)) {
                        material.forEach(mat => {
                            if (mat.map) {
                                console.log('Found texture ID:', mat.map.id);
                                if (mat.map.id === textureId) {
                                    mat.map = newTexture; // Replace the texture
                                    mat.needsUpdate = true; // Ensure the material is updated
                                    console.log('Texture replaced for material:', mat);
                                    return; // Early return to stop further traversal
                                }
                            } else {
                                console.log('No texture map found in material:', mat);
                            }
                        });
                    } else {
                        if (material.map) {
                            console.log('Found texture ID:', material.map.id);
                            if (material.map.id === textureId) {
                                material.map = newTexture; // Replace the texture
                                material.needsUpdate = true; // Ensure the material is updated
                                console.log('Texture replaced for material:', material);
                                return; // Early return to stop further traversal
                            }
                        } else {
                            console.log('No texture map found in material:', material);
                        }
                    }
                } else {
                    console.log('Non-mesh child found:', child);
                }
            });
        } else {
            console.error('Model "kandrora_model" not found in the scene.');
        }
    }, undefined, function (error) {
        console.error('An error happened while loading the new texture:', error);
    });
}


// Function to change the texture of an object by its name
function changeTextureByName(objectName, texturePath) {
        console.log('Changing texture of object with name:', objectName);
        const textureLoader = new THREE.TextureLoader();
        textureLoader.load(texturePath, function (newTexture) {
    // Set the wrapping mode of the texture
    newTexture.wrapS = THREE.RepeatWrapping;
    newTexture.wrapT = THREE.RepeatWrapping;
    newTexture.needsUpdate = true;

    // Traverse the model to find the texture with the specified ID
    const kandora = editor.scene.getObjectByName('kandrora_model');
    if (kandora) {
        kandora.traverse(function (child) {
            console.log('Child: texture', child);
            if (child.isMesh && child.name === objectName) {
                console.log('Found object texture:', child);
                if (Array.isArray(child.material)) {
                    child.material.forEach(mat => {
                        console.log('Found object texture:', mat);
                        mat.map = newTexture;
                        mat.needsUpdate = true;
                    });
                } else {
                    console.log('Found object texture:', child);
                    child.material.map = newTexture;
                    child.material.needsUpdate = true;
                }

                // Log UV coordinates
                if (child.geometry && child.geometry.attributes && child.geometry.attributes.uv) {
                    console.log('UV coordinates:', child.geometry.attributes.uv.array);
                } else {
                    console.warn('No UV coordinates found for this mesh.');
                }
            }
        });

        // Request a render update
        // editor.render(); // Ensure this function exists and triggers a re-render
        console.log('Texture updated for object with name:', objectName);
    } else {
        console.error('Model "kandrora_model" not found in the scene.');
    }
}, undefined, function (error) {
    console.error('An error happened while loading the new texture:', error);
});
   }



// Function to change the color of an object by its name
function changeObjectColorByName(scene, objectName, color) {
  console.log('Changing color of object with name:', objectName);
    scene.traverse(function (child) {
        if (child.isMesh && child.name === objectName) {
          console.log('Found object:', child);
            if (Array.isArray(child.material)) {
                child.material.forEach(mat => {
                    mat.color.set(color);
                });
            } else {
                child.material.color.set(color);
            }
        }
    });
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

        <div class="kandora-3d-customizer"  style="width:100%;height:500px;">
          
        </div>
        <div class="kandora-image-select">
          <img src="assets/images/customizeyourkandora/select-img.png" alt="">
        </div>
      </div>
      <div class="kandora-customize-section">
        <div class="customize-section-box customization-summary d-none desktop sticky-portion my-0">
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
        </div>
        <div class="customize-section-box mt-23">
          <div class="heading active">
            <h3>Choose your Fabric</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="custom-radio-group">
              <label class="custom-radio">
                <input type="radio" name="fabric_radio" data-value="Classic">
                <span>Classic</span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="fabric_radio" data-value="Gold">
                <span>Gold</span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="fabric_radio" data-value="Gold">
                <span>Gold</span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="fabric_radio" data-value="Black Edition">
                <span>Black Edition</span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="fabric_radio" data-value="Black Edition">
                <span>Black Edition</span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="fabric_radio" data-value="Indian Wool">
                <span>Indian Wool</span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="fabric_radio" data-value="Signature">
                <span>Signature</span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="fabric_radio" data-value="Signature">
                <span>Signature</span>
              </label>
            </div>
            <div class="images-option">
              <div class="sub-heading">
                <h3>Copy of Above</h3>
              </div>
              <div class="custom-radio-group">
                <label class="custom-radio">
                  <input type="radio" name="fabric_radio" data-value="Classic">
                  <span>Classic</span>
                </label>
                <label class="custom-radio">
                  <input type="radio" name="fabric_radio" data-value="Gold">
                  <span>Gold</span>
                </label>
                <label class="custom-radio">
                  <input type="radio" name="fabric_radio" data-value="Gold">
                  <span>Gold</span>
                </label>
                <label class="custom-radio">
                  <input type="radio" name="fabric_radio" data-value="Black Edition">
                  <span>Black Edition</span>
                </label>
                <label class="custom-radio">
                  <input type="radio" name="fabric_radio" data-value="Black Edition">
                  <span>Black Edition</span>
                </label>
                <label class="custom-radio">
                  <input type="radio" name="fabric_radio" data-value="Indian Wool">
                  <span>Indian Wool</span>
                </label>
                <label class="custom-radio">
                  <input type="radio" name="fabric_radio" data-value="Signature">
                  <span>Signature</span>
                </label>
                <label class="custom-radio">
                  <input type="radio" name="fabric_radio" data-value="Signature">
                  <span>Signature</span>
                </label>
              </div>
            </div>
            <div class="images-option">
              <div class="sub-heading">
                <h3>Classic: White - Very Soft</h3>
              </div>
              <ul>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/classic-white-very-soft-img1.png"
                      alt="" /></div>
                  <h4>Blue White</h4>
                </li>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/classic-white-very-soft-img2.png"
                      alt="" /></div>
                  <h4>Blue White</h4>
                </li>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/classic-white-very-soft-img3.png"
                      alt="" /></div>
                  <h4>Blue White</h4>
                </li>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/classic-white-very-soft-img4.png"
                      alt="" /></div>
                  <h4>Blue White</h4>
                </li>
              </ul>
            </div>
            <div class="images-option">
              <div class="sub-heading">
                <h3>Classic: White - Soft</h3>
              </div>
              <ul>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/classic-white-very-soft-img1.png"
                      alt="" /></div>
                  <h4>Blue White</h4>
                </li>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/classic-white-very-soft-img2.png"
                      alt="" /></div>
                  <h4>Blue White</h4>
                </li>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/classic-white-very-soft-img3.png"
                      alt="" /></div>
                  <h4>Blue White</h4>
                </li>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/classic-white-very-soft-img4.png"
                      alt="" /></div>
                  <h4>Blue White</h4>
                </li>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/classic-white-very-soft-img1.png"
                      alt="" /></div>
                  <h4>Blue White</h4>
                </li>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/classic-white-very-soft-img2.png"
                      alt="" /></div>
                  <h4>Blue White</h4>
                </li>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/classic-white-very-soft-img3.png"
                      alt="" /></div>
                  <h4>Blue White</h4>
                </li>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/classic-white-very-soft-img4.png"
                      alt="" /></div>
                  <h4>Blue White</h4>
                </li>
              </ul>
            </div>
            <div class="images-option">
              <div class="sub-heading">
                <h3>Classic: White - Medium Soft</h3>
              </div>
              <ul>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/classic-white-very-soft-img1.png"
                      alt="" /></div>
                  <h4>Blue White</h4>
                </li>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/classic-white-very-soft-img2.png"
                      alt="" /></div>
                  <h4>Blue White</h4>
                </li>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/classic-white-very-soft-img3.png"
                      alt="" /></div>
                  <h4>Blue White</h4>
                </li>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/classic-white-very-soft-img4.png"
                      alt="" /></div>
                  <h4>Blue White</h4>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="customize-section-box">
          <div class="heading" disabled="disabled">
            <h3>Choose your Size</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="custom-radio-group">
              <label class="custom-radio">
                <input type="radio" name="size_radio" data-value='Small (S)'>
                <span>Small (S) <em class="price">AED 100.00</em></span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="size_radio" data-value='Medium (M)'>
                <span>Medium (M) <em class="price">AED 200.00</em></span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="size_radio" data-value='Large (L)'>
                <span>Large (L) <em class="price">AED 300.00</em></span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="size_radio" data-value='Extra Large (XL)'>
                <span>Extra Large (XL) <em class="price">AED 400.00</em></span>
              </label>
            </div>
          </div>
        </div>
        <div class="customize-section-box">
          <div class="heading" disabled="disabled">
            <h3>Choose Front Style</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border">
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/front-emirati.png" alt="" /></div>
                  <h4 class="front_style">Emirati with Zipper</h4>
                </li>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/front-emirati.png" alt="" /></div>
                  <h4 class="front_style">Emirati</h4>
                </li>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/front-emirati.png" alt="" /></div>
                  <h4 class="front_style">Emirati</h4>
                </li>
                <li>
                  <div class="img"><img src="assets/images/customizeyourkandora/front-emirati.png" alt="" /></div>
                  <h4 class="front_style">Emirati with Zipper</h4>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="customize-section-box">
          <div class="heading" disabled="disabled">
            <h3>Choose Front Pleat Style</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border">
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/frontpleat-plain.png" alt="" />
                  </div>
                  <h4 class="front_pleat_style">Plain</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/frontpleat-plain.png" alt="" />
                  </div>
                  <h4 class="front_pleat_style">Pleat (Kasra)</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/frontpleat-plain.png" alt="" />
                  </div>
                  <h4 class="front_pleat_style">Plain</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/frontpleat-plain.png" alt="" />
                  </div>
                  <h4 class="front_pleat_style">Pleat (Kasra)</h4>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="customize-section-box">
          <div class="heading" >
            <h3>Choose Side Lines</h3>
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
                  <div class="img" onclick="changeTextureByName('Stiches_plane', 'assets/images/customizeyourkandora/stitches/Stiches2.png')">
                    <img src="assets/images/customizeyourkandora/12-stitches.png" alt="" />
                  </div>
                  <h4 class="stitch_style">4 Stitches</h4>
                </li>
                <li>
                  <div class="img" onclick="changeTextureByName('Stiches_plane', 'assets/images/customizeyourkandora/stitches/Stiches3.png')">
                    <img src="assets/images/customizeyourkandora/4-stitches-with-embroidery.png" alt="" />
                  </div>
                  <h4 class="stitch_style">6 Stitches with Embroidery</h4>
                </li>
                <li>
                  <div class="img" onclick="changeTextureByName('Stiches_plane', 'assets/images/customizeyourkandora/stitches/Stiches4.png')">
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
          <div class="heading"><!--disabled="disabled" -->
            <h3>Choose your Farukha Type</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="">
          </div>
          <div class="customize-option">
            <div class="custom-radio-group">
              <div class="class-new-zee-kandora position-relative">
                <label class="custom-radio">
                  <input type="radio" name="farukha_type" data-value='Detachable'>
                  <span>Detachable</span>
                </label>
                <a class="play-icon-customize" data-target="#modalvideo" data-toggle="modal" href="javascript:void(0)">
                  <img src="assets/images/icons/play-icon.svg" alt="">
                  <div class="d-none modal-video-customizeyourkandora">
                    <video controls poster="assets/images/about/modal-poster.png">
                      <source src="#" type="video/mp4">
                      Your browser does not support the video tag.
                    </video>
                    <!-- <iframe width="100%" height="550" src="https://www.youtube.com/embed/KVtHvq9OJh8" title="Filhaal2 Mohabbat - Akshay Kumar Ft Nupur Sanon | Ammy Virk | BPraak - Jaani | Arvindr Khaira" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe> -->
                  </div>
                </a>
              </div>
              <div class="class-new-zee-kandora position-relative">
                <label class="custom-radio">
                  <input type="radio" name="farukha_type" data-value='Permanent'>
                  <span>Permanent</span>
                </label>
                <a class="play-icon-customize" data-target="#modalvideo" data-toggle="modal" href="javascript:void(0)">
                  <img src="assets/images/icons/play-icon.svg" alt="">
                  <div class="d-none modal-video-customizeyourkandora">
                    <!-- <video controls poster="assets/images/about/modal-poster.png">
                        <source src="#" type="video/mp4">
                        Your browser does not support the video tag.
                    </video> -->
                    <iframe width="1024" height="500" src="https://www.youtube.com/embed/fbvPym5pz0s"
                      title="[5] Amazing Ways to design different necks for your outfits. sewing technique"
                      frameborder="0"
                      allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                      allowfullscreen></iframe>
                  </div>
                </a>
              </div>
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
                    <button onclick="changeTextureByName('Embriodery_plane', 'assets/images/customizeyourkandora/tarboosh08.png')">Change Texture</button>

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
        <div class="customize-section-box">
          <div class="heading" disabled="disabled">
            <h3>Choose Collar Style</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border">
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/collar-2.png" alt="" />
                  </div>
                  <h4 class="collar_style">2.5 Inch</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/collar-3.png" alt="" />
                  </div>
                  <h4 class="collar_style">3.5 Inch</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/collar-2.png" alt="" />
                  </div>
                  <h4 class="collar_style">2.5 Inch</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/collar-3.png" alt="" />
                  </div>
                  <h4 class="collar_style">3.5 Inch</h4>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="customize-section-box">
          <div class="heading" disabled="disabled">
            <h3>Choose Collar Thickness</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="">
          </div>
          <div class="customize-option">
            <div class="custom-radio-group">
              <label class="custom-radio">
                <input type="radio" name="collar_thickness" data-value="Extra Soft">
                <span>Extra Soft</span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="collar_thickness" data-value="Soft">
                <span>Soft</span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="collar_thickness" data-value="Medium">
                <span>Medium</span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="collar_thickness" data-value="Hard">
                <span>Hard</span>
              </label>
            </div>
          </div>
        </div>
        <div class="customize-section-box">
          <div class="heading" disabled="disabled">
            <h3>Choose Cuffs</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border">
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/one-type.png" alt="" />
                  </div>
                  <h4 class="cuffs">One Type</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/one-button.png" alt="" />
                  </div>
                  <h4 class="cuffs">One Button</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/two-buttons.png" alt="" />
                  </div>
                  <h4 class="cuffs">Two Buttons</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/french-cuffs.png" alt="" />
                  </div>
                  <h4 class="cuffs">French Cuffs</h4>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="customize-section-box">
          <div class="heading" disabled="disabled">
            <h3>Choose Back Style</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border align-items-end">
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/Back-plain.png" alt="" />
                  </div>
                  <h4 class="back_style">Plain</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/Back-side-pleats.png" alt="" />
                  </div>
                  <h4 class="back_style">Side Pleats</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/Back-center-pleat.png" alt="" />
                  </div>
                  <h4 class="back_style">Center Pleat</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/Back-center-box-pleat.png" alt="" />
                  </div>
                  <h4 class="back_style">Center Box Pleat</h4>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="customize-section-box">
          <div class="heading" disabled="disabled">
            <h3>Choose Pockets</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border no-pocket">
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/No-Pocket.png" alt="" />
                  </div>
                  <h4 class="pockets">No Pocket</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/Pocket-angled.png" alt="" />
                  </div>
                  <h4 class="pockets">Angled</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/Pocket-v-shaped.png" alt="" />
                  </div>
                  <h4 class="pockets">V Shaped</h4>
                </li>
                <li>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/Pocket-rounded.png" alt="" />
                  </div>
                  <h4 class="pockets">Rounded</h4>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="customize-section-box">
          <div class="heading" disabled="disabled">
            <h3>Choose Pocket Edging</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="">
          </div>
          <div class="customize-option">
            <div class="custom-radio-group">
              <label class="custom-radio">
                <input type="radio" name="pocket_edging" data-value="Extra Soft">
                <span>Extra Soft</span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="pocket_edging" data-value="Soft">
                <span>Soft</span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="pocket_edging" data-value="Medium">
                <span>Medium</span>
              </label>
              <label class="custom-radio">
                <input type="radio" name="pocket_edging" data-value="Hard">
                <span>Hard</span>
              </label>
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
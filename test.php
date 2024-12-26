<style>
    html {
        scroll-behavior: smooth;
    }

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
      width:100%;
      height:calc(75vh); 
      position: relative;
      background-color: #f1f1f1;
      
    }

    @media screen and (max-width:767px) {
        .panel-3d {
            margin-bottom: 20px;
        }
    }

    #resetcamera {
        position: absolute;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        background-color: transparent;
        border: none
        /* padding: 5px 10px; */
        /* border-radius: 5px; */
        cursor: pointer;
        opacity: 0.5;
        transition: all 0.3s ease;

      
    }

    #resetcamera svg {
        width: 25px;
        height: 25px;
    }

    #resetcamera:hover {
        opacity: 1;
    }

    .custom-radio span i {
        width: 30px;
        height: 100%;
        margin-right: 15px;
        border: 1px solid #777;
    }

    .loader {
    width: 48px;
    height: 48px;
    border: 5px solid #FFF;
    border-bottom-color: transparent;
    border-radius: 50%;
    display: inline-block;
    box-sizing: border-box;
    animation: rotation 1s linear infinite;
    position: absolute;
    top: 50% ;
    left: 50% ;
    transform: translate(-50%, -50%);
    z-index: 9999;
    opacity: 0;
    pointer-events: none;
    transition: all 0.3s ease;
    }

    .loading-assets .loader
    {
      opacity: 1;
    }
    

    @keyframes rotation {
  from {
    transform: translate(-50%, -50%) rotate(0deg);
  }
  to {
    transform: translate(-50%, -50%) rotate(360deg);
  }
}

  .panel-3d.loading-assets::after {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  border-radius: 0;
  display: inline-block;
  box-sizing: border-box;
  opacity: 1;
  transition: opacity 0.3s ease, background-color 0.3s ease;
}

.panel-3d::after {
  opacity: 0;
  background-color: rgba(0, 0, 0, 0);
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
    let controls; // Define controls here

    let currentMeshColor = '';

    const state = { variant: 'midnight' };

    init();
    render();
   
    function init() {
        const container = document.querySelector('.panel-3d');

        camera = new THREE.PerspectiveCamera(25, container.clientWidth / container.clientHeight, 0.25, 100);
        camera.position.set(1, 2, 8);
        //camera zoom
        camera.zoom = 1;
        camera.updateProjectionMatrix();

     

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




                // emmisive and metalness intensity
                scene.add(new THREE.AmbientLight(0xffffff, 0.5));
                const light = new THREE.DirectionalLight(0xffffff, 0.5);
                light.position.set(0, 0, 1);
                scene.add(light);





                //ambient intensity 0
                // scene.add(new THREE.AmbientLight(0x000000, 0));
                //direct intensity 0
                // const light = new THREE.DirectionalLight(0x000000, 0);

                render();
                const loader = new GLTFLoader().setPath('models/');

               
                // Function to remove specific objects from a model
                function removeObjects(model) {
                    let objectsToRemove = [];

                    model.traverse((child) => {
                        if (child.isMesh) {
                            console.log("Found mesh:", child.name);
                            if (child.name === 'collar02model_1' || child.name === 'collar02model_2' || child.name === 'collar03model_1' || child.name === 'collar03model_2') {
                                objectsToRemove.push(child);
                                // console.log("Marked for removal", child.name);
                            }

                            if (child.name === 'Embriodery_plane005' || child.name === 'Embriodery_plane002' || child.name === 'tarboosh_tongue' || child.name === 'tarboosh_1') {
                                child.visible = false;
                                // console.log("Made invisible", child.name);
                            }

                            //if kandora_front_part_for_button
                            if (child.name === 'kandora_front_part_for_button') {
                                child.visible = false;
                                // console.log("Remove kandora_front_part_for_button", child.name);
                            }

                            // remove Found mesh: cuff02_1 cuff02_2 cuff02_3 cuff3_1 cuff3_2  cuff3_3  cuff4_1 cuff4_2 cuff4_3
                            if (child.name === 'Cuff01_1' || child.name === 'Cuff01_2' || child.name === 'cuff02_1' || child.name === 'cuff02_2' || child.name === 'cuff02_3' || child.name === 'cuff3_1' || child.name === 'cuff3_2' || child.name === 'cuff3_3' || child.name === 'cuff4_1' || child.name === 'cuff4_2' || child.name === 'cuff4_3') {
                              child.visible = false;
                            }

                            // remove Found mesh: cuff02_1 cuff02_2 cuff02_3 cuff3_1 cuff3_2  cuff3_3  cuff4_1 cuff4_2 cuff4_3
                            // if (child.name === 'collar1_1' || child.name === 'collar1_2' || child.name === 'collar1_3' ||  child.name === 'collar2_2' ||  child.name === 'collar2_2' || child.name === 'collar3_1' || child.name === 'collar3_2' || child.name === 'collar4_1' || child.name === 'collar4_2' || child.name === 'collar4_3' || child.name === 'collar5_1' || child.name === 'collar5_2' || child.name === 'collar6_1' || child.name === 'collar6_2' || child.name === 'collar7_1'  || child.name === 'collar7_2'  || child.name === 'collar8'  || child.name === 'collar9') {
                            //   child.visible = false;
                            // //  console.log("rRemove Collar", child.name);
                            // }

                            //if child incluse collar
                            if (child.name.includes('collar')) {
                                child.visible = false;
                                // console.log("Remove collar", child.name);
                            }

                            //if child includes front_style
                            if (child.name.includes('front_style')) {
                                child.visible = false;
                                // console.log("Remove front_style", child.name);
                            }

                            //if child includes pocket
                            if (child.name.includes('pocket')) {
                                child.visible = false;
                                console.log("Remove pocket", child.name);
                            }

                            // if child includes Stiches_plane
                            if (child.name.includes('Stiches_plane')) {
                                child.visible = false;
                                // console.log("Remove Stiches_plane", child.name);
                            }

                            // if child includes Pleat
                            if (child.name.includes('Pleat')) {
                                child.visible = false;
                                console.log("Remove Pleat", child.name);
                            }
                            

                            
                            

                        }
                    });

                    objectsToRemove.forEach((child) => {
                        if (child.parent) {
                          child.visible = false;
                            child.parent.remove(child);
                            console.log("Removed", child.name);
                        }
                    });
                }

             
                // Load the first GLB file
                loader.load('7 11 24 (2).glb', function (glb1) {
                    glb1.scene.scale.set(1.7, 1.7, 1.7);
                    glb1.scene.position.set(0, -1.1, 0);
                    scene.add(glb1.scene);

                    // Remove specific objects from the first GLB model
                    removeObjects(glb1.scene);
                    render();


                


                  

                  function addObj(objectType, objectFile, objectStyles, textureURL) {
                    // Check if the object is already added
                    let objAdded = false;
                    scene.traverse((child) => {
                      if (child.isMesh && child.name.includes(objectType)) {
                        objAdded = true;
                      }
                    });

                    if (!objAdded) {
                            // class loading-assets to panel-3d
                            document.querySelector('.panel-3d').classList.add('loading-assets');

                            loader.load(objectFile, function (glb) {
                              glb.scene.scale.set(1.7, 1.7, 1.7);
                              if (objectFile === 'pocketaftermath.glb') {
                                glb.scene.position.set(0, -1.1, 0);
                              } else {
                                glb.scene.position.set(0, -1.1, 0);
                              }
                              scene.add(glb.scene);

                              // Remove specific objects from the GLB model
                              removeObjects(glb.scene);
                              setTimeout(() => {

                              // Make the specified styles visible
                              const styles = objectStyles.split(',').map(style => style.trim());
                              glb.scene.traverse((child) => {
                                if (child.isMesh && styles.some(style => child.name.includes(style))) {
                                  child.visible = true;
                                }
                              });

                              //if textureURL is not empty
                              if (textureURL) {
                                changeTextureByName(objectType, textureURL);
                              }
                              updateColorProperty(currentMeshColor);
                              // Render the scene after the model is loaded
                             
                                document.querySelector('.panel-3d').classList.remove('loading-assets');
                              
                                animateCameraToObjPosition(objectType);
                                render();
                              }, 500); // Delay of 500 milliseconds
                            
                            });
                          } else {
                            // Modify the current loaded model
                            const styles = objectStyles.split(',').map(style => style.trim());

                            // Hide all elements first
                            scene.traverse((child) => {
                              if (child.isMesh && child.name.toLowerCase().includes(objectType)) {
                                child.visible = false;
                              }
                            });

                            // Add loading animation
                             document.querySelector('.panel-3d').classList.add('loading-assets');


                            // Make the specified styles visible
                            scene.traverse((child) => {
                              if (child.isMesh && styles.some(style => child.name === style)) {
                                child.visible = true;
                              }
                            });

                            console.log("object type", objectType);

                            //if Pleat1 than hide Pleat2
                            if (objectType === 'Pleat1') {
                              scene.traverse((child) => {
                                if (child.isMesh && child.name === 'Pleat2') {
                                  child.visible = false;
                                  console.log('Pleat2 hidden');
                                }
                              });
                            }

                            //if textureURL is not empty
                            if (textureURL) {
                              changeTextureByName(objectType, textureURL);
                            }
                            updateColorProperty(currentMeshColor);
                            // Render the scene after modifying the model
                            setTimeout(() => {
                              document.querySelector('.panel-3d').classList.remove('loading-assets');
    
                             
                              animateCameraToObjPosition(objectType);
                              render();
                            }, 500); // Delay of 500 milliseconds
                          }
                        }
                //global add collar function
                window.addObj = addObj;

                

                    // Load the second GLB file
                   //add collar to the scene when function is triggered
                  function addCollar(collarstyle) {
                    // class loading-assets to panel-3d
                    document.querySelector('.panel-3d').classList.add('loading-assets');
                    // Check if the collar is already added
                    let collarAdded = false;
                    scene.traverse((child) => {
                      if (child.isMesh && child.name.includes('collar')) {
                        collarAdded = true;
                      }
                    });

                    if (!collarAdded) {
                      loader.load('collar.glb', function (glb2) {
                        glb2.scene.scale.set(1.7, 1.7, 1.7);
                        glb2.scene.position.set(0, -1.1, 0);

                        setTimeout(() => {
                        scene.add(glb2.scene);

                        // Remove specific objects from the second GLB model
                        removeObjects(glb2.scene);

                        // Make the specified collar styles visible
                        const collarStyles = collarstyle.split(',').map(style => style.trim());
                        glb2.scene.traverse((child) => {
                          if (child.isMesh && collarStyles.some(style => child.name.includes(style))) {
                            child.visible = true;
                          }
                        });
                        updateColorProperty(currentMeshColor);
                        // Render the scene after both models are loaded
                       
                          document.querySelector('.panel-3d').classList.remove('loading-assets');
                      
                          animateCameraToObjPosition("collar");
                          render();
                        }, 500); // Delay of 500 milliseconds
                      });
                    } else {
                      // Modify the current loaded model
                      const collarStyles = collarstyle.split(',').map(style => style.trim());

                      // Hide all elements first
                      scene.traverse((child) => {
                        if (child.isMesh && child.name.toLowerCase().includes('collar')) {
                          child.visible = false;
                        }
                      });

                      scene.traverse((child) => {
                        if (child.isMesh && collarStyles.some(style => child.name.includes(style))) {
                          child.visible = true;
                        }
                      });
                      updateColorProperty(currentMeshColor);
                      // Render the scene after modifying the model
                      setTimeout(() => {
                        document.querySelector('.panel-3d').classList.remove('loading-assets');
                     
                        animateCameraToObjPosition("collar");
                        render();
                      }, 500); // Delay of 500 milliseconds
                    }
                  }

                //global add collar function
                window.addCollar = addCollar;
                  

                //add cuff to the scene when function is triggered
                function addCuff(cuffstyle) {
                  // class loading-assets to panel-3d
                  document.querySelector('.panel-3d').classList.add('loading-assets');
                // Check if the cuff is already added
                    let cuffAdded = false;
                    scene.traverse((child) => {
                      if (child.isMesh && child.name.includes('cuff')) {
                        cuffAdded = true;
                        // console.log('Cuff already added');
                      }
                    });

                    if (!cuffAdded) {
                      loader.load('cuff.glb', function (glb2) {
                        glb2.scene.scale.set(1.7, 1.7, 1.7);
                        glb2.scene.position.set(0, -1.1, 0);
                        scene.add(glb2.scene);

                        // Remove specific objects from the second GLB model
                        removeObjects(glb2.scene);
                        setTimeout(() => {
                        // Make the specified cuff styles visible
                        const cuffStyles = cuffstyle.split(',').map(style => style.trim());
                        glb2.scene.traverse((child) => {
                          if (child.isMesh && cuffStyles.some(style => child.name.includes(style))) {
                            child.visible = true;
                          }
                        });

                        // Render the scene after both models are loaded

                        updateColorProperty(currentMeshColor);
                       
                          document.querySelector('.panel-3d').classList.remove('loading-assets');
                       
                          animateCameraToObjPosition("cuff");
                          
                          render(); 
                        }, 500); // Delay of 500 milliseconds
                         
                      });
                    } else {
                      // Modify the current loaded model
                      
                      const cuffStyles = cuffstyle.split(',').map(style => style.trim());
                      
                      //hide all element first
                      scene.traverse((child) => {
                        if (child.isMesh && child.name.toLowerCase().includes('cuff')) {
                          child.visible = false;
                        
                        }
                      });


                      scene.traverse((child) => {
                        
                        if (child.isMesh && cuffStyles.some(style => child.name.includes(style))) {
                          child.visible = true;
                          console.log(cuffStyles, "visible");
                        }
                      });
                      
                      console.log('Cuff already added');

                      // Render the scene after modifying the model
                      
                        // change color of model with current color code with calling updatecolorproperty function
                        updateColorProperty(currentMeshColor);
                        
                        setTimeout(() => {
                          document.querySelector('.panel-3d').classList.remove('loading-assets');
                          
                          animateCameraToObjPosition("cuff");
                          
                          render();
                        }, 500); // Delay of 500 milliseconds
                         

                    }
                  }


                //global add cuff function
                window.addCuff = addCuff;
                    
               
            
                });

                gui = new GUI();
                render();

            });


        //color picker event
        const colorPickers = document.getElementsByClassName('colorPicker');
        Array.from(colorPickers).forEach(picker => {
            picker.addEventListener('input', function (event) {
                const colorValue = event.target.value;
                updateColorProperty(colorValue);
            });
        });



//when click on #resetcamera button
document.getElementById('resetcamera').addEventListener('click', function () {
    resetCamera();
});

var cameraLock = false;
//functio  to reset camera and control to its original position
function resetCamera() {
    // If not camera lock, then return
    // if (!cameraLock) {
    //     return;
    // }

    // console.log('Camera reset');

    // Reset orbiting up and down, left and right
    controls.minPolarAngle = 0; // radians
    controls.maxPolarAngle = Math.PI; // radians
    controls.minAzimuthAngle = -Infinity; // radians
    controls.maxAzimuthAngle = Infinity; // radians

    // Define the desired values
const desiredPosition = {
    x: 1,
    y: 1,
    z: 8
};

const desiredTarget = {
    x: 0,
    y: 0.5,
    z: -0.2
};

const desiredZoom = 1; // Adjust as needed

// Animate camera position
gsap.to(camera.position, {
    duration: 1,
    x: desiredPosition.x,
    y: desiredPosition.y,
    z: desiredPosition.z,
    onUpdate: function () {
        camera.lookAt(desiredTarget.x, desiredTarget.y, desiredTarget.z);
        camera.updateProjectionMatrix();
        controls.update();
        render();
    }
});

// Animate controls target
gsap.to(controls.target, {
    duration: 1,
    x: desiredTarget.x,
    y: desiredTarget.y,
    z: desiredTarget.z,
    onUpdate: function () {
        controls.update();
        render();
    }
});

// Animate camera zoom
gsap.to(camera, {
    duration: 1,
    zoom: desiredZoom,
    onUpdate: function () {
        camera.updateProjectionMatrix();
        controls.update();
        render();
    }
});
}



        

function animateCameraToObjPosition(objType) {
            // Capture the current camera position, zoom level, and controls target
            const currentCameraPosition = camera.position.clone();
            const currentZoom = camera.zoom;
            const currentTarget = controls.target.clone();
            const desiredZoom = 2; // Adjust this value to zoom in

              const minZoom = 0.1;
              const maxZoom = 6;
              //reset orbiting up and down, left and right
              // controls.minPolarAngle = 0; // radians
              // controls.maxPolarAngle = Math.PI; // radians
              // controls.minAzimuthAngle = -Infinity; // radians
              // controls.maxAzimuthAngle = Infinity; // radians

                let  vRotationUp = ''; // radians, limit vertical rotation
                  let vRotationDown = ''; // radians, limit vertical rotation
                  let hRotationLeft = '';// radians, limit horizontal rotation
                  let hRotationRight = '';// radians, limit horizontal rotation
            
         

            // Check if the camera is already at the desired zoom level and position
                

                // let cameraTarget = new THREE.Vector3(desiredPosition.x, desiredPosition.y, desiredPosition.z);

                    //limit orbit control so that cuff can be view properly
                  

                

                controls.minDistance = minZoom; // Minimum zoom distance
                controls.maxDistance = maxZoom; // Maximum zoom distance

           
                const focusObjName = "collar1_1";
            const focusObj = scene.getObjectByName(focusObjName);

                let objPosition = new THREE.Vector3(0, 0, 0); 
                let  target = new THREE.Vector3();

               // If objType is a custom object with a position property
               if (focusObj) {
                   
                    focusObj.getWorldPosition(target);
                    console.log("Object Position target:", target);
              } else {
                
                  console.error("objType does not have a position property");
              }

            //   // Ensure objType is a THREE.Object3D or its subclass
            //   if (focusObj instanceof THREE.Object3D) {
            //       const target = new THREE.Vector3();
            //       let objPosition = focusObj.getWorldPosition(target);
            //       console.log("Object Position:", objPosition);
            //   } else {
            //       console.error("objType is not an instance of THREE.Object3D");
            //   }

                // Desired camera position, zoom level, and controls target
                let desiredPosition = {
                    x: 0,
                    y: 0,
                    z: 0
                };

                let desiredTarget = {
                    x: 0,
                    y: 0,
                    z: 0
                };


                //if pocket
                if (objType.includes('pocket')) {
                    desiredPosition = {
                      x: 0.6537838300525007,
                      y:2.0049606303488843,
                      z: 2.695346242626239,
                    };

                    desiredTarget = {
                      x: 0.07004663828851847,
                      y:1.6796724077862337,
                      z: -0.3386432076848833,
                    };
                }

                //if front_style
                if (objType.includes('front_style')) {
                    desiredPosition = {
                      x: 1.0310418162510335,
                      y: 2.822994792846132,
                      z: 4.101052137554581,
                    };

                    desiredTarget = {
                      x: -0.20023481906162138,
                      y:  1.3583501306971866,
                      z: -0.7220336843873053,
                    };

                
                }

                //if stitch
                if (objType.includes('Stiches_plane')) {
                    desiredPosition = {
                      x: -0.2170975720067706,
                      y: 2.833773137358597,
                      z: 1.3252673091813074,
                    };

                    desiredTarget = {
                      x: -0.04519897689870111,
                      y: 0.6456255802437604,
                      z: -0.4193797814752166,
                    };

        
                }

                //if collar
                if (objType.includes('collar')) {
                    desiredPosition = {
                      x: -0.03228005769457376,
                      y: 2.8984753041502422,
                      z: 4.122503560563652,
                    };

                    desiredTarget = {
                      x: 0.01690763772525074,
                      y: 1.753887059573311,
                      z: -0.9382158849144843,
                    };


                    // limit orbit control so that collar can be view properly
               
                    
                    //camera lock
                 
                }

                

                //if cuff
                if (objType.includes('cuff')) {
                  desiredPosition = {
                    x: 2.026025029339048,
                    y: 1.767713158934895,
                    z: 1.9072892610599246,
                  };

                  desiredTarget = {
                    x: 0,
                    y: 0.5,
                    z: -0.20000000000000004,
                  };

                  //limit orbit control so that cuff can be view properly
                   vRotationUp = Math.PI / 7 ; // radians, limit vertical rotation
                   vRotationDown = Math.PI / 2 ; // radians, limit vertical rotation
                 hRotationLeft = -Math.PI / 10; // radians, limit horizontal rotation
                 hRotationRight = Math.PI / 10; // radians, limit horizontal rotation
               
                 limitCameraControls()
                 


                }

                //if Pleat
                if (objType.includes('Pleat')) {
                    desiredPosition = {
                      x: -1.5710221814094587,
                      y: 1.2076031510606295,
                      z: -4.487272457130005,
                    };

                    desiredTarget = {
                      x: 0.022966,
                      y: 1.601402,
                      z: 0.002886,
                    };

                
                    
                }


                // Animation timeline
              const timeline = gsap.timeline({});

                

  


                timeline.to(camera, {
                    duration: 1,
                    zoom: Math.max(minZoom, Math.min(maxZoom, desiredZoom)), // Ensure desiredZoom is within range
                    ease: "power2.inOut", // Use easing function for smooth animation
                    onUpdate: function () {
                        // camera.lookAt(0,0,0); // Ensure camera looks at the desired target
                        // camera.lookAt(target);  // Ensure camera looks at the target object
                        camera.zoom = Math.max(minZoom, Math.min(maxZoom, camera.zoom)); // Clamp the zoom level
                        camera.updateProjectionMatrix(); // Ensure the camera's projection matrix is updated
                    },
                });

                if (objType === 'kandora_torso') {
                  desiredTarget = new THREE.Vector3(0, 0, 0);
                }

                timeline.to(camera.position, {
                  duration: 1,
                  x: desiredPosition.x,
                  y: desiredPosition.y,
                  z: desiredPosition.z,

                  ease: "power2.inOut", // Use easing function for smooth animation
                  onUpdate: function () {
                    //camera zoom

                    // camera.lookAt(target);  // Ensure camera looks at the target object

                    // camera.lookAt(0,0,0); 
                    camera.updateProjectionMatrix();
            
                    render();
                  },
                  onComplete: function () {
                    //camera zoom
               
                    controls.update(); // Ensure controls are updated
              
                    render();
                    
                   
                
                   
                    
                  },
                },0); // Start at the same time as the zoom animation

                timeline.to(controls.target, {
                  duration: 1,
                  x: desiredTarget.x,
                  y: desiredTarget.y,
                  z: desiredTarget.z,
               
                  onStart: function () {
                    controls.minDistance = 1.3; // Ensure min zoom distance is set before animation
                    controls.maxDistance = 20; // Ensure max zoom distance is set before animation

                  },
                  onUpdate: function () {
                    controls.update();
                  },
                  onComplete: function () {
                    // Ensure the camera is correctly looking at the final target
                    //  camera.lookAt(controls.target);
                    // limitCameraControls()
                  
                    controls.update();
                    controls.minDistance = 1.3; // Ensure min zoom distance is set after animation
                    controls.maxDistance = 20; // Ensure max zoom distance is set after animation

                    
                    console.log("Final Target Position:", controls.target);

                    //if cuff camera lock
                    if (objType.includes('cuff')) {
                      cameraLock = true;
                    } 
                  
                  }
                },0); // Start at the same time as the zoom animation

              

// Function to limit the camera controls
function limitCameraControls() {
  // Set bounds or limitations for the OrbitControls here
  setTimeout(function() {
  controls.maxPolarAngle = Math.PI / 2; // Limit vertical rotation to prevent flipping
  controls.minPolarAngle = Math.PI / 7; // Limit upward tilt
  controls.maxAzimuthAngle = Math.PI / 3; // Limit left and right rotation
  controls.minAzimuthAngle = Math.PI / 2; // Limit left and right rotation
  console.log('Camera controls limited');
  //constrols position console.log
  // console.log(controls.position0);
  }, 100);

  
  // Optionally, enable controls damping for better feeling
  // controls.enableDamping = true;
}

     

render();
            
      


            
        

        }

       

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

            //store color code in global variable
            currentMeshColor = value;


        }


        

        



        function changeTextureByName(textureName, textureUrl) {
            // Preload the image
            var img = new Image();
            img.src = textureUrl;

            img.onload = function () {
                // Image is preloaded, proceed with updating the texture
                console.log('Texture change');
                const loader = new THREE.TextureLoader();
                loader.load(textureUrl, function (texture) {
                    texture.wrapS = THREE.RepeatWrapping;
                    texture.wrapT = THREE.RepeatWrapping;
                    texture.needsUpdate = true; // Ensure the texture is updated

                    scene.traverse((child) => {
                        if (child.isMesh && child.name === textureName) { // Ensure you have a way to identify the mesh
                            child.visible = true;

                            //remove old texture
                            //dispose old texture
                            if (child.material.map) {
                                child.material.map.dispose();
                                child.material.map = null;
                            }
                            //enviroment none
                            scene.environment = null;

                            child.material.needsUpdate = true; // Ensure the material updates immediately
                            child.material.map = texture;

                            // Use emission map
                            child.material.emissiveMap = texture;
                            child.material.emissiveIntensity = 0.3; // Adjust emissive intensity
                            child.material.needsUpdate = true; // Ensure the material updates immediately

                            // Adjust roughness and metalness
                            child.material.roughness = 0; // Adjust roughness
                            child.material.metalness = 0; // Adjust metalness

                            // alpha albedo
                            // child.material.transparent = true;
                            // child.material.opacity = 0;
                            // gsap.to(child.material, {
                            //   duration: 1,
                            //   opacity: 1,
                            //   onUpdate: function () {
                            //     child.material.needsUpdate = true;
                            //   }
                            // });

                            // render();
                        }
                    });
                });
            };
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
                            child.visible = true;
                            document.querySelector('.panel-3d').classList.add('loading-assets');

                            // Load the specular map
                            // Load the specular map
                            const specularLoader = new THREE.TextureLoader();
                            specularLoader.load('models/spec.jpeg', function (specularTexture) {
                                if (child.material) {
                                    if (child.material.type === 'MeshStandardMaterial' || child.material.type === 'MeshPhongMaterial') {
                                        child.material.specularMap = specularTexture;
                                    } else if (child.material.type === 'MeshPhysicalMaterial') {
                                        child.material.roughnessMap = specularTexture;
                                        child.material.metalnessMap = specularTexture;
                                    } else {
                                        console.warn('Material does not support specular maps:', child.material);
                                    }
                                    child.material.needsUpdate = true; // Ensure the material updates immediately
                                }
                            });


                            if (child.name === 'Embriodery_plane005') {
                                //remove old texture
                                //dispose old texture
                                if (child.material.map) {
                                    child.material.map.dispose();
                                    child.material.map = null;
                                }
                                //enviroment none
                                scene.environment = null;


                                child.material.needsUpdate = true; // Ensure the material updates immediately
                                child.material.map = texture;

                                // Use emission map
                                child.material.emissiveMap = texture;
                                child.material.emissiveIntensity = 0.3; // Adjust emissive intensity
                                child.material.needsUpdate = true; // Ensure the material updates immediately

                                // Adjust roughness and metalness
                                // child.material.roughness = 0.2; // Adjust roughness
                                // child.material.metalness = 0.2; // Adjust metalness


                                //get current base color code and apply for emisive 
                                //child.material.emissive.set(0x000000);





                                // child.material.emissiveIntensity = 0.5; // Set emissive intensity to 0
                                child.material.roughness = 0; // Adjust roughness
                                child.material.metalness = 0; // Adjust metalness

                                // child.material.transparent = true;
                                // child.material.opacity = 0;
                                // gsap.to(child.material, {
                                //   duration: 1,
                                //   opacity: 1,
                                //   onUpdate: function () {
                                //     child.material.needsUpdate = true;
                                //   }
                                // });
                            }


                            // Remove the loading animation
                            //add delay
                            setTimeout(() => {
                                document.querySelector('.panel-3d').classList.remove('loading-assets');
                           
                            

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

                                var minZoom = 0.1;
                                var maxZoom = 6;
                                controls.minDistance = minZoom; // Minimum zoom distance
                                controls.maxDistance = maxZoom; // Maximum zoom distance

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
                                        //move camera closer to model
                                        // camera.position.set(desiredPosition.x, desiredPosition.y, desiredPosition.z);
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
                                        controls.maxDistance = 50; // Ensure max zoom distance is set before animation
                                    },
                                    onUpdate: function () {
                                        controls.update();
                                        render();
                                    },
                                    onComplete: function () {
                                        controls.update();
                                        controls.minDistance = 1; // Ensure min zoom distance is set after animation
                                        controls.maxDistance = 50; // Ensure max zoom distance is set after animation
                                    }
                                }, 0); // Start at the same time as the zoom animation

                                

                            }
                          }, 500); // Delay of 500 milliseconds
                            render();
                        }
                    });
                    render();
                });
            };
        }

        // Make updateEmbroideryTexture function available globally
        window.updateEmbroideryTexture = updateEmbroideryTexture;


        // Function to remove embroidery texture
        function removeEmbroideryTexture() {
            console.log('Remove embroidery texture');
            scene.traverse((child) => {
                if (child.isMesh && child.name === 'Embriodery_plane005') {
                    //visible false
                    child.visible = false;


                }
            });
            render();
        }

        // Make removeEmbroideryTexture function available globally
        window.removeEmbroideryTexture = removeEmbroideryTexture;







        renderer = new THREE.WebGLRenderer({ antialias: true });
        renderer.setPixelRatio(window.devicePixelRatio);
        renderer.setSize(container.clientWidth, container.clientHeight);
        //envrionment none
        renderer.toneMapping = THREE.LinearToneMapping;
        renderer.toneMappingExposure = 1;
        container.appendChild(renderer.domElement);

        //metalicness


     



      // Initialize OrbitControls
      const controls = new OrbitControls(camera, renderer.domElement);
      controls.addEventListener('change', render);
      controls.minDistance = 1.3; // Minimum zoom distance
      controls.maxDistance = 20; // Maximum zoom distance
      controls.target.set(0, 0.5, -0.2);
      controls.update();

     //if camera zoom is below 1.2 then reset camera
      controls.addEventListener('change', function () {
          //if cameralock is true then reset camera
          if (cameraLock) {
                    if (camera.position.z > 3 ) {
                      cameraLock = false;
                    resetCamera();
                    console.log('Camera reset');
                  } else {
                    console.log('Camera position.z:', camera.position.z);
                  }
          }else
          {
            console.log('camera lock', cameraLock);
          }
      });


      //if camera position.z become greater than 1.7 than reset camera
     


      // get camera position and zoom level in console log when user move the camera
    //   controls.addEventListener('change', function () {
    //     console.log('Camera:', camera); // Check if camera is defined
    // console.log('Controls:', controls); // Check if controls are defined
    // console.log('Camera Position:', camera.position);
    // console.log('Controls Target:', controls.target);
 
                
             
    //         });

            // Add this function to log the camera and controls values
// function logCameraAndControls() {
//     console.log('Camera Position:', camera.position);
//     console.log('Camera Zoom:', camera.zoom);
//     console.log('Controls Target:', controls.target);
// }

// // Add event listeners to log values when the camera or controls are updated
// controls.addEventListener('change', logCameraAndControls);
// camera.addEventListener('change', logCameraAndControls);


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

                <div class="panel-3d" style="">
                  <span class="loader"></span>
                  <a href="javascript:void(0) "class="reset-camera" id="resetcamera">
                  <?xml version="1.0" standalone="no"?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 20010904//EN"
 "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">
<svg version="1.0" xmlns="http://www.w3.org/2000/svg"
 width="512.000000pt" height="512.000000pt" viewBox="0 0 512.000000 512.000000"
 preserveAspectRatio="xMidYMid meet">

<g transform="translate(0.000000,512.000000) scale(0.100000,-0.100000)"
fill="#000000" stroke="none">
<path d="M3795 4479 c-128 -30 -196 -167 -150 -299 8 -23 15 -44 15 -47 0 -2
-26 14 -57 35 -207 139 -488 245 -763 288 -139 22 -419 22 -557 1 -419 -66
-777 -245 -1072 -537 -560 -553 -725 -1392 -415 -2117 266 -622 841 -1059
1513 -1148 126 -17 353 -19 471 -5 771 96 1400 625 1621 1363 60 203 97 527
69 611 -16 48 -63 102 -111 127 -70 35 -180 20 -237 -34 -50 -46 -65 -94 -73
-233 -14 -222 -59 -396 -155 -589 -205 -413 -580 -699 -1054 -801 -121 -27
-439 -27 -560 0 -474 102 -849 387 -1054 801 -108 218 -156 422 -156 665 0
709 476 1299 1179 1461 129 30 405 37 542 14 183 -30 344 -86 496 -171 l66
-37 -29 -12 c-75 -31 -131 -128 -122 -209 8 -65 50 -130 106 -163 l47 -28 350
-3 c417 -4 436 -2 505 68 39 38 50 58 61 104 l13 58 -122 367 c-105 317 -127
372 -156 404 -53 58 -135 83 -211 66z"/>
</g>
</svg>

                  </a>

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

        <div class="customize-section-box">
          <div class="heading" >
            <h3>Choose Front Style</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border">
                <li onclick="addObj('front_style', 'frontstyle.glb', 'front_style_01');">
                  <div class="img"><img src="images/frontstyle/1.png" alt="" /></div>
                  <h4 class="front_style">Emirati with Zipper</h4>
                </li>
                <li onclick="addObj('front_style', 'frontstyle.glb', 'front_style_02_1,front_style_02_2');">
                  <div class="img"><img src="images/frontstyle/_KA_4507.png" alt="" /></div>
                  <h4 class="front_style">Emirati</h4>
                </li>
                <li onclick="addObj('front_style', 'frontstyle.glb', 'front_style_03_1,front_style_03_2');">
                  <div class="img"><img src="images/frontstyle/_KA_4522.png" alt="" /></div>
                  <h4 class="front_style">Emirati</h4>
                </li>
                <li onclick="addObj('front_style', 'frontstyle.glb', 'front_style_04_1,front_style_04_2');">
                  <div class="img"><img src="images/frontstyle/1.png" alt="" /></div>
                  <h4 class="front_style">Emirati with Zipper</h4>
                </li>
                <li onclick="addObj('front_style', 'frontstyle.glb', 'front_style_05_1,front_style_05_2');">
                  <div class="img"><img src="images/frontstyle/f380ed75-a512-4892-9e18-8775faa6c585-0.png" alt="" /></div>
                  <h4 class="front_style">style 5</h4>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <!-- <div class="customize-section-box">
          <div class="heading"  disabled="disabled">
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
        </div> -->


        <div class="customize-section-box">
          <div class="heading">
            <h3>Choose Stitch Style</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border">
                <li>
                  <div class="img"   onclick="addObj('Stiches_plane', 'stich.glb', 'Stiches_plane', 'models/stitches/s1.png' );">
                    <img src="assets/images/customizeyourkandora/8-stitches.png" alt="" />
                  </div>
                  <h4 class="stitch_style">1 Stitche</h4>
                </li>
                <li>
                  <div class="img" onclick="addObj('Stiches_plane', 'stich.glb', 'Stiches_plane', 'models/stitches/s2.png');">
                    <img src="assets/images/customizeyourkandora/12-stitches.png" alt="" />
                  </div>
                  <h4 class="stitch_style">4 Stitches</h4>
                </li>
                <li>
                  <div class="img" onclick="addObj('Stiches_plane', 'stich.glb', 'Stiches_plane', 'models/stitches/s3.png');">
                    <img src="assets/images/customizeyourkandora/4-stitches-with-embroidery.png" alt="" />
                  </div>
                  <h4 class="stitch_style">6 Stitches with Embroidery</h4>
                </li>
                <li>
                  <div class="img" onclick="addObj('Stiches_plane', 'stich.glb', 'Stiches_plane', 'models/stitches/s4.png');">
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
            <h3>Choose Collar Style</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border">
                <li onclick="addCollar('collar1_1, collar1_2, collar1_3')">
                  <div class="img">
                    <img src="images/collar/K1.png" alt="" />
                  </div>
                  <h4 class="collar_style">1</h4>
                </li>
                <li onclick="addCollar('collar2_1, collar2_2')">
                  <div class="img">
                    <img src="images/collar/K2.png" alt="" />
                  </div>
                  <h4 class="collar_style">2</h4>
                </li>
                <li onclick="addCollar('collar3_1, collar3_2')">
                  <div class="img">
                    <img src="images/collar/K3.png" alt="" />
                  </div>
                  <h4 class="collar_style">3</h4>
                </li>
                <li onclick="addCollar('collar4_1, collar4_2, collar4_3')">
                  <div class="img">
                    <img src="images/collar/K4.png" alt="" />
                  </div>
                  <h4 class="collar_style">4</h4>
                </li>

                <li onclick="addCollar('collar5_1, collar5_2')">
                  <div class="img">
                    <img src="images/collar/K5.png" alt="" />
                  </div>
                  <h4 class="collar_style">5</h4>
                </li>
                <li onclick="addCollar('collar6_1, collar6_2')">
                  <div class="img">
                    <img src="images/collar/K6.png" alt="" />
                  </div>
                  <h4 class="collar_style">6</h4>
                </li>
                <li onclick="addCollar('collar7_1, collar7_2')">
                  <div class="img">
                    <img src="images/collar/K7.png" alt="" />
                  </div>
                  <h4 class="collar_style">7</h4>
                </li>
                <li onclick="addCollar('collar8')">
                  <div class="img">
                    <img src="images/collar/Q1.png" alt="" />
                  </div>
                  <h4 class="collar_style">8</h4>
                </li>
                <li onclick="addCollar('collar9')">
                  <div class="img">
                    <img src="images/collar/Q2.png" alt="" />
                  </div>
                  <h4 class="collar_style">9</h4>
                </li>
              </ul>
            </div>
          </div>
        </div>

        <div class="customize-section-box">
          <div class="heading" >
            <h3>Choose Cuffs</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border">
                <li onclick='addCuff("Cuff01_1, Cuff01_2")'>
                  <div class="img">
                    <img src="images/Cuff/_KA_4070-Edit.png" alt="" />
                  </div>
                  <h4 class="cuffs">One Type</h4>
                </li>
                <li onclick='addCuff("cuff02_1, cuff02_2, cuff02_3")'>
                  <div class="img">
                    <img src="images/Cuff/_KA_4071-Edit.png" alt="" />
                  </div>
                  <h4 class="cuffs">One Button</h4>
                </li>
                <li onclick='addCuff("cuff3_1, cuff3_2, cuff3_3")'>
                  <div class="img">
                    <img src="images/Cuff/_KA_4073-Edit.png" alt="" />
                  </div>
                  <h4 class="cuffs">Two Buttons</h4>
                </li>
                <li onclick='addCuff("cuff4_1, cuff4_2 , cuff4_3")'>
                  <div class="img">
                    <img src="images/Cuff/1.png" alt="" />
                  </div>
                  <h4 class="cuffs">French Cuffs</h4>
                </li>
              </ul>
            </div>
          </div>
        </div>

                <div class="customize-section-box">
                    <div class="heading">
                        <h3>Choose Embroidery Style</h3>
                        <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
                    </div>
                    <div class="customize-option">
                        <div class="images-option mt-0">
                            <ul class="image-border">
                                <li class="active">

                                    <div class="img" onclick="removeEmbroideryTexture()">
                                        <img src="models/embroidery/none.png" alt="" />
                                    </div>
                                    <h4 class="embroidery_style">No Embroidery</h4>
                                </li>
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
                                        onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/new/style4.png')">
                                        <img src="models/embroidery/_KA_4446.png" alt="" />
                                    </div>
                                    <h4 class="embroidery_style">Style 4</h4>
                                </li>
                                <li>
                                    <div class="img"
                                        onclick="updateEmbroideryTexture( 'assets/images/customizeyourkandora/embroidery/new/style5.png')">
                                        <img src="models/embroidery/_KA_4451.png" alt="" />
                                    </div>
                                    <h4 class="embroidery_style">Style 5</h4>
                                </li>
                                <li>
                                    <div class="img"
                                        onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/new/style6.png')">
                                        <img src="models/embroidery/_KA_4456.png" alt="" />
                                    </div>
                                    <h4 class="embroidery_style">Style 6</h4>
                                </li>
                                <li>
                                    <div class="img"
                                        onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/new/style7.png')">
                                        <img src="models/embroidery/_KA_4457.png" alt="" />
                                    </div>
                                    <h4 class="embroidery_style">Style 7</h4>
                                </li>
                                <li>
                                    <div class="img"
                                        onclick="updateEmbroideryTexture('assets/images/customizeyourkandora/embroidery/new/style8.png')">
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

                <div class="customize-section-box">
          <div class="heading" >
            <h3>Choose Back Style</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border align-items-end">
                <li onclick="addObj('Pleat1', 'back Pleats.glb', 'Pleat1');">
                  <div class="img">
                    <img src="images/Backstyle/Plain.png" alt="" />
                  </div>
                  <h4 class="back_style">Plain</h4>
                </li>
                <li onclick="addObj('Pleat2', 'back Pleats.glb', 'Pleat2');">
                  <div class="img">
                    <img src="images/Backstyle/Sidepleats.png" alt="" />
                  </div>
                  <h4 class="back_style">Side Pleats</h4>
                </li>
                
              </ul>
            </div>
          </div>
        </div>

                <div class="customize-section-box">
          <div class="heading" >
            <h3>Choose Pockets</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border no-pocket">
                <li onclick="addObj('pocket','pocketaftermath.glb','pocket2_1');">
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/No-Pocket.png" alt="" />
                  </div>
                  <h4 class="pockets">No Pocket</h4>
                </li>
                <li onclick="addObj('pocket','pocketaftermath.glb','pocket1_1, pocket1_2');">
                  <div class="img">
                    <img src="images/Pocket/P1.png" alt="" />
                  </div>
                  <h4 class="pockets">Style 1</h4>
                </li>
                <li onclick="addObj('pocket','pocketaftermath.glb','pocket2001, pocket2001_1');">
                  <div class="img">
                    <img src="images/Pocket/P2.png" alt="" />
                  </div>
                  <h4 class="pockets">Style 2</h4>
                </li>
                <li onclick="addObj('pocket','pocketaftermath.glb','pocket3_1, pocket3_2');">
                  <div class="img">
                    <img src="images/Pocket/P3.png" alt="" />
                  </div>
                  <h4 class="pockets">Style 3</h4>
                </li>
                <li onclick="addObj('pocket','pocketaftermath.glb','pocket4_1, pocket4_2');">
                  <div class="img">
                    <img src="images/Pocket/P4.png" alt="" />
                  </div>
                  <h4 class="pockets">Style 4</h4>
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
                                <td class="kandora-type-checkmark"><img
                                        src="assets/images/customizeyourkandora/checkmark.svg" alt="" class="d-none" />
                                </td>
                            </tr>
                            <tr>
                                <td>Fabric</td>
                                <td class="fabric_radio"></td>
                                <td><img src="assets/images/customizeyourkandora/checkmark.svg" alt="" class="d-none" />
                                </td>
                            </tr>
                            <tr>
                                <td>Size</td>
                                <td class="size_radio"></td>
                                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" />
                                </td>
                            </tr>
                            <tr>
                                <td>Front Style</td>
                                <td class="front_style"></td>
                                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" />
                                </td>
                            </tr>
                            <tr>
                                <td>Front Pleat Style</td>
                                <td class="front_pleat_style"></td>
                                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" />
                                </td>
                            </tr>
                            <tr>
                                <td>Side Lines</td>
                                <td class="side_lines"></td>
                                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" />
                                </td>
                            </tr>
                            <tr>
                                <td>Stitch Style</td>
                                <td class="stitch_style"></td>
                                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" />
                                </td>
                            </tr>
                            <tr>
                                <td>Embroidery Style</td>
                                <td class="embroidery_style"></td>
                                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" />
                                </td>
                            </tr>
                            <tr>
                                <td>Farukha Type</td>
                                <td class="farukha_type"></td>
                                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" />
                                </td>
                            </tr>
                            <tr>
                                <td>Tarboosh Style</td>
                                <td class="tarboosh_style"></td>
                                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" />
                                </td>
                            </tr>
                            <tr>
                                <td>Collar Style</td>
                                <td class="collar_style"></td>
                                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" />
                                </td>
                            </tr>
                            <tr>
                                <td>Collar Thickness</td>
                                <td class="collar_thickness"></td>
                                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" />
                                </td>
                            </tr>
                            <tr>
                                <td>Cuffs</td>
                                <td class="cuffs"></td>
                                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" />
                                </td>
                            </tr>
                            <tr>
                                <td>Back Style</td>
                                <td class="back_style"></td>
                                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" />
                                </td>
                            </tr>
                            <tr>
                                <td>Pockets</td>
                                <td class="pockets"></td>
                                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" />
                                </td>
                            </tr>
                            <tr>
                                <td>Pocket Edging</td>
                                <td class="pocket_edging"></td>
                                <td><img src="assets/images/customizeyourkandora/checkmark.svg" class="d-none" alt="" />
                                </td>
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
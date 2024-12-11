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
                            if (child.name === 'cuff01_1' || child.name === 'cuff01_2' || child.name === 'cuff02_1' || child.name === 'cuff02_2' || child.name === 'cuff02_3' || child.name === 'cuff3_1' || child.name === 'cuff3_2' || child.name === 'cuff3_3' || child.name === 'cuff4_1' || child.name === 'cuff4_2' || child.name === 'cuff4_3') {
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
                                // console.log("Remove pocket", child.name);
                            }

                            // if child includes Stiches_plane
                            if (child.name.includes('Stiches_plane')) {
                                child.visible = false;
                                // console.log("Remove Stiches_plane", child.name);
                            }

                            // if child includes Pleat
                            if (child.name.includes('Pleat')) {
                                child.visible = false;
                                // console.log("Remove Pleat", child.name);
                            }
                            

                            
                            

                        }
                    });

                    objectsToRemove.forEach((child) => {
                        if (child.parent) {
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
                // class loading-assets to panel-3d
                document.querySelector('.panel-3d').classList.add('loading-assets');
                
                // Check if the object is already added
                let objAdded = false;
                scene.traverse((child) => {
                  if (child.isMesh && child.name.includes(objectType)) {
                    objAdded = true;
                  }
                });
                

                if (!objAdded) {
                  loader.load(objectFile, function (glb) {
                    glb.scene.scale.set(1.7, 1.7, 1.7);
                    if (objectFile === 'pocket.glb') {
                      glb.scene.position.set(0, -1.1, 0.01);
                    } else {
                      glb.scene.position.set(0, -1.1, 0);
                    }
                    scene.add(glb.scene);

                    // Remove specific objects from the GLB model
                    removeObjects(glb.scene);

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

                    // Render the scene after the model is loaded
                    setTimeout(() => {
                      document.querySelector('.panel-3d').classList.remove('loading-assets');
                      render();
                      animateCameraToObjPosition(objectType);
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

                  // Make the specified styles visible
                  scene.traverse((child) => {
                    if (child.isMesh && styles.some(style => child.name.includes(style))) {
                      child.visible = true;
                    }
                  });

                  //if textureURL is not empty
                  if (textureURL) {
                      changeTextureByName(objectType, textureURL);
                    }

                  // Render the scene after modifying the model
                  setTimeout(() => {
                    document.querySelector('.panel-3d').classList.remove('loading-assets');
                    render();
                    animateCameraToObjPosition(objectType);
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

                        // Render the scene after both models are loaded
                        setTimeout(() => {
                          document.querySelector('.panel-3d').classList.remove('loading-assets');
                          render();
                          animateCameraToObjPosition("collar");
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

                      // Render the scene after modifying the model
                      setTimeout(() => {
                        document.querySelector('.panel-3d').classList.remove('loading-assets');
                        render();
                        animateCameraToObjPosition("collar");
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

                        // Make the specified cuff styles visible
                        const cuffStyles = cuffstyle.split(',').map(style => style.trim());
                        glb2.scene.traverse((child) => {
                          if (child.isMesh && cuffStyles.some(style => child.name.includes(style))) {
                            child.visible = true;
                          }
                        });

                        // Render the scene after both models are loaded
                        setTimeout(() => {
                          document.querySelector('.panel-3d').classList.remove('loading-assets');
                          render(); 
                          animateCameraToObjPosition("kandora_torso");
                          

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
                      
                        setTimeout(() => {
                          document.querySelector('.panel-3d').classList.remove('loading-assets');
                          render(); 
                          animateCameraToObjPosition("kandora_torso");
                          

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


        
//         function animateCameraToObjPosition(objType) {
//           scene.traverse((child) => {
//         if (child.isMesh && child.name.includes(objType)) {
//             // Move the camera to view the object
//             const objPosition = child.position.clone();
//             const cameraTarget = new THREE.Vector3(objPosition.x, objPosition.y, objPosition.z);

//             // Capture the current camera position, zoom level, and controls target
//             const currentCameraPosition = camera.position.clone();
//             const currentZoom = camera.zoom;
//             const currentTarget = controls.target.clone();

//             // Desired camera position, zoom level, and controls target
//             let desiredZoom = 1; // Adjust this value to zoom in
//             let desiredPosition = {
//                 x: objPosition.x + 3, // Adjust these values as needed
//                 y: objPosition.y + 4, // Increase y value to move vertically
//                 z: objPosition.z + 5
//             };

            
//             let desiredTarget = objPosition.clone();

//             // If pocket then change position
//             if (objType.includes('pocket')) {
//                 desiredZoom = 3;
//                 desiredPosition.x = objPosition.x + 1; // Adjust these values as needed
//                 desiredPosition.y = objPosition.y + 5; // Increase y value to move vertically
//                 desiredPosition.z = objPosition.z + 5;
//             }

//              //if objType is cuff
//              if (objType === 'cuff') {
//                 desiredPosition.x = 7;
//                 desiredPosition.y = 7;
//                 desiredPosition.z = 6;
//                 desiredZoom = 5;
                
//             }

//             //if objType is collar
//             if (objType.includes('collar')) {
//               desiredZoom = 1;
//               desiredPosition.x = 1.02;
//               desiredPosition.y = 35.75;
//               desiredPosition.z = 40.04;
//             }


//             //if objType is pleat camera.position.set(1, 2, 8);
//             if (objType.includes('Pleat')) {
//               desiredZoom = 2;
//               desiredPosition.x = -2.3;
//               desiredPosition.y = 3.8;
//               desiredPosition.z = -6.70;
//               desiredTarget = child.position.clone();
//               console.log('Pleat position:', desiredPosition);
            
//             }

//             //if objType is frontstyle
//             if (objType.includes('front_style')) {
//               desiredZoom = 3;
//               desiredPosition.x = 1;
//               desiredPosition.y = -2;
//               desiredPosition.z = 8;
//               desiredTarget = child.position.clone();
//               // console.log('Frontstyle position:', desiredPosition);
//           }




//             //if objType is stitches
//             if (objType.includes('Stiches')) {
//               desiredZoom = 3;
//               desiredPosition.x = 1.02;
//               desiredPosition.y = 7;
//               desiredPosition.z = 8;
//               desiredTarget = child.position.clone();
      
//             }
            


       
//             if (objType.includes('collar')) {
//                 scene.traverse((child) => {
//                     if (child.isMesh && child.name.includes('collar')) {
//                         desiredTarget = child.position.clone();
//                     }
//                 });
//             }


//             // Check if the camera is already at the desired zoom level and position
//             if (currentZoom !== desiredZoom || !currentCameraPosition.equals(new THREE.Vector3(desiredPosition.x, desiredPosition.y, desiredPosition.z))) {
//                 const timeline = gsap.timeline();

//                 var minZoom = 0.1;
//                 var maxZoom = 6;
//                 controls.minDistance = minZoom; // Minimum zoom distance
//                 controls.maxDistance = maxZoom; // Maximum zoom distance

//                 timeline.to(camera, {
//                     duration: 1,
//                     zoom: Math.max(minZoom, Math.min(maxZoom, desiredZoom)), // Ensure desiredZoom is within range
//                     ease: "power2.inOut", // Use easing function for smooth animation
//                     onUpdate: function () {
//                         camera.zoom = Math.max(minZoom, Math.min(maxZoom, camera.zoom)); // Clamp the zoom level
//                         camera.updateProjectionMatrix(); // Ensure the camera's projection matrix is updated
//                     },
//                 });

//                 // console.log('Camera start:', camera.position);

           
//                 timeline.to(camera.position, {
//                     duration: 1,
//                     x: desiredPosition.x,
//                     y: desiredPosition.y,
//                     z: desiredPosition.z,
//                     onUpdate: function () {
//                       camera.lookAt(desiredTarget);
//                         controls.update(); // Ensure controls are updated
//                         render();
//                     },
//                     onComplete: function () {
//                         // console.log('Camera end:', camera.position); // Log the camera position after the animation completes
//                     }
//                 }, 0); // Start at the same time as the zoom animation

//                 timeline.to(controls.target, {
//                     duration: 1,
//                     x: desiredTarget.x,
//                     y: desiredTarget.y,
//                     z: desiredTarget.z,
//                     onStart: function () {
//                         controls.minDistance = 1; // Ensure min zoom distance is
//                         controls.maxDistance = 50; // Ensure max zoom distance is set before animation
//                     },
//                     onUpdate: function () {
//                         controls.update();
//                         render();
//                     },
//                     onComplete: function () {
//                         controls.update();
//                         controls.minDistance = 1; // Ensure min zoom distance is set after animation
//                         controls.maxDistance = 50; // Ensure max zoom distance is set after animation
//                     }
//                 }, 0); // Start at the same time as the zoom animation
//             } 
//             render();
//         }
//     });
// }
        

function animateCameraToObjPosition(objType) {
            // Capture the current camera position, zoom level, and controls target
            const currentCameraPosition = camera.position.clone();
            const currentZoom = camera.zoom;
            const currentTarget = controls.target.clone();

            
            var minZoom = 0.1;
            var maxZoom = 50;

            // Desired camera position, zoom level, and controls target
            let desiredZoom = 3; // Adjust this value to zoom in
            const desiredPosition = {
                x: 1, // Adjust these values as needed
                y: 7,
                z: 8
            };

            //if objType is cuff
            if (objType === 'kandora_torso') {
                desiredPosition.x = 9;
                desiredPosition.y = 8;
                desiredPosition.z = 8;
                desiredZoom = 5;
            }

            //if objType is collar
            if (objType.includes('collar')) {
              desiredZoom = 5;
              desiredPosition.x = 1.02;
              desiredPosition.y = 10;
              desiredPosition.z = 10;
            }


            //if objType is pleat camera.position.set(1, 2, 8);
            if (objType.includes('Pleat')) {
              desiredZoom = 2;
              desiredPosition.x = -1;
              desiredPosition.y = 3.8;
              desiredPosition.z = -6.70;
              console.log('Pleat position:', desiredPosition);
            }

            //if objType is frontstyle
            if (objType.includes('front_style')) {
              desiredZoom = 1;
              desiredPosition.x = 0.47;
              desiredPosition.y = 2.13;
              desiredPosition.z = 2.70;
              console.log('Frontstyle position:', desiredPosition);

     
          }

         


            //if objType is pocket
            if (objType.includes('pocket')) {
              desiredZoom = 3;
              desiredPosition.x = 1.02;
              desiredPosition.y = 3;
              desiredPosition.z = 8;
        
              console.log('Pocket position:', desiredPosition);
            }

            //if objType is stitches
            if (objType.includes('Stiches')) {
              desiredZoom = 7;
              desiredPosition.x = -1.5;
              desiredPosition.y = 2;
              desiredPosition.z = 5;
              console.log('Stitches position:', desiredPosition);
            }
            


            let desiredTarget = new THREE.Vector3(-0.06253863501371498, 1.6308571305580224,  -0.3992374464418679);
           
            if (objType === 'cuff') {
              let desiredTarget = new THREE.Vector3(0, 0, 0);
           
            }



            // Check if the camera is already at the desired zoom level and position
            if (currentZoom !== desiredZoom || !currentCameraPosition.equals(new THREE.Vector3(desiredPosition.x, desiredPosition.y, desiredPosition.z))) {
                const timeline = gsap.timeline();

                let cameraTarget = new THREE.Vector3(desiredPosition.x, desiredPosition.y, desiredPosition.z);

                
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

                if (objType === 'kandora_torso') {
                  desiredTarget = new THREE.Vector3(0, 0, 0);
                }

                timeline.to(camera.position, {
                    duration: 1,
                    x: desiredPosition.x,
                    y: desiredPosition.y,
                    z: desiredPosition.z,
                    onUpdate: function () {
                        //camera zoom
                        camera.lookAt(desiredTarget);
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
                        controls.minDistance = 1.8; // Ensure min zoom distance is
                        controls.maxDistance = 50; // Ensure max zoom distance is set before animation
                    },
                    onUpdate: function () {
                        controls.update();
                        render();
                    },
                    onComplete: function () {
                        controls.update();
                        controls.minDistance = 1.8; // Ensure min zoom distance is set after animation
                        controls.maxDistance = 50; // Ensure max zoom distance is set after animation
                    }
                }, 0); // Start at the same time as the zoom animation
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

            // Ensure the texture update is visible when the model is moved

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
      controls.minDistance = 1; // Minimum zoom distance
      controls.maxDistance = 20; // Maximum zoom distance
      controls.target.set(0, 0.5, -0.2);
      controls.update();


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
                  <div class="img"><img src="assets/images/customizeyourkandora/front-emirati.png" alt="" /></div>
                  <h4 class="front_style">Emirati with Zipper</h4>
                </li>
                <li onclick="addObj('front_style', 'frontstyle.glb', 'front_style_02_1,front_style_02_2');">
                  <div class="img"><img src="assets/images/customizeyourkandora/front-emirati.png" alt="" /></div>
                  <h4 class="front_style">Emirati</h4>
                </li>
                <li onclick="addObj('front_style', 'frontstyle.glb', 'front_style_03_1,front_style_03_2');">
                  <div class="img"><img src="assets/images/customizeyourkandora/front-emirati.png" alt="" /></div>
                  <h4 class="front_style">Emirati</h4>
                </li>
                <li onclick="addObj('front_style', 'frontstyle.glb', 'front_style_04_1,front_style_04_2');">
                  <div class="img"><img src="assets/images/customizeyourkandora/front-emirati.png" alt="" /></div>
                  <h4 class="front_style">Emirati with Zipper</h4>
                </li>
                <li onclick="addObj('front_style', 'frontstyle.glb', 'front_style_04_1,front_style_04_2');">
                  <div class="img"><img src="assets/images/customizeyourkandora/front-emirati.png" alt="" /></div>
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
                    <img src="assets/images/customizeyourkandora/collar-2.png" alt="" />
                  </div>
                  <h4 class="collar_style">1</h4>
                </li>
                <li onclick="addCollar('collar2_1, collar2_2')">
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/collar-3.png" alt="" />
                  </div>
                  <h4 class="collar_style">2</h4>
                </li>
                <li onclick="addCollar('collar3_1, collar3_2')">
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/collar-2.png" alt="" />
                  </div>
                  <h4 class="collar_style">3</h4>
                </li>
                <li onclick="addCollar('collar4_1, collar4_2, collar4_3')">
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/collar-3.png" alt="" />
                  </div>
                  <h4 class="collar_style">4</h4>
                </li>

                <li onclick="addCollar('collar5_1, collar5_2')">
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/collar-2.png" alt="" />
                  </div>
                  <h4 class="collar_style">5</h4>
                </li>
                <li onclick="addCollar('collar6_1, collar6_2')">
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/collar-3.png" alt="" />
                  </div>
                  <h4 class="collar_style">6</h4>
                </li>
                <li onclick="addCollar('collar7_1, collar7_2')">
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/collar-2.png" alt="" />
                  </div>
                  <h4 class="collar_style">7</h4>
                </li>
                <li onclick="addCollar('collar8')">
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/collar-3.png" alt="" />
                  </div>
                  <h4 class="collar_style">8</h4>
                </li>
                <li onclick="addCollar('collar9')">
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/collar-3.png" alt="" />
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
                    <img src="assets/images/customizeyourkandora/one-type.png" alt="" />
                  </div>
                  <h4 class="cuffs">One Type</h4>
                </li>
                <li onclick='addCuff("cuff02_1, cuff02_2, cuff02_3")'>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/one-button.png" alt="" />
                  </div>
                  <h4 class="cuffs">One Button</h4>
                </li>
                <li onclick='addCuff("cuff3_1, cuff3_2, cuff3_3")'>
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/two-buttons.png" alt="" />
                  </div>
                  <h4 class="cuffs">Two Buttons</h4>
                </li>
                <li onclick='addCuff("cuff4_1, cuff4_2 , cuff4_3")'>
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
                    <img src="assets/images/customizeyourkandora/Back-plain.png" alt="" />
                  </div>
                  <h4 class="back_style">Plain</h4>
                </li>
                <li onclick="addObj('Pleat2', 'back Pleats.glb', 'Pleat2');">
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/Back-side-pleats.png" alt="" />
                  </div>
                  <h4 class="back_style">Side Pleats</h4>
                </li>
                <li onclick="alert('not available');">
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/Back-center-pleat.png" alt="" />
                  </div>
                  <h4 class="back_style">Center Pleat</h4>
                </li>
                <li onclick="alert('not available');">
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
          <div class="heading" >
            <h3>Choose Pockets</h3>
            <img src="assets/images/customizeyourkandora/chevron-down.svg" alt="" />
          </div>
          <div class="customize-option">
            <div class="images-option mt-0">
              <ul class="image-border no-pocket">
                <li onclick="addObj('pocket','pocket.glb','pocket2_1');">
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/No-Pocket.png" alt="" />
                  </div>
                  <h4 class="pockets">No Pocket</h4>
                </li>
                <li onclick="addObj('pocket','pocket.glb','pocket1_1, pocket1_2');">
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/Pocket-rounded.png" alt="" />
                  </div>
                  <h4 class="pockets">Style 1</h4>
                </li>
                <li onclick="addObj('pocket','pocket.glb','pocket2001, pocket2001_1');">
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/Pocket-angled.png" alt="" />
                  </div>
                  <h4 class="pockets">Style 2</h4>
                </li>
                <li onclick="addObj('pocket','pocket.glb','pocket3_1, pocket3_2');">
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/Pocket-v-shaped.png" alt="" />
                  </div>
                  <h4 class="pockets">Style 3</h4>
                </li>
                <li onclick="addObj('pocket','pocket.glb','pocket4_1, pocket4_2');">
                  <div class="img">
                    <img src="assets/images/customizeyourkandora/Pocket-rounded.png" alt="" />
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
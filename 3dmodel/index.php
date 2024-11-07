<!DOCTYPE html>
<html lang="en">
	<head>
		<title>three.js webgl - GLTFloader + variants</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<link type="text/css" rel="stylesheet" href="main.css">
	</head>

	<body>
		
		<div class="customcontrols">
			<h3>Controls</h3>
			<label for="metallicSlider">Metallic Intensity</label>
			<input type="range" id="metallicSlider" min="0" max="1" step="0.01" value="0.5">

			<label for="hdrBlurSlider">Object Opacity</label>
			<input type="range" id="objectOpacitySlider"  min="0" max="1" step="0.01" value="1">

			<label for="hdrBlurSlider">Background Opacity</label>
			<input type="range" id="BackgroundOpacitySlider"  min="0" max="1" step="0.01" value="1">

			<label for="colorPicker">Model Color</label>
			<input type="color" id="colorPicker" value="#ffffff">

			<label for="hideBackgroundCheckbox">Hide Background</label>
			<input type="checkbox" id="hideBackgroundCheckbox">

			<label for="textureUpload">Change Embroidery Texture</label>
			<input type="checkbox" id="textureUpload" value="models/tarboosh06.png">


			<label for="scaleSlider">Scale Tileable Texture</label>
			<input type="range" id="scaleSlider" min="1" max="50" step="1" value="1">
		
		</div>
		<div class="panel-3d">

		</div>
		<script type="importmap">
			{
				"imports": {
					"three": "../build/three.module.js",
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

				const container = document.createElement( 'div' );
				document.querySelector('.panel-3d').appendChild( container );

				camera = new THREE.PerspectiveCamera( 45, window.innerWidth / window.innerHeight, 0.25, 20 );
				camera.position.set(1, 5, 8); // Center the camera horizontally and set it at a height of 1.5 units
				// camera.lookAt(0, 1.5, 0); // Ensure the camera is looking at the vertical center of the model
				

				scene = new THREE.Scene();

				new RGBELoader()
    .setPath('models/')
    .load('studio_small_08_1k.hdr', function (texture) {

        texture.mapping = THREE.EquirectangularReflectionMapping;

        scene.background = texture;
        scene.environment = texture;

		// scene.background = new THREE.Color(0xffffff); // Set background to white
		// scene.environment = null; // Remove the environment texture
		// //tone mapping linear
		// renderer.toneMapping = THREE.LinearToneMapping;
		// renderer.toneMappingExposure = 1;
		// //ambient intensity 0
		// const ambientLight = new THREE.AmbientLight(0xffffff, 1); // Add ambient light
		// scene.add(ambientLight);
        // scene.add(new THREE.AmbientLight(0xffffff, 0));
        //direct intensity 0
        // const light = new THREE.DirectionalLight(0xffffff, 0);

        // model
        const loader = new GLTFLoader().setPath('models/');
        loader.load('test (1).glb', function (glb) {

            glb.scene.scale.set(2, 2, 2); // Increase the scale to zoom in
            glb.scene.position.set(0, -1.5, 0);

            scene.add(glb.scene);

            // Step 1: Load the texture
			const textureLoader = new THREE.TextureLoader();
			const texture = textureLoader.load('models/uv1.jpeg', function (texture) {
				// Step 2: Set the repeat property
				texture.repeat.set(8, 8); // Adjust the numbers to tile the texture

				// Step 3: Set the wrapS and wrapT properties
				texture.wrapS = THREE.RepeatWrapping;
				texture.wrapT = THREE.RepeatWrapping;

				// Apply the texture to the existing model
				scene.traverse((child) => {
					if (child.isMesh) {
						child.material.map = texture;
						child.material.color.set(0xffffff); // Ensure the color is white to not affect the texture
						child.material.needsUpdate = true;
						render();
					}
				});
			});

			// Convert the texture to black and white
			textureLoader.load('models/uv1.jpeg', function (texture) {
				const canvas = document.createElement('canvas');
				const context = canvas.getContext('2d');
				canvas.width = texture.image.width;
				canvas.height = texture.image.height;
				context.drawImage(texture.image, 0, 0);
				const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
				const data = imageData.data;

				for (let i = 0; i < data.length; i += 4) {
					const brightness = 0.34 * data[i] + 0.5 * data[i + 1] + 0.16 * data[i + 2];
					data[i] = brightness;
					data[i + 1] = brightness;
					data[i + 2] = brightness;
				}

				context.putImageData(imageData, 0, 0);
				const newTexture = new THREE.CanvasTexture(canvas);
				newTexture.repeat.set(8, 8);
				newTexture.wrapS = THREE.RepeatWrapping;
				newTexture.wrapT = THREE.RepeatWrapping;

				// Apply the black and white texture to the model
				scene.traverse((child) => {
					if (child.isMesh) {
						child.material.map = newTexture;
						child.material.color.set(0xffffff); // Ensure the color is white to not affect the texture
						child.material.needsUpdate = true;
						render();
					}
				});
			});

            

            // GUI
            gui = new GUI();

            // Details of the KHR_materials_variants extension used here can be found below
            // https://github.com/KhronosGroup/glTF/tree/master/extensions/2.0/Khronos/KHR_materials_variants
            const parser = glb.parser;

            // On load of model console all uv map name
            glb.scene.traverse((child) => {
                if (child.isMesh) {
                    console.log("uv name", child.geometry.attributes.uv.name);
                }
            });

        });

        render();

    });

					


				document.getElementById('metallicSlider').addEventListener('input', function(event) {
						const metallicValue = event.target.value;
						updateMetallicProperty(metallicValue);
					});

				//object opacity event listener
				document.getElementById('objectOpacitySlider').addEventListener('input', function(event) {
						const objectValue = event.target.value;
						updateObjectOpacity(objectValue);
					});

					//BackgroundOpacitySlider event
					document.getElementById('BackgroundOpacitySlider').addEventListener('input', function(event) {
						const backgroundValue = event.target.value;
						updateBackgroundOpacity(backgroundValue);
					});

					//color picker event
					document.getElementById('colorPicker').addEventListener('input', function(event) {
						const colorValue = event.target.value;
						updateColorProperty(colorValue);
					});

					//hide background event
					document.getElementById('hideBackgroundCheckbox').addEventListener('change', function(event) {
						scene.background = event.target.checked ? null : scene.environment;
						render();
					});

					//scaleSlider event
					document.getElementById('scaleSlider').addEventListener('input', function(event) {
						const scaleValue = event.target.value;
						updateScaleProperty(scaleValue);
					});


					//updateScaleProperty(scaleValue);
					function updateScaleProperty(value) {
						console.log("scale change");
						//change object scale
						scene.traverse((child) => {
							if (child.isMesh) {
								child.material.map.repeat.set(value, value);
								child.material.map.needsUpdate = true;
								//update scene
								render();
							}
						});
					}


					document.getElementById('textureUpload').addEventListener('change', function(event) {
					//get checkbox value
					if (event.target.checked) {
						const texturePath = event.target.value;
						const loader = new THREE.TextureLoader();
						loader.load(texturePath, function(texture) {
							updateEmbroideryTexture(texture);
						});
					}
				});
				

				function updateEmbroideryTexture(texture) {
					texture.wrapS = THREE.RepeatWrapping;
					texture.wrapT = THREE.RepeatWrapping;
					texture.needsUpdate = true; // Ensure the texture is updated

					scene.traverse((child) => {
						if (child.isMesh && child.name === 'Embriodery_plane') { // Ensure you have a way to identify the embroidery mesh
							
							child.material.map = texture;
							
							child.material.needsUpdate = true;
							//update the texture on mesh
							// render();
						}
					});
					renderer.render(scene, camera);
					
				}

					function updateMetallicProperty(value) {
						console.log("intensity change");
						scene.traverse((child) => {
							if (child.isMesh) {
								child.material.metalness = value;
								//update scene
								render();
							}
						});
					}


				// Function to update opacity property
				function updateObjectOpacity(value) {
					console.log("Opacity change");
					//change object opacity
					scene.traverse((child) => {
						if (child.isMesh && child.material.opacity !== undefined) {
							child.material.opacity = value;
							child.material.transparent = true; // Ensure transparency is enabled
							//update scene
							render();
						}
					});
				}

				// Function to update background opacity
				function updateBackgroundOpacity(value) {
					console.log("Background change");
					//change object opacity
					if (scene.background) {
						scene.background = scene.background.clone();
						scene.background.opacity = value;
						scene.background.transparent = true; // Ensure transparency is enabled
						//update scene
						render();
					}
				}

				// Function to update color property
				function updateColorProperty(value) {
					console.log("Color change");
					//change object color
					scene.traverse((child) => {
						if (child.isMesh) {
							child.material.color.set(value);
							//emisive color set
							// child.material.emissive.set(value);
							//update texture change
							child.material.needsUpdate = true;
							//update scene
							render();
						}
					});
					
					// Ensure the texture update is visible when the model is moved
					
				}
					
				renderer = new THREE.WebGLRenderer( { antialias: true } );
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				renderer.toneMapping = THREE.ACESFilmicToneMapping;
				renderer.toneMappingExposure = 1;
				container.appendChild( renderer.domElement );

				const controls = new OrbitControls(camera, renderer.domElement);
				controls.addEventListener('change', render); // use if there is no animation loop
				controls.minDistance = 2;
				controls.maxDistance = 10;
				controls.target.set(0, 0.5, -0.2);

				// // Restrict Y-axis rotation
				// controls.maxPolarAngle = Math.PI / 2; // 90 degrees
				// controls.minPolarAngle = Math.PI / 2; // 90 degrees

				controls.update();

				window.addEventListener( 'resize', onWindowResize );

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

				camera.aspect = window.innerWidth / window.innerHeight;
				camera.updateProjectionMatrix();

				renderer.setSize( window.innerWidth, window.innerHeight );

				render();

			}

			//

			function render() {

				renderer.render( scene, camera );

			}

		</script>
			

	

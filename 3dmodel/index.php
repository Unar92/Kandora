<!DOCTYPE html>
<html lang="en">
	<head>
		<title>three.js webgl - GLTFloader + variants</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
		<link type="text/css" rel="stylesheet" href="main.css">
	</head>

	<body>
		<div id="info">
			<a href="https://threejs.org" target="_blank" rel="noopener">three.js</a> - GLTFLoader + <a href="https://github.com/KhronosGroup/glTF/tree/master/extensions/2.0/Khronos/KHR_materials_variants" target="_blank" rel="noopener">KHR_materials_variants</a><br />
			<a href="https://github.com/pushmatrix/glTF-Sample-Models/tree/master/2.0/MaterialsVariantsShoe" target="_blank" rel="noopener">Materials Variants Shoe</a> by
			<a href="https://github.com/Shopify" target="_blank" rel="noopener">Shopify, Inc</a><br />
			<a href="https://hdrihaven.com/hdri/?h=quarry_01" target="_blank" rel="noopener">Quarry</a> from <a href="https://hdrihaven.com/" target="_blank" rel="noopener">HDRI Haven</a>
		</div>
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
				camera.position.set( 2.5, 1.5, 3.0 );

				scene = new THREE.Scene();

				new RGBELoader()
					.setPath( 'models/' )
					.load( 'studio_small_08_1k.hdr', function ( texture ) {

						texture.mapping = THREE.EquirectangularReflectionMapping;

						scene.background = texture;
						scene.environment = texture;

						render();

						// model

						const loader = new GLTFLoader().setPath( 'models/' );
						loader.load( 'cuff2.glb', function ( glb ) {

							glb.scene.scale.set( 2, 2, 2 ); // Increase the scale to zoom in
							glb.scene.position.set( 0, -0.5, 0 );
							camera.position.set( 0, 2.5, .5 ); // Move the camera closer to zoom in
							controls.target.set( 0, 0, 0 );
							controls.update();

							scene.add( glb.scene );

							// GUI
							gui = new GUI();

							// Details of the KHR_materials_variants extension used here can be found below
							// https://github.com/KhronosGroup/glTF/tree/master/extensions/2.0/Khronos/KHR_materials_variants
							const parser = glb.parser;
							

           				 render();

						} );

					} );


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
							//update scene
							render();
						}
					});
				}
					
				renderer = new THREE.WebGLRenderer( { antialias: true } );
				renderer.setPixelRatio( window.devicePixelRatio );
				renderer.setSize( window.innerWidth, window.innerHeight );
				renderer.toneMapping = THREE.ACESFilmicToneMapping;
				renderer.toneMappingExposure = 1;
				container.appendChild( renderer.domElement );

				const controls = new OrbitControls( camera, renderer.domElement );
				controls.addEventListener( 'change', render ); // use if there is no animation loop
				controls.minDistance = 2;
				controls.maxDistance = 10;
				controls.target.set( 0, 0.5, - 0.2 );
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
			

	

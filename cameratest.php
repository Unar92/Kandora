<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Three.js Camera Focus with GSAP</title>
    <style>
        body { margin: 0; }
        canvas { display: block; }
        #ui { position: absolute; top: 10px; left: 10px; z-index: 1; }
    </style>
</head>
<body>
    <div id="ui">
        <button onclick="focusObject(box)">Focus Box</button>
        <button onclick="focusObject(sphere)">Focus Sphere</button>
        <button onclick="focusObject(cone)">Focus Cone</button>
    </div>

    <!-- Import Three.js and OrbitControls -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r134/three.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/three@0.134.0/examples/js/controls/OrbitControls.js"></script>
    
    <!-- Import GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.0/gsap.min.js"></script>
    
    <script>
        // Scene setup
        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        const renderer = new THREE.WebGLRenderer();
        renderer.setSize(window.innerWidth, window.innerHeight);
        document.body.appendChild(renderer.domElement);

        // Camera controls
        const controls = new THREE.OrbitControls(camera, renderer.domElement);
        camera.position.set(0, 3, 10);
        controls.update();

        // Create objects
        const boxGeometry = new THREE.BoxGeometry();
        const boxMaterial = new THREE.MeshBasicMaterial({ color: 0xff0000 });
        const box = new THREE.Mesh(boxGeometry, boxMaterial);
        box.position.x = -3;
        scene.add(box);

        const sphereGeometry = new THREE.SphereGeometry(1, 32, 32);
        const sphereMaterial = new THREE.MeshBasicMaterial({ color: 0x00ff00 });
        const sphere = new THREE.Mesh(sphereGeometry, sphereMaterial);
        scene.add(sphere);

        const coneGeometry = new THREE.ConeGeometry(1, 2, 32);
        const coneMaterial = new THREE.MeshBasicMaterial({ color: 0x0000ff });
        const cone = new THREE.Mesh(coneGeometry, coneMaterial);  // Correct variable name
        cone.position.x = 3;
        scene.add(cone);

        // Lighting (optional for basic color differentiation)
        const light = new THREE.AmbientLight(0xffffff, 0.5);
        scene.add(light);
        const directionalLight = new THREE.DirectionalLight(0xffffff, 0.5);
        directionalLight.position.set(1, 2, 1).normalize();
        scene.add(directionalLight);

        // Animation loop
        function animate() {
            requestAnimationFrame(animate);
            controls.update();
            renderer.render(scene, camera);
        }
        animate();

        function focusObject(object) {
    const target = new THREE.Vector3();
    object.getWorldPosition(target);

    console.log("Initial Camera Position:", camera.position);
    console.log("Target Position:", target);

    // Temporarily remove limits
    const originalMaxDistance = controls.maxDistance;
    const originalMinDistance = controls.minDistance;
    controls.maxDistance = Infinity;
    controls.minDistance = 0;

    gsap.to(camera.position, {
        duration: 1,
        x: target.x + 5,
        y: target.y + 5,
        z: target.z + 5,
        onUpdate: function () {
            camera.lookAt(target);
            console.log("Animating Position:", camera.position);
        },
        onComplete: function () {
            // Check where it ends
            console.log("Final Camera Position:", camera.position);
            // Restore limits
           // Limit orbit controls
        //    controls.minDistance = 5;
        //     controls.maxDistance = 10;
        //     controls.minPolarAngle = 0;
        //     controls.maxPolarAngle = 0;
        //     controls.minAzimuthAngle = Math.PI / 2;
        //     controls.maxAzimuthAngle = Math.PI / 2;
            // Ensure controls are updated
            controls.update();
        }
    });

    gsap.to(controls.target, {
        duration: 1,
        x: target.x,
        y: target.y,
        z: target.z,
        onUpdate: function () {
            controls.update();
        },
        onComplete: function () {
            console.log("Final Target Position:", controls.target);
        }
    });
}

        // Handle window resize
        window.addEventListener('resize', onWindowResize, false);

        function onWindowResize() {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        }
    </script>
</body>
</html>
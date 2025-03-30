<?php
// certificate.php
session_start();
include 'db.php'; // Your database connection file

// Check if user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || !isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Get user data from database
$username = $_SESSION['username'];
$stmt = $conn->prepare("SELECT username FROM employee WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!$user) {
    die("User not found");
}

// Set current date
$currentDate = date("jS F Y");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Training</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
    <style>
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #f5f5f5;
            font-family: 'Times New Roman', serif;
        }

        .certificate-container {
            width: 900px;
            height: 650px;
            background-color: #fff9e6;
            border: 20px solid #8b4513;
            padding: 40px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.2);
            position: relative;
            text-align: center;
        }

        .certificate-border {
            border: 2px solid #d4af37;
            height: 100%;
            padding: 30px;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .university-name {
            font-size: 36px;
            font-weight: bold;
            color: #8b4513;
            margin-bottom: 30px;
            letter-spacing: 2px;
        }

        .certificate-title {
            font-size: 28px;
            font-weight: bold;
            color: #333;
            margin-bottom: 40px;
            text-decoration: underline;
        }

        .recipient-name {
            font-size: 32px;
            font-weight: bold;
            color: #8b4513;
            margin: 30px 0;
            padding: 10px 0;
            border-top: 2px solid #d4af37;
            border-bottom: 2px solid #d4af37;
            display: inline-block;
        }

        .certificate-text {
            font-size: 18px;
            line-height: 1.6;
            margin: 15px 0;
        }

        .course-name {
            font-weight: bold;
            font-style: italic;
        }

        .date-presented {
            margin-top: 40px;
            font-size: 18px;
        }

        .signatures {
            display: flex;
            justify-content: space-around;
            margin-top: 60px;
        }

        .signature {
            text-align: center;
        }

        .signature-name {
            font-weight: bold;
            margin-top: 60px;
            border-top: 1px solid #000;
            display: inline-block;
            padding-top: 5px;
        }

        .signature-title {
            font-size: 16px;
            margin-top: 5px;
        }

        .watermark {
            position: absolute;
            opacity: 0.1;
            font-size: 120px;
            font-weight: bold;
            color: #8b4513;
            transform: rotate(-45deg);
            top: 30%;
            left: 15%;
            z-index: 1;
        }

        #threejs-canvas {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 0;
            opacity: 0.1;
        }

        .certificate-content {
            position: relative;
            z-index: 2;
        }

        .download-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #8b4513;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
    </style>
</head>

<body>
    <canvas id="threejs-canvas"></canvas>

    <div class="certificate-container">
        <div class="certificate-border">
            <div class="certificate-content">
                <div class="university-name">PROGRAMMATOR</div>
                <div class="certificate-title">CERTIFICATE OF BASIC PROGRAMMING TRAINING</div>

                <div class="certificate-text">This certificate is presented to</div>

                <div class="recipient-name"><?php echo htmlspecialchars($user['username']); ?></div>

                <div class="certificate-text">
                    For successfully completing <span class="course-name">'Bsic Programming concepts'</span><br>
                    as part of the Advanced Computer Studies Electives. Presented
                </div>

                <div class="date-presented">
                    this <?php echo $currentDate; ?>
                </div>

                <div class="signatures">
                    <div class="signature">
                        <div class="signature-name">Ujjwal Kumar</div>
                        <div class="signature-title">Director</div>
                    </div>

                    <div class="signature">
                        <div class="signature-name">Ankush</div>
                        <div class="signature-title">Certified Trainer</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <button class="download-btn" onclick="downloadCertificate()">Download Certificate</button>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        // Three.js background animation
        const canvas = document.getElementById('threejs-canvas');
        const renderer = new THREE.WebGLRenderer({ canvas, alpha: true });
        renderer.setSize(window.innerWidth, window.innerHeight);

        const scene = new THREE.Scene();
        const camera = new THREE.PerspectiveCamera(75, window.innerWidth / window.innerHeight, 0.1, 1000);
        camera.position.z = 5;

        // Add floating elements
        const geometry = new THREE.TorusGeometry(1, 0.4, 16, 100);
        const material = new THREE.MeshBasicMaterial({
            color: 0x8b4513,
            transparent: true,
            opacity: 0.1
        });

        const torus = new THREE.Mesh(geometry, material);
        scene.add(torus);

        function animate() {
            requestAnimationFrame(animate);
            torus.rotation.x += 0.005;
            torus.rotation.y += 0.01;
            renderer.render(scene, camera);
        }
        animate();

        // Handle window resize
        window.addEventListener('resize', () => {
            camera.aspect = window.innerWidth / window.innerHeight;
            camera.updateProjectionMatrix();
            renderer.setSize(window.innerWidth, window.innerHeight);
        });

        // Download certificate as image
        function downloadCertificate() {
            html2canvas(document.querySelector('.certificate-container')).then(canvas => {
                const link = document.createElement('a');
                link.download = 'OHIO-UNIVERSITY-CERTIFICATE-<?php echo $user['username']; ?>.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }
    </script>
</body>

</html>
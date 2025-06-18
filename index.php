<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>مشغل دروس Abwaab</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/hls.js@latest"></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Cairo', sans-serif;
            background: radial-gradient(circle at top left, #1f1c2c, #928dab);
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: linear-gradient(160deg, #000000ee, #1c1c1cdd);
            border: 1px solid #ffd70044;
            box-shadow: 0 0 30px #ffd70066;
            border-radius: 20px;
            padding: 40px 20px;
            max-width: 900px;
            width: 100%;
        }
        h1 {
            color: #ffd700;
            font-size: 2.5rem;
            text-align: center;
            margin-bottom: 25px;
            text-shadow: 0 0 10px #ffd700aa;
        }
        input {
            background: #222;
            color: #ffd700;
            font-size: 1.1rem;
            border: 2px solid #ffd700;
            border-radius: 10px;
            padding: 12px;
            width: 100%;
            margin-bottom: 20px;
        }
        button {
            background: linear-gradient(45deg, #ffcc00, #ff8800);
            color: #000;
            font-size: 1.2rem;
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
            display: block;
            margin: 0 auto 20px;
        }
        button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 10px #ffa500;
        }
        .video-wrapper {
            position: relative;
            width: 100%;
            padding-top: 56.25%;
            background: #000;
            border-radius: 16px;
            overflow: hidden;
            margin-top: 20px;
        }
        video {
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>مشغل دروس Abwaab</h1>
        <input type="text" id="videoKey" placeholder="أدخل كود الفيديو">
        <button onclick="startPlayer()">تشغيل الفيديو</button>
        <div class="video-wrapper" id="videoContainer" style="display:none">
            <video id="video" controls></video>
        </div>
        <div id="status">يرجى إدخال كود الفيديو</div>
    </div>

    <script>
        let hlsInstance = null;
        
        function startPlayer() {
            const videoId = document.getElementById('videoKey').value.trim();
            if (!videoId) {
                document.getElementById('status').textContent = 'الرجاء إدخال كود الفيديو';
                return;
            }
            
            document.getElementById('videoContainer').style.display = 'block';
            loadVideo(videoId);
        }
        
        function loadVideo(videoId) {
            const video = document.getElementById('video');
            const status = document.getElementById('status');
            
            if (hlsInstance) {
                hlsInstance.destroy();
            }
            
            const videoUrl = `proxy.php?videoId=${encodeURIComponent(videoId)}`;
            
            if (Hls.isSupported()) {
                hlsInstance = new Hls();
                hlsInstance.loadSource(videoUrl);
                hlsInstance.attachMedia(video);
                hlsInstance.on(Hls.Events.MANIFEST_PARSED, function() {
                    video.play();
                    status.textContent = 'جاري تشغيل الفيديو...';
                });
            } else if (video.canPlayType('application/vnd.apple.mpegurl')) {
                video.src = videoUrl;
                video.addEventListener('loadedmetadata', function() {
                    video.play();
                });
                status.textContent = 'جاري تشغيل الفيديو (وضع التوافق)...';
            } else {
                status.textContent = 'المتصفح لا يدعم تشغيل هذا النوع من الفيديوهات';
            }
        }
    </script>
</body>
</html>
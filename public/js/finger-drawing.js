class FingerDrawing {
    constructor(videoElement, canvasElement) {
        this.video = videoElement;
        this.canvas = canvasElement;
        this.ctx = canvas.getContext('2d');
        
        // Drawing state
        this.isDrawing = false;
        this.isErasing = false;
        this.lastPoint = null;
        this.drawingCanvas = null;
        this.drawingCtx = null;
        
        // Eraser settings
        this.eraserRadius = 30;
        
        // Colors
        this.colors = [
            '#FF0000', // Red
            '#00FF00', // Green
            '#0000FF', // Blue
            '#FFFF00', // Yellow
            '#FF00FF', // Magenta
            '#00FFFF', // Cyan
        ];
        this.currentColorIndex = 0;
        this.currentColor = this.colors[0];
        
        // Hand detection
        this.hands = null;
        this.camera = null;
        
        this.initializeHandDetection();
        this.setupDrawingCanvas();
    }
    
    async initializeHandDetection() {
        try {
            // Load MediaPipe Hands
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/@mediapipe/hands@0.4/hands.js';
            document.head.appendChild(script);
            
            const cameraScript = document.createElement('script');
            cameraScript.src = 'https://cdn.jsdelivr.net/npm/@mediapipe/camera_utils@0.3/camera_utils.js';
            document.head.appendChild(cameraScript);
            
            // Wait for scripts to load
            await new Promise(resolve => {
                if (window.Hands && window.Camera) {
                    resolve();
                } else {
                    const checkInterval = setInterval(() => {
                        if (window.Hands && window.Camera) {
                            clearInterval(checkInterval);
                            resolve();
                        }
                    }, 100);
                }
            });
            
            // Initialize hands
            this.hands = new window.Hands({
                locateFile: (file) => {
                    return `https://cdn.jsdelivr.net/npm/@mediapipe/hands@0.4/${file}`;
                }
            });
            
            this.hands.setOptions({
                maxNumHands: 1,
                modelComplexity: 1,
                minDetectionConfidence: 0.5,
                minTrackingConfidence: 0.5
            });
            
            this.hands.onResults((results) => this.onHandResults(results));
            
        } catch (error) {
            console.error('Error initializing hand detection:', error);
        }
    }
    
    setupDrawingCanvas() {
        // Create drawing canvas overlay
        this.drawingCanvas = document.createElement('canvas');
        this.drawingCanvas.id = 'drawingCanvas';
        this.drawingCanvas.style.position = 'absolute';
        this.drawingCanvas.style.top = '0';
        this.drawingCanvas.style.left = '0';
        this.drawingCanvas.style.pointerEvents = 'none';
        this.drawingCanvas.style.zIndex = '5';
        
        // Set same size as video canvas
        this.drawingCanvas.width = this.canvas.width;
        this.drawingCanvas.height = this.canvas.height;
        
        this.drawingCtx = this.drawingCanvas.getContext('2d');
        
        // Add to canvas container
        this.canvas.parentElement.appendChild(this.drawingCanvas);
    }
    
    async startCamera() {
        try {
            this.camera = new window.Camera(this.video, {
                onFrame: async () => {
                    if (this.hands) {
                        await this.hands.send({image: this.video});
                    }
                },
                width: 640,
                height: 480
            });
            
            await this.camera.start();
            console.log('Camera started for finger detection');
            
        } catch (error) {
            console.error('Error starting camera:', error);
        }
    }
    
    stopCamera() {
        if (this.camera) {
            this.camera.stop();
            this.camera = null;
        }
    }
    
    isIndexFingerUp(landmarks) {
        // Index finger tip (landmark 8) and MCP joint (landmark 5)
        const indexTip = landmarks[8];
        const indexMcp = landmarks[5];
        
        // Check if index finger tip is above MCP joint
        return indexTip.y < indexMcp.y;
    }
    
    areOtherFingersDown(landmarks) {
        // Check middle, ring, and pinky fingers
        const fingersToCheck = [
            [12, 9],  // Middle finger
            [16, 13], // Ring finger
            [20, 17], // Pinky finger
        ];
        
        for (const [tip, mcp] of fingersToCheck) {
            if (landmarks[tip].y < landmarks[mcp].y) {
                return false;
            }
        }
        return true;
    }
    
    areAllFingersUp(landmarks) {
        // Check if all fingers are up (extended) - open hand gesture
        // For a more reliable detection, we check if fingertips are above their respective PIP joints
        
        // Thumb: tip (4) above MCP (2)
        const thumbUp = landmarks[4].x < landmarks[2].x;
        
        // Index finger: tip (8) above PIP (6)
        const indexUp = landmarks[8].y < landmarks[6].y;
        
        // Middle finger: tip (12) above PIP (10)
        const middleUp = landmarks[12].y < landmarks[10].y;
        
        // Ring finger: tip (16) above PIP (14)
        const ringUp = landmarks[16].y < landmarks[14].y;
        
        // Pinky finger: tip (20) above PIP (18)
        const pinkyUp = landmarks[20].y < landmarks[18].y;
        
        // All fingers must be up for open hand gesture
        return thumbUp && indexUp && middleUp && ringUp && pinkyUp;
    }
    
    getFingerPosition(landmarks) {
        const indexTip = landmarks[8];
        const rect = this.video.getBoundingClientRect();
        
        return {
            x: (1 - indexTip.x) * this.drawingCanvas.width, // Mirror X
            y: indexTip.y * this.drawingCanvas.height
        };
    }
    
    eraseAtPosition(position) {
        // Save the current composite operation
        const originalComposite = this.drawingCtx.globalCompositeOperation;
        
        // Set to destination-out to erase
        this.drawingCtx.globalCompositeOperation = 'destination-out';
        
        // Create a larger eraser for better coverage
        this.drawingCtx.beginPath();
        this.drawingCtx.arc(position.x, position.y, this.eraserRadius, 0, 2 * Math.PI);
        this.drawingCtx.fill();
        
        // Add some smoothing by creating a slightly larger, more transparent eraser
        this.drawingCtx.globalAlpha = 0.5;
        this.drawingCtx.beginPath();
        this.drawingCtx.arc(position.x, position.y, this.eraserRadius + 10, 0, 2 * Math.PI);
        this.drawingCtx.fill();
        
        // Restore the original composite operation and alpha
        this.drawingCtx.globalCompositeOperation = originalComposite;
        this.drawingCtx.globalAlpha = 1.0;
    }
    
    onHandResults(results) {
        // Clear the main canvas for video display
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        if (results.multiHandLandmarks && results.multiHandLandmarks.length > 0) {
            const landmarks = results.multiHandLandmarks[0];
            
            // Draw hand landmarks on main canvas
            this.drawHandLandmarks(landmarks);
            
            const fingerPos = this.getFingerPosition(landmarks);
            
            // Debug: Check hand states
            const isIndexUp = this.isIndexFingerUp(landmarks);
            const areOthersDown = this.areOtherFingersDown(landmarks);
            const areAllUp = this.areAllFingersUp(landmarks);
            
            // Debug logging (comment out in production)
            console.log('Hand state:', {
                indexUp: isIndexUp,
                othersDown: areOthersDown,
                allUp: areAllUp
            });
            
            // Check for 5-finger gesture (all fingers up) for erasing
            if (areAllUp) {
                this.isErasing = true;
                this.isDrawing = false;
                this.lastPoint = null;
                
                // Erase at current position
                this.eraseAtPosition(fingerPos);
                
                // Draw eraser indicator
                this.ctx.strokeStyle = '#FF0000';
                this.ctx.lineWidth = 3;
                this.ctx.beginPath();
                this.ctx.arc(fingerPos.x, fingerPos.y, this.eraserRadius, 0, 2 * Math.PI);
                this.ctx.stroke();
                
                // Show "Erasing" indicator
                this.showErasingIndicator();
                
            } 
            // Check for pointing gesture (index finger up, others down) for drawing
            else if (isIndexUp && areOthersDown) {
                this.isDrawing = true;
                this.isErasing = false;
                
                if (!this.lastPoint) {
                    this.lastPoint = fingerPos;
                } else {
                    // Draw line from last point to current point
                    this.drawingCtx.strokeStyle = this.currentColor;
                    this.drawingCtx.lineWidth = 5;
                    this.drawingCtx.lineCap = 'round';
                    this.drawingCtx.beginPath();
                    this.drawingCtx.moveTo(this.lastPoint.x, this.lastPoint.y);
                    this.drawingCtx.lineTo(fingerPos.x, fingerPos.y);
                    this.drawingCtx.stroke();
                    this.lastPoint = fingerPos;
                }
                
                // Draw indicator circle on main canvas
                this.ctx.fillStyle = this.currentColor;
                this.ctx.beginPath();
                this.ctx.arc(fingerPos.x, fingerPos.y, 10, 0, 2 * Math.PI);
                this.ctx.fill();
                
                // Show "Drawing" indicator
                this.showDrawingIndicator();
                
            } else {
                // Stop both drawing and erasing
                this.isDrawing = false;
                this.isErasing = false;
                this.lastPoint = null;
            }
        } else {
            this.isDrawing = false;
            this.isErasing = false;
            this.lastPoint = null;
        }
    }
    
    drawHandLandmarks(landmarks) {
        // Draw connections
        const connections = [
            [0, 1], [1, 2], [2, 3], [3, 4],  // Thumb
            [0, 5], [5, 6], [6, 7], [7, 8],  // Index finger
            [5, 9], [9, 10], [10, 11], [11, 12],  // Middle finger
            [9, 13], [13, 14], [14, 15], [15, 16],  // Ring finger
            [13, 17], [17, 18], [18, 19], [19, 20],  // Pinky
            [0, 17]  // Palm
        ];
        
        this.ctx.strokeStyle = '#00FF00';
        this.ctx.lineWidth = 2;
        
        for (const [start, end] of connections) {
            const startPoint = landmarks[start];
            const endPoint = landmarks[end];
            
            this.ctx.beginPath();
            this.ctx.moveTo(
                (1 - startPoint.x) * this.canvas.width,
                startPoint.y * this.canvas.height
            );
            this.ctx.lineTo(
                (1 - endPoint.x) * this.canvas.width,
                endPoint.y * this.canvas.height
            );
            this.ctx.stroke();
        }
        
        // Draw landmarks
        this.ctx.fillStyle = '#FF0000';
        for (const landmark of landmarks) {
            this.ctx.beginPath();
            this.ctx.arc(
                (1 - landmark.x) * this.canvas.width,
                landmark.y * this.canvas.height,
                5, 0, 2 * Math.PI
            );
            this.ctx.fill();
        }
    }
    
    showDrawingIndicator() {
        this.ctx.fillStyle = '#00FF00';
        this.ctx.font = '20px Arial';
        this.ctx.fillText('Drawing', 10, 30);
    }
    
    showErasingIndicator() {
        this.ctx.fillStyle = '#FF0000';
        this.ctx.font = '20px Arial';
        this.ctx.fillText('Erasing', 10, 30);
        
        // Show finger state indicators for debugging
        this.ctx.font = '12px Arial';
        this.ctx.fillText('✋ Open Hand Detected', 10, 50);
    }
    
    switchColor() {
        this.currentColorIndex = (this.currentColorIndex + 1) % this.colors.length;
        this.currentColor = this.colors[this.currentColorIndex];
        console.log('Color changed to:', this.currentColor);
    }
    
    clearCanvas() {
        this.drawingCtx.clearRect(0, 0, this.drawingCanvas.width, this.drawingCanvas.height);
        console.log('Canvas cleared');
    }
    
    getDrawingData() {
        return this.drawingCanvas.toDataURL();
    }
    
    setDrawingData(dataUrl) {
        const img = new Image();
        img.onload = () => {
            this.drawingCtx.clearRect(0, 0, this.drawingCanvas.width, this.drawingCanvas.height);
            this.drawingCtx.drawImage(img, 0, 0);
        };
        img.src = dataUrl;
    }
}

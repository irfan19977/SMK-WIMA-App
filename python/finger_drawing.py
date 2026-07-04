import cv2
import mediapipe as mp
import numpy as np

class FingerDrawing:
    def __init__(self):
        self.mp_hands = mp.solutions.hands
        self.hands = self.mp_hands.Hands(
            static_image_mode=False,
            max_num_hands=1,
            min_detection_confidence=0.5,
            min_tracking_confidence=0.5
        )
        self.mp_drawing = mp.solutions.drawing_utils
        
        # Drawing canvas
        self.canvas = None
        self.drawing = False
        self.prev_point = None
        
        # Colors for drawing
        self.colors = [
            (255, 0, 0),    # Red
            (0, 255, 0),    # Green
            (0, 0, 255),    # Blue
            (255, 255, 0),  # Yellow
            (255, 0, 255),  # Magenta
            (0, 255, 255),  # Cyan
        ]
        self.current_color_index = 0
        self.current_color = self.colors[0]
        
        # Initialize camera
        self.cap = cv2.VideoCapture(0)
        
    def is_index_finger_up(self, landmarks):
        """Check if index finger is up (extended)"""
        # Index finger tip (landmark 8) and MCP joint (landmark 5)
        index_tip = landmarks[8]
        index_mcp = landmarks[5]
        
        # Check if index finger tip is above MCP joint (Y coordinate is smaller in OpenCV)
        return index_tip.y < index_mcp.y
    
    def is_other_fingers_down(self, landmarks):
        """Check if other fingers are down (not extended)"""
        # Check middle, ring, and pinky fingers
        fingers_to_check = [
            (12, 9),  # Middle finger
            (16, 13), # Ring finger
            (20, 17), # Pinky finger
        ]
        
        for tip, mcp in fingers_to_check:
            if landmarks[tip].y < landmarks[mcp].y:
                return False
        return True
    
    def get_index_finger_position(self, landmarks, frame_shape):
        """Get the position of index finger tip"""
        index_tip = landmarks[8]
        h, w = frame_shape[:2]
        x = int(index_tip.x * w)
        y = int(index_tip.y * h)
        return (x, y)
    
    def switch_color(self):
        """Switch to next color"""
        self.current_color_index = (self.current_color_index + 1) % len(self.colors)
        self.current_color = self.colors[self.current_color_index]
    
    def clear_canvas(self):
        """Clear the drawing canvas"""
        if self.canvas is not None:
            self.canvas = None
    
    def process_frame(self, frame):
        """Process frame and detect hand for drawing"""
        if self.canvas is None:
            self.canvas = np.zeros_like(frame)
        
        # Convert BGR to RGB
        rgb_frame = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)
        
        # Process hand detection
        results = self.hands.process(rgb_frame)
        
        if results.multi_hand_landmarks:
            for hand_landmarks in results.multi_hand_landmarks:
                # Draw hand landmarks
                self.mp_drawing.draw_landmarks(
                    frame, hand_landmarks, self.mp_hands.HAND_CONNECTIONS
                )
                
                landmarks = hand_landmarks.landmark
                
                # Check if index finger is up and others are down (pointing gesture)
                if self.is_index_finger_up(landmarks) and self.is_other_fingers_down(landmarks):
                    # Get index finger position
                    finger_pos = self.get_index_finger_position(landmarks, frame.shape)
                    
                    # Start drawing if not already drawing
                    if not self.drawing:
                        self.drawing = True
                        self.prev_point = finger_pos
                    else:
                        # Draw line from previous point to current point
                        if self.prev_point:
                            cv2.line(self.canvas, self.prev_point, finger_pos, 
                                   self.current_color, 5)
                        self.prev_point = finger_pos
                    
                    # Draw circle at finger position
                    cv2.circle(frame, finger_pos, 10, self.current_color, -1)
                    
                    # Show "Drawing" indicator
                    cv2.putText(frame, "Drawing", (10, 30), 
                              cv2.FONT_HERSHEY_SIMPLEX, 1, (0, 255, 0), 2)
                else:
                    # Stop drawing
                    self.drawing = False
                    self.prev_point = None
        
        # Combine frame with canvas
        combined = cv2.addWeighted(frame, 0.7, self.canvas, 0.3, 0)
        
        return combined
    
    def run(self):
        """Main loop for finger drawing"""
        while True:
            ret, frame = self.cap.read()
            if not ret:
                break
            
            # Flip frame horizontally for mirror effect
            frame = cv2.flip(frame, 1)
            
            # Process frame
            result_frame = self.process_frame(frame)
            
            # Display instructions
            instructions = [
                "Point with index finger to draw",
                "Press 'c' to change color",
                "Press 'x' to clear canvas",
                "Press 'q' to quit"
            ]
            
            for i, instruction in enumerate(instructions):
                cv2.putText(result_frame, instruction, (10, result_frame.shape[0] - 120 + i*30), 
                          cv2.FONT_HERSHEY_SIMPLEX, 0.6, (255, 255, 255), 2)
            
            # Show current color
            color_text = f"Color: {['Red', 'Green', 'Blue', 'Yellow', 'Magenta', 'Cyan'][self.current_color_index]}"
            cv2.putText(result_frame, color_text, (10, 70), 
                      cv2.FONT_HERSHEY_SIMPLEX, 0.7, self.current_color, 2)
            
            cv2.imshow('Finger Drawing', result_frame)
            
            # Handle key presses
            key = cv2.waitKey(1) & 0xFF
            if key == ord('q'):
                break
            elif key == ord('c'):
                self.switch_color()
            elif key == ord('x'):
                self.clear_canvas()
        
        self.cap.release()
        cv2.destroyAllWindows()

if __name__ == "__main__":
    app = FingerDrawing()
    app.run()

#!/usr/bin/env python3
import sys
import json
import cv2
import numpy as np
import face_recognition
import os

def extract_face_descriptor(image_path):
    """
    Extract face descriptor from image
    """
    try:
        # Load image
        image = face_recognition.load_image_file(image_path)
        
        # Find face locations
        face_locations = face_recognition.face_locations(image)
        
        if not face_locations:
            return {
                'success': False,
                'error': 'No face detected in the image'
            }
        
        if len(face_locations) > 1:
            return {
                'success': False,
                'error': 'Multiple faces detected. Please use image with single face'
            }
        
        # Get face encodings (descriptors)
        face_encodings = face_recognition.face_encodings(image, face_locations)
        
        if not face_encodings:
            return {
                'success': False,
                'error': 'Could not generate face encoding'
            }
        
        # Convert numpy array to list for JSON serialization
        face_descriptor = face_encodings[0].tolist()
        
        return {
            'success': True,
            'face_descriptor': face_descriptor,
            'face_location': face_locations[0]  # top, right, bottom, left
        }
        
    except Exception as e:
        return {
            'success': False,
            'error': str(e)
        }

def main():
    if len(sys.argv) != 2:
        print(json.dumps({
            'success': False,
            'error': 'Usage: python face_recognition.py <image_path>'
        }))
        sys.exit(1)
    
    image_path = sys.argv[1]
    
    # Check if file exists
    if not os.path.exists(image_path):
        print(json.dumps({
            'success': False,
            'error': 'Image file not found'
        }))
        sys.exit(1)
    
    # Extract face descriptor
    result = extract_face_descriptor(image_path)
    
    # Output result as JSON
    print(json.dumps(result))

if __name__ == "__main__":
    main()
# Changelog

## Release
  - Deployed on Heroku
  - Github Repository
  - Add Design Details
  - Setting up Luminosity to run locally

## V1.0.1
  ### June 7 2021
  
  **Bug Fixes**
  - SMTP mail verified
  - Show profile followers and following
  - Important: Fixed login by cookie
  - Prevent empty username and display name
  - Concise privacy policy
  - Minor UI Changes
  
  **To Fix**
  - Host static images for profile images
  
  **Features to add**
  - Show article stats
  - Public API
  - Logo Redesign

  ### July 28 2021
  **Fixes**
  - Profile Image hosting on Cloudinary. Store absolute URL of image in database
  - Increased ``post_max_size``
  - Meta tags modified

  **To Fix**
  - Refactor latest version
  - Test latest version on local setup

  **Features to add**
  - Preview article HTML not plain text
  - Article stats
  - Public API
  - Logo Redesign
  - UI cleanup

## V1.1
  **Fixes**
  - Image Uploading - Prevent API spam, Show loading
  - Local Version Setup and Tested
  - Email API error handling
  - URL Routing fixed
  - Show article results on search
  - Profile controller followers/following errors fixed

  **Changes**
  - Important: Serve unminified JS as default for easier development
  - Create ``ENVIRONMENT`` and ``BASE_FOLDER`` configuration
  - Add Installation Guide
  - Merge into single main branch
  - Highlight-js theme changed to Github-Dark
  - Update INSTALLATION and README

  **Features to add**
  - Same as V1.0.1 Jul28. 
  - No ETA
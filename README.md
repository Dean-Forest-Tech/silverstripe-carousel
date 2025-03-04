Silverstripe Carousel Module
============================

[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/i-lateral/silverstripe-carousel/badges/quality-score.png?b=2)](https://scrutinizer-ci.com/g/i-lateral/silverstripe-carousel/?branch=2)

[![GitHub license](https://img.shields.io/github/license/i-lateral/silverstripe-carousel.svg)](https://github.com/i-lateral/silverstripe-carousel/blob/2/LICENSE)

Module that adds the ability to generate Carousels/Sliders
to your SilverStripe website.

## Requirements

- SilverStripe Framework: 4.*
- SilverStripe CMS: 4.*

## Installation

Either via composer:

    composer require "dft/silverstripe-carousel"

Then run:

    ./vendor/bin/sake dev/build flush=1

or (from your browser):

    http://yoursiteurl.com/dev/build/


## Usage

The following is a quick guide to getting using this module:

### Step 1: Add variable to template

Once installed, you have to add the following template variable to
any templates you require a carousel to appear on:

    $CarouselSlides

### Step 2: Enable carousel on your page

Next, log into the admin interface and edit the page you want to
add a carousel to.

Then click the "Settings" tab, and tick the "show carousel" checkbox.

### Step 4: Setup Carousel

Once this is done you will be able to set the following fields:

- Width and Height settings (this will determine the size of the rendered images)
- Show Controls (this will add next/prev buttons) **NOTE** if you want this to work correctly, you will need to disable hash rewrites in SilverStripe
- Show Indicators (show the blips at the bottom of aa page).
- Interval (how quickly will each slide be displayed).

### Step 5: Add some slides

Once you have finished configuring, you can go back to the "content"
tab and a "carousel" tab should have appeared in the right hand side
of the editing pane.

You can now add slides, attach images and edit their titles.

## Using a different carousel

By default this module uses Bootstrap 4 carousel from a CDN. If you
want to use your own carousel, you will need to copy the
`CarouselSlides` template into your theme and amend the HTML and
Requirements calls
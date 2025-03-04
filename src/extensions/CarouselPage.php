<?php

namespace DFT\SilverStripe\Carousel\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\ORM\DataExtension;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\GridField\GridField;
use Heyday\ResponsiveImages\ResponsiveImageExtension;
use DFT\SilverStripe\Carousel\Model\CarouselSlide;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;

class CarouselPage extends DataExtension
{
    private static $db = [
        'ShowCarousel'  => 'Boolean',
        "CarouselShowIndicators" => "Boolean",
        "CarouselShowControls" => "Boolean",
        "CarouselProfile" => "Varchar",
        "CarouselInterval" => "Int"
    ];

    private static $has_many = [
        'Slides' => CarouselSlide::class
    ];

    private static $defaults = [
        'CarouselProfile' => 'ShortCarousel',
        'CarouselInterval' => 3000,
    ];

    public function updateCMSFields(FieldList $fields)
    {
        if($this->owner->ShowCarousel) {
            $carousel_table = GridField::create(
                'Slides',
                false,
                $this->owner->Slides(),
                GridFieldConfig_RecordEditor::create()
                    ->addComponent(new GridFieldOrderableRows('Sort'))
            );

            $fields->addFieldToTab('Root.Carousel', $carousel_table);
        } else {
            $fields->removeByName('Slides');
        }

        $fields->removeByName('ShowCarousel');
        $fields->removeByName('CarouselProfile');

        parent::updateCMSFields($fields);
    }

    public function updateSettingsFields(FieldList $fields)
    {
        $message = '<p>Configure this page to use a carousel</p>';
        
        $fields->addFieldToTab(
            'Root.Settings',
            LiteralField::create("CarouselMessage", $message)
        );

        $carousel = FieldGroup::create(
            CheckboxField::create(
                'ShowCarousel',
                'Show a carousel on this page?'
            ),
            CheckboxField::create(
                'CarouselShowIndicators',
                $this->owner->fieldLabel('CarouselShowIndicators')
            ),
            CheckboxField::create(
                'CarouselShowControls',
                $this->owner->fieldLabel('CarouselShowControls')
            )
        )->setTitle('Carousel');

        $fields->addFieldToTab(
            'Root.Settings',
            $carousel
        );

        if($this->owner->ShowCarousel) {
            $array = [];
            foreach (array_keys(Config::inst()->get(ResponsiveImageExtension::class, 'sets')) as $key => $value) {
                $array[$value] = $value;
            }
            $fields->addFieldsToTab(
                'Root.Settings',
                [
                    DropdownField::create(
                        'CarouselProfile',
                        $this->owner->fieldLabel('CarouselProfile'),
                        $array
                    )->setEmptyString('Choose one'),
                    NumericField::create(
                        'CarouselInterval',
                        $this->owner->fieldLabel('CarouselInterval')
                    )
                ]
            );
        }
    }

    public function CarouselSlides() {
        return $this
            ->owner
            ->renderWith(
                'DFT\SilverStripe\Carousel\Includes\CarouselSlides',
                [
                    'Slides' => $this->owner->Slides(),
                    'Interval' => $this->owner->CarouselInterval ? $this->owner->CarouselInterval : 3000,
                    'ShowIndicators' => $this->owner->CarouselShowIndicators,
                    'ShowControls' => $this->owner->CarouselShowControls
                ]
            );
    }
}

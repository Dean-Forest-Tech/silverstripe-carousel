<?php

namespace DFT\SilverStripe\Carousel\Extensions;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\ORM\DataExtension;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Core\Config\Config;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\GridField\GridField;
use DFT\SilverStripe\Carousel\Model\CarouselSlide;
use Heyday\ResponsiveImages\ResponsiveImageExtension;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;

class CarouselPage extends DataExtension
{
    private static $extra_requirements = true;

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

    private static $casting = [
        'CarouselSlides' => 'HTMLText'
    ];

    private static $defaults = [
        'CarouselProfile' => 'ShortCarousel',
        'CarouselInterval' => 3000,
    ];

    public function shouldAddCarouselRequireExtras()
    {
        $require = Config::inst()
            ->get(static::class, 'extra_requirements');
        
        return (bool) $require;
    }

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

    public function CarouselSlides(): string
    {
        /** @var SiteTree */
        $owner = $this->getOwner();

        return $owner->renderWith(
            'DFT\SilverStripe\Carousel\Includes\CarouselSlides',
            [
                'Slides' => $owner->Slides(),
                'Interval' => $owner->CarouselInterval ? $owner->CarouselInterval : 3000,
                'ShowIndicators' => $owner->CarouselShowIndicators,
                'ShowControls' => $owner->CarouselShowControls
            ]
        );
    }
}

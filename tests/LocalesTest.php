<?php

use Lykegenes\LocaleSwitcher\Locales;

class LocalesTest extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
    }

    public function tearDown()
    {
    }

    /** @test */
    public function it_verifies_that_the_locale_exists()
    {
        $this->assertTrue(Locales::localeExists('fr'));
        $this->assertTrue(Locales::localeExists('fr-CA'));
        $this->assertTrue(Locales::localeExists('en'));
        $this->assertTrue(Locales::localeExists('en-CA'));
        $this->assertTrue(Locales::localeExists('en-US'));
        $this->assertTrue(Locales::localeExists('en-GB'));

        $this->assertFalse(Locales::localeExists('imaginarylocale'));
    }

    /** @test */
    public function it_gets_language_name_from_locale()
    {
        $this->assertEquals('French', Locales::getLanguageNameFromLocale('fr'));
        $this->assertEquals('French (Canada)', Locales::getLanguageNameFromLocale('fr-CA'));
        $this->assertEquals('English', Locales::getLanguageNameFromLocale('en'));
        $this->assertEquals('English (Canada)', Locales::getLanguageNameFromLocale('en-CA'));
        $this->assertEquals('English (United States)', Locales::getLanguageNameFromLocale('en-US'));
        $this->assertEquals('English (United Kingdom)', Locales::getLanguageNameFromLocale('en-GB'));
    }

    /** @test */
    public function it_identifies_primary_locales()
    {
        $this->assertTrue(Locales::localeIsPrimary('fr'));
        $this->assertTrue(Locales::localeIsPrimary('en'));

        $this->assertFalse(Locales::localeIsPrimary('fr-CA'));
        $this->assertFalse(Locales::localeIsPrimary('en-CA'));
        $this->assertFalse(Locales::localeIsPrimary('en-US'));
        $this->assertFalse(Locales::localeIsPrimary('en-GB'));
    }

    /** @test */
    public function it_identifies_regional_locales()
    {
        $this->assertFalse(Locales::localeIsRegional('fr'));
        $this->assertFalse(Locales::localeIsRegional('en'));

        $this->assertTrue(Locales::localeIsRegional('fr-CA'));
        $this->assertTrue(Locales::localeIsRegional('en-CA'));
        $this->assertTrue(Locales::localeIsRegional('en-US'));
        $this->assertTrue(Locales::localeIsRegional('en-GB'));
    }

    /** @test */
    public function it_converts_regional_locales_to_primary()
    {
        $this->assertEquals('fr', Locales::regionalToPrimaryLocale('fr'));
        $this->assertEquals('fr', Locales::regionalToPrimaryLocale('fr-CA'));
        $this->assertEquals('en', Locales::regionalToPrimaryLocale('en'));
        $this->assertEquals('en', Locales::regionalToPrimaryLocale('en-CA'));
        $this->assertEquals('en', Locales::regionalToPrimaryLocale('en-US'));
        $this->assertEquals('en', Locales::regionalToPrimaryLocale('en-GB'));
    }

}

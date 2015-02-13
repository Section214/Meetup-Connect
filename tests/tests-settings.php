<?php
/**
 * @group meetup_connect_settings
 */
class Test_Settings extends WP_UnitTestCase {
    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function test_get_settings_tabs() {
        $this->assertArrayHasKey( 'settings', meetup_connect_get_settings_tabs() );
    }

    public function test_get_registered_settings() {
        $settings = meetup_connect_get_registered_settings();

        $this->assertArrayHasKey( 'auth_type', $settings['settings'][0] );
    }
}

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
        $this->assertArrayHasKey( 'settings', meetup_connect_get_registered_settings() );
    }

    public function test_get_option() {
        global $meetup_connect_options;

        $meetup_connect_options['my_opt'] = 'option';
        update_option( 'meetup_connect_settings', $meetup_connect_options );

        $this->assertFalse( meetup_connect_get_option( 'fake_opt', false ) );
        $this->assertEquals( 'option', meetup_connect_get_option( 'my_opt', false ) );
    }
}

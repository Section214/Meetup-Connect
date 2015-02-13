<?php
/**
 * @group meetup_connect_misc
 */
class Test_Misc extends WP_UnitTestCase {
    public function setUp() {
        parent::setUp();
    }

    public function tearDown() {
        parent::tearDown();
    }

    public function test_sample() {
        $foo = 'bars';
        $this->assertEquals( 'bar', $foo );
    }
}

<?php

namespace Anna\Session;

use \PHPUnit\Framework\TestCase;

/**
 * Testing framework session class.
 */
class Session2Test extends TestCase
{
    /**
     * Set the name of the session
     */
    public function testSetName()
    {
        $session = new Session2();

        $name = "someName";
        $session->name($name);
        $this->assertTrue(true);
    }



    /**
     * Test
     */
    public function testGetSetHas()
    {
        $session = new Session2();

        $ret = $session->has("key");
        $this->assertFalse($ret, "Session should not have this entry.");

        $ret = $session->get("key");
        $this->assertNull($ret, "Session should return null for this entry.");

        $ret = $session->set("key", "value");
        $ret = $session->has("key");
        $this->assertTrue($ret, "Session should have this entry.");

        $ret = $session->get("key");
        $this->assertEquals($ret, "value", "Session should have a value for this entry.");
    }



    /**
     * Test getOnce()
     */
    public function testGetOnce()
    {
        $session = new Session2();

        $ret = $session->set("key", "value");
        $ret = $session->getOnce("key");
        $this->assertEquals($ret, "value", "Session should have a value for this entry.");

        $ret = $session->get("key");
        $this->assertNull($ret, "Session should return null for this entry.");
    }



    /**
     * Test magic method __debugInfo()
     */
    public function testDebugInfo()
    {
        $session = new Session2();

        $res = $session->__debugInfo();
        $this->assertInternalType("array", $res);
        $this->assertEmpty($res);
        $this->assertEquals($res, $_SESSION);
    }



    /**
     * Destroy the session
     */
    public function testDestroySession()
    {
        $session = new Session2();

        $session->set("key", "value");
        $res = $session->get("key");
        $this->assertEquals("value", $res);

        $session->destroy();
        $this->assertEmpty($_SESSION);
    }


    /**
     * Test append()
     */
    public function testAppend()
    {
        $session = new Session2();

        $res = $session->set("key", "Hello");
        $expected = $session->get("key") . " and goodbye!";
        $res = $session->append("key", " and goodbye!");
        $result = $session->getOnce("key");
        $this->assertEquals($result, $expected, "Session should have a value for this entry.");

        $res = $session->append("key", [3,4,5]);
        $result = $session->get("key");
        $this->assertNull($result, "Session should return null for this entry.");
    }
}

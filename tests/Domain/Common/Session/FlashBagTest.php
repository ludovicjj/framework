<?php

namespace Tests\Domain\Common\Session;

use App\Domain\Common\Session\ArraySession;
use App\Domain\Common\Session\FlashBag;
use PHPUnit\Framework\TestCase;

class FlashBagTest extends TestCase
{
    /** @var ArraySession */
    private $session;

    /** @var FlashBag */
    private $flashBag;

    public function setUp(): void
    {
        $this->session = new ArraySession();
        $this->flashBag = new FlashBag($this->session);
    }

    public function testDeleteFlashAfterDisplayIt()
    {
        $this->flashBag->add('success', 'demo');
        $this->assertEquals('demo', $this->flashBag->get('success'));
        $this->assertNull($this->session->get($this->flashBag::FLASH_SESSION_KEY));
        $this->assertEquals('demo', $this->flashBag->get('success'));
        $this->assertEquals('demo', $this->flashBag->get('success'));
    }
}

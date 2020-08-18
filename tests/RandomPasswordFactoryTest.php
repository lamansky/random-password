<?php
namespace Lamansky\RandomPassword\Test;
use Lamansky\RandomPassword\RandomPasswordFactory;
use PHPUnit\Framework\TestCase;

final class RandomPasswordFactoryTest extends TestCase {

    public function testAlpha () : void {
        $this->assertMatchesRegularExpression(
            '/([a-zA-Z]){6}/',
            RandomPasswordFactory::alpha()->generate(6)
        );
    }

    public function testNumeric () : void {
        $this->assertMatchesRegularExpression(
            '/([0-9]){10}/',
            RandomPasswordFactory::numeric()->generate(10)
        );
    }

    public function testAlphanumeric () : void {
        $this->assertMatchesRegularExpression(
            '/([a-zA-Z0-9]){9}/',
            RandomPasswordFactory::alphanumeric()->generate(9)
        );
    }

    public function testLowerAlphanumeric () : void {
        $this->assertMatchesRegularExpression(
            '/([a-z0-9]){8}/',
            RandomPasswordFactory::loweralphanumeric()->generate(8)
        );
    }

    public function testAscii () : void {
        $this->assertMatchesRegularExpression(
            '/.{7}/',
            RandomPasswordFactory::ascii()->generate(7)
        );
    }

    public function testCustomSets () : void {
        $this->assertMatchesRegularExpression(
            '/[^a-zA-Z]{12}/',
            (new RandomPasswordFactory('ns'))->generate(12)
        );
    }

    public function testCustom () : void {
        $this->assertMatchesRegularExpression(
            '/[abc]{12}/',
            RandomPasswordFactory::custom('abc')->generate(12)
        );
    }

    public function testCustomSet () : void {
        $this->assertMatchesRegularExpression(
            '/[abc]{12}/',
            (new RandomPasswordFactory('c', 'abc'))->generate(12)
        );
    }
}

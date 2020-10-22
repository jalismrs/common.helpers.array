<?php
declare(strict_types = 1);

namespace Tests;

use Jalismrs\HelpersArrayBundle\HelpersArray;
use Maba\GentleForce\Exception\RateLimitReachedException;
use Maba\GentleForce\RateLimitProvider;
use Maba\GentleForce\ThrottlerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;

/**
 * Class HelpersArrayTest
 *
 * @package Tests
 *
 * @covers  \Jalismrs\HelpersArrayBundle\HelpersArray
 */
final class HelpersArrayTest extends
    TestCase
{
    /**
     * mockRateLimitProvider
     *
     * @var \Maba\GentleForce\RateLimitProvider|\PHPUnit\Framework\MockObject\MockObject
     */
    private MockObject $mockRateLimitProvider;
    /**
     * mockThrottler
     *
     * @var \Maba\GentleForce\ThrottlerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private MockObject $mockThrottler;
    
    /**
     * testRegisterRateLimits
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     */
    public function testRegisterRateLimits() : void
    {
        // arrange
        $systemUnderTest = $this->createSUT();
        
        $rateLimits = [];
        
        // expect
        $this->mockRateLimitProvider
            ->expects(self::once())
            ->method('registerRateLimits')
            ->with(
                self::equalTo(HelpersArrayProvider::USE_CASE_KEY),
                self::equalTo($rateLimits)
            );
        
        // act
        $systemUnderTest->registerRateLimits(
            HelpersArrayProvider::USE_CASE_KEY,
            $rateLimits
        );
    }
    
    /**
     * createSUT
     *
     * @return \Jalismrs\HelpersArrayBundle\HelpersArray
     */
    private function createSUT() : HelpersArray
    {
        return new HelpersArray(
            $this->mockRateLimitProvider,
            $this->mockThrottler,
        );
    }
    
    /**
     * testWaitAndIncrease
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     */
    public function testWaitAndIncrease() : void
    {
        // arrange
        $systemUnderTest = $this->createSUT();
        
        // expect
        $this->mockThrottler
            ->expects(self::exactly(2))
            ->method('checkAndIncrease')
            ->with(
                self::equalTo(HelpersArrayProvider::USE_CASE_KEY),
                self::equalTo(HelpersArrayProvider::IDENTIFIER)
            )
            ->willReturnOnConsecutiveCalls(
                self::throwException(
                    new RateLimitReachedException(
                        42,
                        'Rate limit was reached'
                    )
                ),
                null
            );
        
        // act
        $systemUnderTest->waitAndIncrease(
            HelpersArrayProvider::USE_CASE_KEY,
            HelpersArrayProvider::IDENTIFIER
        );
    }
    
    /**
     * testWaitAndIncreaseThrowsRateLimitReachedException
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     * @throws \Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException
     */
    public function testWaitAndIncreaseThrowsRateLimitReachedException() : void
    {
        // arrange
        $systemUnderTest = $this->createSUT();
        
        // expect
        $this->expectException(TooManyRequestsHttpException::class);
        $this->expectExceptionMessage('Loop limit was reached');
        $this->mockThrottler
            ->expects(self::once())
            ->method('checkAndIncrease')
            ->with(
                self::equalTo(HelpersArrayProvider::USE_CASE_KEY),
                self::equalTo(HelpersArrayProvider::IDENTIFIER)
            )
            ->willThrowException(
                new RateLimitReachedException(
                    42,
                    'Rate limit was reached'
                )
            );
        
        // act
        $systemUnderTest->setCap(1);
        $systemUnderTest->waitAndIncrease(
            HelpersArrayProvider::USE_CASE_KEY,
            HelpersArrayProvider::IDENTIFIER
        );
    }
    
    /**
     * testDecrease
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     */
    public function testDecrease() : void
    {
        // arrange
        $systemUnderTest = $this->createSUT();
        
        // expect
        $this->mockThrottler
            ->expects(self::once())
            ->method('decrease')
            ->with(
                self::equalTo(HelpersArrayProvider::USE_CASE_KEY),
                self::equalTo(HelpersArrayProvider::IDENTIFIER)
            );
        
        // act
        $systemUnderTest->decrease(
            HelpersArrayProvider::USE_CASE_KEY,
            HelpersArrayProvider::IDENTIFIER
        );
    }
    
    /**
     * testReset
     *
     * @return void
     *
     * @throws \PHPUnit\Framework\MockObject\RuntimeException
     */
    public function testReset() : void
    {
        // arrange
        $systemUnderTest = $this->createSUT();
        
        // expect
        $this->mockThrottler
            ->expects(self::once())
            ->method('reset')
            ->with(
                self::equalTo(HelpersArrayProvider::USE_CASE_KEY),
                self::equalTo(HelpersArrayProvider::IDENTIFIER)
            );
        
        // act
        $systemUnderTest->reset(
            HelpersArrayProvider::USE_CASE_KEY,
            HelpersArrayProvider::IDENTIFIER
        );
    }
    
    /**
     * setUp
     *
     * @return void
     */
    protected function setUp() : void
    {
        parent::setUp();
        
        $this->mockRateLimitProvider = $this->createMock(RateLimitProvider::class);
        $this->mockThrottler         = $this->createMock(ThrottlerInterface::class);
    }
}

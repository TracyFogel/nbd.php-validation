<?php
use Behance\NBD\Validation\Services\ValidatorService;

/**
 * @group validation
 */
class NBD_Validation_Rules_MatchesRuleTest extends \PHPUnit\Framework\TestCase {

  protected $_class = 'Behance\NBD\Validation\Rules\MatchesRule';

  /**
   * @test
   * @dataProvider isValidDataProvider
   */
  public function isValid( $value1, $value2, $expected ) {

    $name = $this->_class;
    $rule = new $name();
    $key2 = 'def';

    $validator = new ValidatorService( [ $key2 => $value2 ] );
    $closure = $rule->getClosure();
    $context = [
        'validator'  => $validator,
        'parameters' => [ $key2 ]
    ];

    $this->assertEquals( $expected, $closure( $value1, $context ) );

  } // isValid


  /**
   * {@inheritDoc}
   */
  public function getErrorTemplate() {


  } // getErrorTemplate


  /**
   * @test
   */
  public function errorTemplate() {

    $value1 = '123';
    $value2 = '456';

    $key1   = 'abc';
    $key2   = 'def';

    $name1  = 'First Field';
    $name2  = 'Second Field';

    $cage   = [
        $key1 => $value1,
        $key2 => $value2
    ];

    $validator = new ValidatorService( $cage );
    $validator->setRule( $key1, $name1, 'required|alphanumeric' )
              ->setRule( $key2, $name2, 'required|alphanumeric|matches[' . $key1 . ']' );

    $validator->run();

    $errors = $validator->getAllFieldErrorMessages();

    // Ensure the second field has failed, not the first
    $this->assertArrayHasKey( $key2, $errors );
    $this->assertArrayNotHasKey( $key1, $errors );

    $message = $errors[ $key2 ];

    $this->assertStringStartsWith( $name2, $message );
    $this->assertStringEndsWith( $name1, $message );

  } // errorTemplate


  /**
   * @test
   * @expectedException Behance\NBD\Validation\Exceptions\Validator\RuleRequirementException
   */
  public function wrongParameterCount() {

    $name = $this->_class;
    $rule = new $name();

    $closure = $rule->getClosure();
    $context = [
        'parameters' => [ 'abc' ]
    ];

    $closure( 'anything_else', $context );

  } // wrongParameterCount


  /**
   * @test
   * @expectedException Behance\NBD\Validation\Exceptions\Validator\RuleRequirementException
   */
  public function missingValidator() {

    $name = $this->_class;
    $rule = new $name();
    $key2 = 'def';

    $validator = new ValidatorService( [ $key2 => 'anything' ] );
    $closure = $rule->getClosure();
    $context = [
        'validator'  => $validator,
        'parameters' => []
    ];

    $closure( 'anything_else', $context );

  } // missingValidator


  /**
   * @return array
   */
  public function isValidDataProvider() {

    $class1 = new stdClass();
    $class2 = new stdClass();
    $func1  = ( function() {} );
    $func2  = ( function() {} );

    return [
        [ 'abc', 'abc', true ],
        [ 'ábč', 'ábč', true ],
        [ 'ÁBČ', 'ÁBČ', true ],
        [ 'ABC', 'ÁBČ', false ],
        [ 'ÁBČ', 'ABC', false ],
        [ 123, 123, true ],
        [ 123, '123', false ],
        [ '123', 123, false ],
        [ '123', '123', true ],
        [ '', false, false ],
        [ false, '', false ],
        [ '', 0, false ],
        [ 0, '', false ],
        [ '', '', true ],
        [ false, false, true ],
        [ true, true, true ],
        [ null, true, false ],
        [ null, null, false ],
        [ $class1, $class1, true ],
        [ $class1, $class2, false ],
        [ $class1, false, false ],
        [ $class1, $func1, false ],
        [ $func1, $func1, true ],
        [ $func1, $func2, false ],
        [ $func1, $class1, false ],
        [ [], [], true ],
        [ [ 'abc' => 1 ], [], false ],
        [ [], [ 'abc' => 1 ], false ],
        [ [ 'abc' => 1 ], [ 'abc' => 1 ], true ],
        [ [ 0 => 1 ], [], false ],
        [ [], [ 0 => 1 ], false ],
        [ [ 0 => 1 ], [ 0 => 1 ], true ],
    ];

  } // isValidDataProvider

} // NBD_Validation_Rules_MatchesRuleTest

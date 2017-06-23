<?php
/**
 * @group validation
 */
class NBD_Validation_Rules_RangeRuleTest extends \PHPUnit\Framework\TestCase {

  protected $_class = 'Behance\NBD\Validation\Rules\RangeRule';

  /**
   * @test
   * @dataProvider isValidDataProvider
   */
  public function isValid( $data, $min, $max, $expected ) {

    $name = $this->_class;
    $rule = new $name();

    $context['parameters'] = [ $min, $max ];

    $this->assertEquals( $expected, $rule->isValid( $data, $context ) );

  } // isValid


  /**
   * @test
   * @expectedException Behance\NBD\Validation\Exceptions\Validator\InvalidRuleException
   */
  public function invalidRangeMaxParameter() {

    $name = $this->_class;
    $rule = new $name();

    $value = 'abc';

    $context['parameters'] = [ 1, 'a' ];

    $rule->isValid( $value, $context );

  } // invalidRangeMaxParameter


  /**
   * @test
   * @expectedException Behance\NBD\Validation\Exceptions\Validator\InvalidRuleException
   */
  public function invalidRangeMinParameter() {

    $name = $this->_class;
    $rule = new $name();

    $value = 'abc';

    $context['parameters'] = [ 'a', 1 ];

    $rule->isValid( $value, $context );

  } // invalidRangeMinParameter


  /**
   * @return array
   */
  public function isValidDataProvider() {

    return [
        [ 1, 1, 10, true ],
        [ '1', 1, 10, true ],
        [ 1, '1', 10, true ],
        [ '1', '1', 10, true ],
        [ 1, 1, '10', true ],
        [ '1', 1, '10', true ],
        [ 1, '1', '10', true ],
        [ '1', '1', '10', true ],
        [ 10, 1, 10, true ],
        [ '10', 1, 10, true ],
        [ 5, 1, 10, true ],
        [ '5', 1, 10, true ],
        [ 0, 1, 10, false ],
        [ '0', 1, 10, false ],
        [ 0, '1', 10, false ],
        [ '0', '1', 10, false ],
        [ 0, 1, '10', false ],
        [ '0', 1, '10', false ],
        [ 0, '1', '10', false ],
        [ '0', '1', '10', false ],
        [ 11, 1, 10, false ],
        [ '11', 1, 10, false ],
        [ -1, 1, 10, false ],
        [ '-1', 1, 10, false ],
        [ 1.1, 1, 10, true ],
        [ '1.1', 1, 10, true ],
        [ 9.9, 1, 10, true ],
        [ '9.9', 1, 10, true ],
        [ 'five', 1, 10, false ],
        [ [], 1, 10, false ],
        [ false, 1, 10, false ],
        [ 'false', 1, 10, false ],
        [ true, 1, 10, true ],
        [ 'true', 1, 10, false ],
        [ ( new stdClass() ), 1, 10, false ],
        [ ( function() {} ), 1, 10, false ],
    ];

  } // isValidDataProvider

} // NBD_Validation_Rules_RangeRuleTest

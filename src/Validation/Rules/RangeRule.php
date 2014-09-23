<?php

namespace NBD\Validation\Rules;

use NBD\Validation\Abstracts\CallbackRuleAbstract;
use NBD\Validation\Exceptions\Validator\RuleRequirementException;
use NBD\Validation\Exceptions\Validator\InvalidRuleException;

/**
 * Validates that data is a string that contains a sequence of characters
 */
class RangeRule extends CallbackRuleAbstract {

  /**
   * @inheritDoc
   */
  public function __construct() {

    $closure = ( function( $data, array $context ) {

      if ( empty( $context['parameters'] ) || count( $context['parameters'] ) !== 2 ) {
        throw new RuleRequirementException( "Two parameters required for '" . __CLASS__ . "'" );
      }

      if ( is_array( $data ) || is_object( $data ) ) {
        return false;
      }

      list( $min, $max ) = $context['parameters'];

      // IMPORTANT: cast min/max to strings for is_numeric check, otherwise there will be mixed results
      if ( !is_numeric( (string)$min ) || !is_numeric( (string)$max ) ) {
        throw new InvalidRuleException( "Min and max parameters must be numeric values" );
      }

      if ( $min >= $max ) {
        throw new RuleRequirementException( "Range minimum ({$min}) must be less than maximum {$max})" );
      }

      return ( $data >= $min && $data <= $max );

    } );

    $this->setClosure( $closure );

  } // __construct

} // RangeRule